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
    <h2>关注<span  class="cpink">红薯阅读</span>，追书领币更方便</h2>
    <p><img src="__IMG__/yqk.jpg" lazy="y"></p>
    <p><!--关注送银币，-->每天签到领{:C('SITECONFIG.EMONEY_NAME')}，好书免费看</p>
    <br>
    <div class="weixin_wz ">
        <p>操作提示：</p>
        <p>方法1：微信里长按图片选择识别图中二维码</p>
        <p>方法2：微信里搜索公众号：红薯阅读</p>
<?php if ($is_wechat == 0){ ?>
        <p>方法3：长按图片 &gt; 保存到手机 &gt; 打开微信扫一扫 &gt; 相册 &gt; 扫描二维码关注</p><?php }?>
    </div>
    <div style="height: 10px;"></div>
</div>
<div class="weixinbg" id="nan"  style="display: none">
    <h2>关注<span  class="cpink">红薯阅读</span>，追书更方便</h2>
    <p><img src="__IMG__/yqk.jpg" lazy="y"></p>
    
    <br>
    <div class="weixin_wz ">
        <p>操作提示：</p>
        <p>方法1：微信里长按图片选择识别图中二维码</p>
        <p>方法2：微信里搜索公众号：红薯阅读</p>
<?php if ($is_wechat == 0){ ?>
        <p>方法3：长按图片 &gt; 保存到手机 &gt; 打开微信扫一扫 &gt; 相册 &gt; 扫描二维码关注</p><?php }?>
    </div>
    <div style="height: 10px;"></div>
</div>
</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', 'functions', function(){
    
    var cookie_sex_flag = cookieOperate('sex_flag');
    if(cookie_sex_flag=='nan') {
        $('#nv').remove();
        $('#nan').show();
    }
    else{
        $('#nv').show();
        $('#nan').remove();
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
