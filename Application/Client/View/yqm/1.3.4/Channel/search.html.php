<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--主体-->
<div class="shuku top30">
    <div class="shuku1200 " >

        <div class="input2-group" style="padding-left: 120px;">
        <Hongshu:bangdan name="android_{$sex_flag}_zhubian" items="1" id="sousuo">
            <input class="subsearch2" value="{$keyword}" id="keyword" type="text" placeholder="" maxlength="250" autocomplete="off">
        </Hongshu:bangdan>
            <span class="input2-group-btn yahei" onClick="getByKeyword();return false;"><img src="__IMG__/fdj.png"/></span>
        </div>

        <ul class="shukufenlei lf30 top20 ">
            <li class="classids">
                <span class="c9 classids">作品分类：</span>
                <a class="active empty" onclick="getByClassids(this,0);return false;">不限</a>
                <foreach name="category" item="row" key="k">
                <a href="javascript:void(0);" id="{$row.classid}" onclick="getByClassids(this,{$row.classid});return false;">{$row.title}</a></if>
                </foreach>
            </li>

            <li class="updatetime">
                <span class="c9 ">更新时间：</span>
                <a class="active empty" href="javascript:void(0);" onclick="getByUpdatetime(this,0);return false;">不限</a>
                <a href="javascript:void(0);" onclick="getByUpdatetime(this,2);return false;">一周内</a>
            </li>

            <li class="charnum">
                <span class="c9 ">作品字数：</span>
                <a class="active empty" href="javascript:void(0);" onclick="getByCharnum(this,0);return false;">不限</a>
                <a href="javascript:void(0);" onclick="getByCharnum(this,1);return false;">30w字以下</a>
                <a href="javascript:void(0);" onclick="getByCharnum(this,3);return false;">30w-100w字</a>
                <a href="javascript:void(0);" onclick="getByCharnum(this,4);return false;">100w字以上</a>
            </li>

            <li class="finish">
                <span class="c9">作品状态：</span>
                <a class="active empty" href="javascript:void(0);" onclick="getByFinish(this,0);return false;">不限</a>
                <a href="javascript:void(0);" onclick="getByFinish(this,2);return false;">连载中</a>
                <a href="javascript:void(0);" onclick="getByFinish(this,1);return false;">已完结</a>
            </li>
        </ul>

        <div class="main1100">
            <div class="leibie top20">
                <span class="c9" >根据 "</span><span class="cb leibiespan" id="guanjian">无</span><span class="c9" >""分类</span><span class="cb leibiespan" id="fenlei">不限</span><span class="c9" >""时间</span><span class="cb leibiespan" id="shijian">不限</span><span class="c9" >""字数</span><span class="cb leibiespan" id="zishu">不限</span><span class="c9">""状态</span><span class="cb leibiespan" id="zhuangtai">不限</span><span class="c9">" 共有</span><span class="cb" id="totalcount"> 10086 </span><span class="c9" >部作品 <a onclick="qingkong();">重置</a></span>

                <a class="flrt" href="javascript:void(0);" onclick="getByorders(this,2);return false;">按点击</a>
                <a class="flrt" href="javascript:void(0);" onclick="getByorders(this,8);return false;">按收藏</a>
                <a class="flrt active empty" href="javascript:void(0);" onclick="getByorders(this,1);return false;">按更新</a>
            </div>

            <div id="lists" class="book_rongqi top30" pagenum=1 keyword='' keywordtype='2' Pclassids='2' classids="{$classid}" free='0' finish='0' charnum='0' updatetime='0' order='0'>
            </div>

            <div id="fenye" class="pages top30 bom20 clearfix" style="text-align: center;">
            </div>

        </div>

    </div>
</div>
</block>
<block name="script">
<!--搜索结果条目模板-->
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <div class="book2" onclick="doChild('{{ {bid:row.bid} | router:'Book/view','html'}}')">
        <div class="fm bom5">
            <img src="{{row.bookface}}" width="200" height="282"/>
        </div>
        <p class="lf5 c3"><a>{{row.catename}}</a></p>
        <span class="c3 lf5">萌神：{{row.authorname}}</span>
        <span class="cb lf5">{{if row.tag}}{{row['tag'].slice(0, 2).join('，')}}{{/if}}</span>
    </div>
    {{/each}}
    {{/if}}
