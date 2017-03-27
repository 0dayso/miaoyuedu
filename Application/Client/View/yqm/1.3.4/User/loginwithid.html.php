<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--登录开始-->
<div class="login" style="margin-bottom: 50px;">
        <p><input type="text" placeholder="请在这里输入账号或手机号" class="radius4" id="input_user" /></p>
        <p class="password"><span id="show"><input type="password" placeholder="请在这里输入密码" class="radius4" id="input_pwd"/></span><span id="hide" ><a class="openeye" onclick="showpwd();"></a></span></p>
        <p><button type="button" class="radius4" onclick="login(this);">登录</button></p>
    <p><span><input name="checkbox" type="checkbox" checked="checked" class="checkbox" >记住登录密码</span><a onClick="hg_gotoUrl('{:url('User/losepwd')}');" class="more">忘记密码？</a> </p>
</div>
<!--登录结束-->

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
<script type="text/javascript">
var forward_url='<?php if($M_forward=='/'){echo url('User/index');}else{echo $M_forward;}?>';
function login(obj){
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    if(!userName){
        hg_Toast('请输入账号');
        return;
    }
    if(!pwd){
        hg_Toast('请输入密码');
        return;
    }
    lockbutton(obj);
    url='{:url('Userajax/login')}';
    checked=$('.checkbox').attr('checked');
    if(checked){
        remember=1;
    }else{
        remember=0;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,frommobile:0,remember:remember,fu:forward_url},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
        },
        success: function(json){
            console.log(json);
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
    $(obj).html("请稍候…");
}

function unlockbutton(obj) {
    $(obj).attr("onclick","login(this)");
    $(obj).html("登录");
}

function showpwd(){
    var type=$('#input_pwd').attr('type');
    if (type=="password") {
       $('#input_pwd').attr('type','text');
       $('.openeye').removeClass('openeye').addClass('offeye');
    }else{
       $('#input_pwd').attr('type','password');
       $('.offeye').addClass('openeye').removeClass('offeye');
    }
}
</script>
</block>