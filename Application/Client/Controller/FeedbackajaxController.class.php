<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: FeedbackajaxController.class.php 1519 2017-02-28 03:13:03Z dingzi $
 */

namespace Client\Controller;

use Client\Common\Controller;

class FeedbackajaxController extends Controller {
    public function _initialize(){
        parent::_initialize();
        $this->check_user_login();
    }
    /**
     * 留言反馈:具体的某条主题
     *
     * @param int $mid 留言id get
     * @param int $totalnum 总记录条数 get
     * */
    public function _getfeedbackreplylist() {
        //SELECT uid,nickname FROM wis_message WHERE mid=".$mid
        $output = array("status"=>0,"message"=>"","url"=>"");
        $mid = I('get.mid', 0, 'intval');
        if (!$mid) {
            $output['message'] = "参数错误";
            $this->ajaxReturn($output);
        }
        $msgObj = M('message');
        $msgmap = array("mid"=>$mid);
        $msginfo = $msgObj->where($msgmap)->find();
        $fuid = $msginfo['uid'];
        $fnickname = $msginfo['nickname'];
        //翻页
        $maxnum = 10;
        $totalnum = I('get.totalnum', 0, 'intval');
        $msgReplyObj = M('message_reply');
        $replyData = array(
            'r.mid'            => $mid,
            'r.forbidden_flag' => 0,
            'r.delete_flag'    => 0,
        );
        if (!$totalnum) {
            $totalnum = $msgReplyObj->alias('r')->where($replyData)->count();
        }

        $pageObj = new \HS\Pager($totalnum, $maxnum);
        $show = $pageObj->show();
        $replyinfo = $msgReplyObj->alias("r")->field('r.rid,r.mid,r.content,r.uid,r.reply_uid,r.reply_date,r.replystatus,r.nickname,u.username as uname,u.nickname as unickname')
        ->join("wis_read_user AS u on u.uid = r.uid",'LEFT')
        ->order('r.reply_date ASC')->where($replyData)->limit($pageObj->firstRow, $pageObj->listRows)->select();
        if($replyinfo && count($replyinfo) <= 0){
            $output['status'] = 0;
            $list = array();
        }else{
            //格式化
            foreach ($replyinfo as $row) {
                $row['is_reply'] = 0;
                $contentarray = json_decode($row['content'], true);
                if ($row['uid'] != $fuid) {
                    $row['is_reply'] = 1;
                }
                $row['content'] = $contentarray['0']['content'];
                $row['reply_date'] = date("Y-m-d H:i", $row['reply_date']);
                //回复客户昵称
                if($row['nickname']){
                    $row['replyname'] = $row['nickname'];
                }else{
                    $row['replyname'] = $row['unickname']?$row['unickname']:$row['uname'];
                }
                $list[] = $row;
            }
            $output['status'] = 1;
        }

        $output['list'] = $list;
        $output['pagenum'] = $pageObj->nowPage;
        $output['nextpagenum'] = $pageObj->nowPage + 1;
        $output['totalpage'] = $pageObj->totalPages;
        $output['totalnum'] = $totalnum;
        $this->ajaxReturn($output);
    }
    /**
     * 留言反馈：提交留言(三合一)
     *
     * @param int $uid 用户id session
     * @param string $content 回复内容 post
     * @param string $contactway 联系方式 post
     *
     * @return string 提交成功/失败信息
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     * */

