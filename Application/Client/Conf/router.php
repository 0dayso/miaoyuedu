<?php
/**
 * 模块: 言情控
 *
 * 功能: 路由配置
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 */
return array(
    //'URL_HTML_SUFFIX' => 'php', // URL伪静态后缀设置
    'URL_MODEL'       => 2,
    'URL_ROUTER_ON'   => true, // 是否开启URL路由
    'URL_ROUTE_RULES' => array(
        'index'               => 'Client/Index/index',
        'nan'                 => array('Client/Channel/index', array('sex_flag' => 'nan')),
        'nv'                  => array('Client/Channel/index', array('sex_flag' => 'nv')),
        //'free'                => 'Client/Channel/free',
        //'class/:classid'      => 'Client/Channel/search',
        //'search'              => 'Client/Channel/search',
        //'top'                 => 'Client/Channel/rank',
        'pays'                => 'Client/Pay/index',
        //'usercenter'          => 'Client/User/index',
        'myprofile'           => 'Client/User/personal',
        'join'                => 'Client/User/authorreg',
        'register'            => 'Client/User/register',
        'books/:bid'          => 'Client/Book/view',
        'content/:bid/:chpid' => 'Client/Book/read',
        'vip/:bid/:chpid'     => 'Client/Book/readvip',
        'bookreader/:bid'     => 'Client/Book/chapterlist',
        'comment/:bid/index'  => 'Client/Book/comment',
        'readlog'             => 'Client/Book/cookiebookshelf',
        'signin'              => 'Client/User/qiandao',     //签到
        'searchclass/:classid' => 'Client/Channel/search',    //搜索
        'newbooks'            => 'Client/Channel/xinshu',   //新书
        'onsale'              => 'Client/Channel/tejia',    //特价
        'commentdetail/:bid/:comment_id' => 'Client/Book/replycomment', //评论详情页  
        'reward/:bid'         => 'Client/Book/dashang',     //打赏
        'free'                => 'Client/Channel/free',      //免费
        'changenickname'      => 'Client/User/changenickname', //修改昵称
        'mobbind'             => 'Client/User/mobbind',     //绑定手机
        'changepwd'           => 'Client/User/changepwd',   //修改密码
        'message'             => 'Client/Feedback/index',   //反馈首页
        'mymessage'           => 'Client/Feedback/getfeedback', //我的反馈列表页
        'messagedetail/:mid'  => 'Client/Feedback/feedbackreply', //我的反馈详情页
        'assit'               => 'Client/Help/index',        //帮助首页
        'question/:article_id' => 'Client/Help/article',     //帮助详情
        'userlogin'           => 'Client/User/login',   //登录页  
        'accountlogin'        => 'Client/User/loginwithid',     //账号登录页array('Client/User/login',array('type'=>'id'))
        'loginform'           => 'Client/User/loginform', //选择登录方式页面array('Client/User/login',array('action'=>'login_form'))
        'thirdlogin/:type'    => 'Client/User/thirdlogin', //第三方登录
        'lingjiang'           => 'Client/User/lingjiang',   //领奖
        'changeaccount'       => 'Client/User/changeaccount',  //切换账号
        'myshelf'             => 'Client/User/shelf',   //云端书架
        'findpwd'             => 'Client/User/losepwd', //忘记密码  
    )
);
