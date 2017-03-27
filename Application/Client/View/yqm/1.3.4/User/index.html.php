<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--banner2开始-->
<!-- TODO:区分男女背景 ，修改下列class样式就好,判断依据，$sex_flag-->
<div class="banner2 banner2_{$sex_flag|default='nan'}"><div class="lf"><img src="__IMG__/avater.jpg" /></div><div class="rt"><h1>{$userinfo['nickname']}</h1><p>ID ：{$userinfo['uid']}</p></div> </div>
<!--banner2结束-->
<!--个人中心内容开始-->
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('User/personal')}')">个人资料<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('Pay/index',array('idid'=>'usercenter'))}')">充值
                <span>
                    余额：{$userinfo['money']}{:C('SITECONFIG')['MONEY_NAME']}，{$userinfo['egold']}{:C('SITECONFIG')['EMONEY_NAME']}
                    <img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('User/paylogs')}')">充值记录<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('User/salelogs')}')">消费记录<span><img src="__IMG__/ic_after.png" /></span></li>
        </ul>
    </div>
</div>
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('User/shelf')}')">我的书架<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('User/cookiebookshelf')}')">阅读记录<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('User/setbooking')}')">订阅设置<span><img src="__IMG__/ic_after.png" /></span></li>
        </ul>
    </div>
</div>
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('Help/index')}')">帮助<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('Feedback/index')}')">反馈<span><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="hg_gotoUrl('{:url('User/ChangeAccount')}')">切换账号<span><img src="__IMG__/ic_after.png" /></span></li>
        </ul>
    </div>
</div>
<!--个人中心内容结束-->
<!--更多按钮开始-->
<div class="unit">
<div class="more2  cpink noborder" onClick="hg_gotoUrl('{:url('User/logout')}')">退出账号</div>
</div>
<!--更多按钮结束-->
</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', function(){
	UserManager.addListener(function(user){
		//这里判断user.islogin用来处理登录后的事件
	});
});
Do.ready('lazyload',function(){
    Lazy.Load();
    document.onscroll = function(){
        Lazy.Load();
};
});
</script>
</block>