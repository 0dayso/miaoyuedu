<?php
/**
 * 注意，这里是通用的支付配置文件，不允许自行修改！
 */
return array(
    'alipayconfig'     => array(
        'partner'        => '2088701562127742',
        'key'            => 'qmdbn4j33pbegauj2gso29r4o6u6n651',
        'seller_email'   => 'xulina@zhangyue.com',
        'return_url'     => 'http://pay.hongshu.com/alipay/alipay_return_url_new.php',
        'return_url_wap' => 'http://pay.hongshu.com/alipay/alipay_return_url_new_wap.php',
        'notify_url'     => 'http://pay.hongshu.com/alipay/alipay_notify_url.php',
        'sign_type'      => 'MD5',
        'input_charset'  => 'utf-8',
        'transport'      => 'http',
        'showurl'        => 'https://wallet.hongshu.com/profile/pay.html'
    ),
    //微信pc扫码支付
    'wechatconfig'     => array(
        'partner'    => '1217213901',
        'key'        => '7e59a171a947f3ebba40a766ee9d8ee6',
        'return_url' => 'http://pay.hongshu.com/weixin/weixinpay_ReturnUrl_new.php',
        'notify_url' => 'http://pay.hongshu.com/weixin/weixinpay_NotifyUrl.php',
        'submit_url' => 'https://gw.tenpay.com/gateway/pay.htm'
    ),
    //易宝网银支付
    'bankconfig'       => array(
        'partner'    => '10011147241',
        'key'        => '4WmvJ1IjK20122R9y6oC88Xck041145746B39m65YGT8KM78V610DtIIP5Fp',
        'return_url' => 'http://pay.hongshu.com/req/yeepaycallback_new.php',
        'submit_url' => 'https://www.yeepay.com/app-merchant-proxy/node'
    ), //财付通pc支付
    'tencentconfig'    => array(
        'partner'    => '1217183901',
        'key'        => '8fc0ca72e2ed0e6eefd1b81665119d8a',
        'return_url' => 'http://pay.hongshu.com/tenpay/tenpayweb_ReturnUrl_new.php',
        'notify_url' => 'http://pay.hongshu.com/tenpay/tenpayweb_NotifyUrl.php',
        'submit_url' => 'https://gw.tenpay.com/gateway/pay.htm'
    ),
    //易宝卡支付
    'phonecardconfig'  => array(
        'partner'    => '10011147241',
        'key'        => '4WmvJ1IjK20122R9y6oC88Xck041145746B39m65YGT8KM78V610DtIIP5Fp',
        'return_url' => 'http://pay.hongshu.com/yeepaycard/yeepaycardcallback_new.php',
        'submit_url' => 'https://www.yeepay.com/app-merchant-proxy/command.action'
    ),
    'wechatwapconfig'  => array(
        'APPID'                 => 'wx6c945733843fd13f',
        //受理商ID，身份标识
        'MCHID'                 => '1245464102',
        //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
        'KEY'                   => 'hongshuwhongshuwhongshuwhongshuw',
        //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        'APPSECRET'             => 'b0f0c9756f8aa7341d7df03616302fd4',
        //' =>' =>' =>' =>' =>' =>' =>【JSAPI路径设置】' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>
        //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
        'JS_API_CALL_URL'       => 'https://i.hongshu.com/pay/wechat/wap.do',
        //第二版的wap支付,微信code返回页面,获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
        'JS_API_WAPV2_CALL_URL' => 'https://i.hongshu.com/pay/wechat/wapv2.do',
        //' =>' =>' =>' =>' =>' =>' =>【证书路径设置】' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>
        //证书路径,注意应该填写绝对路径
        'SSLCERT_PATH'          => '/data/server/www/tpresource/ThinkPHP/Library/Org/WxPayPubHelper/cacert/apiclient_cert.pem',
        'SSLKEY_PATH'           => '/data/server/www/tpresource/ThinkPHP/Library/Org/WxPayPubHelper/cacert/apiclient_key.pem',
        //' =>' =>' =>' =>' =>' =>' =>【异步通知url设置】' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>
        //异步通知url，商户根据实际开发过程设定
        'NOTIFY_URL'            => 'https://i.hongshu.com/pay/wechat/wapnotify.do',
        //' =>' =>' =>' =>' =>' =>' =>【curl超时设置】' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>' =>
        //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
        'CURL_TIMEOUT'          => 30,
    ),
    'alipaywapconfig'  => array(
        'partner'            => "2088701562127742", //合作身份者ID，以2088开头的16位纯数字
        'key'                => "qmdbn4j33pbegauj2gso29r4o6u6n651", //安全检验码，以数字和字母组成的32位字符
        'seller_email'       => "xulina@zhangyue.com", //签约支付宝账号或卖家支付宝帐户
        'notify_url'         => "http://pay.hongshu.com/alipaywap/notify_url.php", //异步返回消息通知页面，用于告知商户订单状态
        'call_back_url'      => "https://wallet.hongshu.com/pay/alipaywap/return_url.do", //同步返回消息通知页面，用于提示商户订单状态
        'merchant_url'       => "http://m.hongshu.com/pay.php", //网站商品的展示地址
        'subject'            => "红薯币", //产品名称
        //以下内容不需要修改,固定参数
        'Service_Paychannel' => "mobile.merchant.paychannel",
        'Service1'           => "alipay.wap.trade.create.direct", //接口1
        'Service2'           => "alipay.wap.auth.authAndExecute", //接口2
        'format'             => "xml", //http传输格式
        'sec_id'             => "MD5", //签名方式 不需修改
        '_input_charset'     => "UTF-8", //字符编码格式
        'v'                  => "2.0", //版本号
        'write_return_log'   => 1, //是否记录同步返回接口接收到的参数
    ),
    'tenpaywapconfig'  => array(
        'partner'          => "1217183401", //合作伙伴ID
        'key'              => "81efc6f21cb772253ee34542b4ca1e20", //安全检验码
        'callback_url'     => 'https://i.hongshu.com/pay/tenpaywap/return_url.do',
        'notify_url'       => 'http://pay.hongshu.com/tenpaywap/tenpaywap_NotifyUrl.php',
        'write_return_log' => 1, //是否记录同步返回接口接收到的参数
    ),
    //微信SDK 2016-06-14 by dingzi
    'wechatandroidsdk' => array(
        'appid'      => 'wx111d0b81a3faa894',
        'mch_id'     => '1264064001',
        'notify_url' => 'https://wallet.hongshu.com/android/weixinnotify.do',
    ),
    //支付宝SDK 2016-06-15 by dingzi
    'alipayandroidsdk' => array(
        'partner'             => '2088701562127742', //合作身份者id，以2088开头的16位纯数字
        'key'                 => 'qmdbn4j33pbegauj2gso29r4o6u6n651', //安全检验码，以数字和字母组成的32位字符
        'seller_email'        => 'xulina@zhangyue.com', //签约支付宝账号或卖家支付宝帐户
        
        'return_url'          => 'https://wallet.hongshu.com/android/alipayreturn.do', //页面跳转同步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
        'notify_url'          => 'https://wallet.hongshu.com/android/alipaynotify.do', //服务器异步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
        'private_key_path'    => THINK_PATH . '/Library/Org/Alipay/cacert/rsa_private_key.pem', //商户的私钥（后缀是.pen）文件相对路径
        'ali_public_key_path' => THINK_PATH . '/Library/Org/Alipay/cacert/ali_public_key.pem', //支付宝公钥（后缀是.pen）文件相对路径
        'sign_type'           => strtoupper('RSA'), //签名方式 不需修改
        'input_charset'       => strtolower('utf-8'), //字符编码格式 目前支持 gbk 或 utf-8
        'cacert'              => THINK_PATH . '/Library/Org/Alipay/cacert/cacert.pem', //ca证书路径地址，用于curl中ssl校验,请保证cacert.pem文件在当前文件夹目录中
        'transport'           => 'https', //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    ),
    'PAY_LISTS'        => array(
        'SZX'              => array(
            'SCALE' => 85,
            'NAME'  => '神州行充值卡',
        ),
        'CMBCHINA-NET-B2C' => array(
            'SCALE' => 100,
            'NAME'  => '招行网银',
        ),
        'ICBC-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '工行网银',
        ),
        'ABC-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '农行网银',
        ),
        'CCB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '建行网银',
        ),
        'BOCO-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '交行网银',
        ),
        'CIB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '兴业银行网银',
        ),
        'BOC-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '中国银行网银',
        ),
        'ECITIC-NET-B2C'   => array(
            'SCALE' => 100,
            'NAME'  => '中信银行网银',
        ),
        'POST-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '邮政网银',
        ),
        'CEB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '光大网银',
        ),
        'HXB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '华夏银行网银',
        ),
        'HKBEA-NET-B2C'    => array(
            'SCALE' => 100,
            'NAME'  => '东亚银行网银',
        ),
        'SHB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '上海银行网银',
        ),
        'CMBC-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '民生银行网银',
        ),
        'SDB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '深发展网银',
        ),
        'GDB-NET-B2C'      => array(
            'SCALE' => 100,
            'NAME'  => '广东银行网银',
        ),
        'SPDB-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '上海浦东发展银行网银',
        ),
        'BCCB-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '北京银行网银',
        ),
        'NJCB-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '南京银行网银',
        ),
        'NBCB-NET-B2C'     => array(
            'SCALE' => 100,
            'NAME'  => '宁波银行网银',
        ),
        'PINGANBANK-NET'   => array(
            'SCALE' => 100,
            'NAME'  => '平安银行网银',
        ),
        'BNAK_NONGHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '农行汇款',
        ),
        'BANK_GONGHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '工行汇款',
        ),
        'BANK_JIANHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '建行汇款',
        ),
        'BNAK_YOUZHENG'    => array(
            'SCALE' => 100,
            'NAME'  => '邮政汇款',
        ),
        'BNAK_ZHONHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '中行汇款',
        ),
        'BNAK_ZHAOHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '招行汇款',
        ),
        'BNAK_JIAOHANG'    => array(
            'SCALE' => 100,
            'NAME'  => '交行汇款',
        ),
        'ALIPAY'           => array(
            'SCALE' => 100,
            'NAME'  => '支付宝',
        ),
        'TENPAY'           => array(
            'SCALE' => 100,
            'NAME'  => '财付通',
        ),
        'WEIXINPAY'        => array(
            'SCALE' => 98,
            'NAME'  => '微信',
        ),
        'WEIXINPAY_QRCODE' => array(
            'SCALE' => 98,
            'NAME'  => '微信扫码',
        ),
        'YPCARD'           => array(
            'SCALE' => 90,
            'NAME'  => '易宝游戏卡',
        ),
        'OFFER99'          => array(
            'SCALE' => 1,
            'NAME'  => '易瑞特',
        ),
        'UNICOM'           => array(
            'SCALE' => 85,
            'NAME'  => '联通充值卡',
        ),
        'NETEASE'          => array(
            'SCALE' => 75,
            'NAME'  => '网易一卡通',
            'ALIAS' => 'NETEASE-NET',
        ),
        'QQCARD'           => array(
            'SCALE' => 75,
            'NAME'  => 'Q币卡',
            'ALIAS' => 'QQCARD-NET',
        ),
        'SNDACARD'         => array(
            'SCALE' => 75,
            'NAME'  => '盛大点卡',
            'ALIAS' => 'SNDACARD-NET',
        ),
        'SOHU'             => array(
            'SCALE' => 75,
            'NAME'  => '搜狐点卡',
            'ALIAS' => 'SOHU-NET',
        ),
        'ZHENGTU'          => array(
            'SCALE' => 75,
            'NAME'  => '征途点卡',
            'ALIAS' => 'ZHENGTU-NET',
        ),
        'JIUYOU'           => array(
            'SCALE' => 75,
            'NAME'  => '久游点卡',
            'ALIAS' => 'JIUYOU-NET',
        ),
        'WANMEI'           => array(
            'SCALE' => 75,
            'NAME'  => '完美点卡',
            'ALIAS' => 'WANMEI-NET',
        ),
        'UMPAY'            => array(
            'SCALE' => 40,
            'NAME'  => '移动手机短信',
        ),
        'CTY_LIANTONG'     => array(
            'SCALE' => 40,
            'NAME'  => '联通手机短信',
        ),
        'CTY_DIANXING'     => array(
            'SCALE' => 40,
            'NAME'  => '电信手机短信',
        ),
        'ALIPAY_WAP'       => array(
            'SCALE' => 100,
            'NAME'  => '手机版支付宝',
        ),
        'TENPAY_WAP'       => array(
            'SCALE' => 99,
            'NAME'  => '手机版财付通',
        ),
        'CTCPAY'           => array(
            'SCALE' => 60,
            'NAME'  => '天翼手机短信',
        ),
        'SMS_DMJD'         => array(
            'SCALE' => 40,
            'NAME'  => '移动短信话费',
        ),
        'PAYQIHOO'         => array(
            'SCALE' => 50,
            'NAME'  => '奇付通',
        ),
        'PAYQIHOOMOB'      => array(
            'SCALE' => 25,
            'NAME'  => '奇付通短信',
        ),
        'SFYJSDK'          => array(
            'SCALE' => 40,
            'NAME'  => '客户端短信充值(盛峰SDK)',
        ),
        'YDHX_DMJD'        => array(
            'SCALE' => 40,
            'NAME'  => '移动话费充值(动漫基地)',
        ),
        'SMS_MMJD'         => array(
            'SCALE' => 40,
            'NAME'  => '移动手机充值(mm基地)',
        ),
        'APPLEPAY'         => array(
            'SCALE' => 70,
            'NAME'  => '苹果官方支付',
        ),
        'IPAYNOW'          => array(
            'SCALE' => 100,
            'NAME'  => '现在支付',
        ),
        'MHDXPAY'          => array(
            'SCALE' => 40,
            'NAME'  => '客户端盛峰短信充值',
        ),
        'MHDXPAY_WEB'      => array(
            'SCALE' => 40,
            'NAME'  => '网页版盛峰短信充值',
        ),
        'ROBOTUSER'        => array(
            'SCALE' => 100,
            'NAME'  => '机器人帐户渠道',
        ),
        //以下是以前曾经支持过但是找不到相关信息的
        'JUNNET-NET'       => array(
            'SCALE' => 100,
            'NAME'  => 'JUNNET-NET'
        ),
        'SDKPAY'           => array(
            'SCALE' => 100,
            'NAME'  => 'SDKPAY'
        ),
        'TELECOM-NET'      => array(
            'SCALE' => 100,
            'NAME'  => 'TELECOM-NET'
        ),
        'UNIONPAY'         => array(
            'SCALE' => 100,
            'NAME'  => '银联在线支付'
        ),
    )
);
