<extend name="Common/base" />
<block name="body">
<!--个人中心内容开始-->
<div class="unit mtop10">
    <div class="tit3 "><h5>账号：{$userinfo['username']}</h5><h5>ID：{$userinfo['uid']}</h5></div>
    <div class="frame5">
        <ul>
            <li onClick="go_url('User/changenickname')">昵称<span><em >{$userinfo['nickname']}</em><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="go_url('User/mobbind')">手机绑定<span><em >{$mobile}</em><img src="__IMG__/ic_after.png" /></span></li>
            <li onClick="go_url('User/changepwd')">密码修改<span><img src="__IMG__/ic_after.png" /></span></li>
            <li >会员等级<span><em class="cgray mlf10">{$userinfo['viplevelname']}</em></span></li>
            <li >积分等级<span><em class="cgray mlf10">{$userinfo['groupname']}</em></span></li>
        </ul>
    </div>
</div>

<!--个人中心内容结束-->
<!--更多按钮开始-->
<div class="unit" onClick="changeacount();">
    <div class="more2  cpink noborder">切换账号</div>
</div>
<!--更多按钮结束-->

</block>
<block name="script">
<script type="text/javascript">
	Do.ready('lazyload',function(){
	Lazy.Load();
	document.onscroll = function(){
		Lazy.Load();
	};
});
function changeacount(){
    var url=parseUrl('','User/login','open//','html');
    doClient(url) 
}

function go_url(gourl){
   var url=parseUrl('',gourl,'open//','html');
   doClient(url);
}
</script>
</block>