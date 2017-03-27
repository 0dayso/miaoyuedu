<extend name="Common/base" />
<block name="body">
<div style="margin:0 auto; display: none;">
    <img data-src="__STATICURL__/img/html5/weixinsuolue.jpg" />
</div>
<!--搜索开始-->
<div class="ss mtop10">
    <form>
        <div class="lf">
        <Hongshu:bangdan name="android_{$sex_flag}_zhubian" items="1">
            <input type="text" placeholder="{$row['catename']}" class="input1 radius4" value="{$keyword}" id="keyword"/>
        </Hongshu:bangdan>
        </div>
        <div class="rt">
        	<button class="submit" onClick="getByKeyword();return false;" type="submit">搜索</button>
        </div>
    </form>
    <div class="sstag">
        <div class="classids">
        	<a href="javascript:void(0);" class="active" id="0" onclick="getByClassids(this,0);return false;">全部</a>
            <if condition="$sex_flag eq 'nv'">
            <foreach name="category[2]['subclass']" item="row">
            <a href="javascript:void(0);" id="{$row.classid}" onclick="getByClassids(this,{$row.classid});return false;">{$row.title}</a>
            </foreach>
            <else/>
            <foreach name="category" item="row" key="k">
            <if condition="$k neq 2">
            <a href="javascript:void(0);" id="{$row.classid}" onclick="getByClassids(this,{$row.classid});return false;">{$row.title}</a></if>
            </foreach>
            </if>
        </div>
        <div id="showorhidden" style="display: none;">
        <div class="charnum">
        	<a class="active" href="javascript:void(0);" onclick="getByCharnum(this,0);return false;">字数不限</a>
        	<a href="javascript:void(0);" onclick="getByCharnum(this,1);return false;">50万以下</a>
        	<!-- <a href="javascript:void(0);" onclick="getByCharnum(this,2);return false;">30~50万</a> -->
        	<a href="javascript:void(0);" onclick="getByCharnum(this,3);return false;">50~100万</a>
        	<a href="javascript:void(0);" onclick="getByCharnum(this,4);return false;">100万以上</a>
        </div>
        <div class="finish">
        	<a class="active" href="javascript:void(0);" onclick="getByFinish(this,0);return false;">进度不限</a>
        	<a href="javascript:void(0);" onclick="getByFinish(this,1);return false;">完本</a>
        	<a href="javascript:void(0);" onclick="getByFinish(this,2);return false;">连载</a>
        </div>
        <!-- <div class="updatetime">
        	<a class="active" href="javascript:void(0);" onclick="getByUpdatetime(this,0);return false;">更新不限</a>
        	<a href="javascript:void(0);" onclick="getByUpdatetime(this,1);return false;">三日内更新</a>
        	<a href="javascript:void(0);" onclick="getByUpdatetime(this,2);return false;">一周内更新</a>
        	<a href="javascript:void(0);" onclick="getByUpdatetime(this,4);return false;">一月内更新</a>
        </div> -->
        <div class="tagk">
        	<a class="active" href="javascript:void(0);" onclick="getByFree(this,0);return false;">免费不限</a>
        	<a href="javascript:void(0);" onclick="getByFree(this,1);return false;">免费作品</a>
        	<a href="javascript:void(0);" onclick="getByFree(this,2);return false;">VIP作品</a>
        </div>
        </div>
    </div>
    <div class="tagbtn"><a class="cyellow" >查找更多<span class="down"></span></a></div>
    <div id="loadmore" pagenum=1 totalnum=0 totalpage=0 keyword='' keywordtype='1' Pclassids='2' classids="{$classid}" free='0' finish='0' charnum='0' updatetime='0' order='1'></div>
</div>

<!--搜索结束-->
<!--搜索结果开始-->

<div class="unit">
    <div class="tit"><h1 class="cgray" id="totalcount"></h1></div>
    <div class="frame frame0">
        <ul id="result">
        </ul>
    </div>
