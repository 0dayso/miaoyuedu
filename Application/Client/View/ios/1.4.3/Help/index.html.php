<extend name="Common/base" />
<block name="body">
<!--热门问题开始-->
<div class="unit">
    <div class="hotproblem">
       <ul>
       <foreach name="help_mobilefaq" item="row">
           <li onClick="go_help('{$row['article_id']}')"><h4>{$row['title']}</h4><p>{$row['add_time']}</p></li>
       </foreach>
       </ul>
    </div>
</div>

<!--热门问题结束-->
</block>
<block name="script">
<script type="text/javascript">
	Do.ready('lazyload',function(){
	Lazy.Load();
	document.onscroll = function(){
		Lazy.Load();
	};
});

  function go_help(aid){
   var url=parseUrl({article_id:aid},'Help/article','open//','html');
   doClient(url);
}
</script>
</block>

