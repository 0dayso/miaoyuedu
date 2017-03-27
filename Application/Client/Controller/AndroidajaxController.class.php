<?php
/**
* android.hongshu.com的ajax.php
*
* @author: dingzi 2016-06-17 17:21 dingzi $
*
*/

namespace Client\Controller;
use Client\Common\Controller;

class AndroidajaxController extends Controller{
    /**
     *
     */
    public function _androidrequest(){
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
            if(!$uuid){
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
            if (!$uuid) {
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
}