</div>
<!--我喜欢开始-->
<!-- <div class="unit">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div> -->
<!--我喜欢结束-->
<!--搜索结果结束-->
</block>
<block name="script">
<!--搜索结果条目模板-->
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}

    <li onClick="go_bookview('{{row.bid}}')">
        <div class="lf"><img data-src="{{row.bookface}}" /></div>
        <div class="rt4">
            <h1 class="hidden">{{row.catename}}</h1>
            <p>{{row.intro | stripTags}}</p>
            <p><span>{{row.category}} </span><span>{{if row.lzinfo == 1}}完本{{else}}连载{{/if}}</span><span>{{row.charnum | subnum}}万字</span><span>{{row.updatetime | getDateDiff}}更新</span></p>
        </div>
    </li>
    {{/each}}
    {{/if}}
</script>
<!--搜索结果条目模板结束-->


<script type="text/javascript">
    var keyword="{$keyword}";
    $(document).ready(function () {
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
        $('.tagbtn>a').on('click', function(){
            var sp = $(this).children().first();
            var title = '';
            if($(this).find('span').first().hasClass('up')){
                sp.removeClass('up').addClass('down');
                title='查找更多';
            } else {
                sp.removeClass('down').addClass('up');
                title = '收起';
            }
            $(this).html(title).append(sp);
            $('#showorhidden').toggle();
        });
    })
    /*$('#sex a').on('click', function(){
                $('#sex a').removeClass('active');
                $(this).addClass('active');
                $(".classids>a").removeClass("active");
                if($(this).html()=="男生"){
                $('#nan1 a:first').addClass("active");
               }else{
                $('#nv1 a:first').addClass("active");
               }
                is_reload = 1;
                initDoSearch();
                do_search();
            });*/
    //初始化do_search()
    function initDoSearch(){
        $("#loadmore").attr('pagenum',1);
        $("#loadmore").attr('totalnum',0);
        $("#loadmore").attr('totalpage',0);
        $("#loadmore").attr('keyword','');
        $("#loadmore").attr('keywordtype',1);
        $("#loadmore").attr('Pclassids',0);
        $("#loadmore").attr('classids',0);
        $("#loadmore").attr('free',0);
        $("#loadmore").attr('finish',0);
        $("#loadmore").attr('charnum',0);
        $("#loadmore").attr('updatetime',0);
        $("#loadmore").attr('order',1);
    }
    //用来判断使用html还是append,true用html,否则用append
    var sign = true;
    //到达最后一页后停止do_search();
    var is_reload = 1;
    var is_running = false;
    function do_search() {
        //hg_gotoUrl('__ROOT__/index.php?m=Mob&c=Channel&a=searchview&searchkey=' + $('#search').val().trim());
        if(!is_reload){
            return false;
        }
        if(is_running) {
           return false;
        }
        is_running = true;
        var url = "{:url('bookajax/search',array(),'do')}";
        //获取参数
        var keyword = $('#keyword').val();
        if(keyword){
            var order = 0;
        }else{
            var order = 1;
        }
        var keywordtype = $('#loadmore').attr('keywordtype');
        var pclassid = new Array();
        pclassid[0] = $('#loadmore').attr('Pclassids');
        var Pclassids =pclassid.join();;
        var classids = $('#loadmore').attr('classids');
        var free = $('#loadmore').attr('free');
        var finish = $('#loadmore').attr('finish');
        var charnum = $('#loadmore').attr('charnum');
        var updatetime = $('#loadmore').attr('updatetime');
        var pagenum = parseInt($('#loadmore').attr('pagenum'));
        var nextpagenum = pagenum + 1;
        var reqData = {
            keyword:keyword,
            keywordtype:1,
            free:free,
            finish:finish,
            charnum:charnum,
            updatetime:updatetime,
            order:order,
            copyright:1,
            pagesize:10,
            sex_flag:'{$sex_flag}'
                    };
          reqData[hsConfig.PAGEVAR] = pagenum;
        if('{$sex_flag}'=='nv'){
            reqData.classids = classids;
        } else if ('{$sex_flag}'=='nan') {
            reqData.Pclassids = classids;
        }
        //return false;
        $.ajax({
            url:url,
            dataType:'jsonp',
            data:reqData,
            complete:function(){
                is_running = false;
                /*showloading();*/
            },
            beforeSend:function(xhr,settings){
                /*endloading();*/
            },
            error:function(data){
                hg_Toast('没有找到合适的记录！');
            },
            success:function(data){
                $("#totalcount").html('共有'+data.totalcount+'条搜索结果');
                Do.ready('lazyload','template', function(){

                    if(parseInt(data.pagecount) > 0){
                        var htmls = template('huanyihuan_tpl',data);
                        if(pagenum < data.pagecount){
                            $(".more2").html('加载更多');
                            $(".more2").show();
                            $("#loadmore").attr('pagenum',nextpagenum);
                            $("#loadmore").attr('totalnum',data.totalcount);
                            $("#loadmore").attr('totalpage',data.pagecount);
                        }else{
                            $(".more2").hide();
                            if(!sign) hg_Toast('已到达最后一页');
                            is_reload = 0;
                        }
                        if(sign){
                            $('#result').html(htmls);
                        }else{
                            $('#result').append(htmls);
                        }
                        Lazy.Load();
                    }else{
                        $('#result').html('');
                        $(".more2").show();
                        $('.more2').html('该分类暂无书籍');
                        is_reload = 0;
                    }
                });
            }
        });
    }

