<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta content="zh-cn" http-equiv="Content-language" />
    <title>注册</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="__CSS__/bootstrap-responsive.css" rel="stylesheet">
    <link href="__CSS__/style.css" rel="stylesheet">




</head>

<body>


<!--注册-->
<div class="navbar navbar-inverse navbar-fixed-top" style="position: static;">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="/">触手文化</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="#">服务介绍</a></li>
                    <li><a href="#about">关于我们</a></li>
                    <li><a href="#contact">联系我们</a></li>
                    <li><a href="#contact">合作伙伴</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container" style="margin-top: 60px;">

    <div class="form-signin">
        <h3 class="form-signin-heading">注册</h2>
            <input type="text" class="input-block-level" placeholder="用户名" id="input_user">

            <input type="password" class="input-block-level" placeholder="设置登录密码（不少于6位）" id="input_pwd">
            <input type="password" class="input-block-level" placeholder="确认登录密码" id="input_repwd">

            <input type="text" class="input-small" placeholder="验证码" style="width: 50%; display: inline-block;" id="input_sbm">
            <a class="yanzhengma"><img src="{:url('User/imgcode','','do')}" lazy='y' id='imgcode' style="height:36px" onclick='return refreshimg();'/></a>
            <!--
             <label class="checkbox">
               <input type="checkbox" value="remember-me">我已阅读并同意<a href="#">《触手文化协议》</a>
             </label>
             -->
            <button class="btn btn-large btn-primary btn-block" onclick="return doSubmit(this);">注册</button>
            <button class="btn btn-large btn btn-block" style="margin-top: 16px;" onclick="location.href='{:url('User/login')}';return false;">已有账号？直接登录</button>
    </div>

</div>

<div class="container" style="margin-top: 10px;">
    <hr>
    <footer>
        <p style="text-align: center;">© 2017 南京触手文化发展有限公司</p>
    </footer>
</div>


<!--注册结束-->



<script src="http://code.jquery.com/jquery.js"></script>
<script src="__JS__/bootstrap.min.js"></script>

<script>
    function refreshimg(){
        var url = '{:url("User/imgcode","","do")}';
        url+=url.indexOf('?')>0?'&':'?';
        url+=Math.random(10);
        $('#imgcode').attr('src', url);
    }
    function doSubmit(obj){
        var userName=$("#input_user").val();
        if(!userName || userName.length<3) {
            alert('用户名长度至少需要6个字符！');
            $('#input_user').focus();
            return false;
        }
        var pwd=$("#input_pwd").val();
        var repwd = $('#input_repwd').val();
        if(!pwd || pwd.length<6) {
            alert('密码输入不正确！');
            $('#input_pwd').focus();
            return false;
        }
        if(pwd!=repwd) {
            alert('两次输入的密码不一致！');
            $('#input_repwd').focus();
            return false;
        }
        var yzm=$("#input_sbm").val();
        if(!userName){
            alert('请输入账号');
            return false;
        }
        if(!pwd){
            alert('请输入密码');
            return false;
        }
        if(!yzm){
            alert('请输入识别码');
            return false;
        }
        lockbutton(obj);
        url="{:url('Userajax/register','','do')}";
        checked=$('.checkbox').attr('checked');
        if(checked){
            remember=1;
        }else{
            remember=0;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {username:userName,password:pwd,repassword:repwd,fu:'/',yzm:yzm},
            dataType: 'json',
            complete:function(){
                unlockbutton(obj);
                refreshimg();
            },
            success: function(json){
                if(json.status==1){
                    location.href='/';
                }
                else {
                    alert(json.message);
                }
                unlockbutton(obj);
            }});
        return false;
    }

    function lockbutton(obj) {
        $(obj).attr("onclick","");
        $(obj).html('请稍后...');
    }

    function unlockbutton(obj) {
        $(obj).attr("onclick","doSubmit(this)");
        $(obj).html('登录');
    }

</script>

</body>

</html>