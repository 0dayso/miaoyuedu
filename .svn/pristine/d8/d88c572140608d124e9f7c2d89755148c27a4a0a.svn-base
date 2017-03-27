<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<include file="Common/head2" />
<div class="user1060">
			<div class="user960">
				<h1 class="c3" id="title1">我的书圈（0）</h1>
				<ul class="wdsq top20" id="list1" pagenum="1">	
				</ul>
				<div class="pages  bom5 clearfix" style="text-align: right;" id="fenye1"></div>
				
				<h1 class="c3 top40" id="title2">我发起的评论（0）</h1>
				<div class="list4-table top30">
				    <table width="100%" border="0">
				        <tbody id="list2" pagenum="1">
				        </tbody>
				    </table>
				</div>
				<div class="pages top10  bom5 clearfix" style="text-align: right;" id="fenye2"></div>
				
				<h1 class="c3 top40" id="title3">我回复的评论（0）</h1>
				<div class="list4-table top30">
				    <table width="100%" border="0">
				        <tbody id="list3" pagenum="1">
				        </tbody>
				    </table>
				</div>
				<div class="pages top10  bom5 clearfix" style="text-align: right;" id="fenye3">
				</div>
				
				<h1 class="c3 top40" id="title4">回复我的评论（0）</h1>
				<div class="list4-table top30">
				    <table width="100%" border="0">
				        <tbody id="list4" pagenum="1">
				        </tbody>
				    </table>
				</div>
				<div class="pages top10  bom5 clearfix" style="text-align: right;" id="fenye4">
				</div>
				
			</div>
		</div>
	</div>
