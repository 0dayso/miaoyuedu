<!-- <if condition="$Think.const.ACTION_NAME neq 'read'">
     <if condition="$Think.const.ACTION_NAME neq 'view'"> -->
<!--底开始-->
<!--页脚-->
<div class="foot top30">
    <div class="foot1200">
        <div class="foot_left">
            <div class="logo_foot">
                <img src="__IMG__/logo.png"/>
            </div>
            <span class="lf25 cfc">红薯中文网 旗下轻小说网站</span>
            <p class="c6">最有趣的轻小说阅读创作社区</p>
        </div>
        <div class="foot_right">

            <div class="link">
                <p style="color: #fff;">.</p>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>

            </div>

            <div class="link">
                <p>友情链接</p>
                <div><a href="http://www.hongshu.com/" target="_blank">红薯中文网</a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
                <div><a href="#"></a></div>
            </div>

            <div class="link">
                <p>如何投稿</p>
                <div><a href="{:url('Help/Article',array('article_id'=>11),'html')}">签约制度</a></div>
                <div><a href="http://www.hongshu.com/static/zhuanti/all/20160531/index.html" target="_blank">萌神福利</a></div>
                <div><a href="#">签约流程</a></div>
                <div id="foot_userinfo"><a href="#">投稿</a></div>
                <div><a href="{:url('Help/Article',array('article_id'=>8),'html')}">投稿须知</a></div>
                <div><a href="#">创作之路</a></div>
            </div>

            <div class="link">
                <p>联系我们</p>
                <div><a href="{:url('Help/Article',array('article_id'=>10),'html')}">联系我们</a></div>
                <div><a href="#">微信公众号</a></div>
                <div><a href="#">新浪微博</a></div>
                <div><a href="{:url('Feedback/index','','html')}">意见反馈</a></div>

            </div>

            <div class="link">
                <p>关于</p>
                <div><a href="{:url('Help/Article',array('article_id'=>9),'html')}">关于我们</a></div>
                <div><a href="#">免责声明</a></div>
                <div><a href="{:url('Help/Article',array('article_id'=>7),'html')}">版权声明</a></div>
                <div><a href="#">隐私权条款</a></div>
                <div><a href="{:url('Help/index','','html')}">帮助中心</a></div>

            </div>
        </div>

        <p><a href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action">© 2016 红薯中文网 苏ICP备17008861号-3</a></p>
    </div>
</div>


<!--底结束-->
<!-- </if>
</if> -->



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
