<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<style type="text/css">
    .frame .rt2{padding-left:0;}
</style>
<div class="unit mtop10">
    <div class="tit"><h1>限时特价</h1><span class="mlf10">全场五折起</span><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>

    <div class="frame nan">
        <ul id="tejia1">
        </ul> 
    </div>
<!--我喜欢开始-->
<if condition="$sex_flag eq 'nan'">
<div class="unit nan">
    <div class="like mbom40"><a href="javascript:change_like('nv', '{:url('', array('sex_flag'=>'nv'))}');"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看女生小说></a></div>
</div>
<else/>
<div class="unit nv">
    <div class="like mbom40"><a href="javascript:change_like('nan', '{:url('', array('sex_flag'=>'nan'))}');"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
</if>
<!--我喜欢结束-->
</div>
</block>
<block name="script">
<script type="text/html" id="tpl1">
{{each books as row i}}
		<a href="{{ {bid:row.bid} | router:'Book/view','html'}}">
       <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')">
        <div class="rt2">
            <h1 class="hidden">{{row.catename}}</h1>
            <p style="width:100%;height: 20px;overflow: hidden:;"><span style="float: right;margin-left: 10px;">{{row.classname}}</span><span style="float: right;margin-left: 10px;">{{row.authorname}}</span></p>
            <p>{{row.intro | stripTags}}</p>
        </div>
    </li></a>
{{/each}}
</script>
<script type="text/javascript">
    var runtimes = 0;
    var freeout = 0;
function timer(){
var time = times-runtimes;
var DD=Math.floor(time/(60*60*24));  
var HH=Math.floor(time/(60*60))%24;  
var MM=Math.floor(time/60)%60;  
var SS=Math.floor(time)%60;    
document.getElementById("timer1").innerHTML = DD + "天" + HH + ":" + MM + ":" + SS;
runtimes++;
if(time==0){
	clearTimeout(freeout);
	runtimes=0;
	getdiscountlist();
}
}

Do.ready('common','template',function(){
     getdiscountlist()
 });
function getdiscountlist(){
    $.ajax({
        type:'get',
        url: '{:url("Bookajax/getdiscountlist",'','do')}',
        data:{
            num:6,
            sex:'{$sex_flag}',
            type:1
        },
        success: function (data){
            if(data.status==1){
                var htmls=template('tpl1',data);
                $('#tejia1').html(htmls);
                 freeout=setInterval(timer,1000);
                times=data.end;
                window.onload=timer();
            }
        }
});
}
</script>
</block>