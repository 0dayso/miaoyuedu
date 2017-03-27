<extend name="Common/base" />
<block name="body">
<!--消费记录选择开始-->
<div class="frame8">
    <ul>
        <li onclick="ChangeDate(1);return false;"><a class="radius4"><span  class="syy" >上一月</span></a></li>
        <li><span id = "year">{$nowyear}</span>年<span id = "month">{$nowmonth}</span>月</li>
        <li id="nextmonth" style="display:none" onclick="ChangeDate(2);return false;"><a class="radius4 "><span  class="xyy" >下一月</span></a></li>
        <li  id="nextpage" style="display:block"><a class="radius4 disable"><span  >下一月</span></a></li>
    </ul>
</div>
<!--消费记录选择结束-->

<!--消费记录开始-->
<div class="unit">
    <div class="frame7">
        <ul id="lists">
        </ul>
    </div>
</div>
<div id="loadmore" class="more2" year="{$nowyear}" month="{$nowmonth}" totalnum="0" totalpage='0' pagenum='0'></div>
<!--消费记录结束-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
            <li class=" borderbom"><h4>{{row.saleprice}}{{row.moneytypestr}}</h4><p><span class="hidden lf">《{{row.catename}}》{{row.title}}</span><span class="rt">{{row.time}}</span></p></li>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
//切换年月
var year = {$nowyear};
var month = {$nowmonth};
function ChangeDate(type){
	var pagenum = $("#loadmore").attr('pagenum',1);
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
		$("#nextmonth").css('display','none');
		$('#nextpage').css('display','block');
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
		$("#nextmonth").css('display','none');
		$('#nextpage').css('display','block');
	}else{
		$("#nextmonth").css('display','block');
		$('#nextpage').css('display','none');
	}
	year = realYear;
	month = realMonth;
	//设置year、Month
	$("#year").html(realYear);
	$("#month").html(realMonth);
	$("#loadmore").attr('year',realYear);
	$("#loadmore").attr('month',realMonth);
	changestatusnum();
	LoadMore(1);
}
Do.ready('lazyload','functions','template',function(){
     LoadMore(1);
    document.onscroll = function(){
        var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                LoadMore(2);
                changestatusnum();
            }
    }
})
var statusnum=0;
function changestatusnum(){
  statusnum=0;
}
function LoadMore(type){
	if(statusnum==1){
		return;
	}
    var url = "{:url('Userajax/salelogs')}";
    var year = $("#loadmore").attr('year');
    var month = $('#loadmore').attr('month');
    var fangshi="append";
    if (type==1) {
        type="first";
        fangshi='replace';
    }else{
        type="next";
        fangshi="append";
        statusnum=1;
    }
    loadMore(url,'lists','huanyihuan_tpl',fangshi,type,{"year":year,"month":month}, {_callback:Lazy.Load});
}
</script>
</block>