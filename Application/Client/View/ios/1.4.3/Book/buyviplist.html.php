<extend name="Common/base" />
<block name="body">
<div class="rdcon">
   <div class=" rdvip radius4">
    <div class=" rdvip2 radius4">
    <form>

        <php>
            $str = $orderinfo['need_money'] . C('SITECONFIG.MONEY_NAME');
            if ($egold_pay>0 && $userinfo['egold']>=$orderinfo['need_money']) {
            $str = $egold_pay . C('SITECONFIG.EMONEY_NAME');
            } else if ($egold_pay){
            $str = C('SITECONFIG.EMONEY_NAME').'抵' . $egold_pay . C('SITECONFIG.MONEY_NAME');
            }
        </php>


        <h1>订阅VIP章节</h1>
        <if condition="$userinfo"><p>{$userinfo.nickname}</p></if>
        <p>账户余额：<if condition="$userinfo['uid']">{$userinfo['money']}{:C('SITECONFIG.MONEY_NAME')}，{$userinfo['egold']}{:C('SITECONFIG.EMONEY_NAME')}
        <if condition = "$need_pay">
            <span>余额不足<a class="chongzhibtn radius4" onClick="go_url('Pay/index')">充值</a>
        </p></if>
        <else/>
        --
        <span>请登录<a class="chongzhibtn radius4" onClick="go_url('User/login')">登录</a>
        </if>
        <p>
        	<div class="vipchapter clearfix">
        	<php>
            	if($orderinfo['pl_star_chapter']){
            		$chpid = $orderinfo['pl_star_chapter']['chapterid'];
            	}else{
            		$chpid = $chapterinfo['chapterid'];
            	}
        	</php>
        	<span><a onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>1,'chpid'=>$chpid))}')" class="radius4 xuanzhong1">单章订阅</a></span>
        	<span><a onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>10,'chpid'=>$chpid))}')" class="radius4 xuanzhong10">后10章</a></span>
        	<span><a onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>40,'chpid'=>$chpid))}')" class="radius4 xuanzhong40">后40章</a></span>
        	<span><a onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>100,'chpid'=>$chpid))}')" class="radius4 xuanzhong100">后已发表章节</a></span>
        </div>
        <if condition="$orderinfo['need_money'] gt 0">
        <p>
        	<input name="checkbox" type="checkbox" id="dingyue" class="checkbox">自动购买未付费章节，不再提醒
        </p>
        <p class="cgray">在个人中心的“订阅设置”里可取消</p>
        <p class="mtop20 ccolor1" style="color:#476DC5">{$buy_detail_msg}</p>
        </if>
        <if condition = "$need_pay">
            <button type='button' class="vipbtn radius4" onClick="go_url('Pay/index')">余额不足，请充值</button>
            <else />

            <button type="button" class="vipbtn radius4" onclick="<if condition= "$orderinfo['order_count'] gt 1">
                    pl_submitBtn(this);
            <else />
           		submitBtn(this);
                </if>
                ">确认订阅({$str})</button>
            </if>
            </p>
    </form></div></div>

</div>
</block>
<block name="script">
<script type="text/javascript">
	$(function(){
            var plflag = '{$plnum|default=1}';
            plflag = parseInt(plflag);
            if(plflag>1){
                $('.xuanzhong'+plflag).addClass('active');
                $('#dingyue').removeAttr('checked');
                console.log(plflag);
            }else if(plflag=1){
                $('.xuanzhong'+plflag).addClass('active');
                $('#dingyue').attr('checked','checked');
            }
	})
</script>
<script>
var hg_bookName="{$bookinfo['catename']}";
var hg_bid="{$bookinfo.bid}";
var hg_baseUrl="__HOMEDOMAIN__";
<?php
	//批量订阅
	if($orderinfo['order_count']>1){
		$readurl = url('Book/read',array('bid'=>$bid,'chpid'=>$orderinfo['pl_star_chapter']['chapterid']));
?>
	function pl_submitBtn(obj){
	lockbutton(obj);
	var url="{:url('Bookajax/orderChapter')}";
	var url = hg_signUrl(url);
	//判断是否选择自动订阅
	var autobook = $(".checkbox").is(':checked');
	if(autobook){
    	var data = {
    			bid:{$bid},
	    		chpid:{$orderinfo['pl_star_chapter']['chapterid']},
	    		pl_num:{$orderinfo['order_count']},
	    		autoorder:"Y",
    	}
	}else{
		var data = {
	    		bid:{$bid},
	    		chpid:{$orderinfo['pl_star_chapter']['chapterid']},
	    		pl_num:{$orderinfo['order_count']},
	    }
	}
	$.ajax({
		url: url,
		type:"POST",
		data: data,
		dataType: 'json',
		success: function(json){

			if(json.status==1){
                doClient('salesOk', json.doClinetstr);
				if(json.message!=""){
					//hg_Toast(json.message);
				}
				//pl_unlockbutton(obj);
				//并发控制
			}else if (json.status==-1) {
				hg_Toast("处理中,请等待...");
			}else{
				hg_Toast(json.message);
				pl_unlockbutton(obj);
				if(json.url!=""){
					setTimeout(function () {
						window.location.href = json.url;
					},2000);
				}
			}
			pl_unlockbutton(obj);
		}
	});
}
<?php
	}elseif($orderinfo['order_count']==1 && $chapterinfo['chapterid']){
?>
function submitBtn(obj){
	lockbutton(obj);
	var url="{:url('Bookajax/orderChapter')}";
    url = hg_signUrl(url).replaceAll(' ', '+');
	var iswarn='N';
	if(document.getElementsByName("checkbox")[0].checked==true){
		iswarn='Y';
	}else{
		iswarn='N';
	}
	var data = {
		bid:{$bid},
		chpid:{$chapterinfo['chapterid']},
		autoorder:iswarn,
	}
	$.ajax({
		url: url,
		type: "POST",
		data: data,
		dataType: 'json',
		success: function(json){
			if(json.status==1){
                doClient('salesOk', json.doClinetstr);
				if(json.message!=""){
					//hg_Toast(json.message);
				}
				//unlockbutton(obj);
				//并发控制
			}else if (json.status==-1) {
				hg_Toast("处理中,请等待...");
			}else{
				hg_Toast(json.message);
                doClient(json.doClinetstr);
				unlockbutton(obj);
			}
		}
	});
}
<?php }?>
function lockbutton(obj) {
	$(obj).attr("onclick","");
	$(obj).html("请稍候…");
}

function unlockbutton(obj) {
	$(obj).attr("onclick","submitBtn(this);");
	$(obj).html("确认单章购买");
}
function pl_unlockbutton(obj) {
	$(obj).attr("onclick","pl_submitBtn(this);");
	$(obj).html("确认批量购买");
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	//页面底部中位置的点击统计
  	Do.ready('clicktracker');
    //懒加载
  	Do.ready('lazyload',function(){
  		Lazy.Load();
  		document.onscroll = function(){
  			Lazy.Load();
  		};
  	});
});

function go_url(gourl){
   var url=parseUrl('',gourl,'open//','do');
   doClient(url);
}
</script>
</block>
