<extend name="Common/base" />
<block name="body">
<!--其他登录开始-->
<div class="otherlogin mtop40 clearfix">
    <div class="logintit"><h4>通过第三方账号登录</h4></div>
    <ul>
        <li onclick="dowechatlogin();"><span><img src="__IMG__/ic_weixin2.png"/></span><p>微信登录</p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'qq','fu'=>$M_forward))}');"><span><img src="__IMG__/ic_qq.png"/></span><p>QQ登录</p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'baidu','fu'=>$M_forward))}');"><span><img src="__IMG__/ic_baidu.png"/></span><p>百度登录</p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'sina','fu'=>$M_forward))}');"><span><img src="__IMG__/ic_weibo.png"/></span><p>微博登录</p></li>
    </ul>
    <div class="otherlgbtn" onclick="$('#zfb').show();$(this).hide();"><a><img src="__IMG__/ic_more.png" /></a></div>
    <ul id="zfb" class="otherloginbtn clearfix"  style="display: none">
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'alipay','fu'=>$M_forward))}');"><span><img src="__IMG__/ic_zhifubao.png"/></span><p>支付宝登录</p></li>
    </ul>

</div>
<!--其他登录结束-->
<!--登录开始-->
<div class="login login3 clearfix">
    <form>
        <p><button type="button" class="radius4" onClick="hg_gotoUrl('{:url('User/login', array('type'=>'id','fu'=>$M_forward))}')">用手机号或{:C('SITECONFIG.SITE_NAME')}账号登录</button></p>
        <p class="clearfx"><a onClick="hg_gotoUrl('{:url('User/register')}')" class="phbtn flrt">手机号注册</a></p>
       <p class=" cgray mtop20">登录即代表同意<span class="cred" onClick="hg_gotoUrl('{:url('Help/Article',array('article_id'=>C('CLIENT.'.CLIENT_NAME.'.helpid')))}')">{:C('SITECONFIG.SITE_NAME')}服务条款</span></p>
    </form>
</div>
<!--登录结束-->
</block>
<block name="script">
<script type="text/javascript">
    Do.ready('lazyload',function(){
    Lazy.Load();
    document.onscroll = function(){
        Lazy.Load();
    };
});
    function dowechatlogin() {
        doClient('{"Action":"wechatlogin"}');
    }
</script>
</block>