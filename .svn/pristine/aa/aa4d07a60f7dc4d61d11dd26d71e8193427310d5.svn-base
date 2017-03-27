<extend name="Common/base" />
<block name="title">
    <title><notempty name="pageTitle">{$pageTitle}-{:C('CLIENT.'.CLIENT_NAME.'.name')}
<else />{:C('CLIENT.'.CLIENT_NAME.'.name')}
</notempty></title>
    <meta name="keywords" content="{$bookinfo.catename},{$bookinfo.smallsubclassname},{$bookinfo.author},{$bookinfo.tags|str_replace=' ',',',###}" />
    <meta name="description" content="{$bookinfo['intro']|strip_tags}" />
</block>
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<style type="text/css">
    .readtit{background-color:#fff;}
    .readtit{box-shadow:none;border:0;}
</style>
<!-- <style>.btn4{width:50%;}</style> --> 
<!--封面开始-->
<div class="unit mtop10">
    <div class="frame fm fmfree">
        <ul>
            <li>
                <div class="lf2"><img src="{$bookinfo['bid']|getBookfacePath=###,'middle'}"/></div>
                <div class="rt5">
                    <h1>{$bookinfo.catename}<if condition="$bookinfo['pricebeishu'] eq 167"><img src="__IMG__/f2.png" lazy="y" style="width:14px;height:18px;" /></if><if condition="$bookinfo['pricebeishu'] eq 200"><img src="__IMG__/g2.png" lazy="y" style="width:14px;height:18px;" /></if></h1>
                    <p>作者：{$bookinfo['author']}</p>
                    <p>字数：<?php
                        if ($bookinfo['charnum'] > 10000) {
                            $bookinfo['charnum'] = round($bookinfo['charnum'] / 10000, 1) . "万";
                        }

                        echo $bookinfo['charnum'] . '字';
                        ?></p>
                    <p>章节：{$totalchapter}章</p>
                    <p>类别：{$bookinfo.smallsubclassname}</p>
                    <p id="zhuangtai">状态：<if condition="$bookinfo['lzinfo'] eq 1">完本<else/>连载</if></p>
                    <p><span>点击：{:strlen($bookinfo['total_hit'])>=5 ?number_format(($bookinfo['total_hit'] / 1000)).'k+' : number_format($bookinfo['total_hit'])}</span><span class="mlf10">评论：{$total_comment}</span></p>
                    <p id="timer" class="cred"></p>
                </div>
            </li>
        </ul>
    </div>
 <div class=" rdbtn3 clearfix">
 <ul id="statebtncon">
    <li id="sc"><a>收藏</a></li>
    <li id="ysc" style="display:none;"><a>已收藏</a></li>
    <li><a href="{:url('Book/downapp','','html')}" id="xiazai">下载本书</a></li>
    <li id="getlog" style="padding-right: 10px;">
    <if condition="$bookinfo['isvip_last'] eq 1">
    <a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'do')}">
    <else/>
    <a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'html')}">
    </if><span id="readname">开始阅读</span></a></li>
 </ul>
 </div>
</div>
<!--封面结束-->
<!--简介开始-->
<div class="unit">
    <div class="intro">
        <div id="_intro" style="line-height: 23px;
    height: 69px;
    overflow: hidden;
    text-overflow: ellipsis;">{$bookinfo['intro']}</div>
     <div class="introbtn"><a class="cyellow" href="javascript:void(0);" onclick="getIntro();" id="introexptext">展开简介</a></div>
    </div>
</div>
<!--简介结束-->
<!--目录开始-->
<div class="unit">
    <div class="readtit" id="mulu1" onClick="hg_gotoUrl('{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}')">
            <a href="{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" id="mulu2"><h4>目录</h4></a>
            <span id="lastchaptitle"></span>
        </div>
</div>
<!--目录结束-->

<div class="unit" id="dashang1"  onClick="hg_gotoUrl('{:url('Book/dashang',array('bid'=>$bookinfo['bid']),'do')}')">
    <div class="readtit">
        <a href="{:url('Book/dashang',array('bid'=>$bookinfo['bid']),'do')}" id="dashang2"><h4>打赏</h4></a>
        <if condition="$proListTime[0]">
        <span>{$proListTime[0]['nickname']}<c class="mlf10">{$proListTime[0]['proname']}×{$proListTime[0]['num']}></c></span>
        </if>
    </div>
