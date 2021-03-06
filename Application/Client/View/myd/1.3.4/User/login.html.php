<extend name="Common/base" />
<block name="header">
    <div class="header nobg clearfix">
        <div class="container ">
            <div class="logo"><a href="{:url('Index/index')}"><img src="__IMG__/logo.png" /></a></div>
            <div class="topnav">
                <a href="{:url('Channel/fuli','','html')}"><img src="__IMG__/ic_topnav_fuli.png"  />福利</a>
                <a href="javascript:void(0);"><img src="__IMG__/ic_topnav_phone.png"  />喵手机</a>
                <a href="{:url('Pay/index','','do')}"><img src="__IMG__/ic_topnav_chongzhi.png"  />充值</a>
                <a href="{:url('User/login','','do')}" name="nologin" class="tx"><img src="__IMG__/ic_topnav_tx.png" /></a>
                <a href="{:url('User/login','','do')}" name="nologin" class="user">登录</a>
                <a href="{:url('User/index','','do')}" name="islogin" class="tx on"><img src="__IMG__/ic_topnav_tx.png" id="useravatar" /></a>
            </div>
        </div>
    </div>
</block>
<block name="body">
    <div class="container">
        <div class="login clearfix">
            <div class="lf">
                <div class="tit3"><div class="tit3_border"><h1>登录</h1></div></div>
                <div class="frame03 other_login">
                    <ul>
                        <li><a href="{:url('Usercenter/Third/wechatlogin')}" class="radius4 weixin" ></a><span>微信</span></li>
                        <!--li><a href="{:url('Usercenter/third/qqlogin.html')}" class="radius4 qq"></a><span>QQ</span></li>
                        <li><a href="{:url('Usercenter/third/sinalogin.html')}" class="radius4 weibo"></a><span>新浪微博</span></li-->
                    </ul>
                </div>
                <div class="login_con">
                    <h5>使用{:C("SITECONFIG.SITE_NAME")}账号登陆：</h5>
                    <div class="form_item"><input type="text" placeholder="注册手机号或电子邮箱" class="radius4" name="username"/> </div>
                    <p class="wrong" name="username" style="display:none;"></p>
                    <div class="form_item"><input type="password" placeholder="密码" class="radius4" name="password"/><a href="javascript:void(0);" id="eye" isshow="0" class="eye"></a></a></div>
                    <p class="wrong" name="password" style="display:none;"></p>
                    <div class="form_item"><a class="img_ewm"><img id="imgcode" src="{:url('User/imgcode','','do')}" /></a><input type="text" placeholder="请输入验证码" class="ewm radius4" name="yzm"/><span class="right" style="display:none;"></span></div>
                    <p class="wrong" name="yzm" style="display:none;"></p>
                    <div class="form_item2"><button class="mainbtn radius4" id="login">登录</button></div>
                    <p><input id="checkbox" type="checkbox" checked />记住我<a href="{:url('User/losepwd','','do')}" class="forget_password">忘记密码？</a> </p>
                </div>
            </div>
            <div class="rt"><a href="{:url('User/register','','do')}" class="cblue">注册{:C("SITECONFIG.SITE_NAME")}账号</a><a href="{:url('User/authorreg','','do')}" class="cblue">成为{:C("SITECONFIG.SITE_NAME")}作者</a> </div>
        </div>
    </div>
</block>
<block name="script">  
    <script type="text/javascript">
        require(['mod/user'],function(user){
            $('#imgcode').on('click',function(){
                user.refreshimg('#imgcode');
            })
            var fu='<?php if($M_forward=='/'){echo url('User/index','','do');}else{echo $M_forward;}?>';
            $('#login').on('click',function(){
                user.login('username','password','yzm',fu,'#login');
            })
            $('#eye').on('click',function(){
                user.showhidepwd('password','#eye','isshow');
            })
        })
    </script>
</block>