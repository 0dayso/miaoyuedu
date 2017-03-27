<?php
return array(
    'LOAD_EXT_CONFIG'      => 'client,router,credit,messages,payactivity',
    'TAGLIB_PRE_LOAD'      => 'Client\\TagLib\\Hongshu',
    'LOG_RECORD'           => true, // 记录日志
    'ALLOWREPLY'           => '30', //允许用户发回复间隔，单位为秒
    'ALLOWCOMMENT'         => '30', //允许用户发书评间隔，单位为秒
    'COMMENTSIZE'          => 15, //书评每页显示数量
    'REPLYSIZE'            => 15, //书评回复每页显示数量
    'CHPT_CANDISPLAY'      => 1, //可以显示，白名单章节自动通过
    'TICKETSET'            => array('senddaymax' => 2, 'sendmonthmax' => 5),
    'BOOK_CANDISPLAY'      => 1, //是否可以显示?
    'ALLOWZAN'             => '300', //允许用户为评论赞的间隔，单位为秒
    'CHPTABLESIZE'         => 50000, //分表条数
    'FROMDB'               => false, //是否从数据库取数据，false:从缓存取，true：从数据库取
    //Cookie设置跟随原M站的设置
    'COOKIE_PREFIX'        => '', // Cookie前缀 避免冲突
    'COOKIE_PATH'          => '/',
    'SESSION_PREFIX'       => '', // session 前缀
    'SESSION_OPTIONS'      => array(
        'name' => 'yqksid'
    ),
    //自动命令空间
    'AUTOLOAD_NAMESPACE'   => array(
        'HS' => dirname(dirname(dirname(__FILE__))) . '/Common/HS'
    ),
    'VAR_PAGE'             => 'pagenum', //页码参数名称
    'COMMENTMINSIZE'       => 4, //手机版评论最小字数
    'COMMENTMAXSIZE'       => 1000, //手机版评论最大字数
    'SOURCE_VER'           => '0909', //静态资源版本号
    //渠道号cookie前缀和域名
    'CHANNEL_COOKIE_PREFIX' => 'hsc',
    'CHANNEL_COOKIE_DOMAIN' => '.hongshu.com',
);
