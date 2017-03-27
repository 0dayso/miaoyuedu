<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  mtop10 clearfix">
        <div  class="user">
            <div class="lf clearfix">
                <include file="tpl/user" />
            </div>
            <div class="rt">
                <div class="rt_unit rt_czjl clearfix">
                    <div class="user_tit"><h1>充值记录</h1></div>
                    <div class="conditions">

                        <select id="payway">
                            <option value="0">全部充值来源</option>
                            <option value="1">支付宝</option>
                            <option value="2">微信</option>
                        </select>
                        <select id="type">
                            <option value="0">{:C('SITECONFIG.MONEY_NAME')}</option>
                            <option value="1">{:C('SITECONFIG.EMONEY_NAME')}</option>
                        </select>
                        <span>共有<span id="num" totalnum='0'>0</span>笔入账记录</span>
                    </div>
                    <div class="clearfix">
                        <table width="682" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th width="90">单号</th>
                                <th width="152">充值来源</th>
                                <th width="135">数量</th>
                                <th width="104">金额(元)</th>
                                <th width="85">充值状态</th>
                                <th width="124">充值时间</th>
                            </tr>
                            </thead>
                            <tbody id="paylist">

                            </tbody>
                        </table>
                    </div>


                    <div class="page commnnt_pages" id="pagelist">
                    </div>
                </div>

            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/html" id="pay_tpl">
        {{each list as row i}}
            {{if row.hongshumoney}}
                <tr>
                    <td rowspan="2">{{row.payid}}</td>
                    <td class="czfs">充值({{row.name}})</td>
                    <td class="cznum">{{row.gold}}{:C('SITECONFIG.MONEY_NAME')}</td>
                    <td class="czfs">{{row.money}}</td>
                    <td>{{if row.payflag > 1}}<span class="success">成功</span>{{else}}<span class="fail">失败</span>{{/if}}</td>
                    <td>{{row.time}}</td>
                </tr>
                <tr>
                    <td class="czfs">官方(赠送)</td>
                    <td class="cznum">{{row.hongshumoney}}{:C('SITECONFIG.EMONEY_NAME')}</td>
                    <td class="czfs">0</td>
                    <td>{{if row.payflag > 1}}<span class="success">成功</span>{{else}}<span class="fail">失败</span>{{/if}}</td>
                    <td>{{row.time}}</td>
                </tr>
            {{else}}
                <tr>
                    <td>{{row.payid}}</td>
                    <td class="czfs">充值({{row.name}})</td>
                    <td class="cznum">{{row.gold}}{:C('SITECONFIG.MONEY_NAME')}</td>
                    <td class="czfs">{{row.money}}</td>
                    <td>{{if row.payflag > 1}}<span class="success">成功</span>{{else}}<span class="fail">失败</span>{{/if}}</td>
                    <td>{{row.time}}</td>
                </tr>
            {{/if}}
        {{/each}}
    </script>
    <script type="text/javascript">
        //全局参数判断是否改变条件
        var change=0;
        require(['mod/user'],function(user){
            user.listmark();
            getlist();
            $('#payway').on('change',function(){
                getlist();
                change = 1;
            })
            $('#type').on('change',function(){
                getlist();
                change = 1;
            })
        })
        
        function getlist(pagenum){
            if(!pagenum){
                var pagenum=1;
            }
            var payway=$('#payway').val();
            var type=$('#type').val();
            var totalnum=$('#num').html();
            if(change==1){
                totalnum=0;
                change = 0;
            }
            require(['api','template','mod/page','functions'],function(api,template,page){
                var url = parseUrl('','Userajax/paylogs','do');
                var data = {
                    pagenum:pagenum,
                    pagesize:10,
                    totalnum:totalnum,
                    clientmethod:'getlist',
                    pagelistsize:10,
                    moneytype:type,
                    paytype:payway
                }
                api.getapi(url,data,function(data){
                    if(data.status==1){
                        $('#num').html(data.totalnum);
                        var html=template('pay_tpl',data);
                        $('#paylist').html(html);
                        page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist');
                    }else{
                        $('#paylist').html('<tr><td colspan="6">暂无充值记录</td></tr>');
                        $('#pagelist').html('');
                    }
                })
            })
        }
    </script>
</block>