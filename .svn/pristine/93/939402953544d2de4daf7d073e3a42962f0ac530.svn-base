<extend name="Common/base" />
<block name="body">
<!--菜单结束-->
<div class="unit">
    <div class="frame frame0 frame00">
        <ul id="lists">
        </ul>
    </div>
</div>
    <div style="display: none;" id="tanchuang">
    <div class="mu2"></div>
    <div class="tkkj">
    <div class="tk radius4"><div class="tkcon"><p>确定删除吗？</p></div><p class="tkbtn"><a  class="ok" id="sure">确定</a><a class="no" id="nosure">取消</a></p></div>
    </div>
    </div>
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
            <li>
                <div class="lf" onClick="doChild('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/view'}}')">
                    <img src="{{row.imgurl}}" />
                </div>
                <div class="rt3" onClick="doChild('{{ {bid:row.bid,chpid:row.last_readchpid} | router:'Book/view'}}')"><h1 class="hidden"><span class="tag">
                    {{row.category}} | </span>{{row.catename}}</h1><p><span>{{if row.classid2 == 41}}{{row.last_updatechptitle}}{{else}}第{{row.totalChpNum}}章{{/if}}</span>（{{row.last_updatetime | getDateDiff}}更新）</p>
                </div>
                <a class="delete" href="javascript:void(0);" data-bid='{{row.bid}}' onclick="return delFav({{row.bid}})"><img src="__IMG__/ic_delete.png" lazy="y"/></a>
            </li>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
var url="{:url('Userajax/getshelflist')}";
    Do.ready('functions', 'lazyload','template',function(){
		loadMore(url,'lists','huanyihuan_tpl','append','first', {_callback:Lazy.Load});
	    document.onscroll = function(){
	    	var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                loadMore(url,'lists','huanyihuan_tpl','append','next', {_callback:Lazy.Load});
            }
	    }
	});

//删除书架
function delFav(bid){
	$('#tanchuang').show('center');
	$('.ok').on('click', function(){
		$('.ok').unbind('click')

	var url = "{:url('Userajax/delfav')}";
	var data = {
		bid:bid,
	};
	$.ajax({
		type:'get',
		url:url,
		data:data,
		success:function(data){
			if(data.status == 1){
				hg_Toast(data.message);
				window.location.href = data.url;
			}else{
				hg_Toast(data.message);
			}
		}
	});
	 $('#tanchuang').css('display','none');
	 $('.ok').unbind('click')
	 return false;
  })
  $('.no').on('click', function() {
  	 $('#tanchuang').css('display','none');
     $('.no').unbind('click')
  })
  return false;
}
</script>
</block>