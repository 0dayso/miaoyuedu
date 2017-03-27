<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--其他登录开始-->
<div class="otherlogin mtop40 clearfix">
    <div class="logintit"><h4>通过第三方账号登录</h4></div>
    <if condition="isInWechat()">
    <ul>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'wechat','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_weixin2.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'wechat','fu'=>$M_forward),'do')}">微信登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'qq','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_qq.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'qq','fu'=>$M_forward),'do')}">QQ登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'baidu','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_baidu.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'baidu','fu'=>$M_forward),'do')}">百度登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'sina','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_weibo.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'sina','fu'=>$M_forward),'do')}">微博登录</a></p></li>
    </ul>
    <div class="otherlgbtn" onclick="$('#zfb').show();$(this).hide();"><a><img src="__IMG__/ic_more.png" /></a></div>
    <ul id="zfb" class="otherloginbtn clearfix" style="display: none">
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'alipay','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_zhifubao.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'alipay','fu'=>$M_forward),'do')}">支付宝登录</a></p></li>
    </ul>
    <else />
    <ul>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'qq','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_qq.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'qq','fu'=>$M_forward),'do')}">QQ登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'baidu','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_baidu.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'baidu','fu'=>$M_forward),'do')}">百度登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'sina','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_weibo.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'sina','fu'=>$M_forward),'do')}">微博登录</a></p></li>
        <li onClick="hg_gotoUrl('{:url('User/thirdlogin', array('type'=>'alipay','fu'=>$M_forward),'do')}');"><span><img src="__IMG__/ic_zhifubao.png"/></span><p><a href="{:url('User/thirdlogin', array('type'=>'alipay','fu'=>$M_forward),'do')}">支付宝登录</a></p></li>
    </ul>
    </if>
</div>
<!--其他登录结束-->
<!--登录开始-->
<div class="login login3 clearfix" style="margin-bottom: 50px;">
    <form>
        <p><a href="{:url('User/login', array('type'=>'id','fu'=>$M_forward,'sex_flag'=>$sex_flag),'do')}"><button type="button" class="radius4" onClick="hg_gotoUrl('{:url('User/login', array('type'=>'id','fu'=>$M_forward,'sex_flag'=>$sex_flag),'do')}')">用手机号或{:C('SITECONFIG.SITE_NAME')}账号登录</button></a></p>
        <!-- <p><a onClick="hg_gotoUrl('{:url('User/register',array('sex_flag'=>$sex_flag))}')" class="phbtn flrt">手机号注册</a></p> -->
       <p class=" cgray mtop20">登录即代表同意<span class="cred" onClick="hg_gotoUrl('{:url('Help/article',array('article_id'=>C('CLIENT.'.CLIENT_NAME.'.helpid'),'sex_flag'=>$sex_flag),'html')}')"><a href="{:url('Help/article',array('article_id'=>C('CLIENT.'.CLIENT_NAME.'.helpid'),'sex_flag'=>$sex_flag),'html')}">{:C('SITECONFIG.SITE_NAME')}服务条款</a></span></p>
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
</script>
</block>