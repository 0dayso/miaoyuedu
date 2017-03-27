<extend name="Common/base" />
<block name="style">
<style>
.weixinbg{ text-align: center;padding:15px 10px;}
.weixinbg h2{ font-size: 20px;line-height: 36px; margin-bottom: 20px;}
.weixinbg img{ width:200px;height:200px; margin: 0 auto;}
.weixin_wz{ padding:10px;border:1px solid #ddd; text-align: left; background-color: #e9e9e9;  margin: 0 10px;     -webkit-border-radius: 16px;border-radius: 16px;    -moz-border-radius: 16px;}
.weixin_wz p{line-height:20px; margin-bottom: 8px;}
</style>
</block>
<block name="body">
<div class="weixinbg" id="nv"   style="display: none">
    <h2>关注<span  class="cpink">红薯阅读</span>，追书不花钱！</h2>
    <p><img src="__IMG__/yqk.jpg" lazy="y"></p>
    <br>
    <div class="weixin_wz ">
        <p>操作提示：</p>
        <p>1.长按保存上图二维码，打开微信扫一扫，点击右上角添加二维码，点击关注即可</p>
        <p>2.微信内搜索公众号：红薯阅读</p>
        <p></p>
        <p>完成关注后点击菜单栏“我的”-“抢红包”或直接在订阅号内回复关键词：红包。就可以免费领取{:C('SITECONFIG.EMONEY_NAME')}来看小说了。</p>
        <p></p>
        <p>还在等什么？快加入我们吧！</p>
    </div>
    <div style="height: 10px;"></div>
</div>
<div class="weixinbg" id="nan"  style="display: none">
    <h2>关注<span  class="cpink">热血刊</span>，追书不花钱！</h2>
    <p><img src="__IMG__/rxk200.jpg" lazy="y"></p>
    
    <br>
    <div class="weixin_wz ">
        <p>操作提示：</p>
        <p>1.长按保存上图二维码，打开微信扫一扫，点击右上角添加二维码，点击关注即可</p>
        <p>2.微信内搜索公众号：热血刊</p>
        <p></p>
        <p>完成关注后点击菜单栏“我的”-“抢红包”或直接在订阅号内回复关键词：红包。就可以免费领取{:C('SITECONFIG.EMONEY_NAME')}来看小说了。</p>
        <p></p>
        <p>还在等什么？快加入我们吧！</p>

    </div>
    <div style="height: 10px;"></div>
</div>
</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', 'functions', function(){
    if("{$_GET['sex_flag']}"=='nv'){
    	$('#nv').show();
        $('#nan').hide();
    }else{
        var cookie_sex_flag = cookieOperate('sex_flag');
	    if(cookie_sex_flag=='nan') {
	        $('#nv').hide();
	        $('#nan').show();
	    }
	    else{
	        $('#nv').show();
	        $('#nan').hide();
	    }
    }
});


    Do.ready('lazyload',function(){
        Lazy.Load();
        document.onscroll = function(){
            Lazy.Load();
    };
});
</script>
</block>
