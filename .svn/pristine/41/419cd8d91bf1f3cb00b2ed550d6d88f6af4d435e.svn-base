<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="style">
<style type="text/css" media="screen">
 <literal>
 body {-moz-user-select: none;-webkit-user-select: none;}
 </literal>
</style>
</block>
<!--顶部结束-->
<block name="body">
<div class="readtit" id="mianbaoxie" style="width:100%;">
    <div class="readnav hidden ">
        <a href="{:url('Channel/index')}">首页</a>&gt;
         <if condition="$isxiajia">
         <a href="{:url('Book/xiajia','','do')}">{$bookinfo.catename}</a>
         <else/>
         <a href="{:url('Book/view', array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a>
         </if>
        &gt;<a>{$chapterinfo.title}</a>
    </div>
    <div class="readset">
        <span>
          <if condition="$isxiajia">
          <a href="{:url('Book/xiajia','','do')}" id="mulu">
          <else/>
          <a href="{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" id="mulu">
          </if>
            <img src="__IMG__/ic_list_grey.png">目录</a>
        </span>
    </div>
</div>
<!--收藏本书开始-->
<if condition="$bookinfo['is_faved'] eq FALSE">
<div class="alertbk " id="collect">
    <span>收藏本书？方便下次阅读</span>
    <a class="okbtn radius4">确定</a>
    <a class="canclebtn" onClick="quxiao();">取消</a>
</div>
</if>
<!--收藏本书结束-->

<!--阅读开始-->
<script type="text/javascript">
var ss = document.cookie;
var reg = new RegExp(/blackwhite=(\d+);/g);
var s1=reg.exec(ss);
var str = '<div class="rdcon" id="wz">';
if(s1 && s1[1]!=0) {
    str = '<div class="rdcon rdbg_black" id="wz">';
}
document.writeln(str);
</script>
<!-- <div class="rdcon <if condition="$blackwhite eq 1">rdbg_black</if>" id="wz"> -->
    <div class="rdtit">
         <h1>{$chapterinfo.title}</h1>
     </div>
    <!-- 阅读字体背景色开始 -->
    <div class="readsetbtn" mode='{$blackwhite}'>
        <span id="mode"></span>
        <a href="javascript:void(0);" class="aadd radius4" onclick="ChangeFontSize('add');return false;">
        	<img src="__IMG__/ic_aadd.png" />
        </a>
        <a href="javascript:void(0);" class="areduce radius4" onclick="ChangeFontSize('minus');return false;">
        	<img src="__IMG__/ic_areduce.png" />
        </a>
    </div>
    <!-- 阅读字体背景色结束 -->
    <!-- 阅读内容开始 -->
    <div class="rdtext" fsize="16">
        {$chapterinfo['content']}
    </div>
    <!-- 阅读内容结束 -->
    <!-- 作者的话开始 -->
    <if condition="$chapterinfo['author_memo'] neq ''">
    <div class="authorspeak radius4">
        <div class="authorcon">
        <p>作者<span class="cpink">{$bookinfo['author']}</span>说：</p>
        <p>{$chapterinfo['author_memo']} </p>
        </div>
        </div>
    </if>

    <!-- 作者的话结束 -->
    <!-- 上下章节开始 -->
    <div class="frame9 frame13 rdbom clearfix" >
        <ul id="zhangjieinfo">
        <li>
                <if condition="$bookinfo['prevchapter']['isvip']">
                <a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['prevchapter']['chpid']), 'do')}" class="prev radius4">上一章</a>
                <else/>
                <a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['prevchapter']['chpid']))}" class="prev radius4">上一章</a>
                </if>
        </li>

        <li><a href = "{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" class="ml radius4">目录</a></li>

         <li>
                <if condition="$bookinfo['nextchapter']['isvip']">
                <a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['nextchapter']['chpid']), 'do')}" class="next radius4">下一章</a>
                <else/>
                <a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['nextchapter']['chpid']))}" class="next radius4">下一章</a>
                </if>
         </li>
        </ul>
    </div>
    <!-- 上下章节结束 -->
    <!--书签和书评开始-->
    <div class="rdbom2 clearfix">
        <!-- <a>加入书签</a> -->
        <a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="flrt">书评({$bookinfo['commentnum']})</a>
    </div>
    <!--书签和书评结束-->
    <!--二维码开始-->
    <div class="ewm">
        <p style="font-size: 1em; text-align: left;"><b>如何追书：</b></p>
        <p style="text-align: left;" class="ewm_p"><b>【友情提示】</b> <a href="{:url('Book/follow','','html')}" class="cpink">追书不用愁，免费领取{:C('SITECONFIG.EMONEY_NAME')}！</a></p>
        <p style="text-align: left;"><b>【安装APP】</b> <a href="{:url('Book/downapp','','html')}" class="cpink">戳这里下载客户端</a>，在客户端内搜索：<em class="cblue">“{$bookinfo.bid}”</em>即可阅读，每日签到领银币，好书免费读！</p>
        <p style="text-align: left;"><b>【百度搜索】</b> 在百度中搜索：<em class="cblue">{:C('SITECONFIG.SITE_NAME')}</em>，进入网站并搜索本书书号<em class="cblue">“{$bookinfo.bid}”</em>，即可找到本书。</p>
        <if condition="$style eq 'nv'">
        <p class="ewm_p"><img  src="__IMG__/yqk.jpg"></p>
        <p class="ewm_p">微信内可长按识别</p>
        <p class="ewm_p">或在微信公众号里搜索<em>“红薯阅读”</em></p>
        <else/>
        <p class="ewm_p"><img  src="__IMG__/rxk.jpg"></p>
        <p class="ewm_p">微信内可长按识别</p>
        <p class="ewm_p">或在微信公众号里搜索<em>“热血刊”</em></p>
        </if>
    </div>
    <!--二维码结束-->
