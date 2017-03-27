<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<include file="Common/head2" />
<div class="user1060">
            <div class="user960">
                <p class="c6">充值账户：{$userinfo.nickname}</p>
                <p class="c6">账户余额：<span class="cb">{$userinfo.money} </span> {:C('SITECONFIG.MONEY_NAME')}</p>
                <h2 class="top40">自动订阅书籍列表</h2>
                <div class="list4-table top30">
                    <table width="100%" border="0">
                        <tbody id="lists" pagenum="1">

                        </tbody>
                    </table>
                </div>

                <div id="fenye" class="pages top30 bom5 clearfix" style="text-align: center;">
                </div>

            </div>
        </div>
    </div>
</div>

</block>
<block name="script">
    <script type="text/html" id="tpl1">
    {{if list}}
        {{each list as row i}}
        <tr>
        <td><div class="long5">《{{row.catename}}》</div></td>
        <td><div class="long3">
            <div class="togglebutton fllf lf20">
                <label>
                    <input type="checkbox" checked="" id="{{row.bid}}" onclick="dingyue({{row.bid}});"><span class="toggle"></span>
                </label>
            </div>
            <div id="ZT{{row.bid}}">
            自动订阅：开
        </div></td>
    </tr>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
    function refreshlogs(pagenum){
    var url="{:url('Userajax/autodingyue',array(),'do')}";
    if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
        var nextpagenum = pagenum + 1;
        var Data = {
            pagesize:10,
            clientmethod:'refreshlogs',
        }
		Data[hsConfig.PAGEVAR]=pagenum;
        $.ajax({
            type:'GET',
            url:url,
            //dataType:'get',
            data:Data,
            error:function(data){
                hg_Toast('没有找到合适的记录！');
            },
            success:function(data){
                Do.ready('template', function(){
                    if(parseInt(data.totalpage) > 0){
                        $('#fenye').html(data.pagelist);
                        var htmls = template('tpl1',data);
                        $('#lists').html(htmls);
                        if(pagenum <= data.totalpage){
                            $("#lists").attr('pagenum',nextpagenum);
                        }else{
                            hg_Toast('没有更多了哟~');
                        }
                    }else{
                        $('#lists').html('暂无最新记录');
                    }
                });
            }
        });
}
Do.ready('common', function(){
   refreshlogs();
});

function dingyue(bid){
    var url="{:url('Userajax/saveautodingyue',array(),'do')}";
    var autobook = $("#"+bid).is(":checked");
    var type="";
    if(autobook){
        var type="save";

    }else{
        var type="del";

    }
        var Data = {
            bid:bid,
            type:type
        }
        $.ajax({
            type:'POST',
            url:url,
            //dataType:'get',
            data:Data,
            success:function(data){
                if(data.status == 1){
                    if(data.type == "save"){
                        $("#ZT"+bid).html("自动订阅：开");
                    }else{
                        $("#ZT"+bid).html("自动订阅：关");
                    }
                }else{
                    hg_Toast('网络状态不佳，请稍后重试...');
                    history.go(0);
                }
            }
        });
}
</script>
<include file="Common/foot2" />
</block>


