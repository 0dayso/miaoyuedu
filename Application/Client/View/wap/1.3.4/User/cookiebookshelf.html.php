<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--菜单结束-->
<div class="unit">
    <div class="tit2"><ul><li><a href="{:url('User/shelf',array('sex_flag'=>$sex_flag),'do')}"><span >我的书架</span></a></li><li><a href="javascript:void(0);" class="active"><span>阅读记录</span></a> </li></ul></div>
    <div class="frame frame0">
        <ul id="lists">
        </ul>
    </div>
</div>
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
           <li>
                {{if row.isvip == 0}}
                <a href="{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/read','html'}}"><div onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/read','html'}}')" class="rt3">{{else}}
                <a href="{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/readvip','do'}}"><div onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/readvip','do'}}')" class="rt3">{{/if}}
                	<h1 class="hidden"><span class="tag">
                    {{row.category}} | </span>{{row.catename}}</h1><p><span>{{if row.classid2 == 41}}{{row.last_updatechptitle}}{{else}}第{{row.last_updatechpnum}}章{{/if}}</span>（{{row.last_vipupdatetime | getDateDiff}}更新）</p><p><span class="cblue">继续阅读</span>（{{row.last_readchpnum}}/{{row.totalChpNum}}）</p>
                </div></a>
                {{if row.isfav}}
                <a class="collection active">已收藏</a>
                {{else}}
                <a class="collection" onclick="InsertFav({{row.bid}},this)" id="no">收藏到书架</a>
                {{/if}}
            </li>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
var url="{:url('Bookajax/getreadlog','','do')}"
   Do.ready('lazyload',function(){
		loadMore(url,'lists','huanyihuan_tpl','append','first', {_callback:Lazy.Load})
	})
//添加到书架
function InsertFav(bid,obj){
	if(!bid){
		hg_Toast('缺少参数');
		return false;
	}
	var data = {
		bid:bid,
	}
	var url = "{:url('Userajax/insertfav','','do')}";
	$.ajax({
		type:'get',
		url: url,
		data:data,
		success: function (data){
			//console.log(data);
			hg_Toast(data.message);
			$(obj).addClass("active");
			$(obj).html("已收藏");
		}
	})
}
</script>
</block>