</div>
<!--评论开始-->
<div class="unit">
    <div class="tit "><h1>精华评论</h1><a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="cred flrt "><span class="ic_write" onClick="hg_gotoUrl('{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}')" >我要评论</span></a></div>
    <div class="frame10 comments">
     <if condition="count($comments) gt 0">
     <ul>
        <foreach name="comments" item="row">
            <if condition="$row.forbidden_flag eq 1">
                    <php>$uid=intval($userinfo['uid'])</php>
                    <if condition="$row.uid eq $uid">
                 <li>
                        <div>
                        <h4>{$row.nickname}
                        <if condition="$row.highlight_flag eq 1">
                            <span class="ic_jh">精华</span></if>
                        <if condition="$row.lcomment eq 1">
                            <span class="ic_cp">长评</span></if>
                        </h4>
                         <p class="cred">内容正在审核中</p>
                         <p class="cred">{$row.content}</p>
                        </div>
                 </li>
                    </if>
             <else/>
                 <li>
                        <div>
                        <h4>{$row.nickname}
                            <if condition="$row.highlight_flag eq 1">
                                 <span class="ic_jh">精华</span></if>
                            <if condition="$row.lcomment eq 1">
                                <span class="ic_cp">长评</span></if>
                        </h4>
                        <p>{$row.content}</p>
                        </div>
                </li>
             </if>
                    <!-- <p class="combom"><span class="fllf"></span><a onClick="hg_gotoUrl('{:url('Book/comment',array('bid'=>$bookinfo['bid']))}')" class="detail">详情</a><a href="#" class="reply">回复({$row.reply_amount})</a></p> -->

            </li>
        </foreach>
        </ul>
        <else/>
            <div style="text-align: center;">暂无评论</div>
        </if>
    </div>
    <div class="morecom"><a class="cyellow" href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}">更多书评></a></div>
</div>

<!--评论结束-->

<!--读过这本书的还读过开始-->
<div class="unit" style="margin-bottom: 60px;">
    <div class="tit borderbom"><h1>读过这本书的还读过</h1></div>
    <div class="frame3">
        <ul>
        <php>$_i = 0;</php>
        <Hongshu:bangdan name="class_{$style}_chaptercainixihuan" items="4">
            <if condition="$row[bid] neq $bookinfo[bid]">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')" class="hidden">
                <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}">{$row['catename']}</a><span class="zzm">{$row.author}</span><span class="tag">{$row.smallsubclassname}</span>
            </li>
            <php>$_i++;if($_i>=3)break;</php>
            </if>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--读过这本书的还读过结束-->
</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', function(){
    getreadlog();
    var runtimes = 0;
    var endtime = 0;
    $.ajax({
        type:'get',
        url: '{:url("Bookajax/getbookstatus",'','do')}',
        data:{
            bid:{$bookinfo.bid|intval},
            bang:'free'
        },
        success: function (data){
            $('#lastchaptitle').html(data.lastupdatechptitle);
            endtime = data.end;
            if(data.isxiajia){
                $('#zhuangtai').html('状态：下架');
                $('#mulu1').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                $('#mulu2').attr("href","{:url('Book/xiajia','','do')}");
                $('#dashang1').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                $('#dashang2').attr("href","{:url('Book/xiajia','','do')}");
                Do.ready('template',function(){
                    var htmls=template('tpl3',data);
                    $('#statebtncon').html(htmls);
                });
            }else if(data.isFree){
                $('.frame').addClass('fmfree');
                setInterval(timer1,1000);
                window.onload=timer1();
            var data = {
                isFree: data.isFree,
                bid: {$bookinfo.bid|intval},
                isFav: data.isFav,
                url: data.readurl,
                title: data.title
            };
            $('#xiazai').html('限免中').attr('href','').addClass('disable');
            $('#readname').html('免费阅读');
            $('#getlog').addClass('freebtn');
       }else if(data.isDiscount || data.isBookDiscount){
           $('.frame').addClass('fmfree');
                setInterval(timer2,1000);
                window.onload=timer2();
            };
        }
    });

    function timer1(){
        var time = endtime-runtimes;
        if(time<0){
            return false;
        }
        var DD=Math.floor(time/(60*60*24));
        var HH=Math.floor(time/(60*60))%24;
        var MM=Math.floor(time/60)%60;
        var SS=Math.floor(time)%60;

        document.getElementById("timer").innerHTML = "限时免费中！还剩"+ DD + "天" + HH + ":" + MM + ":" + SS;
        runtimes++;
        if(time==0){
            runtimes=0;
            history.go(0);
        }

    }
    function timer2(){
        var time = endtime-runtimes;
        if(time<0){
            return false;
        }
        var DD=Math.floor(time/(60*60*24));
        var HH=Math.floor(time/(60*60))%24;
        var MM=Math.floor(time/60)%60;
        var SS=Math.floor(time)%60;

        document.getElementById("timer").innerHTML = "打折中！还剩"+ DD + "天" + HH + ":" + MM + ":" + SS;
        runtimes++;
        if(time==0){
            runtimes=0;
            history.go(0);
        }
    }
});
</script>
<script type="text/javascript">
    Do.ready('lazyload',function(){
        Lazy.Load();
        document.onscroll = function(){
            Lazy.Load();
        };
    });

    function getIntro() {
        var h = $('#_intro').css('height');
        if(h=='auto'){
            h='69px';
            text="展开简介";
        } else {
            h='auto';
            text="收起简介";
        }
        $('#_intro').css({height:h});
        $('#introexptext').html(text)
            }

    function getreadlog(){
       var url = "{:url('Bookajax/getViewReadLog', '', 'do')}";
       $.ajax({
        type:'get',
        url: url,
        data:{
            bid:{$bookinfo.bid|intval},
        },
        success: function (data){
        	Do.ready('template',function(){
               var readname=$('#readname').html();
                if(readname=='免费阅读'){
                data.xianmian=true;}
        		var htmls=template('tpl1',data);
                $('#getlog').html(htmls);
        	});
        }
    });
    }

