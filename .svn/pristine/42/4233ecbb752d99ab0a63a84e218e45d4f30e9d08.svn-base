<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--热门问题开始-->
<div class="unit">
    <div class="hotproblem">
       <ul>
       <foreach name="help_mobilefaq" item="row">
           <li onClick="hg_gotoUrl('{:url('Help/article',array('article_id'=>$row['article_id'],'sex_flag'=>$sex_flag),'html')}')"><a href="{:url('Help/article',array('article_id'=>$row['article_id'],'sex_flag'=>$sex_flag),'html')}"><h4>{$row['title']}</h4><p>{$row['add_time']}</p></a></li>
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
</script>
</block>

