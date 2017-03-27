<include file="Common/head" />
</head>
<body>

<!--顶部开始-->
<block name="header">
</block>
<!--顶部结束-->

<!--内容开始-->
<block name="body"></block>
<!--内容结束-->

<!--底开始-->
<block name="footer">
    <include file="Common/foot" />
</block>
<!--底结束-->

<!-- 代码开始 -->
<script src="__STATICURL__/Public/Client/common/js/lib/jquery.min.js?ver={:C('SOURCE_VER')}"></script>
<script src="__STATICURL__/Public/Client/common/js/lib/do.js?ver={:C('SOURCE_VER')}" data-cfg-corelib="jquery" data-cfg-ver="{:C('SOURCE_VER')}"></script>
<include file="Common/var" />
<include file="Common/foot1" />
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
<block name="script">
<script>
    Do.ready('common', 'functions', function(){
        UserManager.checkLogin();
    });
</script>
</block>
<!-- 代码结束 -->

</body>
</html>