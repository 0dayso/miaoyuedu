<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--热门问题开始-->
<div class="unit">
    <div class="hotproblem2">
        <h2><if condition="$replystatus eq 1"><span class="cred">[客服回答]></span></if>{$mycontent['title']}</h2>
        <p>{$mycontent['content']}</p>
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