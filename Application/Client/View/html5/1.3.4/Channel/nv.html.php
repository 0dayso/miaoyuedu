<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="banner">
    <Hongshu:bangdan name="static_m_ad_nv">
        <div class="banner">
            {$row}
        </div>
    </Hongshu:bangdan>
</block>
<block name="body">
<!--公告开始-->
<div class="unit">
    <div class="gongao tit" onClick="hg_gotoUrl('{:url('Book/downapp',array('sex_flag'=>'nv'),'html')}');"><p class="hidden "><span class="laba"><a href="{:url('Book/downapp',array('sex_flag'=>'nv'),'html')}"><img src="__IMG__/ic_laba.png" /></a></span>下载客户端，签到得银币，好书免费看！</p></div>
</div>
<!--公告结束-->
<!--主编推荐开始-->
<div class="unit">
    <div class="tit"><h1>主编推荐</h1></div>
    <div class="frame clearfix">
        <ul>
            <Hongshu:bangdan name="android_nv_zhubian" items="2">
            <li class="clearfix" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div class="lf"><img data-src="{$row.face}"/></div>
                <div class="rt"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><h1 class="hidden">{$row.catename}</h1></a>
                 <p>{$row.intro}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--主编推荐结束-->
<!--火热推荐开始-->
<div class="unit">
    <div class="tit"><h1>火热推荐</h1></div>
    <div class="frame2">
        <ul>
            <Hongshu:bangdan name="android_nv_huore" items="3">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div><img data-src="{$row.face}"/>

                    <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><p>{$row.catename}</p></a></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--火热推荐结束-->
<!--今日免费开始-->
<div class="unit">
    <div class="tit"><h1>今日免费</h1><a href="{:url('Channel/free',array('sex_flag'=>'nv'),'html')}" class="more">更多></a><span class="flrt mrt10"><em class="cred" id="timer"></em></span></div>
    <div class="frame2">
        <ul id="free1">
        </ul>
    </div>
</div>
<!--今日免费结束-->
<!--重磅推荐开始-->
<div class="unit">
    <div class="tit"><h1>重磅推荐</h1></div>
    <div class="frame2">
        <ul>
        <Hongshu:bangdan name="android_nv_chongbang" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div><img data-src="{$row.face}"/>

                   <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><p>{$row.catename}</p></a></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--重磅推荐结束-->
<!--潜力推荐开始-->
<div class="unit">
    <div class="tit"><h1>潜力推荐</h1></div>
    <div class="frame2">
        <ul>
           <Hongshu:bangdan name="android_nv_qianli" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div><img data-src="{$row.face}"/>

                    <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><p>{$row.catename}</p></a></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--潜力推荐结束-->
<!--标签开始-->
<div class="unit ">
    <div class="frame4  clearfix">
        <ul>
         <foreach name="category[subclass]" item="row">
            <li class="tag2" onClick="hg_gotoUrl('{:url('Channel/search',array('classid'=>$row['classid'],'sex_flag'=>'nv'),'html')}')"><a href="{:url('Channel/search',array('classid'=>$row['classid'],'sex_flag'=>'nv'),'html')}">{$row.title}</a></li>
        </foreach>
        </ul>
    </div>
</div>
<!--标签结束-->
<!--新书上架开始-->
<div class="unit">
    <div class="tit"><h1>新书上架</h1></div>
    <div class="frame2">
        <ul>
          <Hongshu:bangdan name="android_nv_xinshu" items="9">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div><img data-src="{$row.face}"/>

                    <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><p>{$row.catename}</p></a></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>

</div>
<!--新书上架结束-->
<!--新书和免费开始-->
<div class="unit">
    <div class="frame14 twobk clearfix">
        <ul>
            <li onClick="hg_gotoUrl('{:url('Channel/xinshu',array('sex_flag'=>'nv'),'html')}');"><a href="{:url('Channel/xinshu',array('sex_flag'=>'nv'),'html')}"><h2 class="cpink">女频新书</h2></a><p>最新小说免费看</p></li>
            <li onClick="hg_gotoUrl('{:url('Channel/tejia',array('sex_flag'=>'nv'),'html')}');"><a href="{:url('Channel/tejia',array('sex_flag'=>'nv'),'html')}"><h2 class="cyellow">天天特价</h2></a><p>好书5折起</p></li>
        </ul>
    </div>