    public function _saveaddfeedback() {
        $output = array("status"=>0,"message"=>"","url"=>"","needlogin"=>0);
        $uid = isLogin();
        if (!$uid) {
            $output['needlogin'] = 1;
            $output['message'] = "请登录";
            $output['url'] = url("User/login",array(),'do');
            $this->ajaxReturn($output);
        }
        $ip = get_client_ip();
        //判断发表时间间隔
        if ($ip) {
            $mk_time = time() - session($ip);
            if ($mk_time < 10) {
                $output['message'] = "您发反馈的间隔时间太短,请稍等再发";
                $this->ajaxReturn($output);
            }
        }
        if(CLIENT_NAME == 'yqm'){
            $yzm = I('post.yzm',0,'intval');
            if(!$yzm){
                $output['message'] = '请输入验证码';
                $this->ajaxReturn($output);
            }
            $verifyModel = new \HS\Verify();
            if(!($verifyModel->check($yzm))){
                $output['message'] = '验证码错误';
                $this->ajaxReturn($output);
            }
        }
        $data = array();
        //内容
        $content = I('post.content','','trim,removeXSS');
        if (!$content) {
            $output['message'] = "请输入内容";
            $this->ajaxReturn($output);
        }
        if (!preg_match('/[\x{4e00}-\x{9fa5}]+/iu', $content)) {
            $output['message'] = "请输入中文";
            $this->ajaxReturn($output);
        }
        //othermessage?
        //         $othermessage = I('post.othermessage');
        //         if ($othermessage) {
        //             $content = $content . " | " . $othermessage;
        //         }
        //联系方式
        $contactway = I('post.contactway','','trim');
        if(CLIENT_NAME == 'yqm'){
            $contactway = I('post.email','','trim');
            if(!$contactway && !isValidEmail($contactway)){
                $output['message'] = '请输入联系方式';
                $this->ajaxReturn($output);
            }
        }
        if ($contactway) {
            $data['contactway'] = $contactway;
        }
        //是否匿名反馈
        $niming = I('post.niming',0,'intval');
        //反馈类型
        //         $communtype = I('post.communtype', 0, 'intval');
        //         if (!$communtype) {
        //             $this->error('请选择正确的反馈类型');
        //         }
        //插入数据库 wis_message

        $data['uid'] = $uid;
        $data['nickname'] = addslashes(session('nickname'));
        $data['last_replynickname'] = addslashes(session('nickname'));
        if($niming){
            $data['nickname'] = '匿名用户';
            $data['uid'] = 0;
            $data['last_replynickname'] = '匿名用户';
        }
        $title = cutstr($content, 80);
        $data['title'] = addslashes($title);
        $data['creation_date'] = NOW_TIME;
        $data['messagetype'] = 1;
        $data['is_anony'] = 1;
        $data['last_replytime'] = NOW_TIME;
        $data['replynum'] = 0;
        $data['communtype'] = 4;
        //$data['contactway'] = $contactway;
        $mid = M('message')->add($data);
        if ($mid) {
            $data = array();
            $data['mid'] = $mid;
            $data['uid'] = $uid;
            $data['nickname'] = addslashes(session('nickname'));
            $contentarray = array('0'=>array('content'=>$content,'nickname'=>session('nickname')));
            $data['content'] = json_encode($contentarray);
            $data['reply_date'] = NOW_TIME;
            $data['sourceid'] = C("CLIENT.".CLIENT_NAME.".fromsiteid");
//             if(CLIENT_NAME == "html5"){
//                 $data['sourceid'] = 6;
//             }else{
//                 $data['sourceid'] = 7;
//             }

            M('message_reply')->add($data);
            if(CLIENT_NAME == "html5"){
                $output['status'] = 1;
                $output['message'] = '发表成功！';
                $this->ajaxReturn($output);
            }else{
                $output['status'] = "1";
                $output['feedbackId'] = $mid;
                $this->ajaxReturn($output);
            }
        } else {
            if(CLIENT_NAME == "html5"){
                $output['message'] = "发表失败";
                $this->ajaxReturn($output);
            }else{
                $output['status'] = "0";
                $this->ajaxReturn($output);
            }
        }
    }
    /**
     * 留言反馈：回复留言
     *
     * @param int $uid 用户id session
     * @param int $mid 回复主题的id post
     * @param string $reply_content 回复内容 post
     *
     * @return string 成功/失败信息
     * */

    public function _saveaddreplycommun() {
        $output = array("status"=>0,"message"=>"","url"=>"");
        $uid = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $this->ajaxReturn($output);
        }
        $ip = get_client_ip();
        $cookname = ip2long($ip) . '_addreplycommun';
        if (!cookie($cookname)) {
            ;
        } else {
            $mk_time = NOW_TIME - cookie($cookname);
            if ($mk_time < 180) {
                $output['message'] = '您回复间隔时间太短,请稍等再发';
                $this->ajaxReturn($output);
            }
        }
        $nickname = session('nickname');
        $nickname = daddslashes($nickname, true);
        //取值
        $mid = I('post.mid', 0, 'intval');
        if (!$mid) {
            $output['message'] = "参数错误！";
            $this->ajaxReturn($output);
        }
        //判断是否关闭回复:SELECT is_anony,is_close FROM wis_message WHERE mid=".$mid
        $closemap = array("mid"=>$mid);
        $msginfo = M('message')->field('is_anony,is_close')->where($closemap)->find();
        if ($msginfo['is_close']) {
            $output['message'] = '对不起，该主题已经关闭回复功能';
            $this->ajaxReturn($output);
        }

