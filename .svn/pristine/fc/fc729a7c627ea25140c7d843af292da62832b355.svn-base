<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<include file="Common/head2"/>
        <div class="user1060" id="maodian">
            <div class="user960">
                <p class="c6">充值账户：{$userinfo.nickname}</p>
                <p class="c6">账户余额：<span class="cb">{$userinfo.money} </span> {:C('SITECONFIG.MONEY_NAME')}</p>
                <h2 class="top40">请选择充值方式及额度</h2>
                <div class="top20 empty">
                    <volist name='allow_pay' id='pay'>
                        <button class="zffs rt20" zhekou="{$pay|getPayConfig}" id='{$pay}' onclick="getPayActivity(this);">
                            <img src="__IMG__/{$pay|strtolower}.jpg"/>（1元={$pay|getPayConfig}{:C('SITECONFIG.MONEY_NAME')}）
                        </button>
                    </volist>
                </div>
                <div class="top30 bom30 empty2" id="list">
                </div>
                <p class="zfqr">您将支付<span class="cb" id="jine"> 0 </span>元人民币，获得<span class="cb zfqr_span"> 0 </span>{:C('SITECONFIG.MONEY_NAME')}</p>
                <button class="bcsz" style="margin: auto; display: block;" id="vipbtn">充值</button>
            </div>
        </div>
    </div>
</div>

<form method="POST" target="_self" action="" id="payform" style="display:none">
    <input type="hidden" id="payway" name="payway" value="<?php echo $is_wechat?'13':'12';?>">
    <input type="hidden" name="uid" value="{$userinfo.uid}">
    <input type="hidden" name='username' value='{$userinfo.username}'>
    <input type="hidden" name="siteid" value="{$siteid}">
    <input type="hidden" name="fu" value="{:C('ROOT_URL')}{:url('Pay/Index', '','do')}">
    <input type="hidden" name="amount" id="amount" value="<?php echo $amount;?>">
    <input type="submit">
</form>

</block>
<block name="script">
<script type="text/html" id="tpl_zfje">
    {{each allow_money as value}}
    <button onclick="changejine(this)" class="zfje rt20" id="id{{value}}" money="{{value}}" money_name="{{money_name}}" zhekou="{{zhekou}}"
    {{if give && give.hasOwnProperty(value)}}
     quan="{{give[value]['num']}}" quantype="{{give[value]['type']}}"
    {{/if}}
            >{{value}}元
            {{if give && give.hasOwnProperty(value)}}
			<div style="height:20px;font-size:10px;margin-top:15px;color:#ff577b">
				送{{give[value]['num']}}
				{{if give[value]['type']==2}}{{money_name}}{{else}}{:C('SITECONFIG')['EMONEY_NAME']}{{/if}}
			</div>
            {{/if}}
    </button>
    {{/each}}
</script>

<script>

var cur_zhekou = 0;                           //当前选中充值的折扣;
var cur_money = {$money|intval};                             //当前选中充值金额;
var cur_paytype = '{$pay}';                     //当前选中充值类别
var allow_money = [{$allow_money|implode=','}];
var emoney_name = '{:C('SITECONFIG')['EMONEY_NAME']}';

Do.ready('lazyload',function(){
	Lazy.Load();
    $('#{$pay}').click();
	document.onscroll = function(){
		Lazy.Load();
	};
});

function jsuan_hongshubi(){
	var hongshubi = parseInt(cur_zhekou)*parseInt(cur_money);
	$('.zfqr_span').html(hongshubi);
}

Do.ready('common','functions',function(){
    $("#vipbtn").bind('click',function(){
        pay_next_step();
    });
});


function pay_next_step(){
    showloading();

    $('#amount').val(cur_money);
    if(cur_paytype == 'ALIPAY'){
        $('#payform').attr('action', parseUrl({}, 'Pay/Alipay', 'do'));
    }
    else {
        endloading();
        hg_Toast('暂不支持！');
        return false;
    }
    document.getElementById("payform").submit();
    endloading();
}


function getPayActivity(obj){
    if(typeof(obj)==='object') {
        $('.zffs').removeClass('active');
        $(obj).addClass('active');
        cur_paytype = $(obj).attr('id');
        cur_zhekou = $(obj).attr('zhekou');
    }
    var url = "{:url('Payajax/getPayActivity','','do')}";
    $.ajax({
        type:'post',
        url: url,
        data:{paytype:cur_paytype},
        success: function (data){
            cur_money = 30;
            if(typeof(data)!='object') {
                data = {};
            }
            data.allow_money = allow_money;
            Do.ready('template',function(){
                data.money_name = '{:C('SITECONFIG.MONEY_NAME')}';
                htmls=template('tpl_zfje',data);
                $('#list').html(htmls);
                changejine($('#list').find('#id'+cur_money));
            });
        }
    })
}
function changejine(obj){
    $('.zfje').removeClass('active');
    cur_money = $(obj).attr('money');
    money_name = $(obj).attr('money_name');
    quan=$(obj).attr('quan');
    quantype=$(obj).attr('quantype');
    $(obj).addClass('active');
    $('#jine').html(cur_money);
    if(quan>0){
        $('#daijinquan').html('+'+quan+(quantype==2?money_name:emoney_name))
    }else{
        $('#daijinquan').html('');
    }
	var hongshubi = parseInt(cur_zhekou)*parseInt(cur_money);
	$('.zfqr_span').html(hongshubi);
}

</script>
<include file="Common/foot2" />
</block>