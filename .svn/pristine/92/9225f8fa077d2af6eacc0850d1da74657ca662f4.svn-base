<extend name="Common/base" />
<block name="body">
<body class="bgce">
<!--签到页面start-->
<div class="sign signtop clearfix">
    <span class="boxsizing"><php> echo intval(date('m'));</php>月</span>
            <if condition="$isqiandao">
            <div class="btn-qd" onClick="hg_gotoUrl('{:url('User/lingjiang')}')"><a class="signbtn radius4">签到</a></div>
            <elseif condition="intval($is_need_lingjiang) neq 0" />
            <div class="btn-qd" onClick="hg_gotoUrl('{:url('User/lingjiang')}')"><a class="signbtn radius4">抽奖({$is_need_lingjiang})</a></div>
            <else/>
            <div class="sign signtop">
            <a class="signbtn2 radius4">今日已签到</a>
            </div>
            </if>
</div>
<div class="sign signbom">
    <div class="signtime clearfix">
        <ul>
        <?php
           $week=intval(Date("w", strtotime(Date("Y-n-1"))));
        for ($i=0;$i<$week;$i++) {
           echo "<li></li>";
        }
        ?>
        <?php foreach($qiandaodays as $key=>$row){
        	$class= '';
            $thisday=date("j");
            $day = intval($row['day']);
        	if($thisday==$day) $class.=' next radius50';
        	$weekend = '';
			if((($week+$day) % 7)==0 || (($week+$day) % 7)==1) $weekend =' zm';
			if($row['is_qiandao']==1) $class.=' signed';
            ?>
          <li class="<?php echo $weekend;?>" ><div class="signtimecon"><?php echo $day;?><span class="<?php echo $class;?>"></span></div>
          </li><?php }?>
        </ul>


    </div>

</div>
<!--签到页面end-->
</block>