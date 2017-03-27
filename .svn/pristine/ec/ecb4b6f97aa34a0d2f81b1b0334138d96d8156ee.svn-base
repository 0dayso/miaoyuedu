<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--本期重磅免费开始-->
<div class="unit mtop10">
    <div class="tit"><h1>本期重磅免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>
    <div class="frame clearfix">
        <ul class="nan">
        <php>$_oldbid=0;</php>
        <Hongshu:bangdan name="android_free{$sex_flag}_benjizhudai" items="1" full="true" cutorder="rand">
            <li class="clearfix" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img src="{$row.face}"/></div>
                <div class="rt">
                    <h1 class="hidden">{$row.catename}</h1>
                      <p class="free01p f14c0">{$row.author}/著</p>
                    <p  class="free01p "><span class="mrt10">{$row.subclassname}</span><span class="mrt10" ><if condition="$row.lzinfo eq 1">完本<else/>连载</if></span></p>
                    <p  class="free01p"><?php echo floor($row['charnum']/10000); ?>万字</p>
                </div>
            </li>
            <if condition="!$_oldbid">
            <php>$_oldbid=$row['bid'];</php>
            </if>
        </Hongshu:bangdan>   
        </ul>
    </div>
</div>
<!--本期重磅免费结束-->
<!--限时全本免费开始-->
<div class="unit">
    <div class="tit"><h1>限时全本免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer2"></em></span></div>
    <div class="frame2">
        <ul class="nan"> 
        <php>$__i = 0;</php>
        <Hongshu:bangdan name="android_free{$sex_flag}_benjizhudai" items="4" cutorder="rand" key="k">
        <if condition="$row.bid neq $_oldbid">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img src="{$row.face}"/>
                <p>{$row.catename}</p></div>
            </li>
            <php>$__i++;</php>
        </if>
        <if condition="$__i egt 3">
        <php>break;</php>
        </if>     
        </Hongshu:bangdan>    
        </ul>
    </div>
</div>
<!--限时全本免费结束-->
<!--免费新书开始-->
<div class="unit mtop10">
    <div class="tit"><h1>免费新书</h1><a class="more" href="javascript:hg_gotoUrl('{:url('Channel/xinshu')}');">8本></a></div>
    <div class="frame clearfix">
        <ul class="nan">
        <Hongshu:bangdan name="android_xinshu{$sex_flag}_xinshumianfendu" items="4">
            <li class="clearfix" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img src="{$row.face}"/></div>
                <div class="rt">
                    <h1 class="hidden">{$row.catename}</h1>
                    <p>{$row.intro}</p>
                </div>
            </li>
         </Hongshu:bangdan>    
        </ul>
    </div>
</div>
<!--免费新书结束-->

<!--我喜欢开始-->
<if condition="$sex_flag eq 'nan'">
<div class="unit nan">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nv'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看女生小说></a></div>
</div>
<else/>
<div class="unit nv">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nan'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
</if>
<!--我喜欢结束-->
</block>
<block name="script">
<script type="text/javascript">
	var runtimes = 0;
function timer(){
var time = {:getFreeEndTime()}-runtimes;
var DD=Math.floor(time/(60*60*24));  
var HH=Math.floor(time/(60*60))%24;  
var MM=Math.floor(time/60)%60;  
var SS=Math.floor(time)%60;    
document.getElementById("timer1").innerHTML = DD + "天" + HH + ":" + MM + ":" + SS;
document.getElementById("timer2").innerHTML = DD + "天" + HH + ":" + MM + ":" + SS;
runtimes++;
}
setInterval(timer,1000);
window.onload=timer; 
</script>
</block>
