<extend name="Common/base" />
<block name="body">
<!--充值顶部开始-->
<div class="unit">
    <div class="pro">
        <p>充值账户：{$userinfo['nickname']}</p>

        <p>账户余额：{$userinfo.money}{:C('SITECONFIG.MONEY_NAME')}，
{$userinfo.egold}{:C('SITECONFIG.EMONEY_NAME')}({:C('SITECONFIG.EMONEY_NAME')}月底清零)
        </p>
    </div>
</div>
<!--充值顶部结束-->
<div class="czway">
    <div class="cztit borderbom">请选择充值额度</div>
    <!--金额选择开始-->
    <div class="frame11 money clearfix">
        <ul>
            <if condition="session('priv') neq ''">
            <li><div class="qb radius4" id="1id" money="1"><span class="span">1元测</span><p>70{:C('SITECONFIG.MONEY_NAME')}</p></div></li>
            </if>

            <li><div class="qb radius4 active" id="18id" money="18"><span class="span">18元</span><p>1260{:C('SITECONFIG.MONEY_NAME')}</p></div></li>
            <li><div class="qb radius4" id="30id" money="30"><span class="span">30元</span><p>2100{:C('SITECONFIG.MONEY_NAME')}</p></li>
            <li><div class="qb radius4" id="50id" money="50"><span class="span">50元</span><p>3500{:C('SITECONFIG.MONEY_NAME')}</p></li>
            <li><div class="qb radius4" id="98id" money="98"><span class="span">98元</span><p>6860{:C('SITECONFIG.MONEY_NAME')}</p></li>
            <li><div class="qb radius4" id="198id" money="198"><span class="span">198元</span><p>13860{:C('SITECONFIG.MONEY_NAME')}</p></li>
            <li><div class="qb radius4" id="518id" money="518"><span class="span">518元</span><p>36260{:C('SITECONFIG.MONEY_NAME')}</p></li>
        </ul>
    </div>
    <!--金额选择结束-->
   <button class="radius4" id="vipbtn">去支付</button>
</div>
<!--充值方式结束-->
<!--注释开始-->
<div class="explain"><p>提示：若充值出现有问题，请留言给<a href="{:url('Feedback/index')}" class="cpink">客服</a> </p></div>
<!--注释结束-->


</block>
<block name="script">
<script>
var cur_money = 18;//当前选中充值金额;

$(".qb").bind('click',function(){
  	$('.qb').removeClass('active');
  	cur_money = $(this).attr('money');
	$(this).addClass('active');
});

Do.ready('lazyload',function(){
	Lazy.Load();
	document.onscroll = function(){
		Lazy.Load();
	};
});

$("#vipbtn").bind('click',function(){
    do_applepay();
});
function do_applepay(){
	var payProductId = '{:C('SITECONFIG.APPLY_PAY_PREFIX')}money'+cur_money;
	var params = '{"payProductChannel":"1","payProductId":"'+payProductId+'"}';
	//hg_Toast('暂时不可用,充值功能正在升级,请稍后',3);
    Do.ready('functions', function(){
        doClient('payProduct', params);
    });
}


</script>
</block>