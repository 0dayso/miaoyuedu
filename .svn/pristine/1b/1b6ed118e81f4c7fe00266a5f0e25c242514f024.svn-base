<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--公告开始-->
<!--
<div class="unit">
    <div class="gongao tit" onClick="hg_gotoUrl('{:url('User/qiandao', array('action'=>'form'))}');"><p class="hidden "><span class="laba"><img src="__IMG__/ic_laba.png" /></span>每日签到，红薯银币免费得！</p></div>
</div>
-->
<!--公告结束-->
<!--主编推荐开始-->
<div class="unit">
    <div class="tit"><h1>主编推荐</h1></div>
    <div class="frame clearfix">
        <ul>
        <Hongshu:bangdan name="android_{$sex_flag}_zhubian" items="2">
            <li class="clearfix" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img data-src="{$row.face}"/></div>
                <div class="rt"><h1 class="hidden">{$row.catename}</h1>
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
        <Hongshu:bangdan name="android_{$sex_flag}_huore" items="3">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img data-src="{$row.face}"/>

                    <p>{$row.catename}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--火热推荐结束-->
<!--今日免费开始-->
<div class="unit">
    <div class="tit"><h1>今日免费</h1><a href="javascript:hg_gotoUrl('{:url('Channel/free')}');" class="more">更多></a><span class="flrt mrt10"><em class="cred" id="timer"></em></span></div>
    <div class="frame2">
        <ul>
        <Hongshu:bangdan name="android_free{$sex_flag}_benjizhudai" items="3">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img data-src="{$row.face}"/>

                    <p>{$row.catename}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--今日免费结束-->
<!--潜力推荐开始-->
<if condition="$sex_flag eq 'nv'">
<div class="unit">
    <div class="tit"><h1>重磅推荐</h1></div>
    <div class="frame2">
        <ul>
        <Hongshu:bangdan name="android_nv_chongbang" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img data-src="{$row.face}"/>

                    <p>{$row.catename}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
</if>
<div class="unit">
    <div class="tit"><h1>潜力推荐</h1></div>
    <div class="frame2">
        <ul>
        <Hongshu:bangdan name="android_{$sex_flag}_qianli" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img data-src="{$row.face}"/>

                    <p>{$row.catename}</p></div>
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
<if condition="$sex_flag eq 'nan'">
        <foreach name="category" item="row">
            <li class="tag3" onClick="hg_gotoUrl('{:url('Channel/search',array('classid'=>$row['classid']))}')">{$row.title}</li>
        </foreach>
<else/>
         <foreach name="category[subclass]" item="row">
            <li class="tag2" onClick="hg_gotoUrl('{:url('Channel/search',array('classid'=>$row['classid']))}')">{$row.title}</li>
        </foreach>
</if>
        </ul>
    </div>
</div>
<!--标签结束-->
<!--新书上架开始-->
<div class="unit">
    <div class="tit"><h1>新书上架</h1></div>
    <div class="frame2">
        <ul>
        <Hongshu:bangdan name="android_{$sex_flag}_xinshu" items="9">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div><img data-src="{$row.face}"/>

                    <p>{$row.catename}</p></div>
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
        <if condition="$sex_flag eq 'nan'">
            <li onClick="hg_gotoUrl('{:url('Channel/xinshu')}');"><h2 class="cgreen">男频新书</h2><p>最新免费小说免费看</p></li>
            <li onClick="hg_gotoUrl('{:url('Channel/tejia')}');"><h2 class="cyellow">天天特价</h2><p>热销小说免费</p></li>
        <else/>
            <li onClick="hg_gotoUrl('{:url('Channel/xinshu')}');"><h2 class="cpink">女频新书</h2><p>总裁求交往</p></li>
            <li onClick="hg_gotoUrl('{:url('Channel/tejia')}');"><h2 class="cyellow">天天特价</h2><p>1{:C('SITECONFIG.MONEY_NAME')}起</p></li>
        </if>
        </ul>
    </div>
</div>
<!--新书和免费结束-->
<!--畅销榜开始-->
<div class="unit">
    <div class="tit borderbom"><h1><if condition="$sex_flag eq 'nan'">男生<else/>女生</if>红文榜</h1></div>
    <div class="frame3">
        <ul id="lists">
        </ul>
    </div>
</div>
<!--畅销榜结束-->
<if condition="$sex_flag eq 'nan'">
<div class="unit nan">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nv'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看女生小说></a></div>
</div>
<else/>
<div class="unit nv">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nan'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
</if>
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
document.getElementById("timer").innerHTML = "还剩"+ DD + "天" + HH + ":" + MM + ":" + SS;
runtimes++;
}
setInterval(timer,1000);
window.onload=timer;
</script>
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <li class="hidden" onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')"><span class="num num{{i+1}}">{{i+1}}</span><span class="tag">{{row.classname}} | </span>{{row.catename}}</li>
        {{/each}}
    {{/if}}
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
        var url = "{:url('Bookajax/search')}";

        $.ajax({
            type: "GET",
            url: url,
            data: {method:'search',sex_flag:'{$sex_flag}',sortby:sortby,order:1,pagesize:10},
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
    </script>
</block>
