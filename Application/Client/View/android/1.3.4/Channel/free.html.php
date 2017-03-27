<extend name="Common/base" />
<block name="body">
<!--本期重磅免费开始-->
<div class="unit mtop10">
    <div class="tit"><h1>本期重磅免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer1"></em></span></div>
    <div class="frame clearfix">
        <ul id="free1">
        </ul>
    </div>
</div>
<!--本期重磅免费结束-->
<!--限时全本免费开始-->
<div class="unit">
    <div class="tit"><h1>限时全本免费</h1><span class="flrt mrt10">还有<em class="cred" id="timer2"></em></span></div>
    <div class="frame2">
        <ul id="free2">
        </ul>
    </div>
</div>
<!--限时全本免费结束-->
<!--免费新书开始-->
<div class="unit mtop10">
    <div class="tit"><h1>免费新书</h1><a class="more" href="javascript:hg_gotoUrl('{:url('Channel/xinshu',array('sex_flag'=>$sex_flag))}');">更多></a></div>
    <div class="frame clearfix">
        <ul>
        <Hongshu:bangdan name="android_xinshu{$sex_flag}_xinshumianfendu" items="4">
            <li class="clearfix" onClick="doChild('{:url('Book/view',array('bid'=>$row['bid']))}')">
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
<div class="unit">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div>
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
Do.ready('common','template','lazyload',function(){
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
            data['books'][0]['charnum']=parseInt(data['books'][0]['charnum']/10000);
            if(data.status==1){
                var htmls=template('tpl1',data);
                $('#free1').html(htmls);
                var htmls=template('tpl2',data);
                $('#free2').html(htmls);
                Lazy.Load();
                 freeout=setInterval(timer,1000);
                times=data.end;
                window.onload=timer();
            }
        }
});
}
</script>
<script type="text/html" id="tpl1">
<li class="clearfix" onClick="doChild('{{ {bid:books[0]['bid']} | router:'Book/view','html'}}')">
    <div class="lf"><img src="{{books[0]['cover']}}"/></div>
    <div class="rt">
        <h1 class="hidden">{{books[0]['catename']}}</h1>
          <p class="free01p f14c0">{{books[0]['author']}}/著</p>
        <p  class="free01p "><span class="mrt10">{{books[0]['smallsubclassname']}}</span><span class="mrt10" >{{if books[0]['lzinfo'] ==1}}完本{{else}}连载{{/if}}</span></p>
        <p  class="free01p">{{books[0]['charnum']}}万字</p>
    </div>
</li>
</script>
<script type="text/html" id="tpl2">
{{each books as row i}}
{{if i != 0}}
<li onClick="doChild('{{ {bid:row.bid} | router:'Book/view','html'}}')">
    <div><img src="{{row.cover}}"/>
    <p>{{row.catename}}</p></div>
</li>
{{/if}}
{{/each}}    
</script>
</block>
