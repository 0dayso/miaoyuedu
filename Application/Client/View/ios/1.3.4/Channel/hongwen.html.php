<extend name="Common/base" />
<block name="body">
<div class="unit mtop10">
    <div class="tit"><h1>周销售榜</h1><span class="mlf10"></span></div>

    <div class="frame">
        <ul id="lists">
        </ul>
    </div>
<!--我喜欢开始-->
<div class="unit">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div>
<!--我喜欢结束-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')">
                <div class="lf"><img src="{{row.bookface}}"/></div>
                <div class="rt"><h1 class="hidden">{{row.catename}}</h1>
                <div class="tejia clearfix"><h5 class="fllf ">{{row.authorname}}</h5></div>
                    <p class="h38em">{{row.intro | stripTags}}</p></div>
    </li>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
    $(document).ready(function () {
        loadmore();
    });
        function loadmore(){
        var sortby = 'lastweek_salenum';
        var url = "{:url('Bookajax/search')}";

        $.ajax({
            type: "GET",
            url: url,
            data: {method:'search',sex_flag:'{$sex_flag}',sortby:sortby,order:1,pagesize:20},
            timeout: 9000,
            dataType:'jsonp',
            success: function (data) {
                Do.ready('template', 'lazyload', function(){
                    if(data.bookinfo.length>0){
                        var htmls = template('huanyihuan_tpl',data);
                        $('#lists').html(htmls);
                        Lazy.Load();
                    }else{
                        Do.ready('functions', function(){
                            hg_Toast(data.message);
                        });
                    }
                });
            }
        });
   }
</script>
</block>