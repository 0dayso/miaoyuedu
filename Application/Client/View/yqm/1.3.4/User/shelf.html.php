<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<include file="Common/head2" />
<div class="user1060">
            <div id="lists" class="book_rongqi top10" pagenum="1">
            </div>

            <div id="fenye" class="pages top30 bom5 clearfix" style="text-align: center;">
            </div>
        </div>
    </div>
</div>
</block>
<block name="script">
<script type="text/html" id="tpl1">
 {{each list as row i}}
    <div class="book2 bom20">
                    <div class="fm bom5">
                        <img src="{{row.imgurl}}" width="200" height="282" />
                        <div class="fm_mb2" style="display:none;">
                            <div class="guanbi flrt" onclick="deletebook({{row.bid}});">
                                <img src="__IMG__/guanbi.png"/ >
                            </div>
                            {{if row.isvip == 1}}
                            <button class="mb_btn" onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/read','do'}}')">
                            {{else}}
                            <button class="mb_btn" onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/read','html'}}')">
                            {{/if}}{{if row.isread == 1}}继续阅读{{else}}开始阅读{{/if}}</button>
                        </div>

                    </div>
                    <p class="lf5 c3" onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view','html'}}')"><a>{{row.catename}}</a></p>
                    <span class="c3 lf5">萌神：{{row.authorname}}</span>
                    {{if row.tags != ''}}<span class="cb lf5">{{row['tags'][0]}}，{{row['tags'][1]}}，{{row['tags'][2]}}</span>{{/if}}
    </div>
 {{/each}}
</script>
<script type="text/javascript">
Do.ready('common', function(){
        loadmore();
    });
        function loadmore(pagenum){
        var url = "{:url('Userajax/getshelflist',array(),'do')}";
        if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
	var Data = {pagesize:8,clientmethod:'loadmore'}
		Data[hsConfig.PAGEVAR]=pagenum;
        $.ajax({
            type: "GET",
            url: url,
            data: Data,
            timeout: 9000,
            success: function (data) {
                Do.ready('template', 'lazyload', function(){
                    if(data.totalpage>0){
                        $('#fenye').html(data.pagelist);
                        var htmls = template('tpl1',data);
                        $('#lists').html(htmls);
                        $('.fm').mouseover(function(){
                        $(this).find('.fm_mb2').show();
                    });
                    $('.fm').mouseout(function(){
                        $(this).find('.fm_mb2').hide();
                    });
                    }else{
                        Do.ready('functions', function(){
                            $('#lists').html("<p class='c9 lf30'>暂无记录</p>");
                        });
                    }
                });
            }
        });
   }

   function deletebook(bid){
       var url = "{:url('Userajax/delfav',array(),'do')}";
       $.ajax({
            type: "GET",
            url: url,
            data: {bid:bid},
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                  hg_Toast('删除成功！');
                  loadmore();
              }else{
                  hg_Toast('网络故障，请稍候重试！');
              }
           }
        });
   }
   </script>
   <include file="Common/foot2" />
</block>