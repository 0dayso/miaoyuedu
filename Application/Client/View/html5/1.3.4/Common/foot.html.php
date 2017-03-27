<if condition="$Think.const.ACTION_NAME neq 'read' and $Think.const.ACTION_NAME neq 'view' and $Think.const.ACTION_NAME neq 'readvip'">
<!--底开始-->
<style>
.qiehuan{padding:10px 0;}
.qiehuan a {color: #666666;margin-right: 25px;font-size: 12px;}

.qiehuan a.active{color:#f00;}
</style>
<div class="footer" id="foot1">
<div class="foottop" >
    <a href="__ROOT__/index.html">首页</a>
    <a href="{:url('User/index', '', 'do')}">个人中心</a>
    <a href="{:url('Pay/index', '', 'do')}">充值</a>
    <a href="{:url('Help/index')}">帮助</a>
    <a href="{:url('Feedback/index')}">反馈</a>
    <a class="return radius2" onclick="javascript:scroll(0,0);"><span class="ictop"><img src="__IMG__/ic_top.png"/></span>回顶部</a>
</div>
</div>
<!--底结束-->
</if>
<script type="text/html" id="footinfo">
<div class="foottop" >
    <a href="javascript:hg_gotoUrl('__ROOT__/{{sex_flag}}.html')">首页</a>
        <a href="javascript:hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'User/index','do'}}')">个人中心</a><a href="javascript:hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Pay/index','do'}}')">充值</a>
        <a href="javascript:hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Help/index','html'}}')">帮助</a>
        <a href="javascript:hg_gotoUrl('{{ {sex_flag:sex_flag} | router:'Feedback/index','do'}}')">反馈</a>
        <a class="return radius2" onclick="javascript:scroll(0,0);"><span class="ictop"><img src="__IMG__/ic_top.png"/></span>回顶部</a>
</div>
<div class="qiehuan"><a href="http://www.hongshu.com/">电脑版</a><a class="active">触屏版</a><a href="http://g.hongshu.com/">无图版</a><a href="{:url('Book/downapp','','html')}">手机客户端</a></div>
<div class="footbom">
{{if sex_flag == 'nv'}}
    <p>微信公众号</p>
    <p><img src="__IMG__/yqk.jpg"/></p>
    <p>微信内可长按识别</p>
    <p>或在微信公众号里搜索"红薯阅读"</p>
{{else}}
    <p>微信公众号</p>
    <p><img src="__IMG__/rxk.jpg"/></p>
    <p>微信内可长按识别</p>
    <p>或在微信公众号里搜索"热血刊"</p>
 {{/if}}
</div>
</script>


<php>
if (function_exists('canTest') && cantest()){
    echo '<div id="debug_msg" style="display: block; min-height: 200px; width: 100%; overflow: hidden;word-break:break-all;">';
    if(strpos($_SERVER['HTTP_HOST'], 'test.com')) {
        echo '内测<br/>';
    } else {
        echo '线上测试<br/>';
    }
    echo 'CLIENT_NAME:'.CLIENT_NAME.'<br />';
    echo 'CLIENT_VERSION:'.CLIENT_VERSION.'<br />';
    if($userinfo){
        echo 'UserInfo:';
        pre($userinfo);
    }
    echo 'path:'.$_SERVER['PHP_SELF'].'<br/>';
    echo '_GET:' . print_r($_GET, 1) . '<br/>';
    echo '_POST:' . print_r($_POST, 1) . '<br/>';
    echo 'querystring:'.$_SERVER['QUERY_STRING'].'<br/>';
    echo 'referer:' . $_SERVER['HTTP_REFERER'] . '<br />';
    echo 'M_forward:' . $M_forward . '<br />';
    echo '_get_uuid_device:';
    print_r($deviceInfo);
    echo '<hr>';
    echo '</div>';
}
</php>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?7812088632f736563a8af77411b2ba32";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>