<?php
return array(//默认的客户端
    'DEFAULT_CLIENT' => 'myd',
    'VAR_PAGE'             => 'pagenum', //页码参数名称
    //自动命令空间
    'AUTOLOAD_NAMESPACE' => array('HS' => dirname(dirname(dirname(__FILE__))) . '/Common/HS'),
    'DEFAULT_CONTROLLER' => 'User', // 默认控制器名称
    'COOKIE_DOMAIN' => '.miaotest.com', // 这里必须是顶级域名，否则可能会出现跨域的情况而导致不能正常登录
    'SESSION_PREFIX'       => '', // session 前缀
    'SESSION_OPTIONS'      => array(
        'name' => 'yqksid'
    ),
    'URL_MODEL' => 2,
    'URL_ROUTER_ON' => true, // 是否开启URL路由
    'URL_ROUTE_RULES' => array(
        'index'               => 'Client/Index/index',
        'nan'                 => array('Client/Channel/index', array('sex_flag' => 'nan')),
        'nv'                  => array('Client/Channel/index', array('sex_flag' => 'nv')),
        'pays'                => 'Client/Pay/index',
        'books/:bid'          => 'Client/Book/view',
        'content/:bid/:chpid' => 'Client/Book/read',
        'vip/:bid/:chpid'     => 'Client/Book/readvip',
        'bookreader/:bid'     => 'Client/Book/chapterlist',
        'comment/:bid/index'  => 'Client/Book/comment',
        'readlog'             => 'Client/Book/cookiebookshelf',
        'searchclass/:classid' => 'Client/Channel/search',    //搜索
        'newbooks'            => 'Client/Channel/xinshu',   //新书
        'onsale'              => 'Client/Channel/tejia',    //特价
        'commentdetail/:bid/:comment_id' => 'Client/Book/replycomment', //评论详情页
        'reward/:bid'         => 'Client/Book/dashang',     //打赏
        'free'                => 'Client/Channel/free',      //免费
        'message'             => 'Client/Feedback/index',   //反馈首页
        'mymessage'           => 'Client/Feedback/getfeedback', //我的反馈列表页
        'messagedetail/:mid'  => 'Client/Feedback/feedbackreply', //我的反馈详情页
        'assit'               => 'Client/Help/index',        //帮助首页
        'question/:article_id' => 'Client/Help/article',     //帮助详情







        'center' => 'Usercenter/User/index',
        'myprofile' => 'Usercenter/User/personal',
        'join' => 'Usercenter/User/authorreg',
        'register' => 'Usercenter/User/register',
        'signin' => 'Usercenter/User/qiandao',     //签到
        'changenickname' => 'Usercenter/User/changenickname', //修改昵称
        'mobbind' => 'Usercenter/User/mobbind',     //绑定手机
        'changepwd' => 'Usercenter/User/changepwd',   //修改密码
        //'userlogin' => 'Usercenter/User/login',   //登录页
        'accountlogin' => 'Usercenter/User/loginwithid',     //账号登录页array('Usercenter/User/login',array('type'=>'id'))
        'loginform' => 'Usercenter/User/loginform', //选择登录方式页面array('Usercenter/User/login',array('action'=>'login_form'))
        'thirdlogin/:type' => 'Usercenter/User/thirdlogin', //第三方登录
        'lingjiang' => 'Usercenter/User/lingjiang',   //领奖
        'changeaccount' => 'Usercenter/User/changeaccount',  //切换账号
        'myshelf' => 'Usercenter/User/shelf',   //云端书架
        'findpwd' => 'Usercenter/User/losepwd', //忘记密码
        'islogin'=> 'Usercenter/Userajax/checklogin',
        'paylogs'=> 'Usercenter/User/paylogs',
    ));