</div>
<!--新书和免费结束-->
<!--畅销榜开始-->
<div class="unit">
    <div class="tit borderbom"><h1>女生人气榜</h1></div>
    <div class="frame3">
        <ul id="lists">
        </ul>
    </div>
</div>
<!--畅销榜结束-->
<div class="unit">
    <div class="like mbom40"> <a href="javascript:change_like('nan', '__ROOT__/nan.html');"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
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
document.getElementById("timer").innerHTML = "还剩"+ DD + "天" + HH + ":" + MM + ":" + SS;
runtimes++;
    if(times<runtimes){
        if(freeout) {
            clearTimeout(freeout);
            runtimes = 0;
        }
        getfreelist();
    }
}
</script>
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <li class="hidden" onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')"><span class="num num{{i+1}}">{{i+1}}</span><span class="tag">{{row.classname}} | </span>{{row.catename}}</li>
        {{/each}}
    {{/if}}
</script>
<script type="text/html" id="tpl1">
{{each books as row i}}
    <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')">
        <div><img data-src="{{row.cover}}"/>
         <p>{{row.catename}}</p></div>
    </li>
{{/each}}    
</script>
<script type="text/javascript">
        /**控制榜单和广告行为的js**/
        Do.ready('touchslider', function(){
            var p2 = 0;
            var tt = new TouchSlider({
                id: 'slider1',
                auto: '0',
                fx: 'ease-out',
                direction: 'left',
                speed: 300,
                timeout: 4500,
                client: true,
                before: function (index) {
                    var as2 = $('#dot>div');
                    if ((typeof p2) !== 'undefined') {
						if(p2<=as2.length && p2>=0){
							$(as2[p2]).addClass('v3_dot_r').removeClass('v3_cur');
						}
                    }
                    p2 = index;
					if(p2<=as2.length && p2>=0){
						$(as2[p2]).addClass('v3_dot_r').addClass('v3_cur');
					}
                }
            });
            window.addEventListener('load', function() {
                touchEvent('li');
            }, false);
        });
        function touchstartremove(classname) {
            $("." + classname).css({"background-image": "none"});
        }
        function touchendadd(classname) {
            $("." + classname).css({"background-image": "url(__IMG__/splitline1.png)"});
        }

        Do.ready('lazyload', function () {
            Lazy.Load();
            document.onscroll = function () {
                Lazy.Load();
            };
        });
$(document).ready(function () {
        loadmore();
    });
        function loadmore(){
        var sortby = 'lastweek_salenum';
        var url = "{:url('Bookajax/search','','do')}";

        $.ajax({
            type: "GET",
            url: url,
            data: {method:'search',sex_flag:'nv',sortby:sortby,order:1,pagesize:10},
            timeout: 9000,
            dataType:'jsonp',
            success: function (data) {
                Do.ready('template', 'lazyload', function(){
                    if(data.bookinfo.length>0){
                        var htmls = template('huanyihuan_tpl',data);
                        $('#lists').html(htmls);
                        Lazy.Load();
                    }else{
                        Do.ready('functions', function(){
                            hg_Toast(data.message);
                        });
                    }
                });
            }
        });
   }
Do.ready('common','template',function(){
    getfreelist();
 });
function getfreelist(){
     $.ajax({
        type:'get',
        url: '{:url("Bookajax/getfreelist",'','do')}',
        data:{
            num:3,
            sex:'nv',
            bang:'free'
        },
        success: function (data){
            if(data.status==1){
                var htmls=template('tpl1',data);
                $('#free1').html(htmls);
                freeout=setInterval(timer,1000);
                times=data.end;
                window.onload=timer();
            }
        }
});
}
    </script>
</block>

