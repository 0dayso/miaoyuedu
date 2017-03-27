<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<php>
    $type=$_GET['type'];
    if($type=='popular'){
       $row=$popularfeedbacks;
    }elseif($type=='private'){
       $row=$myfeedbacks;
    }
</php>
<if condition="$row">
<div class="unit">
    <div class="hotproblem">
        <ul>
        <foreach name='row' item="row">
            <a href="{:url('Feedback/feedbackreply',array('mid'=>$row['mid'],'type'=>$type,'sex_flag'=>$sex_flag),'do')}"><li onClick="hg_gotoUrl('{:url('Feedback/feedbackreply',array('mid'=>$row['mid'],'type'=>$type,'sex_flag'=>$sex_flag),'do')}')"><h4><if condition="$row['totalreply'] gt 1"><span class="cred">[客服回答]></span></if><a href="{:url('Feedback/feedbackreply',array('mid'=>$row['mid'],'type'=>$type,'sex_flag'=>$sex_flag),'do')}">{$row['title']}</a></h4><p>{$row['creation_date']}</p></li></a>
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
