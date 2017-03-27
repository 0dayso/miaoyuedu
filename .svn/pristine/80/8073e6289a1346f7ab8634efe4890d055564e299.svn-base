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

                <div class="list4-table top30">
                    <table width="100%" border="0">
                        <tbody id="lists" pagenum="1">
                        </tbody>
                    </table>
                </div>

                <div id="fenye" class="pages top30 bom5 clearfix"></div>
            </div>
        </div>
    </div>
</div>


</block>
<block name="script">
<script type="text/html" id="tpl1">
    {{if list}}
        {{each list as row i}}
        {{if row.payflag > 1}}
        <tr>
            <td><div class="long1 cb">{{row.money}}元</div></td>
            <td><div class="long2">{{row.payname}}充值成功</div></td>
            <td><div class="long3">订单号：{{row.payid}}</div></td>
            <td><div class="long4">充值时间：{{row.addtime}}</div></td>
        </tr>
        {{else}}
        <tr>
            <td><div class="long1 cb">{{row.money}}元</div></td>
            <td><div class="long2 cc">{{row.payname}}充值失败</div></td>
            <td><div class="long3 cc">订单号：{{row.payid}}</div></td>
            <td><div class="long4 cc">充值时间：{{row.addtime}}</div></td>
        </tr>
        {{/if}}
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
   function refreshlogs(pagenum){
    var url="{:url('Userajax/paylogs',array(),'do')}";
    if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
        var nextpagenum = pagenum + 1;
        var Data = {
            //uid:'{$userinfo.uid}',
            pagesize:10,
            clientmethod:'refreshlogs',
        }
		Data[hsConfig.PAGEVAR]=pagenum;
        $.ajax({
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
</script>
<include file="Common/foot2" />
</block>