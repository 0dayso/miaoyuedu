<extend name="Common/base" />
<block name="header">
    <include file="Common/head2" />
</block>
<block name="body">
	
	<div class="weixinbg">
	
	    <div class="weixin_wz">
		    <p><b>操作提示：</b></p>
		    <p>检测到当前环境不支持支付宝支付，请使用下列方式充值。</p>
		    <p></p>
		    <p>方法：点击右上角“…”按纽，然后选择在浏览器中打开进行充值。 建议使用<font color="red">UC浏览器</font>。</p>
		</div>
		<img style="height: 80%; width: 80%;" src="__IMG__/weixin_zhidao2.jpg"/>
		<div style="height: 10px;"></div>
	</div>
</block>

<block name="style">
	<style>
		
.weixinbg{ background: white; padding: 15px;}
.weixinbg h2 { font-size: 1.25em; text-align: center;}
.weixinbg img { width: 70%; height: 70%; padding-top: 20px; margin: auto; display: block;}
.weixinbg p { text-align: center; }
.weixin_wz { width: 92%; margin: auto; -webkit-border-radius:16px;  border: 1px solid rgba(0,0,0,.1); padding: 15px 10px 15px 10px;}
.weixin_wz p { display: block; margin: auto; width: 95%; text-align: left; line-height: 2em; font-size: 0.875em; margin-top: 5px; margin-bottom: 5px;}
	</style>
</block>