//添加到书架
function InsertFav(bid){
    var zt=$('#sc').attr('zt');
    if(zt==1){
        return;
    }
    if(!bid){
        hg_Toast('缺少参数');
        return false;
    }
    var data = {
        bid:bid,
    }
    var url = "{:url('Userajax/insertfav','','do')}";
    $.ajax({
        type:'get',
        url: url,
        data:data,
        beforeSend:function(){
           $('#sc').attr('zt','1');
        },
        success: function (data){
            if(data.status == 1){
               hg_Toast(data.message);
               $('#ysc').show();$('#sc').hide();
            }else{
                hg_Toast(data.message);
                $('#sc').attr('zt','0');
                if(data.url){
                    window.location.href = data.url;
                }
            }
        }
    })
}
function shoucang(){
    var data = {
        bid:'{$bookinfo['bid']}',
    }
    var url = "{:url('Bookajax/checkfav',array(),'do')}";
    $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data){
                if(data.isfav == 1){
                  $('#ysc').show();$('#sc').hide();
            }
            }
    });
}
Do.ready("common",function(){
   UserManager.addListener(function(userinfo){
         $('#sc').on('click',function(){
               if(userinfo.islogin){
                   InsertFav({$bookinfo.bid});
               }else{
                   hg_Toast('请先登录');
               }
         });
   });
   shoucang();
});

</script>
<script type="text/html" id="tpl1">
    {{if isvip}}
    <a href="{{ {bid:bid,chpid:lastreadchpid} | router:'Book/readVip','do'}}" onClick="hg_gotoUrl('{{ {bid:bid,chpid:lastreadchpid} | router:'Book/readVip','do'}}')">
    {{else}}
    <a href="{{ {bid:bid,chpid:lastreadchpid} | router:'Book/read','html'}}" onClick="hg_gotoUrl('{{ {bid:bid,chpid:lastreadchpid} | router:'Book/read','html'}}')">
    {{/if}}
    {{if xianmian}}
    <span id="readname">免费阅读</span></a>
    {{else}}
    {{if lastreadchporder <=1}}
    <span id="readname">开始阅读</span></a>
    {{else}}
    <span id="readname">继续阅读</span></a>
    {{/if}}{{/if}}
</script>
<script type="text/html" id="tpl3">
    <li onclick="hg_gotoUrl('{:url('Book/xiajia','','do')}')"><a>收藏</a></li>
    <li><a href="{:url('Book/downapp','','html')}" id="xiazai">下载本书</a></li>
    <li style="padding-right: 10px;">
    <a href="{:url('Book/xiajia','','do')}"><span id="readname">开始阅读</span></a></li>
</script>
</block>
