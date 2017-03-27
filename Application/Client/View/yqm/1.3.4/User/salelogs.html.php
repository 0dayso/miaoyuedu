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
				<div class="yuefenfanye top10">
					<a onclick="ChangeDate(1);return false;">← 上一月</a>
					<div class="c3"><span id="year">{$nowyear}</span>年<span id="month">{$nowmonth}</span>月</div>
					<a onclick="ChangeDate(2);return false;" style="display:none;" id="nextmonth">下一月→</a>
					<a class="meiyoule">没有了</a>
				</div>

				<div class="list4-table top20">
				    <table width="100%" border="0">
				        <tbody id="lists" year="{$nowyear}" month="{$nowmonth}" totalnum="0" totalpage='0' pagenum='0'>
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
        	<td><div class="long1">{{row.saleprice}}{:C('SITECONFIG.MONEY_NAME')}</div></td>
          	<td><a><div class="long5 cb" onClick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/readVip'}}')">《{{row.catename}}》{{row.title}}</div></a></td>
          	<td><div class="long4">消费时间：{{row.time}}</div></td>
        </tr>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
//切换年月
var year = {$nowyear};
var month = {$nowmonth};
function ChangeDate(type){
	var pagenum = $("#lists").attr('pagenum',1);
	//判断选定日期是否为当月
	var tmpMonth;
	if(month< 10){
		tmpMonth = "0" + month;
	}else{
		tmpMonth = month;
	}
	var chooseDate = parseInt(year+''+tmpMonth);
	var date = new Date();
	var nowYear = date.getFullYear();
	var nowMonth = date.getMonth()+1;
	var tmpMonth;
	if(nowMonth < 10){
		tmpNowMonth = "0" + nowMonth;
	}else{
		tmpNowMonth = nowMonth;
	}
	var nowDate = parseInt(nowYear+''+tmpNowMonth);
	//选定时间大于等于当前年月的时候显示下一页
	if(chooseDate >= nowDate){
		$("#nextmonth").hide();
		$('.meiyoule').show();
	}
	//1上月 2下月
	var realMonth;
	var realYear;
	if(type == 1){
		var chgMonth = month - 1;
		if(chgMonth < 1){
			realMonth = 12;
			realYear = year - 1;
			if(realYear <= 2012){
				realYear = 2012;
			}
		}else{
			var chgMonth = month - 1;
			realMonth = chgMonth;
			realYear = year;
		}
	}else if(type == 2){
		var chgMonth = month+1;
		if(chgMonth > 12){
			realMonth = 1;
			realYear = year + 1;
		}else if(chgMonth > nowMonth && year > nowYear){
			realMonth = nowMonth;
			realYear = year;
		}else{
			realMonth = chgMonth;
			realYear = year;
		}
	}
	if(parseInt(realMonth) < 10){
		fMonth = "0"+realMonth;
	}else{
		fMonth = realMonth;
	}
	var realDate = parseInt(realYear+''+fMonth);
	if(realDate >= nowDate){
		$("#nextmonth").hide();
		$('.meiyoule').show();
	}else{
		$("#nextmonth").show();
		$('.meiyoule').hide();
	}
	year = realYear;
	month = realMonth;
	//设置year、Month
	$("#year").html(realYear);
	$("#month").html(realMonth);
	$("#lists").attr('year',realYear);
	$("#lists").attr('month',realMonth);
	LoadMore(1);
}
function LoadMore(pagenum){
    var url = "{:url('Userajax/salelogs',array(),'do')}";
    var year = $("#lists").attr('year');
    var month = $('#lists').attr('month');
    if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
    var Data = {
    	    year:year,
            month:month,
            pagesize:10,
            clientmethod:'LoadMore',
        }
		Data[hsConfig.PAGEVAR]=pagenum;
    $.ajax({
            url:url,
            //dataType:'get',
            data:Data,
            success:function(data){
                Do.ready('template', function(){
                    if(parseInt(data.totalpage) > 0){
                        $('#fenye').html(data.pagelist);
                        var htmls = template('tpl1',data);
                        $('#lists').html(htmls);
                        if(pagenum <= data.totalpage){
                            $("#lists").attr('pagenum',data.nextpagenum);
                        }else{
                            hg_Toast('没有更多了哟~');
                        }
                    }else{
                        $('#lists').html('暂无最新记录');
                        $('#fenye').html('');
                    }
                });
            }
        });

}
Do.ready('common', function(){
   LoadMore(1);
});

</script>
<include file="Common/foot2" />
</block>