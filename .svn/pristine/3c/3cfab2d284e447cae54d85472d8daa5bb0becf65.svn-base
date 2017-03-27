<extend name="Common/base" />
<block name="banner">
    <Hongshu:bangdan name="static_android_ad_{$sex_flag}">
        <div class="banner">
            {$row}
        </div>
    </Hongshu:bangdan>
</block>
<block name="body">
<div class="unit">
	<div class="tit2"><ul id="bangtype"><li><a id="topsale" class="active"><span>人气榜</span></a></li><li><a id="newbook"><span>新书榜</span></a> </li></ul></div>
    <div class="frame">
    	<ul id="lists"></ul>
    </div>
    <!--更多按钮开始-->
    <div class="more2" onClick="loadmore(50);$(this).hide();">排行50强</div>
    <!--更多按钮结束-->
</div>
<!--我喜欢开始-->
<div class="unit mtop10">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div>
<!--我喜欢结束-->
<!--底开始-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <li onClick="doChild('{{ {bid:row.bid} | router:'Book/view'}}')">
        <div class="lf">
            <span class="num num{{i + 1}}">{{i + 1}}</span>
            <img data-src="{{row.bookface}}" />
        </div>
                <div class="rt2">
                    <h1 class="hidden">{{row.catename}}</h1>
                    <p>{{row.intro | stripTags}}</p>
                </div>
            </li>
        {{/each}}
    {{/if}}
</script>

<script type="text/javascript">
    Do.ready('lazyload','functions', function(){
            loadmore();
            document.onscroll=function(){Lazy.Load();};
        });
    	Do.ready('common', function(){
    		$('#bangtype a').on('click', function(){
    			$('#bangtype a').removeClass('active');
    			$(this).addClass('active');
    			$('.more2').show();
    			loadmore();
    		});
    	});

   function loadmore(pagesize){
        var sortby = 'lastweek_salenum';
        var needvip=0;
        var url = "{:url('Bookajax/search')}";
        if($('#newbook').hasClass('active')) {
            var sortby = '';
        }
        if(!pagesize){
        	var pagesize=20;
        }

        $.ajax({
            type: "GET",
            url: url,
            data: {method:'search',sex_flag:'{$sex_flag}',sortby:sortby,order:1,pagesize:pagesize,free:2},
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