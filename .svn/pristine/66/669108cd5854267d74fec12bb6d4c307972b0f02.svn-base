<extend name="Common/base" />
<block name="body">
<if condition="$myfeedbacks">
<div class="unit">
    <div class="hotproblem">
        <ul>
        <foreach name='myfeedbacks' item="row">
            <li onClick="hg_gotoUrl('{:url('Feedback/feedbackreply',array('mid'=>$row['mid']))}')"><h4><if condition="$row['totalreply'] gt 1"><span class="cred">[客服回答]></span></if>{$row['title']}</h4><p>{$row['creation_date']}</p></li>
        </foreach>
        </ul>
    </div>
</div>
<else/>
<div>
   <p class="norecord">暂无记录</p>
</div>
</if>
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
