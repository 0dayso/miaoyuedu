<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="keywords" content="">
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=1" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="__CSS__/style.css" media="all" />
    <title>{:C('SITECONFIG.SITE_NAME')}</title>
</head>
<body  style="background-color: #fff;">
<!--404开始-->
<div class="noye">
<h1>抱歉，本书已下架，暂不开放阅读</h1>
 <p>{$msg}</p>
    <div class="yemnr404"><a href="{:C('ROOT_URL')}" class="sy">首页</a><a href="{:url('Channel/search')}" class="sk">书库</a><span class="flrt rt10">
  <form action="{:url('Channel/search')}" method="GET"><input type="text" name="keyword" class="ssinput">
  <button type="submit" class="ssinputbtn">搜索</button></form>
  </span> </div>
    <div class="yemnr4042 ">
        <p><span class="c1">或者</span> <a href="javascript:history.back(-1)" class="cred">返回上一页</a></p>
    </div>
</div>
<!--404结束-->
</body>
</html>