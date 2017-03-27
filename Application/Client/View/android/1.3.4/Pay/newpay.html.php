<extend name="Common/base" />
<block name="body">
    <!--充值顶部开始-->
    <style>.way h5 { line-height: 54px;}</style>
    <div class="unit">
        <div class="pro">
            <p>充值账户：{$userinfo.nickname}</p>
            <p>账户余额：{$userinfo.money}{:C('SITECONFIG.MONEY_NAME')}，
                {$userinfo.egold}{:C('SITECONFIG.EMONEY_NAME')}({:C('SITECONFIG.EMONEY_NAME')}月底清零)
            </p>
        </div>
    </div>
    <!--充值顶部结束-->
    <!--充值方式开始-->
    <div class="czway">
        <div class="cztit borderbom">请选择充值方式及额度</div>
        <div class="methods clearfix">
            <ul>
                <li>
                    <div class="way radius active" id="zfbid" zhekou="{:getPayConfig('ALIPAY')}" paytype='ALIPAY'>
                        <div class="lf"><img lazy="y" src="__IMG__/ic_zhifubao2.png"/></div>
                        <div class="rt">
                            <h5>支付宝支付</h5>
                        </div>
                    </div>
                </li>
                <li >
                    <div class="way radius4" id="wxid" zhekou="{:getPayConfig('WEIXINPAY_QRCODE')}" paytype='WEIXINPAY_QRCODE'>
                        <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                        <div class="rt">
                            <h5>微信扫码支付</h5>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--金额选择开始-->
        <div class="frame11 money clearfix">
            <ul>
                <if condition="session('priv') neq ''">
                    <li><span class="span2 radius4" id="1id" money="1">1元测</span></li>
                </if>
                <li><span class="span2 radius4" id="20id" money="20">20元</span></li>
                <li><span  class="span2 radius4 active" id="30id" money="30">30元</span></li>
                <li><span  class="span2 radius4" id="50id" money="50">50元</span></li>
                <li><span  class="span2 radius4" id="100id" money="100">100元</span></li>
                <li><span class="span2 radius4" id="500id" money="500">500元</span></li>
            </ul>
        </div>
        <!--金额选择结束-->
        <div class="cznum"><span id="jine">充值30元，获得</span> <b>3000{:C('SITECONFIG')['MONEY_NAME']}</b></div>

        <button class="radius4" id="vipbtn">去支付</button>

        <form method="POST" target="_self" action="{:url('Pay/Nowpay/wap')}" id="payform" style="display:none">
            <input type="hidden" name="uid" value="{$userinfo['uid']}">
            <input type="hidden" name='username' value='<?php echo session('username');?>'>
            <input type="hidden" name="siteid" value="{$siteid}">
            <input type="hidden" name="fu" value="{:url('Pay/Index',array('fu'=>$M_forward),'do')}">
            <input type="hidden" name="channel" id="channel" value="">
            <input type="hidden" name="payway" id="payway" value="0">
            <input type="hidden" name="amount" id="amount" value="<?php echo $amount;?>">
            <input type="submit">
        </form>
    </div>
    <!--充值方式结束-->
    <!--注释开始-->
    <div class="explain"><p>注：如果使用微信充值提示充值失败,请试用微信扫码支付,或使用支付宝充值</p></div>
    <!--注释结束-->

</block>
<block name="script">
    <script>

        var cur_zhekou = 100;//当前选中充值的折扣;
        var cur_money = 30;//当前选中充值金额;
        var cur_paytype = 'ALIPAY';//当前选中充值类别
        function jsuan_hongshubi(){
            $('#jine').html('充值'+cur_money+'元，获得');
            var hongshubi = parseInt(cur_zhekou)*parseInt(cur_money);
            $('.cznum b').html(hongshubi+'{:C('SITECONFIG')['MONEY_NAME']}');
        }

        $(".methods ul li div").bind('click',function(){
            $('div.methods ul li div').removeClass('active');
            cur_zhekou = $(this).attr('zhekou');
            cur_paytype = $(this).attr('paytype');
            $(this).addClass('active');
            jsuan_hongshubi();
        });

        $("div.frame11 ul li span").bind('click',function(){
            $('div.frame11 ul li span').removeClass('active');
            cur_money = $(this).attr('money');
            $(this).addClass('active');
            jsuan_hongshubi();
        });

        $(function(){
            jsuan_hongshubi();
        })
        Do.ready('lazyload',function(){
            Lazy.Load();
            document.onscroll = function(){
                Lazy.Load();
            };
        });
        var client_params = '';
        var client_channel = '';

        $("#vipbtn").bind('click',function(){
            var tmp = doClient('getParams');
            if (typeof (tmp) !== "undefined" && tmp != '' && tmp != null) {
                client_params = JSON.parse(tmp);
                console.log(client_params);
                if (client_params != '') {
                    client_channel = client_params.channel;
                }
            }
            pay_next_step();
        });
        function pay_next_step(){
            showloading(5);
            $('#channel').val(client_channel);
            $('#amount').val(cur_money);
            if(cur_paytype == 'WEIXINPAY'){
                $('#payway').val('13');
                $('#payform').attr('action', '__PAYDOMAIN__/Nowpay/wechatwap.do');
            } else if (cur_paytype == 'WEIXINPAY_QRCODE') {
                $('#payway').val('13');
                $('#payform').attr('action', '__PAYDOMAIN__/Nowpay/qrcode.do');
            }
            else if(cur_paytype == 'ALIPAY'){
                $('#payway').val('12');
                $('#payform').attr('action', '__PAYDOMAIN__/wapalipay/alipaywap.do');
            }
            else if (cur_paytype.substr(-4) == '_SDK') {
                var url = parseUrl({}, 'pay/'+cur_paytype.toLowerCase().replace('_', ''));
                url = hg_signUrl(url);

                $.ajax({
                    type:'post',
                    url: url,
                    data:{amount:cur_money},
                    success: function (data){
                        endloading();
                        if(data.status == 1){
                            doClient(data.command);
                        } else {
                            if(data.message) {
                                hg_Toast(data.message);
                            } else {
                                hg_Toast('系统调用出错！');
                            }
                        }
                    },
                    error: function(){
                        endloading();
                        hg_Toast('系统调用出错！');
                    }
                });
                return false;
            }
            else {
                endloading();
                return false;
            }
            document.getElementById("payform").submit();
            //endloading();
        }
    </script>
</block>