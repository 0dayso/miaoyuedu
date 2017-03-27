<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="style">
<style>
    .barrage_box p {
        height: 22px;
        line-height: 22px;
        overflow: hidden;
    }
</style>
</block>
<block name="body">
<div id="muluclose">
<script type="text/javascript">
var ss = document.cookie + ';';
var reg = new RegExp(/blackwhite=(\d+);/g);
var s1=reg.exec(ss);
var str = '<div class="main top20 bom40">';
if(s1 && s1[1]!=0) {
    str = '<div class="main top20 bom40 blackmd">';
}
document.writeln(str);
</script>
    <div class="main1200">
        <div class="read">
            <span class="rd_nav c9"><a href="{:url('Index/index','','html')}">首页</a> > <a href="{:url('Channel/search',array('classid'=>$bookinfo['classid']),'html')}">{$bookinfo.classname}</a> > <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a></span>
            <h1 class="c3 top50">{$chapterinfo.title}</h1>
            <div class="rd_xx c6 top20 bom40">
                <span>萌神：<a class="c6">{$bookinfo.author}</a></span>
                <span>字数：{$chapterinfo.charnum}</span>
                <span>更新时间：{$chapterinfo.updatetime}</span>
            </div>
            <div class="read_zw" style="display:none;">
                {$chapterinfo.content}
            </div>
            <div class="readbom" style="display:block;">
                <div class="readtextbtn" id="zhangjieinfo">
                    <ul class="top60 bom30">
                    <if condition="$chapterinfo['prev_chpid'] eq ''">
                    <li><a class="gray readtextbtnlast readtextbtnlast_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}" class="readtextbtnlast readtextbtnlast_01">上一章</a></li>
                </if>
                <li style="width: 240px;"></li>
                <if condition="$chapterinfo['next_chpid'] eq ''">
                    <li><a class="gray readtextbtnnext readtextbtnnext_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}" class="readtextbtnlast readtextbtnlast_01">下一章</a></li>
                </if>
                        <button onclick="$('#dashang').show();"><img src="__IMG__/hb.png">赏</button>
                    </ul>
                </div>
                <div class="dashang" id="dashang" style="display:none;">
                    <div class="yqd">
                        <img src="__IMG__/yuanqidan.gif" height="300" width="400">
                    </div>
                    <h1 class="top20">元气弹 x <span class="cb" id="shuliang">5</span></h1>
                    <div class="dashang_cz top30 bom20">
                            <a class="rt50" onclick="changeshuliang(0);">-</a>
                            <span id="jine">500 元气币</span>
                            <a class="lf50" onclick="changeshuliang(1);">+</a>
                    </div>
                        <button id="dsbutton">赏一发！</button>
                        <span class="zy c6 top10 bom5">赠言</span>
                        <div>
                            <textarea class="textarea09" placeholder="作者辛苦了，送您5发元气弹，送您上天！" id="zengyan"></textarea>
                        </div>
                        <div class="top5 bom70">
                            <p class="lf90 c9" id="username"></p>
                            <p class="lf90 c9" id="login">目前尚有<span> 0</span> 元气币 <span class="cf00">余额不足</span> <a class="cb" target="_blank" href="{:url('Pay/index#maodian','','do')}">点此充值</a></p>
                        </div>
                        <div class="yuan100" onclick="$('#dashang').hide();">
                            <img src="__IMG__/guanbi_g.png" width="30" height="30">
                        </div>
                    </div>
            </div>

            <div class="zuofudong empty">
                <button class="fang_rd" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}')"><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}"><img src="__IMG__/fengmian.png" ></a>封面</button>
                <button class="fang_rd" onclick="openmulu();"><img src="__IMG__/mulu.png">目录</button>
                <button class="fang_rd" id="sc"><img src="__IMG__/shoucang.png">收藏</button>
                <button class="fang_rd" style="display:none;" id="ysc"><img src="__IMG__/yishoucang.png">已收藏</button>
                <button class="fang_rd" id="sz1" onclick="$('.shezhi').show();$('#sz2').show();$(this).hide()"><img src="__IMG__/shezhi.png">设置</button><button class="fang_rd" id="sz2" onclick="$('.shezhi').hide();$('#sz1').show();$(this).hide();" style="display:none;"><img src="__IMG__/shezhi.png">设置</button>
                <!-- <button class="fang_rd" onClick="hg_gotoUrl('{:url('Help/index')}')"><img src="__IMG__/bangzhu.png">帮助</button> -->
            </div>
            <div class="read_shezhi_hei shezhi" style="display:none;">
                    <div class="read_shezhi_l fllf">
                        <img class="lf15 top10" src="__IMG__/ic_flar.png" width="30" height="30" id="modeimg">
                        <span class="flrt top12 lf5 c9" id="modename">白天模式</span>
                    </div>
                    <div class="read_shezhi_r flrt">
                        <a class="cf" size="14" id="s14">Aa</a>
                        <a class="cf" size="16" id="s16" style="font-size: 16px;">Aa</a>
                        <a class="cf" size="20" id="s20" style="font-size: 20px;">Aa</a>
                    </div>
                </div>
            <div class="muluopen empty" style="position: absolute; top: 70px; display: none;">
                <i class="arrow arrow-1"></i>
                <i class="arrow arrow-2"></i>
                <div class="muluopencon">
                            <ul id="lists">
                            </ul>
                </div>
            </div>
            <div class="zhongfudong empty">
                <button id="bofangdanmu" class="fang_rd" onclick="playdanmu(this)"><img src="__IMG__/bofang.png"><span id="bfdm">播放弹幕</span></button>
                <button id="senddanmu" class="fang_rd"><img src="__IMG__/tucao.png">发送弹幕</button>
            </div>
            <div class="readtk_ping empty" style="display:none;">
                <div class="flrt" onclick="$('.readtk_ping').hide();$('#senddanmu').attr('num',0);"><img class="guanbi" src="__IMG__/guanbi.png" height="20" width="20"></div>
                <p>我要发弹幕</p>
                <div class="commentform  bom20 clearfix " style="padding: 0; border: none;">
                        <div>
                            <textarea id="tucao" class="textarea03" placeholder="既然来了，就留几句话吧" onkeyup="check();"></textarea>
                        </div>
                        <p class="flrt c9 rt5"><span id="tucaoshuzi">0</span>/30</p>

                        <div class="pl2_sd top35">
                            <div class="rightnr flrt">
                                <span id="yonghuming"></span>

                              <button id="pl2_sd" class="gray" disabled="disabled">发表吐槽</button>
                            </div>
                        </div>
                </div>
            </div>

            <div class="youfudong empty" id="zhangjieinfo2">
            <if condition="$chapterinfo['prev_chpid'] eq ''">
                   <button class="shangyizhang gray" ><img src="__IMG__/shangyizhang.png">没有了</button>
                <else/>
                    <button class="shangyizhang" onclick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}');"><img src="__IMG__/shangyizhang.png"><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['prev_chpid']))}">上一章</a></button>
                </if>
                <if condition="$chapterinfo['next_chpid'] eq ''">
                    <button class="xiayizhang gray"><img src="__IMG__/xiayizhang.png">没有了</button>
                <else/>
                    <button class="xiayizhang" onclick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}');"><img src="__IMG__/xiayizhang.png"><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['next_chpid']))}">下一章</a></button>
                </if>
            </div>
        </div>

    </div>
