<!--菜单结束-->
<!--一级导航栏开始-->
<div class="header">
    <div id="head1">
    <a class="logo" href="__ROOT__/nv.html"><img src="__IMG__/logo2.png"/></a>
<script type="text/javascript">
var ss = document.cookie;
var reg = new RegExp(/sex_flag=(\w+);/g);
var s1=reg.exec(ss);
var str = '<div class="top navnv">';
if(s1 && s1[1]!='nv') {
    str = '<div class="top navnan">';
}
document.writeln(str);
    </script>
        <div class="nav">
            <ul>
                <li ><a id="nav_class" href="__ROOT__/nv.html"><span>女生书城</span></a></li>
                <li class="nava"><a class="icnext"><img src="__IMG__/ic_arrow_drop_down.png" /></a></li>
                <li><a id="nav_top" href="{:url('Channel/rank')}"><span>排行</span></a></li>
                <li><a id="nav_search" href="{:url('Channel/search')}"><span>分类</span></a></li>
                <li><a id="nav_free" href="{:url('Channel/free')}"><span>免费</span></a></li>
                <li><a id="nav_pay" href="{:url('Pay/index')}"><span>充值</span></a></li>
            </ul>
        </div>

    </div>

    </div>
    <!--用户登录、等信息-->
    <div class="bom" id="head1_userinfo">
        <a href="{:url('Channel/search')}" class="sousuo"><span><img src="__IMG__/ic_search_grey.png" lazy="y" width="24" height="24" /></span>搜索</a>

        <div class="accounts">
            <span>
            	<!--
                	
                	描述：
                	{{ {sex_flag:sex_flag} | router:'User/login','do'}}
                	{:url('User/login', array('sex_flag'=>$sex_flag), 'do')}
                	---------------------------------
                	gotoFu('User/thirdlogin', {type:'sina'}, 'do');
                	{:url('User/thirdlogin', array('type'=>'sina'), 'do')}
                -->
            	
            	
                <a href="{:url('User/login', array('sex_flag'=>$sex_flag), 'do')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'User/login','do'}}')" style="cursor: pointer">登录</a>
                <a href="{:url('User/thirdlogin', array('type'=>'sina'), 'do')}" <img src="__IMG__/weibo-m.png" width="24" height="24" alt="微博登录"></a>
                <a href="{:url('User/thirdlogin', array('type'=>'qq'), 'do')}" <img src="__IMG__/qq-m.png" width="24" height="24" alt="qq登录"></a>
                <a href="{:url('User/thirdlogin', array('type'=>'baidu'), 'do')}" <img src="__IMG__/baidu-m.png" width="24" height="24" alt="百度登录"></a>
            </span>
        </div>
        
    </div>
</div>
<!--一级导航栏结束-->
<script type="text/html" id="userinfo_tpl">
    <a href="{:url('Channel/search')}" class="sousuo"><span><img src="__IMG__/ic_search_grey.png" lazy="y" width="24" height="24" /></span>搜索</a>
    {{if islogin}}
        <div class="accounts on">
            <span>
                <a href="{:url('User/shelf', array('sex_flag'=>$sex_flag), 'do')}">我的藏书</a>
                <a href="{:url('Book/cookiebookshelf', array('sex_flag'=>$sex_flag), 'do')}">阅读记录</a>
                <a href="{:url('User/index', array('sex_flag'=>$sex_flag), 'do')}"  class="username">{{nickname}}</a>
            </span>
        </div>
    {{else}}
        <div class="accounts">
            <span>
                <a href="{:url('User/login', array('sex_flag'=>$sex_flag), 'do')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'User/login','do'}}')" style="cursor: pointer">登录</a>
                <a href="{:url('User/thirdlogin', array('type'=>'sina'), 'do')}" <img src="__IMG__/weibo-m.png" width="24" height="24" alt="微博登录"></a>
                <a href="{:url('User/thirdlogin', array('type'=>'qq'), 'do')}" <img src="__IMG__/qq-m.png" width="24" height="24" alt="qq登录"></a>
                <a href="{:url('User/thirdlogin', array('type'=>'baidu'), 'do')}" <img src="__IMG__/baidu-m.png" width="24" height="24" alt="百度登录"></a>
            </span>
        </div>
    {{/if}}
</script>
<script>
    function gotoFu(str, para, ext) {
        var url = parseUrl(para, str, ext);
        var fu = window.location.href;
        url = changeURLArg(url, 'fu', fu);
        return hg_gotoUrl(url);
    }
</script>
<script type="text/html" id="headinfo1">
<a class="logo" href="__ROOT__/{{sex_flag}}.html" onClick="hg_gotoUrl('__ROOT__/{{sex_flag}}.html')"><img src="__IMG__/logo2.png"/></a>
    <div class="top nav{{sex_flag}}">
        <div class="nav">
            <ul>
                <li ><a id="nav_class" href="__ROOT__/{{sex_flag}}.html" onClick="hg_gotoUrl('__ROOT__/{{sex_flag}}.html')"><span>{{if sex_flag == 'nan'}}男{{else}}女{{/if}}生书城</span></a> </li>
                <li class="nava"><a class="icnext"><img src="__IMG__/ic_arrow_drop_down.png" /></a>
                   {{if sex_flag == 'nan'}}
                    <ul class="select2">
                        <li onClick="change_like('nv', '__ROOT__/nv.html');"><a >女生书城</a></li>
                    </ul>{{/if}} {{if sex_flag == 'nv'}}
                    <ul class="select2">
                        <li onClick="change_like('nan', '__ROOT__/nan.html');"><a>男生书城</a></li>
                    </ul>{{/if}}

                </li>
                <li><a id="nav_top" href="{:url('Channel/rank', array('sex_flag'=>$sex_flag), 'html')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Channel/rank','html'}}')"><span>排行</span></a></li>
                <li><a id="nav_search" href="{:url('Channel/search', array('sex_flag'=>$sex_flag), 'html')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Channel/search','html'}}')"><span>分类</span></a></li>
                <li><a id="nav_free" href="{:url('Channel/free', array('sex_flag'=>$sex_flag), 'html')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Channel/free','html'}}')"><span>免费</span></a></li>
                <li><a id="nav_pay" href="{:url('Pay/index', array('sex_flag'=>$sex_flag), 'do')}" onClick="hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Pay/index','do'}}')"><span>充值</span></a></li>
            </ul>
        </div>

    </div>
</script>