        $reply_content = I('post.content','','trim,removeXSS');
        if (!$reply_content) {
            $output['message'] = '请输入内容';
            $this->ajaxReturn($output);
        }
        $replyData = array();
        $replyData['mid'] = $mid;
        $replyData['uid'] = $uid;
        $replyData['nickname'] = $nickname;
        $replyData['sourceid'] = 7;
        $contentarray = array('0' => array('content' => $reply_content, 'nickname' => $nickname));
        $replyData['content'] = json_encode($contentarray);
        $replyData['reply_date'] = time();
        //插入wis_message_reply
        $res = M('message_reply')->add($replyData);
        if ($res) {
            //设置cookie
            cookie($cookname, NOW_TIME, NOW_TIME + 300);
            //更新message:"UPDATE wis_message SET replynum = replynum + 1,last_replytime = ".time().",last_replynickname = '".daddslashes($nickname,true)."' WHERE mid = ".$mid
            $msgObj = M('message');
            $map = array('mid=' . $mid);
            $saveData = array('last_replynickname' => $nickname, 'last_replytime' => NOW_TIME, 'replynum' => array('exp', 'replynum' . '+1'));
            $msgObj->where($map)->save($saveData);
            $output['status'] = 1;
            $output['message'] = '回复成功';
            $this->ajaxReturn($output);
        } else {
            $output['status'] = "-1";
            $this->ajaxReturn($output);
        }
    }

    /**
     * android、ios保存反馈
     *
     * @param string $content 反馈内容
     * @param string $contactway 联系方式
     *
     * @return array json数组
     */
    public function _savafeedback() {//TODO：原feedback.php中action==save
        if (!CLIENT_NAME || !in_array(CLIENT_NAME, array('android', 'ios'))) {
            $output['message'] = "未知客户端来源";
            $this->ajaxReturn($output);
        }

        $uid = isLogin();
        if ($uid) {
            $nickname = session('nickname');
        } else {
            $uid = '';
            $nickname = CLIENT_NAME . '匿名用户';
        }

        //接收所有post传值
        $postarr = I('post.');
        $postarr['communtype'] = 8;
        $output = array("status" => 0, "message" => "", "feedbackId" => "");

        //TODO:这部分逻辑，原站错误
        $ip = get_client_ip();
        if (session('feedback') && session('feedback.sendip') == $ip) {
            $mk_time = NOW_TIME - session('feedback.sendlasttime');
            if ($mk_time < 180) {
                $output['message'] = "您发反馈的间隔时间太短,请稍等再发";
                $this->ajaxReturn($output);
            }
        }

        foreach ($postarr as $k => $v) {
            $postarr[$k] = removeXSS(strip_tags(stripdslashes($v)));
        }
        $content = I('post.content', '', 'trim,removeXSS');
        if ($content == "") {
            $output['message'] = "请输入内容";
            $this->ajaxReturn($output);
        }

        $contactway = I('post.contactway', '', 'trim');
        if ($contactway == "") {
            $output['message'] = "请输入联系方式";
            $this->ajaxReturn($output);
        }
        $communtype = trim($postarr['communtype']);
        $communtype = intval($communtype);
        if (!$communtype) {
            $output['message'] = "请选择正确的反馈类型";
            $this->ajaxReturn($output);
        }
        $nickname = daddslashes($nickname, true);
        $title = substr($content, 0, 10);
        $contactway = daddslashes($contactway, true);
        $data = array(
            'uid' => $uid,
            'nickname' => $nickname,
            'title' => daddslashes($title, true),
            'creation_date' => NOW_TIME,
            'messagetype' => 1,
            'is_anony' => 1,
            'last_replynickname' => $nickname,
            'replynum' => 1,
            'communtype' => $communtype,
            'contactway' => $contactway,
        );
        $mModel = M('message');
        $ret = $mModel->data($data)->add();
        $mid = $mModel->getLastInsID();
        if ($ret) {
            unset($data);
            $contentarraytem = array('0' => array('content' => $content, 'nickname' => $nickname));
            $contentarray = mysql_escape_string(json_encode($contentarraytem));
            $data = array(
                'mid' => $mid,
                'uid' => $uid,
                'nickname' => $nickname,
                'content' => $contentarray,
                'reply_date' => time(),
                'sourceid' => 7,
            );
            $replyModel = M('message_reply');
            $replyModel->data($data)->add();
            //TODO:这部分逻辑，原站错误
            session("feedback.sendip", $ip);
            session("feedback.sendlasttime", time());
            $output['status'] = 1;
            $output['feedbackId'] = $mid;
            $this->ajaxReturn($output);
        } else {
            $output['status'] = 0;
            $this->ajaxReturn($output);
        }
    }
    /**
     * 喵阅读，添加反馈
     * @param int $type 反馈类型 post
     * @param string $content 反馈内容 post
     * @param int $isanony 是否匿名(=1匿名，=2不匿名) post
     * @param string $email post
     * @param int $imgcode 验证码 post
     */
    public function _addFeedback(){
        $output = array('status'=>'','message'=>'','url'=>'');
        $type = I('post.type',0,'intval');
        $content = I('post.content','','trim,strip_tags,removeXSS');
        $isanony = I('post.isanony',0,'intval');
        $email = I('post.email','','trim');
        $imgcode = I('post.imgcode',0,'intval');
        //验证，验证码
        if(!$imgcode){
            $output['message'] = '请输入验证码';
            $this->ajaxReturn($output);
        }
        if($imgcode != session('imgcode')){
            $output['message'] = '验证码错误，请重新输入';
            $this->ajaxReturn($output);
        }
        //邮箱
        if(!$email){
            $email = '';
        }
        if(!isValidEmail($email)){
            $output['message'] = '邮箱格式错误';
            $this->ajaxReturn($output);
        }
        //没有设置匿名则要检查登录
        if(!$isanony){
            $uid = isLogin();
            if(!$uid){
                $output['message'] = '请先登录';
                $this->ajaxReturn($output);
            }
        }
        //检测反馈频次
        $cacheModel = new \HS\MemcacheRedis();
        $key = '#Feedback#'.get_client_ip();
        $cacheTime = $cacheModel->getMc($key);
        if($cacheTime){
            $output['message'] = '对不起，反馈太频繁，请稍后再试';
            $this->ajaxReturn($output);
        }
        //图片
        if(is_uploaded_file($_FILES['file']['tmp_filename'])){
            $year=date("Y");//按天上传
            $month=date("m");
            $day=date("d");
            $name = rand(100,1000);
            $name = $name.time();
            $config = array(
                'maxSize'    =>    512*1024,
                'rootPath'   =>    C('IMG1_ROOT').'/feedback',
                'savePath'   =>    "/".$year."/".$month."/".$day."/",
                'saveName'   =>     $name."_feedback",
                'exts'       =>    C('IMGEXT'),
                'autoSub'    =>    false,
            );
            if (!is_dir($config['rootPath'] . $config['savePath'])) {
                mkdir(iconv("UTF-8", "GBK", $config['rootPath'] . $config['savePath']),0777,true);
            }
            if (file_exists($config['rootPath'] . $config['savePath'] . $config['saveName'] . '.jpg')) {
                unlink($config['rootPath'] . $config['savePath'] . $config['saveName'] . '.jpg');
            }
            $upload = new \Think\Upload($config);// 实例化上传类
            $info =$upload->uploadOne($_FILES['file']);
            if(!$info){
                $output['message'] = $upload->getError();
                $this->ajaxReturn($output);
            }else{
                $imgPath = 'feedback'.$info['savepath'].$info['savename'];
            }   
        }
        //反馈类型(默认16，即服务态度投诉)
        if(!$type){
            $type = 16;
        }
        //匿名
        if($isanony){
            $uid = 0;
            $nickname = '匿名';
        }else{
            $uid = session('uid');
            $nickname = session('nickname') ?session('nickname') : session('username');
        }
        $title = mb_substr($content, 0,80,'UTF-8');
        $data = array(
            'uid'=>$uid,
            'nickname' => $nickname,
            'title' => $title,
            'creation_date' => NOW_TIME,
            'messagetype' => 1,
            'is_anony' => $isanony,
            'last_replytime' => NOW_TIME,
            'last_replynickname' => $nickname,
            'replynum' => 1,
            'communtype' => $type,
            'contactway' => $email
        );
        $messageModel = M('message');
        $res = $messageModel->add($data);
        if(intval($res) > 0){
            $cacheModel->setMc($key, NOW_TIME, 180);
            //图片
            if($imgPath){
                $data = array();
                $data['message_id'] = $res;
                $data['img'] =$imgPath;
                $imgModel = M('MessageImg');
                $imgModel->add($data);
            }
            $contentarray = array('0'=>array('content'=>addslashes($content),'nickname'=>addslashes($nickname)));
            $replyData = array(
                'mid' => $res,
                'uid' => $uid,
                'nickname' => $nickname,
                'content' => json_encode($contentarray),
                'reply_date' => NOW_TIME
            );
            $relpyModel = M('MessageReply');
            $replyRes = $relpyModel->add($replyData);
            $output['status'] = 1;
            $output['message'] = '发表成功';
        }else{
            $output['message'] = '发表失败，请稍后再试';
        }
        $this->ajaxReturn($output);
    }



}