</div>
</div>
    </block>
    <block name="script">
    <script type="text/html" id="tpl1">
        {{each list as row i}}
          {{if row.isvip == 0}}
          <li isvip="0"onclick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','html'}}')">
          <div class="left"><a><span>{{row.title}}</span></a></div>
          <div class="right">免费</div>
          {{else}}
          <li isvip="0"onclick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','do'}}')">
          <div class="left"><a><span>{{row.title}}</span></a></div>
           <div class="right">vip</div>
           {{/if}}
        </li>
        {{/each}}
    </script>
<script type="text/javascript">
function changeshuliang(type){
    var shuliang=parseInt($('#shuliang').html());
    if(type==1){
        shuliang=shuliang+1;
    }else if(type==0){
        if(shuliang==1){
        return;
        }
        shuliang=shuliang-1;
    }
    $('#shuliang').html(shuliang);
    $('#jine').html(shuliang*100+"元气币");
    var nickname=$('#username').html();
    var usermoney=parseInt($('#usermoney').html());
    if(usermoney<(shuliang*100)){
        var htmls='目前尚有<span id="usermoney">'+usermoney+'</span>元气币<span class="cf00">余额不足</span> <a class="cb" target="_blank" href="{:url("Pay/index#maodian","","do")}">点此充值</a>'
        $('#login').html(htmls);
    }else{
        var htmls='目前尚有<span id="usermoney">'+usermoney+'</span>元气币'
        $('#login').html(htmls);
    }
    $('#zengyan').val("作者辛苦了，送您"+shuliang+"发元气弹，"+nickname+"送您上天！");
}
function check(){
    var num=$('#tucao').val();
    $('#pl2_sd').attr('disabled',true);
    $('#pl2_sd').addClass('gray');
    if(num.length>30){
        hg_Toast('最多只能发表30个字符！');
        $('#tucao').val(num.substr(num.length-30,30));
    } else if(num.length>0){
       $('#pl2_sd').removeAttr('disabled',true);
       $('#pl2_sd').removeClass('gray');
    }
    $('#tucaoshuzi').html(num.length);
}

    function playdanmu(obj) {
        var y = $(obj);
        if (y.attr("loadding") == 1) {
            return false
        }
        if(y.hasClass('opened')){
            __barrager_times = 0;
            if(typeof($.fn.barrager)=='function'){
            $.fn.barrager.removeAll()
            }
            y.removeClass('opened');
            var htmls='<img src="__IMG__/bofang.png"><span id="bfdm">播放弹幕</span>'
            y.html(htmls);
            return;
        }
        y.addClass('opened');
        $.ajax({
            url: "{:url('Bookajax/getbarragerdata','','do')}",
            data: {
                page: 0,
                bid: {$bookinfo.bid|intval},
                cid: {$chapterinfo.chapterid|intval},
                count: 50
            },
            beforeSend: function() {
                y.attr("loadding", 1);
                y.text("加载中..")
            },
            type: "GET",
            success: function(z) {
                if (z.status == 1) {
                    for(var i=0;i<z.data.total_num;i++){
                        str = z.data.tsukkomi_list[i].tsukkomi_content;
                        id =  z.data.tsukkomi_list[i].id;
                        addBarrage(str, id);
                    }
                }
            },

            complete: function() {
                y.attr("loadding", 0);
                var htmls='<img src="__IMG__/pb.png"><span id="bfdm">屏蔽弹幕</span>'
                y.html(htmls);
            }
        })
    }

