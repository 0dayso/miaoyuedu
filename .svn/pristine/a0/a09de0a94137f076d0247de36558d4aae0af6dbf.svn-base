<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--目录内容头部开始-->
<div class="unit mtop10">
    <div class="mulu">
    <h1 class="hidden">{$bookname}</h1>
    <p id="zhuangtai"><if condition="$lz_info eq 1">完本<else/>连载中</if></p>
    <p><span>共{$totalnum}章</span><span><a class="cpink"><if condition="$chpnum neq 0">昨日更新{$chpnum}章节</if></a></span></p>
    </div>
    <div class="frame6" id="nav-wrapper" style="background:rgba(255,255,255,.8);">
        <ul>
                <li onclick="loadmore('first')" class="zqh"><a class="radius4"><span  class="first">第1章</span></a></li>
                <li onclick="loadmore('prev')" class="zqh"><a class="radius4"><span  class="before">前50章</span></a></li>
                <li onclick="loadmore('next')" class="zqh"><a class="radius4 "><span  class="after">后50章</span></a></li>
                <li onclick="loadmore('last')" class="zqh"><a class="radius4"><span  class="last">最后</span></a></li>
        </ul>
    </div>
</div>
<!--目录内容头部结束-->

<!--目录内容开始-->
<div class="unit">
    <div class="tit2 borderbom">
    	<ul id="sort">
        		<li>
        			<a href="javascript:void(0);" class="active zqh" onclick="sortBy(this,'ASC');return false;"><span >顺序</span></a>
        		</li>
        		<li>
        			<a href="javascript:void(0);" class="zqh" onclick="sortBy(this,'DESC');return false;"><span>倒序</span></a>
        		</li>
    	</ul>
    </div>
    <div class="frame3">
        <ul id="lists">
        </ul>
    </div>
</div>
<!--目录内容结束-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
    {{each list as row i}}
     {{if row.juantitle}}
    <div class="tit8 borderbom hidden">{{row.juantitle}}</div> 
    {{/if}}
        {{if row.isvip == 1}}
        <li class="hidden borderbom cjcon yxj" onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/readVip','do'}}')"><div class="hidden fllf cj"><span class="order">{{row.title}}</span></div>{{if row.isorder==1}}<span class="flrt lock"><img src="__IMG__/ic_lock_open.png" /></span>{{else}}<span class="flrt lock"><img src="__IMG__/ic_lock.png" /></span>{{/if}}</li>
        {{else}}
        <li class="hidden borderbom cjcon yxj" onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','html'}}')"><div class="hidden fllf cj"><span class="order">{{row.title}}</span></div><span class="flrt cblue free">免费</span></li>
        {{/if}}
    {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
Do.ready('common', function(){
    $.ajax({
        type:'get',
        url: '{:url("Bookajax/getbookstatus",'','do')}',
        data:{
            bid:{$bookinfo.bid|intval}
        },
        success: function (data){
            if(data.isxiajia){
                $('#zhuangtai').html('已下架');
                $('.zqh').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                zhuangtai="isxiajia";
            }
        }
    });
});
//获取书架
var url = "{:url('Bookajax/chapterlist', array('bid'=>$bid), 'do')}";
Do.ready('functions','template',function(){
    loadmore('first');
});
sort = 'ASC';
//正序反序
function sortBy(obj,order){
    sort = order;
    $("#sort>li>a").removeClass("active");
    $(obj).addClass("active");
   
    loadmore('first');
}
function loadmore(type){
    var aurl = url;
    aurl+= aurl.indexOf('?')>0?'&':'?';
    aurl+='sortby='+sort;

    loadMore(aurl,'lists','huanyihuan_tpl','replace',type, {_callback:callback});


}
function callback(){
    Lazy.Load
    if(zhuangtai=="isxiajia"){
        $('.yxj').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')")
    }
}
$(document).ready(function(){

      function fixDiv(div_id){
    		var offsetTop=arguments[1]?arguments[1]:0;
    		var Obj=$('#'+div_id);



    		document.onscroll = function(){

    			var div = document.getElementById(div_id);
    			var ObjTop = div.offsetTop;
    			var iScroll = scrollTop();
    			//var spanTop = ObjTop-iScroll;

    			//console.log(ObjTop,iScroll,spanTop);

    			if(iScroll<=ObjTop){
    					Obj.css({'position':'relative','top':'0px'});
    				}else{
    					Obj.css({'position':'fixed','top':'0px','z-index':1});

    				}
    			};
    	}
    	Do.ready('lazyload',function(){
    	    fixDiv('nav-wrapper');
    	});
});

</script>
</block>