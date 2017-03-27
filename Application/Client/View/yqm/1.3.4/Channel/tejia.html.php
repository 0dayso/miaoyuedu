<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<div class="unit mtop10">
    <div class="tit"><h1>限时特价</h1><span class="mlf10">全场五折起</span><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>

    <div class="frame nan">
        <ul>
        <Hongshu:bangdan name="android_tejiaindex_{$sex_flag}tejia" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img src="{$row.face}"/></div>
                <div class="rt"><h1 class="hidden">{$row.catename}</h1>

                    <div class="tejia clearfix"><h5 class="fllf ">{$row.author}</h5><span class="cred flrt"><em
                            class="through cgray">5/千字</em>五折</span></div>
                    <p class="h38em">{$row.intro}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul> 
    </div>
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
</div>
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
runtimes++;
}
setInterval(timer,1000);
window.onload=timer; 
</script>
</block>