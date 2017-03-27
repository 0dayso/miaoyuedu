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
<block name="style">
<style type="text/css" media="screen">
 <literal>
 body {-moz-user-select: none;-webkit-user-select: none;}
 </literal>
</style>
</block>
<!--顶部结束-->
<block name="body">
<!--收藏本书开始-->
<div id="shoucang" style="display:none;"><div class="alertbk " id="collect">
    <span>收藏本书？方便下次阅读</span>
    <a class="okbtn radius4">确定</a>
    <a class="canclebtn" onClick="quxiao();">取消</a>
</div></div>

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
    <div class="rdtit">
    <p class="rdbt"><span id="shouye">
            <a href="__ROOT__/nv.html" style="color: #666;">首页</a>
            </span><span >></span>
            <a href="{:url('Book/view', array('bid'=>$bookinfo['bid']),'html')}" id="bookname" class="rdname hidden">{$bookinfo.catename}</a>
            <span >></span>
            <a class="rdname hidden">{$chapterinfo.title}</a>
    </p>
         <h1>{$chapterinfo.title}</h1>
     </div>
    <!-- 阅读字体背景色开始 -->
    <div class="readsetbtn">
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
        <p style="text-align: left;" class="ewm_p"><b >【友情提示】</b><a href="{:url('Book/follow','','html')}" class="cpink">想免费看此书？快关注我们的微信吧！</a></p>
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
<script src="http://author.hongshu.com/hit.php?bid={$bookinfo['bid']}&nojs=1"></script> 
<script type="text/html" id="shouyeinfo">
    <a href="javascript:hg_gotoUrl('__ROOT__/{{sex_flag}}.html')" style="color: #666;">首页</a>
</script>
<script type="text/html" id="xiajia_tpl">
    <li onclick="hg_gotoUrl('{:url('Book/xiajia','','do')}')">
    <a class="prev radius4">上一章</a></li>
    <li><a href = "{:url('Book/xiajia','','do')}" class="ml radius4">目录</a></li>
    <li onclick="hg_gotoUrl('{:url('Book/xiajia','','do')}')"><a class="next radius4">下一章</a></li>
</script>
    <script type="text/html" id="shangyizhang">
    {{if prechapter.chpid == ''}}
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
     {{if nextchapter.isvip == 0}}<li onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')">{{else}}<li onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')">{{/if}}<a class="next radius4">下一章</a></li>{{/if}}
</script>

<script type="text/javascript">
    var bid = {$bookinfo.bid|intval}, chpid = {$chapterinfo.chapterid|intval}, isvip = {$chapterinfo.isvip|intval}, cidx = {$chapterinfo.chporder|intval};
    var zhuangtai="";
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
                if(data.status == 1){
                   var htmls=template('shangyizhang',data);
                   $('#zhangjieinfo').html(htmls);
                   if(zhuangtai=="isxiajia"){
                     var htmls=template('xiajia_tpl',data);
                   $('#zhangjieinfo').html(htmls);
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

    //屏蔽一下可能会出现的复制等操作，虽然在移动端、手机浏览器端没多大用处
    document.onselectstart = function(e) {
        return false;
    }
    document.oncontextmenu = function(e) {
        return false;
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
                if(data.isfav == 0){
                    Do.ready('template',function(){
                        $('#shoucang').show();
                        var quxiao=cookieOperate('shoucang');
                        if(quxiao == {$bookinfo.bid|intval}){
                            $('#shoucang').hide();
                        }
                    });
                }
            }
        });
    }

    function quxiao(){
        $('#collect').hide();
        writeCookie('shoucang',{$bookinfo.bid|intval},99999);
    }

    //获取书籍状态
	function getBookStatus(){
		var data = {
	            bid:'{$bookinfo['bid']}',
	        }
	    var url = "{:url('Bookajax/getBookStatus',array(),'do')}";
		$.ajax({
            type:'post',
            url: url,
            data:data,
            success: function (data){
                if(data.isxiajia == true){
					$('#collect').html("对不起，本书已下架！");
                    $('#bookname').attr("href","{:url('Book/xiajia','','do')}");
                    Do.ready('template',function(){
                       var htmls=template('xiajia_tpl');
                       $('#zhangjieinfo').html(htmls);
                    });
                    zhuangtai="isxiajia";
                }
            }
        });
	}
		
    //改变背景模式
    Do.ready('functions','template',function(){
        getPreNextChapter();
        //1 黑夜  0 白天
        var bgnum = cookieOperate('blackwhite');
        if(bgnum!=1) {
            bgnum = 0;
        }
        if (bgnum==1) {
            $('#wz').addClass('rdbg_black');
            var htmls='<a class="whiteday radius4" onclick="changemode(0);"><img src="__IMG__/ic_flare.png" /></a>';
        }else{
            $('#wz').removeClass('rdbg_black');
            var htmls='<a class="blackday radius4" onclick="changemode(1);"><img src="__IMG__/ic_brightness.png" /></a>';
        }
        //$('#wz').show();
        $('#mode').html(htmls);
        ChangeFontSize();
        updateReadLog(bid, chpid, cidx, isvip);
    });
    Do.ready('lazyload',function(){
            Lazy.Load();
            document.onscroll = function(){
                Lazy.Load();
        };
    });

    Do.ready("common", 'functions',function(){
        if(!checkBookFav(bid)) {
            var quxiao=cookieOperate('shoucang');
            if(quxiao == {$bookinfo.bid|intval}){
                $('#shoucang').hide();
            } else {
                $('#shoucang').show();
            }
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
        getBookStatus();
    });

    Do.ready('template',function(){
        var data={};
        data.sex_flag=cookieOperate('sex_flag');
        if(!data.sex_flag){
            data.sex_flag="{:C('DEFAULT_SEX')}";
        }
        var htmls=template('shouyeinfo',data);
        $('#shouye').html(htmls);
    });

</script>
</block>