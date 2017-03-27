<script type="text/javascript">
    var hsConfig = {
        "DEEP": "{:C('URL_PATHINFO_DEPR')}", //PATHINFO分割符
        "VAR": ["{:C('VAR_MODULE')}", "{:C('VAR_CONTROLLER')}", "{:C('VAR_ACTION')}"],
        "BIND_MODULE": "{:C('BIND_MODULE')}",   //默认模块
        "MODULE": "{:C('MODULE_NAME')}",
        "CONTROLLER": "{:C('CONTROLLER_NAME')}",
        "ACTION": "{:C('ACTION_NAME')}",
        "PAGEVAR": "{:C('VAR_PAGE', NULL, 'p')}",
        "URL_MODEL": "{:C('URL_MODEL')}",
        "USERINFO": {uid: 0, nickname: '', islogin: false, unloaded:true},
        "JSPATH":"__JS__",
        "ROUTER":{$jsRouter|json_encode},
        "SOURCE_VER":"{:C('SOURCE_VER')}",
    };
    if(!hsConfig.SOURCE_VER) {
        hsConfig.SOURCE_VER = '1.0';
    }

    //网站域名设置
    var hs_const_domain = {
        host: location.hostname,
        userdomain: "{:C('USERDOMAIN')}",
        clientdomain: "{:C('CLIENTDOMAIN')}",
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

    
</script>

