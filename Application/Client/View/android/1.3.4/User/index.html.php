<extend name="Common/base" />
<block name="body">
<div class="homebg">
    <div class="left"><if condition="$userinfo['avatar']"><img src="{$userinfo['avatar']}" class="avater"/><else/><img src="__IMG__/avater.jpg" class="avater"/></if></div>
    <div class="right perxinxi">
        <div class="rttop">{$userinfo['nickname']}</div>
        <div class="rtbom"><span>{$userinfo['viplevelname']}</span><span>{$userinfo['groupname']}</span></div>
    </div>
    <div class="homesignbtn btnshadow" onClick="hg_gotoUrl('{:url('User/qiandao')}')">签到<!--登录成多加一个类名active--></div></div>
<div class="pertitbg"><span class="pertit c54">我的帐户</span></div>
<div class="homecon borderbottom rtarrow displaybox2">
    <ul>
        <li onClick="hg_gotoUrl('{:url('User/personal')}')">
            <div class="left"><span class="ic_account ic_tit"></span></div>
            <div class="right">个人资料</div>
        </li>
        <li onClick="hg_gotoUrl('{:url('Pay/index',array('idid'=>'usercenter'))}')">
            <div class="left"><span class="ic_chongzhi ic_tit"></span></div>
            <div class="right">充值<span class="yue">{$userinfo['money']}{:C('SITECONFIG')['MONEY_NAME']}</span></div>
        </li>
        <li onClick="hg_gotoUrl('{:url('User/paylogs')}')">
            <div class="left"><span class="ic_record ic_tit"></span></div>
            <div class="right">充值记录</div>
        </li>
        <li onClick="hg_gotoUrl('{:url('User/salelogs')}')">
            <div class="left"><span class="ic_shopping ic_tit"></span></div>
            <div class="right ">消费记录</div>
        </li>

    </ul>
</div>
<div class="pertitbg"><span class="pertit c54">其他</span></div>
<div class="homecon borderbottom rtarrow displaybox2">
    <ul>
        <li onClick="hg_gotoUrl('{:url('User/shelf')}')">
            <div class="left"><span class="ic_mybook ic_tit"></span></div>
            <div class="right">我的云书架</div>
        </li>
        <li onClick="hg_gotoUrl('{:url('User/setbooking')}')">
            <div class="left"><span class="ic_setting ic_tit"></span></div>
            <div class="right">订阅设置</div>
        </li>
    </ul>
</div>

<!--更多按钮开始-->
<div class="unit mtop20" onClick="changeacount()">
    <div class="more2  cred noborder">切换账号</div>
</div>
<!--更多按钮结束-->
</block>
<block name="script">
<script type="text/javascript">
function changeacount(){
    var url='{:url('User/ChangeAccount')}';
    doClient({Action:'saveP30',P30:'empty',fu:url});
    hg_gotoUrl('{:url('User/ChangeAccount',array('sex_flag'=>$sex_flag, 'fu'=>'webview'),'do')}');
}
</script>
</block>