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
                    <div class="user_tit"><h1>消费记录</h1></div>
                    <div class="conditions">

                        <select id="month">
                            <option value="1">1月</option>
                            <option value="2">2月</option>
                            <option value="3">3月</option>
                            <option value="4">4月</option>
                            <option value="5">5月</option>
                            <option value="6">6月</option>
                            <option value="7">7月</option>
                            <option value="8">8月</option>
                            <option value="9">9月</option>
                            <option value="10">10月</option>
                            <option value="11">11月</option>
                            <option value="12">12月</option>
                        </select>
                        <select id="year">
                        </select>
                        <span>本月共有<span id="num">0</span>条消费记录,消费<span id="money">0</span>{:C('SITECONFIG.MONEY_NAME')},<span id="emoney">0</span>{:C('SITECONFIG.EMONEY_NAME')}。</span>
                    </div>
                    <div class="xfjl clearfix">
                        <table width="682" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th width="50">序号</th>
                                <th width="350" colspan="2">消费项目</th>
                                <th width="100">消费数量</th>
                                <th width="144">消费时间</th>
                            </tr>
                            </thead>
                            <tbody id="salelist">
                        
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
    <script type="text/html" id="sale_tpl">
        {{each list as row i}}
            <tr>
                <td>{{row.ordernum}}</td>
                <td ><a class="xf_name ellipsis" href="{{row.booklink}}" target="_blank">《{{row.catename}}》</a></td>
                <td ><p class="xf_chapter ellipsis">{{row.title}}</p></td>
                <td class="czfs">{{row.saleprice}}({{row.moneytypestr}})</td>
                <td>{{row.time}}</td>
            </tr>
        {{/each}}
    </script>
    <script type="text/javascript">
        require(['mod/user'],function(user){
            user.listmark();
            var years="";
            var yearstart=2015;
            var myDate = new Date();
            var nyear=myDate.getFullYear();
            for(var i=nyear;i>=yearstart;i--){
                years +='<option value='+i+'>'+i+'年</option>';
            }
            $('#year').html(years);    
            getlist();
            $('#year').on('change',function(){
                getlist();
            })
            $('#month').on('change',function(){
                getlist();
            })
        })
        function getlist(pagenum){
            var year=$('#year').val();
            var month=$('#month').val();
            if(!pagenum){
                var pagenum=1;
            }
            require(['api','template','mod/page','functions'],function(api,template,page){
                var url = parseUrl('','Userajax/salelogs','do');
                var data = {
                    pagenum:pagenum,
                    pagesize:10,
                    clientmethod:'getlist',
                    pagelistsize:10,
                    year:year,
                    month:month
                }
                api.getapi(url,data,function(data){
                    if(data.status==1){
                        for(var i in data.list){
                            data.list[i].booklink=parseUrl({bid:data.list[i].bid},'Book/view','html');
                        }
                        var html=template('sale_tpl',data);
                        $('#salelist').html(html);
                        page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist');
                        $('#money').html(data.totalmoney);
                        $('#emoney').html(data.totalemoney);

                    }else{
                        $('#salelist').html('<tr><td colspan="5">暂无消费记录</td></tr>');
                        $('#pagelist').html('');
                    }
                })
            })

        }
    </script>
</block>