</div>
</block>

<block name="script">
<script type="text/html" id="shouyeinfo">
    <a href="javascript:hg_gotoUrl('__ROOT__/{{sex_flag}}.html')" style="color: #666;">首页</a>
</script>
<script type="text/html" id="shangyizhang">
<if condition="$isxiajia">
    <li onclick="hg_gotoUrl('{:url('Book/xiajia')}')"><a class="prev radius4">上一章</a></li>
    <li><a href = "{:url('Book/xiajia','','do')}" class="ml radius4">目录</a></li>
    <li onclick="hg_gotoUrl('{:url('Book/xiajia')}')"><a class="next radius4">下一章</a></li>
<else/>
    {{if prechapter == ''}}
    <li><a class="prev disable radius4">没有了</a></li>
    {{else}}
    {{if prechapter.isvip == 0}}
        <li onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')">
    {{else}}<li onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')">
    {{/if}}<a class="prev radius4">上一章</a></li>{{/if}}

        <li><a href = "{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" class="ml radius4">目录</a></li>
     {{if nextchapter.chpid == ''}}
         <li><a class="next disable radius4">没有啦</a></li>
      {{else}}
     {{if nextchapter.isvip == 0}}
     <li onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')">{{else}}
     <li onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')">{{/if}}
     	<a class="next radius4">下一章</a></li>{{/if}}
</if>
</script>

<script type="text/javascript">
    var bid = {$bookinfo.bid|intval}, chpid = {$chapterinfo.chapterid|intval}, isvip = {$chapterinfo.isvip|intval}, cidx = {$chapterinfo.chporder|intval};
    Do.ready('template',function(){
        var data={};
        data.sex_flag=cookieOperate('sex_flag');
        if(!data.sex_flag){
            data.sex_flag="{:C('DEFAULT_SEX')}";
        }
        var htmls=template('shouyeinfo',data);
        $('#shouye').html(htmls);
    });

    //面包屑滚动
    function gundong(){
        var _top = scrollTop();
        var _height = $(window).height();
        var read_top = 48;

        //左侧菜单
        var pos = {top: 0}
        if(_top>read_top){
            pos.top = _top-read_top;
        }
        /*var zfd_height = $('.zuofudong').offset().height;
        if(read_height < zfd_height+pos.top){
            pos.top = read_height - zfd_height-100;
        }*/
        $('#mianbaoxie').css(pos);
    }

    Do.ready('common','functions', function(){
    gundong();
        document.onscroll=function(){
            gundong();
        }
    });
    
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
            /*console.log(data);*/
            if(data.status == 1){
               var htmls=template('shangyizhang',data);
               $('#zhangjieinfo').html(htmls);
            }
        }
    })
}

