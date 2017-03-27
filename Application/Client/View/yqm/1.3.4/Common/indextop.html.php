<div style="margin:0 auto; display: none;">
		<img data-src="http://img1.hongshu.com/img/html5/weixinsuolue.jpg" />
</div>

<script>
	var global_islogin = "<?php echo session('islogin');?>";
</script>

<?php
$_nav = basename($_SERVER['SCRIPT_NAME']);
$hnavs = array(
	'index.php'=> '首页',
	'nan.php'=>'男生',
	'nv.php'=>'女生',
	'top.php'=>'排行',
	'class.php'=>'分类',
	'pay.php'=>'充值'
);
if(!isset($hnavs[$_nav])) $_nav = 'index.php';

 if (basename($_SERVER['SCRIPT_NAME'])=='index.php')?>
<div class="header">
    <a class="logo" href="javascript:hg_gotoUrl('index.php');" data-clktrack="g_top_menu|1"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/logo2.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/logo2.png"/></a>
    <div class="top">
    	<div class="nav">
    		<?php
    		$menu_num = 2;
    		foreach($hnavs as $k=>$v){
    		echo '<a href="javascript:hg_gotoUrl(\'';
				echo $k;
    		echo '\');"';
				if($k==$_nav) echo ' class="active" ';
    		echo ' data-clktrack="g_top_menu|'.$menu_num.'"><span>'.$v.'</span></a>';
    		$menu_num++;

    		}
			?>

    	</div>
    </div>

    <div class="bom">
        <a href="javascript:hg_gotoUrl('search.php');" class="sousuo"  data-clktrack="g_top_menu|8"><span><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_search_grey.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_search_grey.png" width="24" height="24"  /></span>搜索</a>

    <?php
	if(session('islogin')){
	?>

		<div class="accounts on">
			<span style="white-space:nowrap;overflow: hidden;text-overflow:ellipsis;">
				<a href="javascript:hg_gotoUrl('bookshelf.php');"  data-clktrack="g_top_menu|13"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_book_grey.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_book_grey.png" width="24" height="24">我的藏书</a>
				<a href="javascript:hg_gotoUrl('cookiebookshelf.php');"  data-clktrack="g_top_menu|14"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_local_library_grey.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_local_library_grey.png" width="24" height="24" >阅读记录</a>
                <a href="javascript:hg_gotoUrl('user.php');" class="username"  data-clktrack="g_top_menu|15"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_person_grey.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/ic_person_grey.png" width="24" height="24" />{$userinfo['nickname']}</a>
			</span>
        </div>

	<?php
	}else{
	?>

		<div class="accounts">
			<span>
				<a href="javascript:hg_gotoUrl('login.php');"  data-clktrack="g_top_menu|9">登录</a>
				<a href="javascript:hg_gotoUrl('/thirdlogin.php?type=<?php echo urlencode('/third/sina/login');?>');"  data-clktrack="g_top_menu|10"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/weibo-m.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/weibo-m.png" width="24" height="24" alt="微博登录"></a>
				<a href="javascript:hg_gotoUrl('/thirdlogin.php?type=<?php echo urlencode('/third/qq/login');?>');"  data-clktrack="g_top_menu|11"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/qq-m.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/qq-m.png" width="24" height="24" alt="qq登录"></a>
				<a href="javascript:hg_gotoUrl('/thirdlogin.php?type=<?php echo urlencode('/third/baidu/login');?>');"  data-clktrack="g_top_menu|12"><img src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/baidu-m.png" data-src="<?php echo $SITECONFIG['cssurl'];?>/img/html5/baidu-m.png" width="24" height="24" alt="百度登录"></a>
			</span>
        </div>

	<?php
	}
	?>


    </div>
</div>