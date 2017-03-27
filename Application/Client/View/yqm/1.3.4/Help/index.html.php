<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--热门问题开始-->
<div class="main top20 bom40">
  <div class="help1200">
    <div class="helplist960">
      <h4 class="c3">萌众常见问题TOP10</h4>
      <div class="helplist top30">
        <div class="helplist_1">
        <!-- <foreach name="help_mobilefaq" item="row">
          <a class="c6" href="{:url('Help/article',array('article_id'=>$row['article_id']))}">· {$row.title}</a>
        </foreach> -->
         <foreach name="readerfaq" item="row">
          <a class="c6" href="{:url('Help/article',array('article_id'=>$row['article_id']),'html')}">· {$row.title}</a>
         </foreach>
        </div>
      </div>
    </div>
    
    <div class="helplist960">
      <h4 class="c3 top60">元气萌社区规则</h4>
      <div class="helplist top30">
        <div class="helplist_1">
         <foreach name="siterules" item="row">
          <a class="c6" href="{:url('Help/article',array('article_id'=>$row['article_id']),'html')}">· {$row.title}</a>
         </foreach>
        </div>
      </div>
    </div>
    
    <div class="helplist960">
      <h4 class="c3 top60">萌神帮助中心</h4>
      <div class="helplist top30 ">
        <div class="helplist_1">
          <foreach name="author_help" item="row">
          <a class="c6" href="{:url('Help/article',array('article_id'=>$row['article_id']),'html')}">· {$row.title}</a>
         </foreach>
        </div>
      </div>
    </div>
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

