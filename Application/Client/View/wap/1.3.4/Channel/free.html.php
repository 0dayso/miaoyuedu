<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<style type="text/css">
    .frame .rt2{padding-left:0;}
    .frame h1 {
    height: 36px;
    line-height: 36px;
    font-size: 16px;
    padding-right: 120px;
    width: 100%;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}

</style>
<!--本期重磅免费开始-->
<div class="unit mtop10">
    <div class="tit"><h1>本期重磅免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>
    <div class="frame clearfix">
        <ul class="nan" id="free1">
        </ul>
    </div>
</div>
<!--本期重磅免费结束-->
<!--限时全本免费开始-->
<div class="unit">
    <div class="tit"><h1>限时全本免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer2"></em></span></div>
    <div class="frame">
        <ul class="nan" id="free2">
        </ul>
    </div>
</div>
<!--限时全本免费结束-->
<!--免费新书开始-->
<div class="unit mtop10">
    <div class="tit"><h1>免费新书</h1><a class="more" href="{:url('Channel/xinshu',array('sex_flag'=>$sex_flag),'html')}">更多></a></div>
    <div class="frame clearfix">
        <ul class="nan">
        <Hongshu:bangdan name="android_xinshu{$sex_flag}_xinshumianfendu" items="4">
        	<a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}">
            <li class="clearfix" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div style="position:relative;">
                    <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><h1 class="hidden ">{$row.catename}</h1></a>
                    <p style="height: 20px;"><span style="float:right;font-size:11px;color:#999;margin-right:10px;">{$row.smallsubclassname}</span><span style="float:right;font-size:11px;color:#999;margin-right:10px;">{$row.author}</span></p>
                    <p>{$row.intro}</p>
                </div>
            </li></a>
         </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--免费新书结束-->

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
</block>
<block name="script">
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
document.getElementById("timer2").innerHTML = DD + "天" + HH + ":" + MM + ":" + SS;
runtimes++;
if(times<runtimes){
        if(freeout) {
            clearTimeout(freeout);
            runtimes = 0;
        }
        getfreelist();
    }
}
Do.ready('common','template',function(){
     getfreelist()
 });
function getfreelist(){
    $.ajax({
        type:'get',
        url: '{:url("Bookajax/getfreelist",'','do')}',
        data:{
            num:4,
            sex:'{$sex_flag}',
            bang:'free'
        },
        success: function (data){
            if(data.status==1){
                data['books'][0]['charnum']=parseInt(data['books'][0]['charnum']/10000);
                var htmls=template('tpl1',data);
                $('#free1').html(htmls);
                var htmls=template('tpl2',data);
                $('#free2').html(htmls);
                 freeout=setInterval(timer,1000);
                times=data.end;
                window.onload=timer();
            }
        }
});
}
</script>
<script type="text/html" id="tpl1">
<a href="{{ {bid:books[0]['bid']} | router:'Book/view','html'}}">
<li class="clearfix" onClick="hg_gotoUrl('{{ {bid:books[0]['bid']} | router:'Book/view','html'}}')">
    <div class="rt2">
        <h1 class="hidden">{{books[0]['catename']}}</h1>
        <p style="width:100%;height: 20px;overflow: hidden:;"><span style="float: right;margin-left: 10px;">{{books[0]['smallsubclassname']}}</span><span style="float: right;margin-left: 10px;">{{books[0]['author']}}</span></p>
        <p>{{books[0]['intro'] | stripTags}}</p>
    </div>
</li></a>
</script>
<script type="text/html" id="tpl2">
{{each books as row i}}
{{if i != 0}}
<a href="{{ {bid:row.bid} | router:'Book/view','html'}}">
<li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')">
    <div class="rt2">
        <h1 class="hidden">{{row.catename}}</h1>
        <p style="width:100%;height: 20px;overflow: hidden:;"><span style="float: right;margin-left: 10px;">{{row.smallsubclassname}}</span><span style="float: right;margin-left: 10px;">{{row.author}}</span></p>
        <p>{{row.intro | stripTags}}</p>
    </div>
</li></a>
{{/if}}
{{/each}}    
</script>
</block>
