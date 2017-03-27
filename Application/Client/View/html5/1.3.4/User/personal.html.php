<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--个人中心内容开始-->
<div class="unit">
    <div class="tit3 "><h5>账号：{$userinfo['username']}</h5><h5>ID：{$userinfo['uid']}</h5></div>
    <div class="frame5">
        <ul>
            <li onClick="hg_gotoUrl('{:url('User/changenickname',array('sex_flag'=>$sex_flag),'do')}')">昵称<span><em >{$userinfo['nickname']}</em><a href="{:url('User/changenickname',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/mobbind',array('sex_flag'=>$sex_flag),'do')}')">手机绑定<span><em >{$mobile}</em><a href="{:url('User/mobbind',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li onClick="hg_gotoUrl('{:url('User/changepwd',array('sex_flag'=>$sex_flag),'do')}')">密码修改<span><a href="{:url('User/changepwd',array('sex_flag'=>$sex_flag),'do')}"><img src="__IMG__/ic_after.png" /></a></span></li>
            <li >会员等级<span><em class="cgray mlf10">{$userinfo['viplevelname']}</em></span></li>
            <li >积分等级<span><em class="cgray mlf10">{$userinfo['groupname']}</em></span></li>
        </ul>
    </div>
</div>

<!--个人中心内容结束-->
<!--更多按钮开始-->
<div class="unit" onClick="hg_gotoUrl('{:url('User/ChangeAccount',array('sex_flag'=>$sex_flag),'do')}')">
    <a href="{:url('User/ChangeAccount',array('sex_flag'=>$sex_flag),'do')}"><div class="more2  cpink noborder">切换账号</div></a>
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

</script>
</block>