function go_bookview(bid){
   var url=parseUrl({bid:bid},'Book/view','open//','html');
   doClient(url);
}

    //根据类别搜索
    function getByClassids(obj,classid){
        $('#loadmore').attr('pagenum',1);
        $('#loadmore').attr('classids',classid);
        sign = true;
        is_reload = 1;
        do_search();
        $('.classids>a').removeClass('active');
        $(obj).addClass('active');
    }
    //根据字数搜索
    function getByCharnum(obj,charnum){
        $('#loadmore').attr('pagenum',1);
        $('#loadmore').attr('charnum',charnum);
        sign = true;
        is_reload = 1;
        do_search();
        $('.charnum>a').removeClass('active');
        $(obj).addClass('active');
    }
    //根据进度搜索
    function getByFinish(obj,finish){
        $('#loadmore').attr('pagenum',1);
        $('#loadmore').attr('finish',finish);
        sign = true;
        is_reload = 1;
        do_search();
        $('.finish>a').removeClass('active');
        $(obj).addClass('active');
    }
    //根据更新时间搜索
    function getByUpdatetime(obj,updatetime){
        $('#loadmore').attr('pagenum',1);
        $('#loadmore').attr('updatetime',updatetime);
        sign = true;
        is_reload = 1;
        do_search();
        $('.updatetime>a').removeClass('active');
        $(obj).addClass('active');
    }
    //根据是否免费搜索
    function getByFree(obj,free){
        $('#loadmore').attr('pagenum',1);
        $('#loadmore').attr('free',free);
        sign = true;
        is_reload = 1;
        do_search();
        $('.tagk>a').removeClass('active');
        $(obj).addClass('active');
    }
    //根据关键字搜索
    function getByKeyword(){
        /*initDoSearch();*/
        var keyword = $("#keyword").val();
        if(!keyword){
            keyword = $('#keyword').attr('placeholder');
        }
        $('#loadmore').attr('pagenum',1);
        $('#keyword').val(keyword);
        /*$('#loadmore').attr('order',0);*/
        sign = true;
        is_reload = 1;
        do_search(1);
    }

    Do.ready('lazyload', function () {
        document.onscroll = function () {
            if($("#loadmore").length > 0){
                var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                sign = false;
                    do_search();
            }
            Lazy.Load();
                }
            }
    });

    <if condition="$classid">
        var classid = {$classid};
        $(".classids>a").removeClass("active");
        $("#"+classid).addClass("active");
        $("#loadmore").attr('pagenum',1);
        $("#loadmore").attr('classids',classid);
        $('#keyword').val('');
        sign = true;
        is_reload = 1;
        do_search();
    <elseif condition="$keyword" />
    getByKeyword();
    <else />
    initDoSearch();
    do_search();
    </if>
</script>
</block>