</div>
</block>
<block name="script">
<script type="text/html" id="tpl1">
{{each booklist as row i}}
	<li onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/comment'}}')">
		<p class="top25">{{row.catename}}</p>
		<span class="top5">评论（{{row.totalcomment}}）</span>
	</li>
{{/each}}	
</script>
<script type="text/html" id="tpl2">
{{each commentlist as row i}}
	<tr>
		<td onClick="hg_gotoUrl('{{ {bid:row.bid,comment_id:row.comment_id} | router:'Book/replyComment'}}')"><a><div class="long4 cb">{{if row.forbidden_flag==1}}<span class="cf00">[待审]</span>{{/if}}{{#row.content}}</div></a></td>
	  	<td><div class="long6 c6"><img class="top12 fllf" src="__IMG__/dp_g.png"/ width="10" height="16">（{{row.zan_amount}}）</div></td>
	  	<td><div class="long6 c6"><img class="top12 fllf" src="__IMG__/pl_g.png"/ width="16" height="16">（{{row.reply_amount}}）</div></td>
	  	<td><div class="long1 c6">{{row.creation_date}}</div></td>
	  	<td onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')"><a><div class="long4 cb">《{{row.catename}}》</div></a></td>
	</tr>  
{{/each}}	
</script>
<script type="text/html" id="tpl3">
{{each commentlist as row i}}
	<tr>
		<td onClick="hg_gotoUrl('{{ {bid:row.bid,comment_id:row.comment_id} | router:'Book/replyComment'}}')"><a><div class="long4 cb">{{if row.forbidden_flag==1}}<span class="cf00">[待审]</span>{{/if}}{{#row.content}}</div></a></td>
	  	<td><div class="long6 c6"><img class="top12 fllf" src="__IMG__/dp_g.png"/ width="10" height="16">（{{row.zan_amount}}）</div></td>
	  	<td><div class="long6 c6"><img class="top12 fllf" src="__IMG__/pl_g.png"/ width="16" height="16">（{{row.reply_amount}}）</div></td>
	  	<td><div class="long1 c6">{{row.creation_date}}</div></td>
	  	<td onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')"><a><div class="long4 cb">《{{row.catename}}》</div></a></td>
	</tr>
{{/each}}	
</script>
<script type="text/html" id="tpl4">
{{each commentlist as row i}}
	<tr>
		<td onClick="hg_gotoUrl('{{ {bid:row.bid,comment_id:row.comment_id} | router:'Book/replyComment'}}')"><a><div class="long4 cb">{{if row.forbidden_flag==1}}<span class="cf00">[待审]</span>{{/if}}{{#row.content}}</div></a></td>
	  	<td><div class="long6 c6">{{row.nickname}}</div></td>
	  	<td><div class="long6 c6"></div></td>
	  	<td><div class="long1 c6">{{row.creation_date}}</div></td>
	  	<td onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')"><a><div class="long4 cb">《{{row.catename}}》</div></a></td>
	</tr>
{{/each}}	
</script>
<script type="text/javascript">
Do.ready('common','functions',function(){
   shuQuanBooks();
   myComments();
   myReplyComments();
   myCommentReplies();
});
function shuQuanBooks(pagenum){
       var url = "{:url('Userajax/shuQuanBooks',array(),'do')}";
       var Data = {pagesize:8,clientmethod:'shuQuanBooks'}
       if(!pagenum){
          var pagenum=parseInt($('#list1').attr('pagenum'));
       }
       Data[hsConfig.PAGEVAR]=pagenum;
       $.ajax({
            type: "GET",
            url: url,
            data: Data,
            timeout: 9000,
            success: function (data) {
              Do.ready('template', 'lazyload', function(){
                    if(data.totalnum>0){
                        $('#fenye1').html(data.pagelist);
                        $('#title1').html("我的书圈（"+data.totalnum+"）");
                        var htmls = template('tpl1',data);
                        $('#list1').html(htmls);
                    }else{
                        Do.ready('functions', function(){
                            $('#list1').html("<p class='c9 lf30'>暂无记录</p>");
                        });
                    }
                });
           }
        });
   }

   function myComments(pagenum){
       var url = "{:url('Userajax/myComments',array(),'do')}";
       var Data = {pagesize:5,clientmethod:'myComments'}
       if(!pagenum){
          var pagenum=parseInt($('#list2').attr('pagenum'));
       }
       Data[hsConfig.PAGEVAR]=pagenum;
       $.ajax({
            type: "GET",
            url: url,
            data: Data,
            timeout: 9000,
            success: function (data) {
              Do.ready('template', 'lazyload', function(){
                    if(data.totalnum>0){
                        $('#fenye2').html(data.pagelist);
                        $('#title2').html("我发起的评论（"+data.totalnum+"）");
                        var htmls = template('tpl2',data);
                        $('#list2').html(htmls);
                    }else{
                        Do.ready('functions', function(){
                            $('#list2').html("<p class='c9 lf30'>暂无记录</p>");
                        });
                    }
                });
           }
        });
   }

   function myReplyComments(pagenum){
       var url = "{:url('Userajax/myReplyComments',array(),'do')}";
       var Data = {pagesize:5,clientmethod:'myReplyComments'}
       if(!pagenum){
          var pagenum=parseInt($('#list3').attr('pagenum'));
       }
       Data[hsConfig.PAGEVAR]=pagenum;
       $.ajax({
            type: "GET",
            url: url,
            data: Data,
            timeout: 9000,
            success: function (data) {
              Do.ready('template', 'lazyload', function(){
                    if(data.totalnum>0){
                        $('#fenye3').html(data.pagelist);
                        $('#title3').html("我回复的评论（"+data.totalnum+"）");
                        var htmls = template('tpl3',data);
                        $('#list3').html(htmls);
                    }else{
                        Do.ready('functions', function(){
                            $('#list3').html("<p class='c9 lf30'>暂无记录</p>");
                        });
                    }
                });
           }
        });
   }

   function myCommentReplies(pagenum){
       var url = "{:url('Userajax/myCommentReplies',array(),'do')}";
       var Data = {pagesize:5,clientmethod:'myCommentReplies'}
       if(!pagenum){
          var pagenum=parseInt($('#list4').attr('pagenum'));
       }
       Data[hsConfig.PAGEVAR]=pagenum;
       $.ajax({
            type: "GET",
            url: url,
            data: Data,
            timeout: 9000,
            success: function (data) {
              Do.ready('template', 'lazyload', function(){
                    if(data.totalnum>0){
                        $('#fenye4').html(data.pagelist);
                        $('#title4').html("回复我的评论（"+data.totalnum+"）");
                        var htmls = template('tpl4',data);
                        $('#list4').html(htmls);
                    }else{
                        Do.ready('functions', function(){
                            $('#list4').html("<p class='c9 lf30'>暂无记录</p>");
                        });
                    }
                });
           }
        });
   }
</script>
<include file="Common/foot2"/>
</block>