var __barrager_times = 0;
function addBarrage(str, id){
    if(!id || typeof(id)=='undefined') {
        id = 0;
    }
    var item={
        dataid: id,
        info:str, //文字
        href:'', //链接
        close:true, //显示关闭按钮
        speed:6, //延迟,单位秒,默认6
        left: 0,
        color:'#ffffff', //颜色,默认白色
        old_ie_color:'#ffffff', //ie低版兼容色,不能与网页背景相同,默认黑色
    }
    item.bottom = (__barrager_times%3)*50 + $(window).height()/2-70;
    item.left = Math.floor(Math.random() * 50 + parseInt(__barrager_times/3) * 420);
    item.maxWidth = 27*400;
    Do.ready('barrager',function(){
        $('.read').barrager(item);
    });
    __barrager_times++;
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
    var url = "{:url('Userajax/insertfav',array(),'do')}";
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
            if(data.status == 1){
               hg_Toast(data.message);
               $('#sc').hide();$('#ysc').show();
            }
        }
    })
}

//阅读记录

Do.ready('common','functions', function(){
    gundong();
        document.onscroll=function(){
            gundong();
        }
    UserManager.addListener(function(user){
        //这里判断user.islogin用来处理登录后的事件
        if(user.islogin){
            checkshoucang(user.uid);
            $('#senddanmu').on('click',function(){
               opendanmu();
            });
            $('#pl2_sd').on('click',function(){
               senddanmu(user.uid);
            });
            var htmls='<span class="c9 rt10">'+user.nickname+'</span>'
            $('#yonghuming').html(htmls);
            $('#dsbutton').on('click',function(){
                sendyuanqidan();
            });
            $('#username').html(user.nickname);
            var html="作者辛苦了，送您5发元气弹，"+user.nickname+"送您上天！"
            $('#zengyan').attr('placeholder',html);
            if(user.money>500){
              var htmls="目前尚有<span id='usermoney'>"+user.money+"</span>元气币"
              $('#login').html(htmls);
            }else{
              var htmls='目前尚有<span id="usermoney">'+user.money+'</span>元气币<span class="cf00">余额不足</span> <a class="cb" target="_blank" href="{:url("Pay/index#maodian","","do")}">点此充值</a>'
              $('#login').html(htmls); 
            }
        } else {
            $('#sc').show();$('#ysc').hide();
            $('#sc').on('click',function(){
                hg_Toast('请先登录！');
            });
            $('#senddanmu').on('click',function(){
               hg_Toast('请先登录！');
            });
            var htmls='<span class="cb rt25" onClick="hg_gotoUrl("{:url("User/login","","do")}");">登录</span>'
            $('#yonghuming').html(htmls);
            $('#dsbutton').on('click',function(){
                hg_Toast('请先登录！');
            });
            $('#login').html('请先<a class="cb" href="{:url("User/login","","do")}">登录</a>在进行打赏！');
        }
    });
});
function sendyuanqidan(){
    var url = "{:url('Bookajax/dashang',array(),'do')}";
    var bid='{$bookinfo.bid}';
    var shuliang=parseInt($('#shuliang').html());
    if(shuliang==0){
        hg_Toast('请选择道具数量！');
        return;
    }
    var content=$('#zengyan').val();
    var data = {
        bid:bid,
        num:shuliang,
        pid:1,
        content:content
    }
    $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data){
            if(data.status == 1){
               hg_Toast('打赏成功');
               var money=parseInt($('#usermoney').html())+parseInt(data.moneyinfo.lastmoney);
               var htmls='目前尚有<span id="usermoney">'+money+'</span>元气币<span class="cf00">'
              $('#login').html(htmls); 
            }else{
               hg_Toast(data.message);
            }
        }
    })
}
function opendanmu(){
  var num=$('#senddanmu').attr('num');
  if(num==0){
    $('.readtk_ping').show();
    $('#senddanmu').attr('num',1);
  }else{
    $('.readtk_ping').hide();
    $('#senddanmu').attr('num',0);
  }
}
function checkshoucang(uid){
    var url = "{:url('Bookajax/checkfav',array(),'do')}";
    var bid='{$bookinfo.bid}';
    var data = {
        bid:bid,
        uid:uid
    }
    $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data){
            if(data.isfav == 1){
               $('#ysc').show();$('#sc').hide();
            }else{
               $('#sc').show();$('ysc').hide();
               $('#sc').on('click',function(){
                   InsertFav(bid);
               })
            }
        }
    })
}
function getchp(){
    var url = "{:url('Bookajax/getallchapter',array(),'do')}";
    var bid='{$bookinfo.bid}';
    var data = {
        bid:bid,
    }
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
        Do.ready('template',function(){
            if(data.status == 1){
               var htmls=template('tpl1',data);
               $('#lists').html(htmls);
            }else{
               $('#lists').html('网络状态不佳，请刷新页面重试！');
               }
            })
        }
    })
}
 function openmulu(){
    var obj = $('.muluopen');
    if(obj.hasClass('opened')){
        closemulu();
        return false;
    }
    $('.muluopen').show();
    obj.addClass('opened');
    setTimeout(function(){$('#muluclose').bind('click', closemulu);}, 500);

 }
 function closemulu(){
    var obj = $('.muluopen');
    obj.removeClass('opened');
    $('#muluclose').unbind('click');
    $('.muluopen').hide();
 }
 Do.ready('functions','template','common', function () {
        getchp();
        var bid = '{$bookinfo.bid|intval}'; 
        var chpid = '{$chapterinfo.chapterid|intval}';
        var isvip = '{$chapterinfo.isvip|intval}';
        var cidx = '{$chapterinfo.chporder|intval}';
        updateReadLog(bid,chpid,cidx,isvip);
        getPreNextChapter();
        $('.read_shezhi_r a').on('click',function(){
            var size=$(this).attr('size');
            $('.read_zw p').css('font-size',size+'px');
            writeCookie('fontsize',size,999999);
        });
        $('.read_shezhi_l').on('click',function(){
            var modename=$('#modename').html();
            if(modename=='白天模式'){
                writeCookie('blackwhite','0',999999);
                $('.main').removeClass('blackmd');
                var mode=0;
            }else if(modename=='黑夜模式'){
                writeCookie('blackwhite','1',999999);
                $('.main').addClass('blackmd');
                var mode=1;
            }
            changemode(mode);
        });
        var danmu=cookieOperate('danmu');
        var fontsize=cookieOperate('fontsize');
        $('.read_zw p').css('font-size',fontsize+'px');
        $('#s'+fontsize).addClass('active');
        $('.read_zw').show();
        gundong();
        if(danmu==1){
           $('#bofangdanmu').click();
        }
        var mode=cookieOperate('blackwhite');
        changemode(mode);
    });
