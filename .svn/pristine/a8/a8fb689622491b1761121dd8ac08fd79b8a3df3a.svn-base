<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10 bgf clearfix">
        <div class="password">
            <div class="tit5"><div class="tit5_border"><h1>找回密码</h1></div></div>
            <div class="password_tit clearfix">
                <a href="javascript:void(0);" class="active" name="lose1">电子邮箱找回密码</a>
                <a href="javascript:void(0);" name="lose2">手机验证码找回密码</a>
            </div>
            <div name="lose1">
                <div class="form_item"><input name="mail" type="text" placeholder="注册的电子邮箱" class="radius4" /> </div>
                <p class="wrong" name="mail"></p>
                <div class="form_item2"><button id="tijiao1" class="mainbtn radius4">提交</button></div>
            </div>
            <div name="lose2" style="display:none;">
                <div class="form_item"><input type="text" name="phone" placeholder="注册的手机号" class="radius4" /> </div>
                <p class="wrong" name="phone"></p>
                <div class="form_item"><input type="text" id="yzm" placeholder="请输入验证码" class="ewm radius4" /><a href="javascript:void(0);" class="img_ewm_btn radius4" id="getyzm">获取验证码</a> </div>
                <p class="wrong"></p>
                <div class="form_item2"><button id="tijiao2" class="mainbtn radius4">提交</button></div>
            </div>
            
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/user'],function(user){
            user.changefindmethod('a[name^="lose"]','div','lose');
            $('#tijiao1').on('click',function(){
                user.emailfind('mail');
            })
            $('#tijiao2').on('click',function(){
                user.phonefind('phone','#yzm');
            })
            $('#getyzm').on('click',function(){
                user.getyzm('phone');
            })
        })
    </script>
</block>