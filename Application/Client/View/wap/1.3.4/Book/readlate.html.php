<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--收藏本书开始-->
<!-- <if condition="$bookinfo['is_faved'] eq FALSE">
<div class="alertbk " id="collect">
    <span>收藏本书？方便下次阅读</span>
    <a class="okbtn radius4">确定</a>
    <a class="canclebtn" onClick="$('#collect').hide();">取消</a>
</div>
</if> -->
<!--收藏本书结束-->
<!--阅读开始-->
<div class="rdcon rdend">
    <div class="overcon01">
     <p class="rdbt"><span id="shouye">
            <a href="__ROOT__/nv.html" style="color: #666;">首页</a></span>
            <span >></span>
             <if condition="$isxiajia">  
             <a href="{:url('Book/xiajia','','do')}" class="rdname hidden">
             <else/>
             <a href="{:url('Book/view', array('bid'=>$bookinfo['bid']))}" class="rdname hidden">
             </if>{$bookinfo.catename}</a>
            <span >></span>
            <a class="rdname hidden">{$chapterinfo.title}</a>
    </p>
     <if condition="$bookinfo['lzinfo'] eq 1">
     <h1>全本完</h1>
     <p >你已经读完整本书！您可以<a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>！</p>
     <p>也许你会喜欢以下书籍！</p>
     <else/>
        <h1>待续...</h1>
        <p >你已经读完最新章节，作者正在努力码字中，请等待作者上传更新，一般作者会坚持每日更新！</p>
        <p >您可以<a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>努力更新码字！</p>
    </if>
    </div>
</div>
    <!--火热推荐开始-->
    <div >
        <div class="tit"><h1 class="rdnxh clearfix">您可能也喜欢这些书</h1></div>
        <div class="frame3">
            <ul>
            <Hongshu:bangdan name="android_{$style}_qianli" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')" class="hidden">
                 <a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}">{$row['catename']}</a>
            </li>
            </Hongshu:bangdan>
            </ul>
       </foreach>
        </div>
    </div>
    <!--火热推荐结束-->
    <!-- 上下章节开始 -->
    <div style="padding:0 10px;">
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
        <a href="#" class="flrt">书评({$commentnum})</a>
    </div>
    <!--书签和书评结束-->
    <!--二维码开始-->
    <div class="ewm">
        <p style="font-size: 1em; text-align: left;"><b>如何追书：</b></p>
        <p style="text-align: left;" class="ewm_p"><b >【友情提示】</b> <a href="{:url('Book/follow','','html')}" class="cpink">追书不用愁，免费领取{:C('SITECONFIG.EMONEY_NAME')}！</a></p>
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
<!--阅读结束-->
<!--注释开始-->
<!--注释结束-->
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
        <li onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')">{{else}}
        <li onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')">{{/if}}
        	<a class="prev radius4">上一章</a></li>{{/if}}

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
        Do.ready("common",function(){
           UserManager.addListener(function(userinfo){
                 $('.okbtn').on('click',function(){
                       if(userinfo.islogin){
                           InsertFav({$bookinfo.bid});
                       }else{
                           hg_Toast('请先登录');
                       }
                 });
           });
        });
    </script>
<script type="text/javascript">
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
            hg_Toast(data.message);
           $('#collect').hide();
        }
    })
}
Do.ready('lazyload',function(){
        Lazy.Load();
        document.onscroll = function(){
            Lazy.Load();
    };
});

//获取上下章
function getPreNextChapter(){
    var data = {
        bid:{$bookinfo.bid|intval},
        chpid:'final'
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
Do.ready('template',function(){
    getPreNextChapter();
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
<include file="Common/foot2"/>