function changemode(mode){
    if(mode==1){
        $('.shezhi').addClass('read_shezhi_hei').removeClass('read_shezhi_bai');
        $('#modename').html("白天模式");
        $('#modeimg').attr('src','__IMG__/ic_flar.png');
        $('.read_shezhi_r a').addClass('cf').removeClass('c9');
    }else{
        $('.shezhi').addClass('read_shezhi_bai').removeClass('read_shezhi_hei');
        $('#modename').html("黑夜模式");
        $('#modeimg').attr('src','__IMG__/ic_brightness.png');
        $('.read_shezhi_r a').addClass('c9').removeClass('cf');
    }
 }
 function senddanmu(uid){
   var url = "{:url('Bookajax/sendbarrage',array(),'do')}";
   var bid='{$bookinfo.bid}';
   var cid='{$chapterinfo.chapterid}';
   var content=$('#tucao').val();
   if (!content) {
      hg_Toast('请输入内容');
      return false;
   }
   var data = {
        bid:bid,
        cid:cid,
        uid:uid,
        content:content
    }
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
            if(data.status == 1){
                var danmu=$('#bfdm').html();
                if (danmu=='屏蔽弹幕') {
                    addBarrage(content, data.id);
                }
               $('#tucao').val('');
               hg_Toast(data.message);
            }else{
               hg_Toast(data.message);
            }
        }
    })
}