Do.ready("functions",'template',function(){
    getPreNextChapter();
	ChangeFontSize();
    var quxiao=cookieOperate('shoucang');
    if(quxiao == {$bookinfo.bid|intval}){
        $('#collect').hide();
    }
    updateReadLog(bid, chpid, cidx, isvip);
});
//改变背景模式
Do.ready('functions', function(){
    //1 黑夜  0 白天
    var bgnum=$('.readsetbtn').attr('mode');
    if (bgnum==1) {
        var htmls='<a class="whiteday radius4" onclick="changemode(0);"><img src="__IMG__/ic_flare.png" /></a>';
    }else{
        var htmls='<a class="blackday radius4" onclick="changemode(1);"><img src="__IMG__/ic_brightness.png" /></a>';
    }
    $('#mode').html(htmls);
});

function changemode(num){
    if (num=='0') {
        var htmls='<a class="blackday radius4" onclick="changemode(1);"><img src="__IMG__/ic_brightness.png" /></a>';
        $('#wz').removeClass('rdbg rdbg_black');
        writeCookie('blackwhite','0',9999999);
    }else{
        var htmls='<a class="whiteday radius4" onclick="changemode(0);"><img src="__IMG__/ic_flare.png" /></a>';
        $('#wz').addClass('rdbg rdbg_black');
        writeCookie('blackwhite','1',9999999);
    }
    $('#mode').html(htmls);
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
               $('#collect').hide();
            }else{
				hg_Toast(data.message);
				if(data.url){
					window.location.href = data.url;
				}
            }
        }
    })
}
//设置字体,当前16px
function ChangeFontSize(type){
	//获取当前字体
	var cookiefont = parseInt(cookieOperate("fontsize"));
	if(!cookiefont){
		var	fontsize = parseInt($(".rdtext").attr("fsize"));
	}else{
		var fontsize = cookiefont;
	}
	if(type == "add"){
		fontsize = fontsize+2;
	}else if(type == "minus"){
		fontsize = fontsize-2;
	}

	if(fontsize > 20){
		fontsize = 20;
	}else if(fontsize < 12){
		fontsize = 12;
	}

	writeCookie("fontsize",fontsize,86400);
	$(".rdtext>p").css("font-size",fontsize+"px");
	$(".rdtext").attr("fsize",fontsize);
}
Do.ready('lazyload',function(){
    Lazy.Load();
    document.onscroll = function(){
        Lazy.Load();
    };
});

        Do.ready("common",function(){
            var isxiajia=false;
        <if condition="$isxiajia">
         isxiajia=true;
        </if>
        if(isxiajia == true){
            $('#collect').html('本书已下架！');
            return;
        }
           UserManager.addListener(function(userinfo){
                 $('.okbtn').on('click',function(){
                       if(userinfo.islogin){
                           InsertFav({$bookinfo.bid},{$chapterinfo.chapterid});
                       }else{
                           hg_Toast('请先登录');
                       }
                 });
           });
        });

        //屏蔽一下可能会出现的复制等操作，虽然在移动端、手机浏览器端没多大用处
document.onselectstart = function(e) {
    return false;
}
document.oncontextmenu = function(e) {
    return false;
}

function quxiao(){
    $('#collect').hide();
    writeCookie('shoucang',{$bookinfo.bid|intval},99999);
}
</script>
</block>