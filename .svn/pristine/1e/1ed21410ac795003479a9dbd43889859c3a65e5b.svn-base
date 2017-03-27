<extend name="Common/base" />
<block name="body">
<div class="h100 bgcf">
    <div class="homebg sidebarbg">
    <if condition="$userinfo.islogin eq 'TRUE'">
        <div class="left" onclick="doChild('{:C('ROOT_URL')}{:url('User/index')}')"><if condition="$userinfo['avatar']"><img src="{$userinfo['avatar']}" class="avater"/><else/><img src="__IMG__/avater.jpg" class="avater"/></if></div>
        <div class="right perxinxi"></div>
        <div class="right2"><a class="ic_settings" href="javascript:void(0);" onclick="doChild('{:C('ROOT_URL')}{:url('User/index')}')">设置</a></div>
        <div class="bom  cf fontsize16" onclick="doChild('{:C('ROOT_URL')}{:url('User/index')}')">{$userinfo.nickname}</div>
    <else/>
         <div class="left" onclick="doChild('{:C('ROOT_URL')}{:url('User/login')}')"><img src="__IMG__/avater.jpg" class="avater"/></div>
        <div class="right perxinxi"></div>
        <div class="right2"><a class="ic_settings" href="javascript:void(0);" onclick="doChild('{:C('ROOT_URL')}{:url('User/login')}')">设置</a></div>
        <div class="bom  cf fontsize16" onclick="doChild('{:C('ROOT_URL')}{:url('User/login')}')">登录</div>
    </if>
    </div>
    <div class="homecon  displaybox2  sidebarcon borderbottom1 ">
        <ul>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('Pay/index')}')">
                <div class="left"><span class="ic_wallet2 ic_tit"></span></div>
                <div class="right c87 fontsize14">快速充值</div>
            </li>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('User/qiandao')}')">
                <div class="left"><span class="ic_today ic_tit"></span></div>
                <div class="right c87  fontsize14">签到送币<span class="reddot2 bgca mlf10 radius50"></span></div>
            </li>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('User/shelf')}')">
                <div class="left"><span class="ic_clound ic_tit"></span></div>
                <div class="right c87  fontsize14">云端书架</div>
            </li>
        </ul>
    </div>
    <div class="homecon   displaybox2 sidebarcon">
        <ul>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('Channel/free',array('sex_flag'=>'nan'))}')">
                <div class="left"><span class="ic_boyfree ic_tit"></span></div>
                <div class="right c87  fontsize14">男生免费</div>
            </li>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('Channel/free',array('sex_flag'=>'nv'))}')">
                <div class="left"><span class="ic_girlfree ic_tit"></span></div>
                <div class="right c87  fontsize14">女生免费</div>
            </li>
            <li onClick="doChild('{:C('ROOT_URL')}{:url('Channel/tejia')}')">
                <div class="left"><span class="ic_local ic_tit"></span></div>
                <div class="right c87  fontsize14">天天打折</div>
            </li>
        </ul>
    </div>
</div>
</block>
