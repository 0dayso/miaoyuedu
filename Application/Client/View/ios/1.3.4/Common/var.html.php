<script type="text/javascript">
    var hsConfig = {
        "DEEP": "{:C('URL_PATHINFO_DEPR')}", //PATHINFO分割符
        "VAR": ["{:C('VAR_MODULE')}", "{:C('VAR_CONTROLLER')}", "{:C('VAR_ACTION')}"],
        "MODULE": "{:C('MODULE_NAME')}",
        "CONTROLLER": "{:C('CONTROLLER_NAME')}",
        "ACTION": "{:C('ACTION_NAME')}",
        "PAGEVAR": "{:C('VAR_PAGE', NULL, 'p')}",
        "URL_MODEL": "{:C('URL_MODEL')}",
        "USERINFO": JSON.parse('{$userinfo|json_encode}'),
        "JSPATH":"__JS__",
        "ROUTER":{$jsRouter|json_encode},
        "SOURCE_VER":"{:C('SOURCE_VER')}",
    };
    if(!hsConfig.SOURCE_VER) {
        hsConfig.SOURCE_VER = '1.0';
    }
    if(!hsConfig.USERINFO.hasOwnProperty('uid')) {
        hsConfig.USERINFO = {uid: 0, nickname: '', islogin: false};
    }
    //网站设置
    var hs_const_domain = {
        hostName: "__HOMEDOMAIN__",
        waphostName: "http://g.hongshu.com",
        vipHostName: "http://vip.hongshu.com", //vip
        mmHostName: "__GIRLDOMAIN__", //女生首页
        ggHostName: "__BOYDOMAIN__", //男生首页
        payHostName: "__PAYDOMAIN__", //支付host
        commentHostName: "__HOMEDOMAIN__", //书评host,预留
        authorHostName: "__HOMEDOMAIN__", //作者中心,预留
        passportHostName: "__USERDOMAIN__", //用户(登录注册)中心,预留
        resourceHost: Do.getConfig('jsDomain'), //静态资源
        saferesourceHost: "__STATICURLSAFE__", //安全静态资源
        userHost: "__USERDOMAIN__", //user模块域名
        homeHost: "__HOMEDOMAIN__", //home模块域名
        payHost: "__PAYDOMAIN__", //支付模块域名
        bookHost: "__HOMEDOMAIN__", //book模块域名
        host: location.hostname,
        cookiedomain: "{:C('COOKIE_DOMAIN')}",
        cookieprefix: "{:C('COOKIE_PREFIX')}"
    };

    //url设置
    var hs_const_url = {
        ajax_userinfo: '{:url("Client/Userajax/checklogin", "", "do")}',
        //min_staticres : hs_const_domain.resourceHost + "/min/f=",//minify
        min_staticres: hs_const_domain.resourceHost, //minify
        staticres: hs_const_domain.resourceHost + "/Public/javascript", //web用的
        tjsitecount_js: hs_const_domain.resourceHost + '/Public/javascript/tjsitecount.js',
        refsitecounturl: hs_const_domain.hostName + '/sitecount/ref.do',
        clicktrackerurl: hs_const_domain.hostName + '/sitecount/click.do',
        userfaceurl: hs_const_domain.resourceHost + '/avatar/',
        userfacesafeurl: hs_const_domain.saferesourceHost + '/avatar/',
    };
    var siteurl = '__ROOT__';
    Do.ready('common', 'functions', function(){
        writeCookie('sex_flag', '{$sex_flag}');
    });
    var _banner = $('#scroll');
    if(_banner.length>0){
        $('#scroll').css({'margin-top':0});
    }

</script>