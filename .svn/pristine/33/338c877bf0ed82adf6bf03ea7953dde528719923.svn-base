<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--热门问题开始-->
<div class="main top20 bom40">
    <div class="help1200">
        <div class="helplist960">
            <p class="help_nav"><a class="c9" href="{:url('Index/index','','html')}">首页</a> > <a class="c9" href="{:url('Help/index','','html')}">帮助中心</a> > <a class="c9">{$title}</a></p>
            <h4 class="c3 bom40">{$title}</h4>
            <div class="help_wd">
                {$content}
            </div>
        </div>
        
        <!-- <div class="helplist960">
            <h1 class="c3 top60">相关问题</h1>
            <div class="helplist top30 ">
                <div class="helplist_1">
                    <foreach name="relatefaq" item="row">
                      <a class="c6" href="{:url('Help/article',array('article_id'=>$row['article_id']))}">· {$row.title}</a>
                    </foreach>
                </div>
            </div>
        </div> -->
        
        
        
    </div>
</div>
<!--热门问题结束-->
<!--底开始-->
</block>
<block name="script">
    <script>
    	Do.ready('lazyload',function(){
    	Lazy.Load();
    	document.onscroll = function(){
    		Lazy.Load();
    	};
    });
    </script>
</block>