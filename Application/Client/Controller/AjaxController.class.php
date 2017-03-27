<?php
// +----------------------------------------------------------------------
// | 红薯网 [ Home模块下Ajax统一调用控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2015 http://www.hongshu.com All rights reserved.
// +----------------------------------------------------------------------
// | Version: v2.0
// +----------------------------------------------------------------------
// | Author: jiachao <jiachao@hongshu.com>
// +----------------------------------------------------------------------
// | Date: 2015-09-07
// +----------------------------------------------------------------------
// | Last Modified by: jiachao
// +----------------------------------------------------------------------
// | Last Modified time: 2015-09-07 12:56
// +----------------------------------------------------------------------
namespace Client\Controller;

use Client\Common\Controller;
use Org\Util\String;

class AjaxController extends Controller {

    /**
     * 向百度提交链接
     */
    public function checkurlAction() {
        
        if(!IS_AJAX || C('CLIENT.'.CLIENT_NAME.'.domain')!=='m.hongshu.com') {
            exit();
        }
        exit;
        $api = 'http://data.zz.baidu.com/urls?site=m.hongshu.com&token=4YGBHNXK6pTL93P5';
        $_file = TEMP_PATH.'/urls.txt';
        $urls = array();
        if(file_exists($_file)) {
            $urls = file($_file);
            array_walk($urls, function(&$v){
                $v = trim($v);      //有可能会有换行符，为避免不必要的麻烦，清除一下
            });
        }
        $url = I('url', '', 'trim');
        if(str_replace(C('CLIENT.'.CLIENT_NAME.'.domain'), '', $url)!=$url) {
            $key = md5($url);
            $cacheObj = new \HS\MemcacheRedis();
            if(!$cacheObj->get($key) && !in_array($url, $urls)) {
                //需要指提交
                $cacheObj->setMc($key, NOW_TIME, 48*3600);  //每两天提交一次？
                $urls[] = $url;
                if(count($urls)>100) {
                    //发送！  提交的时候要处理并发。如果有正在提交的进程，则不能再次提交，同样的，追加链接地址也一样。
                    $data = str_replace('m.client.hongshutest.com:8090', 'm.hongshu.com', implode("\n", $urls));
                    $result = PostData($data, $api);
                    $result = json_decode($result);
                    if($result->success) {
                        //提交成功了
                        \Think\Log::write(print_r(array('links'=>$urls, 'result'=>$result), 1), 'DATA', '', LOG_PATH . 'SEND_TO_BAIDU');
                        $urls = array();
                    }
                }
                file_put_contents($_file, implode("\n", $urls));
            }
        }
    }

    /**
     * 获取签到、红点
     *
     * IOS:1.3.5起，切换至此处
     */
    public function _clientrequest(){
        $ajaxdata = array("status"=>0,"message"=>"","url"=>"");
        $method = I("param.method","","trim");
        if(!in_array($method,array("checkqiandao","checkpoint","getuserinfo"))){
            $ajaxdata['message'] = C('ERRORS.params');
            $this->ajaxReturn($ajaxdata);
        }
        if($method == "checkqiandao"){
            if(CLIENT_NAME == 'android'){
                $uuid = $this->deviceInfo['UUID'];
            }else{
                $uuid = I("post.uuid",'',"trim");
            }
            if(!$uuid || is_null($uuid)){
                exit;
            }
            $thisday = date("ymd");
            //SELECT egold FROM wis_android_qiandao WHERE uuid='{$uuid}' AND qiandao_day={$thisday}
            $where = array(
                "uuid"=>$uuid,
                "qiandao_day"=>$thisday,
            );
            $tabName = CLIENT_NAME."_qiandao";
            $linjianginfo = M($tabName)->where($where)->find();
            if(!$linjianginfo || count($linjianginfo)<=0){
                $is_need_qiandao = 1;
            }else{
                $is_need_qiandao = 0;
            }
            $this->ajaxReturn(array('is_need_qiandao' => $is_need_qiandao));
        }else if($method == "checkpoint"){
            $needpoint = 0;
            if(CLIENT_NAME == 'android'){
                $uuid = $this->deviceInfo['UUID'];
            }else{
                $uuid = I("post.uuid",'',"trim");
            }
            if (!$uuid || is_null($uuid)) {
                exit;
            }
            $thisday = date("ymd");
            //SELECT egold FROM wis_android_qiandao WHERE uuid='{$uuid}' AND qiandao_day={$thisday}
            $map = array(
                "uuid" => $uuid,
                "qiandao_day" => $thisday,
            );
            $tabName = CLIENT_NAME."_qiandao";
            $linjianginfo = M($tabName)->where($map)->find();
            if(!$linjianginfo || count($linjianginfo)<=0){
                $is_need_qiandao = 1;
            }else{
                $is_need_qiandao = 0;
            }
            $needpoint += $is_need_qiandao;
            $ajaxdata = array("b1"=>$needpoint);
            $this->ajaxReturn($ajaxdata);
        }else if($method == "getuserinfo"){
            $uid = I("param.uid",0,"intval");
            if(!$uid){
                exit;
            }
            $userModel = new \Client\Model\UserModel();
            $userinfo = $userModel->getUserbyUid($uid);
            $userinfo['nickname'] = $userinfo['nickname']?$userinfo['nickname']:$userinfo['username'];
            $isauthor = $userModel->checkAuthorByUid($uid);
            $userinfo['isauthor'] = $isauthor?1:0;
            $userinfo['groupname'] = C("USERGROUP.".$userinfo['groupid'].".title");
            $userinfo['avatar'] = getUserFaceUrl($uid,"middle");
            $this->ajaxReturn($userinfo);
        }

    }
    
