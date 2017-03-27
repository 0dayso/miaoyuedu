<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <link href="__CSS__/gift.css?ver={:C('SOURCE_VER')}" type="text/css" rel="stylesheet" />
    <script type="text/javascript">var hsConfig={};</script>
<!-- 代码开始 -->
<script src="__STATICURL__/Public/Client/common/js/lib/zepto.min.js?ver={:C('SOURCE_VER')}"></script>
<script src="__STATICURL__/Public/Client/common/js/lib/do.js?ver={:C('SOURCE_VER')}"></script>
<include file="Common/var" />
<script src="__STATICURL__/Public/Client/common/js/conf/do_config.js?ver={:C('SOURCE_VER')}"></script>
<script src="__JS__/conf/do_config.js?ver={:C('SOURCE_VER')}"></script>
<script src="__JS__/mod/functions.js?ver={:C('SOURCE_VER')}"></script>

    <title>微信签到送代金券</title>

    <!--微信JSSDK-->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        wx.config({
            debug: false,
            appId: '{$wechatSign["appId"]}',
            timestamp: '{$wechatSign["timestamp"]}',
            nonceStr: '{$wechatSign["nonceStr"]}',
            signature: '{$wechatSign["signature"]}',
            jsApiList: ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo', 'onMenuShareQZone'],
                url: '{$wechatSign['url']}',
                jsapi_ticket:'{$wechatSign['jsapi_ticket']}'
            });
            wx.ready(function () {
            var sharedata = {
            title: '言情控喊你来微信签到抢代金券啦！', // 分享标题
                desc: '每天都能抢，你也来试试手气吧！', // 分享描述
                link: location.href, // 分享链接
                imgUrl: '__IMG__/avater.jpg', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                }
            };
                wx.onMenuShareAppMessage(sharedata);
                wx.onMenuShareAppMessage(sharedata);
                wx.onMenuShareQQ(sharedata);
                wx.onMenuShareWeibo(sharedata);
                wx.onMenuShareQZone(sharedata);
                //微信朋友圈只能分享一项
                //sharedata.title = sharedata.desc;
                wx.onMenuShareTimeline(sharedata);
            });
            wx.error(function (res) {
            console.log(res.errMsg); //打印错误消息。及把 debug:false,设置为debug:ture就可以直接在网页上看到弹出的错误提示
            });

    </script>
    <!--微信JSSDK-->


</head>
<body>
