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
<style>.btn4{width:50%;}</style>
<!--封面开始-->
<div class="unit mtop10">
    <div class="frame fm">
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
                    <p id="timer" class="cred"></p>
                </div>
            </li>
        </ul>
    </div>
 <div class=" rdbtn4 clearfix">
     <div class="btn4 hidden">点击({:strlen($bookinfo['total_hit'])>=5 ?number_format(($bookinfo['total_hit'] / 1000)).'k+' : number_format($bookinfo['total_hit'])})</div>
     <!-- <div class="btn4 hidden">粉丝({$fensitotal})</div> -->
     <div class="btn4 hidden">评论({$total_comment})</div>
     <!-- <div class="btn4 hidden">分享</div> -->
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
     <div class="readtit" onClick="hg_gotoUrl('{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}')">
        <a href="{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" id="mulu"><h4>目录</h4></a>
        <span id="lastchaptitle"></span>
    </div>
</div>
<!--目录结束-->
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
                       <div class="lf"><if condition="$row['avatar']"><img src="{$row.avatar}" /><else/><img src="__IMG__/avater.jpg" /></if></div>
                        <div class="rt">
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
                        <div class="lf"><if condition="$row['avatar']"><img src="{$row.avatar}" /><else/><img src="__IMG__/avater.jpg" /></if></div>
                        <div class="rt">
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
    <div class="tit"><h1>读过这本书的还读过</h1></div>
    <div class="frame2">
        <ul>
        <php>$_i = 0;</php>
        <Hongshu:bangdan name="class_{$style}_chaptercainixihuan" items="4">
            <if condition="$row[bid] neq $bookinfo[bid]">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div><img src="{$row.face}"/>
                    <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}"><p>{$row['catename']}</p></a></div>
            </li>
            <php>$_i++;if($_i>=3)break;</php>
            </if>
        </Hongshu:bangdan>
        </ul>
    </div>
</div>
<!--读过这本书的还读过结束-->
<!--底部四个按钮开始-->

<div class="statebtn4">
    <div class="statebtncon">
    <div class="lf" >
         <div id="dashang" class="statebtn01 statebtn" onClick="hg_gotoUrl('{:url('Book/dashang',array('bid'=>$bookinfo['bid']),'do')}')"><a href="{:url('Book/dashang',array('bid'=>$bookinfo['bid']),'do')}"><img src="__IMG__/ic_gift.png" /></a><span>打赏</span></div>
        <div class="statebtn02 statebtn" id="sc" >
        <img src="__IMG__/ic_favorite.png?{:C('SOURCE_VER')}" /><span>收藏</span>
        </div>
        <div class="statebtn02 statebtn " id="ysc" style="display:none;"><img src="__IMG__/ic_favorite2.png?{:C('SOURCE_VER')}" /><span>已收藏</span></div>
        </div>
        <div id="down">
        <div class="statebtn03 statebtn" onClick="hg_gotoUrl('{:url('Book/downapp','','html')}')"><a href="{:url('Book/downapp','','html')}"><img src="__IMG__/ic_file_download.png?{:C('SOURCE_VER')}"/></a><span>下载本书</span>
        </div></div>
        <div id="getlog">
        <if condition="$bookinfo['isvip_last'] eq 1">
        <div class="statebtn04 statebtn" onClick="hg_gotoUrl('{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'do')}')"><a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'do')}"><img src="__IMG__/ic_local_library.png" /></a>
        <else/>
        <div class="statebtn04 statebtn" onClick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'html')}')"><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'html')}"><img src="__IMG__/ic_local_library.png" /></a>
        </if><span id="readname">开始阅读</span></div>
        </div>
        
    </div>
</div>
<!--底部四个按钮结束-->
</block>
<block name="script">
<script src="http://author.hongshu.com/hit.php?bid={$bookinfo['bid']}&nojs=1"></script>
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
                $('.readtit').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                $('#mulu').attr("href","{:url('Book/xiajia','','do')}")
                Do.ready('template',function(){
                    var htmls=template('tpl3',data);
                    $('.statebtncon').html(htmls);
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
            Do.ready('template',function(){
                var htmls=template('tpl2',data);
                $('#down').html(htmls);
            });
            $('#readname').html('免费阅读');
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
                //console.log(htmls);
                $('#getlog').html(htmls);
        	});
        }
    });
    }

//添加到书架
function InsertFav(bid){
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
        success: function (data){
            if(data.status == 1){
               hg_Toast(data.message);
               $('#ysc').show();$('#sc').hide();
            }else{
                hg_Toast(data.message);
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
<script type="text/html" id="tpl2">
<div class="statebtn03 statebtn statebtn02dis"><img src="__IMG__/ic_file_download.png"/><span>限免中</span></div>
</script>
<script type="text/html" id="tpl1">
    {{if isvip}}
    <div class="statebtn04 statebtn" onClick="hg_gotoUrl('{{ {bid:bid,chpid:lastreadchpid} | router:'Book/readVip','do'}}')">
    {{else}}
    <div class="statebtn04 statebtn" onClick="hg_gotoUrl('{{ {bid:bid,chpid:lastreadchpid} | router:'Book/read','html'}}')">
    {{/if}}<img src="__IMG__/ic_local_library.png" />
    {{if xianmian}}
    <span id="readname">免费阅读</span></div>
    {{else}}
    {{if lastreadchporder <=1}}
    <span id="readname">开始阅读</span></div>
    {{else}}
    <span id="readname">继续阅读</span></div>
    {{/if}}{{/if}}
</script>
<script type="text/html" id="tpl3">
    <div class="lf" >
         <div class="statebtn01 statebtn" onClick="hg_gotoUrl('{:url('Book/xiajia','','do')}')"><img src="__IMG__/ic_gift.png" /><span>打赏</span></div>
        <div class="statebtn02 statebtn" onClick="hg_gotoUrl('{:url('Book/xiajia','','do')}')">
        <img src="__IMG__/ic_favorite.png?{:C('SOURCE_VER')}" /><span>收藏</span>
        </div>
    </div>
        <div class="statebtn03 statebtn" onClick="hg_gotoUrl('{:url('Book/downapp','','html')}')"><a href="{:url('Book/downapp','','html')}"><img src="__IMG__/ic_file_download.png?{:C('SOURCE_VER')}"/></a><span>下载本书</span>
        </div>
        <div class="statebtn04 statebtn" onClick="hg_gotoUrl('{:url('Book/xiajia','','do')}')"><img src="__IMG__/ic_local_library.png" /><span id="readname">开始阅读</span></div>
</script>
</block>
