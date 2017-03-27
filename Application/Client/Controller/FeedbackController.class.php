<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: FeedbackController.class.php 1519 2017-02-28 03:13:03Z dingzi $
 */

namespace Client\Controller;

use Client\Common\Controller;

class FeedbackController extends Controller{
    public function _initialize(){
        parent::_initialize();
        $this->check_user_login();
    }
    /**
     * 反馈主页
     *
     * @return array $userinfo(用户信息)
     * @return int $popularnum(常见问题数量)
     * @return int $mynum(我的问题数量)
     * */
    public function _index() {
        $this->pageTitle = "反馈与意见";
        $uid = isLogin();
        if(!$uid){
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                doClient("User/login");
            }elseif (CLIENT_NAME == 'ios'){
                header("Location:".url('User/login',array("fu"=>url("Feedback/index")),'do'));
                exit;
            }else{
                $this->redirect(url('User/login',array("fu"=>url("Feedback/index",array(),'do')),'do'),'',2,'请先登录');
            }
        }
        //常见问题数量
        //$popularNum = M("message")->count();
        $popularNum = 0;
        S(C('rdconfig'));
        $popularNum = count(S('_newstaticfilelistmobilefaq'));
        //我的问题数量
        $myNum = 0;
        $fdmap = array("uid"=>$uid);
        $myfeedback = M("message")->where($fdmap)->count();
        if($myfeedback){
            $myNum = $myfeedback;
        }
        //dump($myfeedback);
        
//         if($myfeedback !== false && $myfeedback){
//             $myNum = count($myfeedback);
//             $mids = array_column($myfeedback,"mid");
//             $map = array(
//                 "mid"=>array("IN",$mids),
//             );
//             if(is_array($mids) && count($mids)>=1){
//         	       $replies = M("message_reply")->where($map)->select();
//         	       if($replies !== false){
//         	           $is_reply = true;
//         	       }else{
//         	           $is_reply = false;
//         	       }
//             }else{
//                 $is_reply = false;
//             }
//             $this->assign("is_reply",$is_reply);
//         }
        $this->assign('userinfo',session());
        $this->assign("popularnum",$popularNum);
        $this->assign("mynum",$myNum);
        $this->display();
    }

    /**
     * 反馈问题列表（我的问题）
     *
     * @param string type 请求类型（private我的问题、popular常见问题(暂时没有)）
     *
     * @return array 问题列表
     * */
    public function getfeedbackAction(){
        /*20条creation_date DESC*/
        $type = I("get.type","","trim");
        $this->pageTitle = "我的问题";
        $uid = isLogin();
        if(!$uid){
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                doClient("User/login");
            }elseif (CLIENT_NAME == 'ios'){
                header("Location:".url('User/login',array("fu"=>url("Feedback/getfeedback")),'do'));
                exit;
            }else{
                $this->redirect('User/login',array("fu"=>url("Feedback/getfeedback")),2,'请先登录');
            }
        }
        $map = array(
            "uid"=>$uid,
        );
        $myfeedbacks = M('message')->query("SELECT *,count(r.rid) as totalreply FROM wis_message as m left join `wis_message_reply` as r on r.mid=m.mid WHERE m.uid=".$uid." group by m.mid order by creation_date DESC");
        foreach($myfeedbacks as &$vo){
            $vo['creation_date'] = date("Y年m月d日",$vo['creation_date']);
        }

        $this->assign("myfeedbacks",$myfeedbacks);
        $this->assign("type",$type);
        $this->display();
    }
    /**
     * 留言反馈:留言回复页
     *
     * @param int $mid 某个反馈主题的id
     *
     * @return int $mid
     * */
    public function feedbackReplyAction(){
        $this->pageTitle = "我的反馈";
        $uid = isLogin();
        if(!$uid){
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                doClient("User/login");
            }elseif (CLIENT_NAME == 'ios'){
                header("Location:".url('User/login',array("fu"=>url("Feedback/feedbackReply")),'do'));
                exit;
            }else{
                $this->redirect(url('User/login',array("fu"=>url("Feedback/feedbackReply",array(),'do')),'do'),'',2,'请先登录');
            }
        }
        $mid = I('get.mid',0,'intval');
        if(!$mid){
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                _exit('参数错误！');
            }
            if(CLIENT_NAME == 'ios'){
                header("Location:".$this->M_forward);
                exit;
            }else{
                $this->redirect($this->M_forward,'',2,'参数错误');
            }
        }
        $map = array(
            'uid'=>$uid,
            'mid'=>$mid
        );
        $msginfo = M("message")->where($map)->find();
        if(!$msginfo){
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                _exit('参数错误！');
            }
            if(CLIENT_NAME == 'ios'){
                header("Location:".$this->M_forward);
                exit;
            }else{
                $this->redirect($this->M_forward,'',2,'参数错误');
            }
        }
        $msginfo['creation_date'] = date("Y年m月d日",$msginfo['creation_date']);

        $this->assign("msginfo",$msginfo);
        $this->assign('mid',$mid);
        $this->display();
    }
    /**
     * 喵阅读，反馈首页
     */
    public function _index_myd(){
        $this->pageTitle = '留言反馈';
        
        $this->assign('active','feedback');
        $this->display();
    }








}