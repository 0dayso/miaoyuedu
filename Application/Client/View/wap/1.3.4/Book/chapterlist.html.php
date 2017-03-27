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
            <li onclick="loadmore('first')" id="first"><a class="qian radius4"><span  class="first">最前</span></a></li>
            <li onclick="loadmore('prev')" id="prev"><a class="qian radius4"><span  class="before">前50章</span></a></li>
            <li onclick="loadmore('next')" id="next"><a class="hou radius4 "><span  class="after">后50章</span></a></li>
            <li onclick="loadmore('last')" id="last"><a class="hou radius4"><span class="last">最后</span></a></li>
        </ul>
    </div>
</div>
<!--目录内容头部结束-->

<!--目录内容开始-->
<div class="unit">
    <div class="tit2 borderbom">
    	<ul id="sort">
    		<li>
    			<a class="active zqh" onclick="sortBy(this,'ASC');return false;"><span >顺序</span></a>
    		</li>
    		<li>
    			<a class="zqh" onclick="sortBy(this,'DESC');return false;"><span>倒序</span></a>
    		</li>
    	</ul>
    </div>
    <div class="frame3">
        <ul id="lists" pagenum="1" totalpage="0" next="0" pre="0">
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
                $('#next').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                $('#last').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
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
    switch (type) {
            case 'first':
                var pagenum=1;
                break;
            case 'prev':
                var pagenum=$('#lists').attr('pre');
                break;
            case 'next':
                var pagenum=$('#lists').attr('next');
                break;
            case 'last':
                var pagenum=$('#lists').attr('totalpage');
                break;
        }
    var data={
            bid:'{$bookinfo['bid']|intval}',
            pagesize:50,
            sortby:sort, 
        };
        data[hsConfig.PAGEVAR] = pagenum;
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
          if(data.status==1){
                var htmls=template('huanyihuan_tpl',data);
                $('#lists').attr('totalpage',data.totalpage);
                $('#lists').attr('pre',data.prepagenum);
                $('#lists').attr('next',data.nextpagenum);
                if(data.pagenum==1){
                   $('.qian').addClass('disable');
                   $('.hou').removeClass('disable');
                   $('#first').attr('onclick','');
                   $('#prev').attr('onclick','');
                   $('#next').attr('onclick','loadmore("next")');
                   $('#last').attr('onclick','loadmore("last")');
                   if(zhuangtai=="isxiajia"){
                      $('#next').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                      $('#last').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                      $('.yxj').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                      $('.zqh').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                   }
                }else if(data.pagenum==data.totalpage){
                   $('.qian').removeClass('disable');
                   $('.hou').addClass('disable');
                   $('#next').attr('onclick','');
                   $('#last').attr('onclick','');
                   $('#first').attr('onclick','loadmore("first")');
                   $('#prev').attr('onclick','loadmore("prev")');
                }else{
                   $('.qian').removeClass('disable');
                   $('.hou').removeClass('disable');
                   $('#first').attr('onclick','loadmore("first")');
                   $('#prev').attr('onclick','loadmore("prev")');
                   $('#next').attr('onclick','loadmore("next")');
                   $('#last').attr('onclick','loadmore("last")');
                }
                
                $('#lists').html(htmls);
                if(zhuangtai=="isxiajia"){
                    $('.yxj').attr("onclick","hg_gotoUrl('{:url('Book/xiajia','','do')}')");
                }
          }
        }
});

    /*loadMore(aurl,'lists','huanyihuan_tpl','replace',type, {_callback:Lazy.Load});*/


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