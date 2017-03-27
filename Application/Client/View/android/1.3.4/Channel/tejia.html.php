<extend name="Common/base" />
<block name="body">
<div class="unit mtop10">
    <div class="tit"><h1>限时特价</h1><span class="mlf10">全场五折起</span><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>

    <div class="frame">
        <ul id="tejia1">
        </ul> 
    </div>
<!--我喜欢开始-->
<div class="unit">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div>
<!--我喜欢结束-->
</div>
</block>
<block name="script">
<script type="text/html" id="tpl1">
{{each books as row i}}
       <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')">
        <div class="lf"><img src="{{row.cover}}"/></div>
        <div class="rt"><h1 class="hidden">{{row.catename}}</h1>

            <div class="tejia clearfix"><h5 class="fllf ">{{row.author}}</h5><span class="cred flrt"><em
                    class="through cgray" style="text-decoration: line-through;">5/千字</em>{{row.discount}}折</span></div>
            <p class="h38em">{{#row.intro}}</p></div>
    </li>
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

Do.ready('common','template','lazyload',function(){
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
                Lazy.Load();
                 freeout=setInterval(timer,1000);
                times=data.end;
                window.onload=timer();
            }
        }
});
}
</script>
</block>