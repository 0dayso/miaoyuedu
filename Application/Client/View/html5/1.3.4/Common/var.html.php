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
        homeHost: "__MOBDOMAIN__",
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

    //全站头部
    Do.ready('common', 'functions', function(){
        var sex_flag = '{$sex_flag|default=C('DEFAULT_SEX')}';
        if(!cookieOperate('sex_flag') && sex_flag && hsConfig.CONTROLLER!=='Index') {
            //cookie里没有，这时设置一个默认的，然后显示一次浮动条
            writeCookie('sex_flag', sex_flag, 3153600000);
            //console.log('display banner');
        }
        if(msg=cookieOperate('__msg__')){
            hg_Toast(msg, 1);
            writeCookie('__msg__', '', -1);
        }
        //绑定LI的onclick事件
        $('li[href]').each(function(index, obj) {
            var url = $(obj).attr('href');
            $(this).removeAttr('href');
            if(url){
                $(obj).on('click', function(){
                    hg_gotoUrl(url);
                    return false;
                });
            }
        });
        //绑定用户登录信息
        UserManager.addListener(function(user){
            Do.ready('template',function(){
                //console.log('user',user);
                if ($('#userinfo_tpl').length > 0) {
                    var obj1 = $('#head1_userinfo');
                    var obj = $('#head2_userinfo');
                    user.sex_flag=cookieOperate('sex_flag');
                    if(!user.sex_flag){
                       user.sex_flag="{:C('DEFAULT_SEX')}";
                   }
                    if(obj.length){
                        var html = template('userinfo_tpl',user);
                        obj.html(html);
                    }
                    if(obj1.length){
                        var html = template('userinfo_tpl',user);
                        obj1.addClass('clearfix');
                        obj1.html(html);
                    }
                }
            })
        });

        //菜单激活状态
        $(document).ready(function () {
            if (hsConfig.CONTROLLER === 'Book' || hsConfig.CONTROLLER === 'Channel') {
                switch (hsConfig.ACTION) {
                    case 'free':
                        $('#nav_free').addClass('active');
                        break;
                    case 'search':
                        $('#nav_search').addClass('active');
                        break;
                    case 'rank':
                        $('#nav_top').addClass('active');
                        break;
                    case 'index':
                        $('#nav_class').addClass('active');
                        break;
                }
            } else if (hsConfig.CONTROLLER === 'Pay') {
                $('#nav_pay').addClass('active');
            } else if (hsConfig.CONTROLLER === 'Index') {
                $('#nav_class').addClass('active');
            }
        });
        var _banner = $('#scroll');
        if(_banner.length>0){
            $('#scroll').css({'margin-top':0});
        }
    });

var _url = '';
if (window.parent != window.self){
    try{_url = parent.document.location;}catch(err){ _url = document.location;}
}else{
    _url = document.location;
}
var sjbfy;
var _bid='{$bookinfo['bid']}';
var _chapterid='{$chapterinfo.chapterid}';
var erweimadiv = $('.ewm_p');

var _p = _url.hash;
if(_p){
    var fromsid = _p.substring(_p.indexOf('fromsid=')+8,_p.length);
    if(fromsid!=undefined && fromsid!='' && fromsid!=null){
        //if(fromsid=='225' || fromsid=='226' || fromsid=='237' || fromsid=='236' || fromsid=='223' || fromsid=='224'){
        /*
            if(erweimadiv!=undefined){
                Do.ready('functions',function(){


                    sjbfy = 1;

                    erweimadiv.remove();

                    sjbfy = parseInt(sjbfy)+1;
                    writeCookie('sjbfy',sjbfy,86400);
                })

            }
        */
        //}
        var img=new Image();
        img.setAttribute('lazy','y');

        img.src="__HOMEDOMAIN__/{:url('Home/Tjsitecount/setsid','','do')}?sid="+fromsid;

        $('body').append(img);
    }
}
/*
if(erweimadiv!=undefined){
    Do.ready('functions',function(){

        sjbfy = cookieOperate('sjbfy');

        if(sjbfy!=undefined && sjbfy!=null && sjbfy!=''){
            sjbfy = parseInt(sjbfy);

            if(sjbfy<=10){
               erweimadiv.remove();
            }
            sjbfy = parseInt(sjbfy)+1;
            writeCookie('sjbfy',sjbfy,86400);
        }
    })
}
*/
Do.ready('template',function(){
    var data={};
   data.sex_flag=cookieOperate('sex_flag');
   if(!data.sex_flag){
       data.sex_flag="{:C('DEFAULT_SEX')}";
   }
   var object_head1=$('#head1');
   if(object_head1.length){
    var htmls=template('headinfo1',data);
       object_head1.html(htmls);
   }
   var object_foot1=$('#foot1');
   if(object_foot1.length){
      var htmls=template('footinfo',data);
      object_foot1.html(htmls);
   }
});
</script>