</script>
<!--搜索结果条目模板结束-->
<script type="text/javascript">
    var keyword="{$keyword}";
     Do.ready('common', function(){
        if(!keyword){
            keyword = $('#keyword').attr('placeholder');
        }
        $('#keyword')
            .focus(function(){
                $(this).attr('placeholder','');
            })
            .blur(function(){
                $(this).attr('placeholder',keyword);
            });
});
   //初始化do_search()
    function initDoSearch(){
        $("#lists").attr('pagenum',1);
        $("#lists").attr('keyword','');
        $("#lists").attr('keywordtype',1);
        $("#lists").attr('Pclassids',0);
        $("#lists").attr('classids',0);
        $("#lists").attr('free',0);
        $("#lists").attr('finish',0);
        $("#lists").attr('charnum',0);
        $("#lists").attr('updatetime',0);
        $("#lists").attr('order',0);
    }

    function do_search(pagenum) {
        var url = "{:url('bookajax/search',array(),'do')}";
        //获取参数
        var keyword = $('#lists').attr('keyword');
        var keywordtype = $('#lists').attr('keywordtype');
        var pclassid = new Array();
        pclassid[0] = $('#lists').attr('Pclassids');
        var Pclassids =pclassid.join();;
        var classids = $('#lists').attr('classids');
        var free = $('#lists').attr('free');
        var finish = $('#lists').attr('finish');
        var charnum = $('#lists').attr('charnum');
        var updatetime = $('#lists').attr('updatetime');
        var order = $('#lists').attr('order');
        if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
        var nextpagenum = pagenum + 1;
        var reqData = {
            keyword:keyword,
            keywordtype:keywordtype,
            free:free,
            finish:finish,
            charnum:charnum,
            updatetime:updatetime,
            order:order,
            sortby:$('#lists').attr('sortby'),
            copyright:1,
            pagesize:8,
            clientmethod:'do_search',
        };
        reqData[hsConfig.PAGEVAR] = pagenum;
        /*if('{$sex_flag}'=='nv'){
            reqData.classids = classids;
        } else if ('{$sex_flag}'=='nan') {*/
            reqData.Pclassids = classids;
        /*}*/
        //return false;
        $.ajax({
            url:url,
            data:reqData,
            type:'POST',
            error:function(data){
                hg_Toast('没有找到合适的记录！');
            },
            success:function(data){
                $("#totalcount").html(data.totalcount);
                Do.ready('template', function(){
                     if(parseInt(data.pagecount) > 0){
                        var htmls = template('huanyihuan_tpl',data);
                        $('#fenye').html(data.pagelist);
                        $("#lists").attr('pagenum',data.pagenum);
                        $('#lists').html(htmls);
                        }else{
                        var htmls='<p class="c9">该分类暂无书籍</p>';
                        $('#lists').html(htmls);
                        $('#fenye').html('');
                       }
                });
            }
        });
    }
//排序条件搜索
    function getByorders(obj,order){
        $('#lists').attr('pagenum',1);
        if(order == 8) {
            order = 1;
            $('#lists').attr('sortby', 'total_fav');
        } else {
            $('#lists').attr('sortby', '');
        }
        $('#lists').attr('order',order);
        do_search();
        $('.flrt').removeClass('active');
        $(obj).addClass('active');
    }

    //根据类别搜索
    function getByClassids(obj,classid){
		$('#lists').attr('pagenum',1);
		$('#lists').attr('classids',classid);
		do_search();
		$('.classids>a').removeClass('active');
		$(obj).addClass('active');
        var htmls=$(obj).html();
        $('#fenlei').html(htmls);
    }
    //根据字数搜索
    function getByCharnum(obj,charnum){
    	$('#lists').attr('pagenum',1);
		$('#lists').attr('charnum',charnum);
		do_search();
		$('.charnum>a').removeClass('active');
		$(obj).addClass('active');
        var htmls=$(obj).html();
        $('#zishu').html(htmls);
    }
    //根据进度搜索
    function getByFinish(obj,finish){
    	$('#lists').attr('pagenum',1);
		$('#lists').attr('finish',finish);
		do_search();
		$('.finish>a').removeClass('active');
		$(obj).addClass('active');
        var htmls=$(obj).html();
        $('#zhuangtai').html(htmls);
    }
    //根据更新时间搜索
    function getByUpdatetime(obj,updatetime){
    	$('#lists').attr('pagenum',1);
		$('#lists').attr('updatetime',updatetime);
		do_search();
		$('.updatetime>a').removeClass('active');
		$(obj).addClass('active');
        var htmls=$(obj).html();
        $('#shijian').html(htmls);
    }
    //根据关键字搜索
    function getByKeyword(){
    	initDoSearch();
		var keyword = $("#keyword").val();
        if(!keyword){
            keyword = $('#keyword').attr('placeholder');
        }
		$('#lists').attr('pagenum',1);
		$('#lists').attr('keyword',keyword);
        $('#lists').attr('order',0);
        $('#guanjian').html(keyword);
		do_search();
        $('.flrt').removeClass('active');
    }
  function qingkong(){
    $('.updatetime>a').removeClass('active');
    $('.charnum>a').removeClass('active');
    $('.finish>a').removeClass('active');
    $('.classids>a').removeClass('active');
    $('.flrt').removeClass('active');
    $('.empty').addClass('active');
    $('#guanjian').html('无');
    initDoSearch();
    do_search();
  }
Do.ready('common', function(){
  <if condition="$classid">
        var classid = {$classid};
        $(".classids>a").removeClass("active");
        $("#"+classid).addClass("active");
        $("#lists").attr('pagenum',1);
        $("#lists").attr('classids',classid);
        do_search();
    <elseif condition="$keyword" />
    getByKeyword();
    <else />
    initDoSearch();
    do_search();
    </if>
});

</script>
<include file="Common/foot2" />
</block>

