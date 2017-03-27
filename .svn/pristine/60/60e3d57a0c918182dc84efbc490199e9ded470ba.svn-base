<!--banner-->
<div class="user_bn">
    <div class="user_bn1200">
        <img src="__IMG__/user_bn01.jpg" lazy="y" style="display:block" />
        <img src="__IMG__/user_bn02.jpg" lazy="y" style="display:block" />
    </div>
</div>
<!--头像-->
<div class="user_tx">
    <div class="user_tx1200">
        <div class="user_tx164">
            <div class="user_tx144" onclick="hg_gotoUrl('{:url('User/personal#divUserFace')}')">
            <if condition="$user['userimg']">
                <img src="{$user['userimg']}" height="144" width="144" id="avatarbig" lazy="y">
            <else/>
                <img src="__IMG__/ic_person.jpg" height="144" width="144" id="avatarbig" lazy="y">
            </if>
            </div>
        </div>
        <h1 class="c3">{$userinfo.nickname}</h1>
        <p><span class="rt20 c6">账号：{$userinfo.username}</span><span class="c6">用户ID：{$userinfo.uid}</span><a class="cb lf20" onclick="hg_gotoUrl('{:url('User/logout')}')">退出</a></p>
    </div>
</div>

<!--主体-->
<div class="user">
    <div class="user1200 top10 ">
        <ul class="user_kazu">
            <li class="user_ka rt21" id="nav_personal" onclick="hg_gotoUrl('{:url('User/personal')}')"><a href="{:url('User/personal')}">萌族资料</a></li>
            <li class="user_ka rt21" id="nav_shelf" onclick="hg_gotoUrl('{:url('User/shelf')}')"><a href="{:url('User/shelf')}">我的次元库</a></li>
            <li class="user_ka rt21" id="nav_shuquan" onclick="hg_gotoUrl('{:url('User/shuquan')}')"><a href="{:url('User/shuquan')}">我的书圈</a><div class="hongdian" style="display:none;" id="hongdian"></div></li>
            <li class="user_ka rt21" id="nav_pay" onclick="hg_gotoUrl('{:url('Pay/index#maodian')}')"><a href="{:url('Pay/index#maodian')}">充值</a></li>
            <li class="user_ka rt21" id="nav_paylogs" onclick="hg_gotoUrl('{:url('User/paylogs')}')"><a href="{:url('User/paylogs')}">充值记录</a></li>
            <li class="user_ka rt21" id="nav_salelogs" onclick="hg_gotoUrl('{:url('User/salelogs')}')"><a href="{:url('User/salelogs')}">消费记录</a></li>
            <li class="user_ka flrt" id="nav_setbooking" onclick="hg_gotoUrl('{:url('User/setbooking')}')"><a href="{:url('User/setbooking')}">订阅设置</a></li>
        </ul>
