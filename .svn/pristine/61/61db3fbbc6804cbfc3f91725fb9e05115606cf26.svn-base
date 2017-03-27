<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="banner">
    <Hongshu:bangdan name="static_m_ad_{$sex_flag}">
        <div class="banner">
            {$row}
        </div>
    </Hongshu:bangdan>
</block>
<block name="body">
<div class="unit mtop10">
	<div class="tit2 tit10"><ul id="bangtype"><li><a id="topsale" class="active"><span>畅销榜</span></a></li><li><a id="newbook"><span>新书榜</span></a> </li></ul></div>
    <div class="frame">
    	<ul id="lists"></ul>
    </div>
</div>
<!--底开始-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')">
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
    	Do.ready('zepto', function(){
    		$('#bangtype a').on('click', function(){
    			$('#bangtype a').removeClass('active');
    			$(this).addClass('active');
    			loadmore();
    		});
    	});


   function loadmore(){
        var sortby = 'lastweek_salenum';
        var url = "{:url('Bookajax/search')}";
        if($('#newbook').hasClass('active')) {
            var sortby = '';
        }
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