function gundong(){
    var _top = scrollTop();
    var _height = $(window).height();
    var read_top = $('.read').offset().top;
    var read_height = $('.read').offset().height;

    //左侧菜单
    var pos = {top: 0}
    if(_top>read_top){
        pos.top = _top-read_top;
    }
    var zfd_height = $('.zuofudong').offset().height;
    if(read_height < zfd_height+pos.top){
        pos.top = read_height - zfd_height;
    }
    $('.zuofudong').css(pos);
    posshezhi={top:pos.top+204};
    $('.shezhi').css(posshezhi);

    //目录
    var pos1={top: pos.top+68};
    $('.muluopen').css(pos1);

    //右侧弹幕
    var yfd_height = $('.youfudong').offset().height;
    $('.zhongfudong').css(pos);

    //吐槽匡
    $('.readtk_ping').css(pos1);

    //翻页
    var pos2={top: pos.top+272};
    if(read_height <_height+_top-240){
        pos2.top = read_height - 240;
    }
    $('.youfudong').css(pos2);

}

//屏蔽一下可能会出现的复制等操作，虽然在移动端、手机浏览器端没多大用处
document.onselectstart = function(e) {
    return false;
}
document.oncontextmenu = function(e) {
    return false;
}
document.onkeyup = function(key){
    if(key.keyCode==39 || key.keyIdentifier=='Right'){
        $('.xiayizhang').click();
    } else if (key.keyCode==37 || key.keyIdentifier=='Left') {
        $('.shangyizhang').click();
    }
}


//获取上下章
function getPreNextChapter(){
    var data = {
        bid:{$bookinfo.bid|intval},
        chpid:{$chapterinfo.chapterid|intval}
    }
    var url = "{:url('Bookajax/getPreNextChapter','','do')}";
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
            //console.log(data);
            if(data.status == 1){
               var html1=template('shangyizhang',data);
               $('#zhangjieinfo').html(html1);
               var html2=template('xiayizhang',data);
               $('#zhangjieinfo2').html(html2);
            }
        }
    })
}
</script>
<script type="text/html" id="shangyizhang">
<ul class="top60 bom30">
    {{if prechapter.chpid == ''}}
        <li><a class="readtextbtnlast readtextbtnlast_01 gray">没有了</a></li>
    {{else}}
        {{if prechapter.isvip == 0}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{else}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{/if}}上一章</a></li>
    {{/if}}
        <li style="width: 240px;"></li>
    {{if nextchapter.chpid == ''}}
        <li><a class="readtextbtnnext readtextbtnnext_01 gray">没有了</a></li>
    {{else}}
        {{if nextchapter.isvip == 0}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{else}}
             <li><a href="javascript:hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{/if}}下一章</a></li>
    {{/if}}
       <button onclick="$('#dashang').show();"><img src="__IMG__/hb.png">赏</button>
</ul>
</script>
<script type="text/html" id="xiayizhang">
    {{if prechapter.chpid == ''}}
        <button class="shangyizhang gray" ><img src="__IMG__/shangyizhang.png">没有了</button>
    {{else}}
        {{if prechapter.isvip == 0}}
            <button class="shangyizhang" onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')">
        {{else}}
            <button class="shangyizhang" onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')">
        {{/if}}<img src="__IMG__/shangyizhang.png">上一章</button>
    {{/if}}
    {{if nextchapter.chpid == ''}}
        <button class="xiayizhang gray"><img src="__IMG__/xiayizhang.png">没有了</button>
    {{else}}
        {{if nextchapter.isvip == 0}}
            <button class="xiayizhang" onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')">
        {{else}}
             <button class="xiayizhang" onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')">
        {{/if}}<img src="__IMG__/xiayizhang.png">下一章</button>
    {{/if}}
</script>
</block>