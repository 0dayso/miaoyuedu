<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<style type="text/css">
    .frame .rt2 { padding-left: 30px;}    
    .num{margin-top: 10px;}
</style>
<div class="unit mtop10">
	<div class="tit2 tit10"><ul id="bangtype"><li><a id="topsale" class="active"><span>人气榜</span></a></li><li><a id="newbook"><span>新书榜</span></a> </li></ul></div>
    <div class="frame">
    	<ul id="lists"></ul>
    </div>
    <div class="more2" onClick="loadmore(50);$(this).hide();">排行50强</div>
</div>
<!--我喜欢开始-->
<if condition="$sex_flag eq 'nan'">
<div class="unit nan">
    <div class="like mbom40"><a href="javascript:change_like('nv', '{:url('', array('sex_flag'=>'nv'))}');"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看女生小说></a></div>
</div>
<else/>
<div class="unit nv">
    <div class="like mbom40"><a href="javascript:change_like('nan', '{:url('', array('sex_flag'=>'nan'))}');"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
</if>
<!--我喜欢结束-->
<!--底开始-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <a href="{{ {bid:row.bid} | router:'Book/view','html'}}">
    <li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')">
        <div class="lf">
            <span class="num num{{i + 1}}">{{i + 1}}</span>
        </div>
                <div class="rt2">
                    <h1 class="hidden">{{row.catename}}</h1>
                    <p style="width:100%;height: 20px;overflow: hidden:;"><span style="float: right;margin-left: 10px;">{{row.classname}}</span><span style="float: right;margin-left: 10px;">{{row.authorname}}</span></p>
                    <p>{{row.intro | stripTags}}</p>
                </div>
            </li></a>
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
        var url = "{:url('Bookajax/search','','do')}";
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

        /**控制榜单和广告行为的js**/
        Do.ready('touchslider', function(){
            var p2 = 0;
            var tt = new TouchSlider({
                id: 'slider1',
                auto: '0',
                fx: 'ease-out',
                direction: 'left',
                speed: 300,
                timeout: 4500,
                client: true,
                before: function (index) {
                    var as2 = $('#dot>div');
                    if ((typeof p2) !== 'undefined') {
                        if(p2<=as2.length && p2>=0){
                            $(as2[p2]).addClass('v3_dot_r').removeClass('v3_cur');
                        }
                    }
                    p2 = index;
                    if(p2<=as2.length && p2>=0){
                        $(as2[p2]).addClass('v3_dot_r').addClass('v3_cur');
                    }
                }
            });
            window.addEventListener('load', function() {
                touchEvent('li');
            }, false);
        });
        function touchstartremove(classname) {
            $("." + classname).css({"background-image": "none"});
        }
        function touchendadd(classname) {
            $("." + classname).css({"background-image": "url(__IMG__/splitline1.png)"});
        }
</script>
</block>