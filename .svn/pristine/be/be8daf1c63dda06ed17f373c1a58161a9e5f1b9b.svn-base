<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">

<!--主体-->
<div class="user top25">
    <div class="user1200 top10 ">
        <ul class="user_kazu">
            <li class="user_ka rt16 active" onclick="hg_gotoUrl('{:url('User/login')}')"><a href="{:url('User/login')}">登录</a></li>
            <li class="user_ka rt16" onclick="hg_gotoUrl('{:url('User/register')}')"><a href="{:url('User/register')}">注册</a></li>
            <li class="user_ka flrt" onclick="hg_gotoUrl('{:url('User/authorreg')}')"><a href="{:url('User/authorreg')}">注册为萌神</a></li>
            
        </ul>
        <div class="user1060">
            <div class="login960">
                
                <div class="form-warp clearfix bom30" id="biaodan">
                        <div class="form-tit3 bom20 clearfix top20 c6">
                            <h3>欢迎抵达元气萌の世界</h3>
                        </div>
                        <p class="clearfix">
                            <input class="" id="input_user" type="text" name="username" id="txtUserName" placeholder="用户名" autocomplete="off" value="" onkeyup="check();" onchange="check();">
                        </p>
                            <div id="wrong1" class="wrong" style="display:inline-block;padding-top: 3px"></div>
                        <p class="passwordp clearfix">
                            <a class="eye-closed" onclick="showpwd();"></a>
                            <input id="input_pwd" class="password" type="password" name="password" id="txtPassWord" maxlength="20" placeholder="密码" autocomplete="off" value="" onkeyup="check();" onchange="check();">
                        </p>
                        <div id="wrong2" class="wrong" style="display:inline-block;padding-top: 3px"></div>
                        <p class="clearfix">
                            <input type="submit" value="登录" class="submitbtn" style="cursor: pointer" onclick="login(this)" id="denglu" disabled="disabled">
                        </p>
                        <p class="forgot">
                            <label style="line-height: 25px; height: 25px">
                                <input type="checkbox" tabindex="1" name="remeber" id="box" class="checkbox"> 记住我
                            </label>
                            <!-- <a class="forgot-password" target="_blank" href="#">忘记密码？</a> -->
                        </p>
                        <input type="hidden" name="__hash__" value="">
                </div>
                
                
                
            </div>
        </div>
    </div>
</div>

</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', function(){
    $('body').keyup(function(event) {
        switch (event.keyCode) {
            case 13:
                login();
                break;
        }
    });
});
var forward_url='<?php if($M_forward=='/'){echo url('User/personal');}else{echo $M_forward;}?>';
function check(){
    $('#denglu').attr('disabled',true);
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    if(userName&&pwd){
        $('#denglu').removeAttr('disabled');
        return;
    }
    
}
Do.ready('common',function(){
   check();
});
function login(obj){
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    if(!userName){
        $('#wrong1').html('请输入账号');
        return;
    }
    if(!pwd){
        $('#wrong2').html('请输入密码');
        return;
    }
    lockbutton(obj);
    var url="{:url('Userajax/login',array(),'do')}";
    var checked = $("#box").is(":checked");
    if(checked){
        remember=1;
    }else{
        remember=0;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,remember:remember,fu:forward_url},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
        },
        success: function(json){
            if(json.status==1){
                hg_gotoUrl(json.url);
            }
            else {
                hg_Toast(json.message);
            }
            unlockbutton(obj);
        }});
}

function lockbutton(obj) {
    $(obj).attr("onclick","");
    $(obj).val("请稍候…");
}

function unlockbutton(obj) {
    $(obj).attr("onclick","login(this)");
    $(obj).val("登录");
}

function showpwd(){
    var type=$('#input_pwd').attr('type');
    if (type=="password") {
       $('#input_pwd').attr('type','text');
       $('.eye-open').removeClass('eye-open').addClass('eye-closed');
    }else{
       $('#input_pwd').attr('type','password');
       $('.eye-closed').addClass('eye-open').removeClass('eye-closed');
    }
}
</script>
</block>