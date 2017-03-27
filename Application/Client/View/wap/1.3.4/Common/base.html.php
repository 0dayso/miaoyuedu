<include file="Common/head" />
</head>
<body>

<block name="header">
    <!--顶部开始-->
    <!--顶部结束-->
</block>

<!--内容开始-->
<block name="body"></block>
<!--内容结束-->

<!--底开始-->
<block name="footer">
    <include file="Common/foot" />
</block>
<!--底结束-->

<!-- 代码开始 -->
<script src="__STATICURL__/Public/Client/common/js/lib/zepto.min.js?ver={:C('SOURCE_VER')}"></script>
<script src="__STATICURL__/Public/Client/common/js/lib/do.js?ver={:C('SOURCE_VER')}"></script>
<include file="Common/var" />
<script src="__STATICURL__/Public/Client/common/js/conf/do_config.js?ver={:C('SOURCE_VER')}"></script>
<script src="__JS__/conf/do_config.js?ver={:C('SOURCE_VER')}"></script>
<script src="__JS__/mod/functions.js?ver={:C('SOURCE_VER')}"></script>
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https'){
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    } else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>
<block name="script"></block>
<!-- 代码结束 -->
<script>
    Do.ready('common', 'functions', function(){
        UserManager.checkLogin();
    });
</script>
</body>
</html>