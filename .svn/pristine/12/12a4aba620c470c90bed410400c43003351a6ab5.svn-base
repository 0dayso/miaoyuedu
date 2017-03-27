<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta content="zh-cn" http-equiv="Content-language" />
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="__CSS__/bootstrap-responsive.css" rel="stylesheet">
    <link href="__CSS__/style.css" rel="stylesheet">




</head>

<body>
<!--登录-->
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
                    <li class="active"><a href="#service">服务介绍</a></li>
                    <li><a href="#about">关于我们</a></li>
                    <li><a href="#contact">联系我们</a></li>
                    <li><a href="#partner">合作伙伴</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container" style="margin-top: 60px;">

    <div class="form-signin">
        <h3 class="form-signin-heading">登录</h2>
            <input type="text" class="input-block-level" placeholder="用户名" id="input_user">
            <div style="position: relative;">
                <!--a href="#" class="eye-closed"></a-->
                <input type="password" class="input-block-level" placeholder="密码" id="input_pwd">
            </div>

            <input type="text" class="input-small" placeholder="验证码" style="width: 50%; display: inline-block;" id="input_sbm">
            <a class="yanzhengma"><img src="{:url('User/imgcode','','do')}" lazy='y' id='imgcode' style="height:36px" onclick='return refreshimg();'/></a>

            <a href="#" style="margin-bottom: 15px; display: block;">忘记密码？</a>

            <button class="btn btn-large btn-primary btn-block" onclick="return login(this);">登录</button>
            <button class="btn btn-large btn btn-block" style="margin-top: 16px;" onclick="location.href='{:url('User/register')}';return false;">没有账号？请注册</button>
    </div>





</div>

<div class="container" style="margin-top: 10px;">
    <hr>
    <footer>
        <p style="text-align: center;">© 2017 南京触手文化发展有限公司</p>
    </footer>
</div>

<!--登录结束-->




<script src="http://code.jquery.com/jquery.js"></script>
<script src="__JS__/bootstrap.min.js"></script>
<script>
    function refreshimg(){
        var url = '{:url("User/imgcode","","do")}';
        url+=url.indexOf('?')>0?'&':'?';
        url+=Math.random(10);
        $('#imgcode').attr('src', url);
    }
    function login(obj){
        var userName=$("#input_user").val();
        var pwd=$("#input_pwd").val();
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
        url="{:url('Userajax/login','','do')}";
        checked=$('.checkbox').attr('checked');
        if(checked){
            remember=1;
        }else{
            remember=0;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {username:userName,password:pwd,frommobile:0,remember:remember,fu:'/',yzm:yzm},
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
        $(obj).attr("onclick","login(this)");
        $(obj).html('登录');
    }

</script>
</body>

</html>