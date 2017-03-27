<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<div style="padding: 10px; text-align: center; font-size: 14px; line-height: 26px;" class="system-message mtop20">
    <?php if(isset($message)) {?>
<h1>:)</h1>
<p class="success"><?php echo($message); ?></p>
<?php }else{?>
<h1>:(</h1>
<p class="error"><?php echo($error); ?></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
</block>
<block name="script">
    <script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();

</script>
</block>