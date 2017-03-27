<!DOCTYPE html>
<html>
<head lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="wap-font-scale"  content="no"/>
<meta http-equiv="Cache-Control" content="no-transform"/>
<meta name="format-detection" content="telephone=no"/>
<block name="title">
    <title><notempty name="pageTitle">{$pageTitle}-{:C('CLIENT.'.CLIENT_NAME.'.name')}
<else />{:C('CLIENT.'.CLIENT_NAME.'.name')}
</notempty></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
</block>
<link rel="shortcut icon" href="__IMG__/favico64.ico" type="image/vnd.microsoft.icon">
<link rel="shortcut icon" href="__IMG__/favico64.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="__IMG__/apple-touch-icon.png" />
<block name="css">
<link href="__CSS__/style.css?ver={:C('SOURCE_VER')}" rel="stylesheet" type="text/css">
    <if condition="$style">
    <link href="__CSS__/{$style}.css?ver={:C('SOURCE_VER')}" rel="stylesheet" type="text/css">
    <else/>
    <if condition="$sex_flag">
    <link href="__CSS__/{$sex_flag}.css?ver={:C('SOURCE_VER')}" rel="stylesheet" type="text/css"></if>
    </if>
</block>
<block name="style">

</block>

<if condition="isInWechat()">
<!--微信JSSDK-->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
wx.config({
   debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
   appId: '{$wechatSign["appId"]}',
   timestamp: '{$wechatSign["timestamp"]}',
   nonceStr: '{$wechatSign["nonceStr"]}',
   signature: '{$wechatSign["signature"]}',
   jsApiList: ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone'],
   url: '{$wechatSign['url']}',
   jsapi_ticket:'{$wechatSign['jsapi_ticket']}'
});
wx.ready(function (res) {
    var sharedata = {
        title: "{$shareTitle}{:C('SITECONFIG.SITE_NAME')}", // 分享标题
        <if condition="$shareDesc">
        desc: '{$shareDesc}', // 分享描述
        <else />
        desc: '红薯阅读',
        </if>
        link: location.href, // 分享链接
        imgUrl: 'http://img1.hongshu.com/Public/Mob/images/hongshu_wx.png', // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () {
            // 用户确认分享后执行的回调函数
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    };
    console.log(res);
    wx.onMenuShareAppMessage(sharedata);
    wx.onMenuShareAppMessage(sharedata);
    wx.onMenuShareQQ(sharedata);
    wx.onMenuShareWeibo(sharedata);
    wx.onMenuShareQZone(sharedata);
    //分享到朋友圈时只有标题，所这里拼接一下
    sharedata.title = sharedata.title+"  "+sharedata.desc;
    wx.onMenuShareTimeline(sharedata);
});
wx.error(function (res) {
    //hg_Toast(res.errMsg);  //打印错误消息。及把 debug:false,设置为debug:ture就可以直接在网页上看到弹出的错误提示
});
</script>
<!--微信JSSDK-->
</if>