    /**
     * IOS客户端热修复
     * @param string $cversion 客户端版本号,如1.3.8
     * @param int $sversion 脚本版本号,如1.3.8.0/1.3.8.1...
     * 
     * @return json
     *      isDownload:是否需要下载(1需要下载/0不需要下载)
     *      jsVersion:最新的版本号
     *      jsUrl:下载脚本的地址（如不需要下载则为空）
     */
    public function _checkhotfix_ios(){
        $output = array('isDowload'=>0,'jsVersion'=>'','jsUrl'=>'');
        $cversion = I('param.cversion','','trim');
        $sversion = I('param.sversion',0,'trim');
        if(!$cversion || !$sversion){
            $this->ajaxReturn($output);
        }
        $output['cVersion'] = $cversion;
        //是否已关闭热修复
        $confModel = M('site_config');
        $forbiddenWhere = array('conf_name'=>'hotfix_forbbiden_'.CLIENT_NAME);
        $forbiddenVersions = $confModel->where($forbiddenWhere)->getField('conf_value');
        if($forbiddenVersions && in_array($cversion, explode('/',$forbiddenVersions))){
            $output['isDowload'] = 2;
            $this->ajaxReturn($output);
        }
        //判断cversion和sversion是否对应
        $sversionAry = explode('.', $sversion);
        //删除js版本号
        array_pop($sversionAry);
        //根据sversion获取的客户端版本号
        $cversionFromsversion = implode('.',$sversionAry);
        //判断2个版本号是否对应
        $issame = false;
        if($cversion == $cversionFromsversion){
            $issame = true;
        }
        //查询最新版本号
        $hotfixModel = M('hotfix');
        $where = array('client_version'=>$cversion,'client'=>CLIENT_NAME);
        $lastest_script_version = $hotfixModel->where($where)->order('client_release DESC')->getField('script_version');
        if(!$lastest_script_version){
            $this->ajaxReturn($output);
        }
        if($lastest_script_version <= $sversion){
            $this->ajaxReturn($output);
        }
        //查询上传根目录
        $rootwhere = array('conf_name'=>'hotfix_upload_rootpath');
        $sroot = $confModel->where($rootwhere)->getField('conf_value');
        //文件路径
        $filepath = $sroot.'/'.CLIENT_NAME.'/'.$cversion.'/'.$lastest_script_version.'.js';
        //要下载的脚本
        if(file_exists($filepath)){
            $output['isDowload'] = 1;
            $output['jsVersion'] = $lastest_script_version;
            $output['jsUrl'] = 'https:'.C('TMPL_PARSE_STRING.__STATICURLSAFE__').'/hotfix/ios/'.$cversion.'/'.$lastest_script_version.'.js';
        }
        $this->ajaxReturn($output);
    }
    
    /**
     * IOS下载脚本地址
     * @param string $cversion 客户端版本号
     * @param int $sversion 脚本版本号
     * 
     */
    public function _downloadhotfix_ios(){
        $cversion = I('param.cversion','','trim');
        $sversion = I('param.sversion',0,'trim');
        if(!$cversion || !$sversion){
            client_output_error('Params Error!');//参数错误
        }
        //判断cversion和sversion是否对应
        $sversionAry = explode('.', $sversion);
        $intsversion = str_replace('.', '', $sversion);
        //删除js版本号
        array_pop($sversionAry);
        //根据sversion获取的客户端版本号
        $cversionFromsversion = implode('.',$sversionAry);
        if($cversion !== $cversionFromsversion){
            client_output_error('Versions Error!');
        }
        
        $sroot = C('IMG1_ROOT').'/Public/Client/ios/hotfixscript/'.$cversion;
        //判断是否是最新版本
        $files = array();
        $numericfilename = array();
        $lastedsversion = '';   //最新的版本号
        if(is_dir($sroot)){
            $files = scandir($sroot);
            foreach($files as $vo){
                $tmpary = array();
                if(is_file($sroot.'/'.$vo) && substr($vo, -3) == '.js'){
                    $tmpary = explode('.',$vo);
                    $numericfilename[] = intval($tmpary[0]);
                }
            }
            if($numericfilename){
                $lastedsversion = max($numericfilename);
            }
        }else{
            client_output_error('No Such A File!');
        }
        //没有最新版本或参数中的版本不是最新版本
        if(!$lastedsversion || $intsversion != $lastedsversion){
            client_output_error('Not The Correct Version!');
        }
        $scriptname = $sroot.'/'.$intsversion.'.js';
        if(file_exists($scriptname)){
            $fp = fopen($scriptname,'r');
            $filesize = filesize($scriptname);
            header('Content-type:txt/html;charset:utf-8');
            header('Accept-Ranges:bytes');
            header('Accept-Length:'.$filesize);
            header('Content-Disposition:attachment;filename='.$sversion.'.js');
            $perlen = 2048;//每次读取长度
            $file_count = 0;
            if(!feof($fp) && $file_count < $filesize){
                $content = fread($fp,$perlen);
                echo $content;
                $file_count += $perlen;
            }
            fclose($fp);
        }else{
            client_output_error('File Not Found!');
        }
    }
}
