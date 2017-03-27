<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta content="zh-cn" http-equiv="Content-language" />
    <title>{$msgTitle}</title>
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
        <h3 class="form-signin-heading">提示信息</h3>
        <p>{$message}</p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
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
    <script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>

</html>