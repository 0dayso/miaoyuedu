<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--banner2开始-->
<!-- TODO:区分男女背景 ，修改下列class样式就好,判断依据，$sex_flag-->
<div class="banner2 banner2_{$sex_flag|default='nan'}"><div class="lf"><if condition="$userinfo['avatar']"><img src="{$userinfo['avatar']}" lazy="y"/><else/><img src="__IMG__/avater.jpg" lazy="y"/></if></div><div class="rt"><h1>{$userinfo['nickname']}</h1><p>ID ：{$userinfo['uid']}</p></div> </div>
<!--banner2结束-->
<!--个人中心内容开始-->
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('User/personal',array('sex_flag'=>$sex_flag),'do')}')">个人资料<span><a href="{:url('User/personal',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('Pay/index',array('idid'=>'usercenter','sex_flag'=>$sex_flag),'do')}')">充值
                <span>
                    余额：{$userinfo['money']}{:C('SITECONFIG')['MONEY_NAME']}，{$userinfo['egold']}{:C('SITECONFIG')['EMONEY_NAME']}
                    <a href="{:url('Pay/index',array('idid'=>'usercenter','sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/paylogs',array('sex_flag'=>$sex_flag),'do')}')">充值记录<span><a href="{:url('User/paylogs',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/salelogs',array('sex_flag'=>$sex_flag),'do')}')">消费记录<span><a href="{:url('User/salelogs',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/card',array('sex_flag'=>$sex_flag),'do')}')">红薯卡<span><a href="{:url('User/card',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
        </ul>
    </div>
</div>
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('User/shelf',array('sex_flag'=>$sex_flag),'do')}')">我的书架<span><a href="{:url('User/shelf',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('Book/cookiebookshelf',array('sex_flag'=>$sex_flag),'do')}')">阅读记录<span><a href="{:url('Book/cookiebookshelf',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/setbooking',array('sex_flag'=>$sex_flag),'do')}')">订阅设置<span><a href="{:url('User/setbooking',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
        </ul>
    </div>
</div>
<div class="unit">
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('Help/index',array('sex_flag'=>$sex_flag),'html')}')">帮助<span><a href="{:url('Help/index',array('sex_flag'=>$sex_flag),'html')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('Feedback/index',array('sex_flag'=>$sex_flag),'do')}')">反馈<span><a href="{:url('Feedback/index',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/ChangeAccount',array('sex_flag'=>$sex_flag),'do')}')">切换账号<span><a href="{:url('User/ChangeAccount',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
        </ul>
    </div>
</div>
<!--个人中心内容结束-->
<!--更多按钮开始-->
<div class="unit">
<div class="more2  cpink noborder" onClick="hg_gotoUrl('{:url('User/logout',array('sex_flag'=>$sex_flag),'do')}')">退出账号</div>
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