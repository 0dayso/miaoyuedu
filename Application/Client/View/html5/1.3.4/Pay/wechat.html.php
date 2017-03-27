<extend name="Common/base" />
<block name="header">
    <include file="Common/head2" />
</block>
<block name="body">
<style>.way h5 { line-height: 54px;}</style>

    <!--充值顶部开始-->
    <div class="unit">
        <div class="pro">
            <p>充值账户：{$userinfo.nickname}</p>
            <p>账户余额：{$userinfo.money}{:C('SITECONFIG.MONEY_NAME')}
            </p>
        </div>
    </div>
    <!--充值顶部结束-->
    <!--充值方式开始-->
    <div class="czway">
        <div class="cztit borderbom">请选择充值方式及额度</div>
        <div class="methods clearfix">
            <ul>
                <if condition="$is_wechat eq 1">
                    <if condition="$is_use_wechatqrcode eq 1">
                        <!--渠道用户-->
                        <li>
                            <div class="way radius4" id="wxid" zhekou="{:getPayConfig('WEIXINPAY_QRCODE')}" paytype='WEIXINPAY_QRCODE'>
                                <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                                <div class="rt">
                                    <h5>微信扫码支付</h5>
                                    </div>
                            </div>
                        </li>
                        <li >
                            <div class="way radius4 active" id="wxid" zhekou="{:getPayConfig('WEIXINPAY')}" paytype='WEIXINPAY'>
                                <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                                <div class="rt">
                                    <h5>微信支付</h5>

                                </div>
                            </div>
                        </li>
                    <else/>
                        <li >
                            <div class="way radius4 active" id="wxid" zhekou="{:getPayConfig('WEIXINPAY')}" paytype='WEIXINPAY'>
                                <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                                <div class="rt">
                                    <h5>微信支付</h5>

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="way radius4" id="wxid" zhekou="{:getPayConfig('WEIXINPAY_QRCODE')}" paytype='WEIXINPAY_QRCODE'>
                                <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                                <div class="rt">
                                    <h5>微信扫码支付</h5>
                                    </div>
                            </div>
                        </li>
                    </if>
                    <li>
                        <div class="way radius4" id="zfbid" zhekou="{:getPayConfig('ALIPAY_WAP')}" paytype='ALIPAY_WAP'>
                            <div class="lf"><img lazy="y" src="__IMG__/ic_zhifubao2.png"/></div>
                            <div class="rt">
                                <h5>支付宝支付</h5>
                                </div>
                        </div>
                    </li>
                <else/>
                    <li>
                        <div class="way radius4 active" id="zfbid" zhekou="{:getPayConfig('ALIPAY_WAP')}" paytype='ALIPAY_WAP'>
                            <div class="lf"><img lazy="y" src="__IMG__/ic_zhifubao2.png"/></div>
                            <div class="rt">
                                <h5>支付宝支付</h5>

                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="way radius4" id="wxid" zhekou="{:getPayConfig('WEIXINPAY_QRCODE')}" paytype='WEIXINPAY_QRCODE'>
                            <div class="lf"><img lazy="y" src="__IMG__/ic_weixin.png"/></div>
                            <div class="rt">
                                <h5>微信扫码支付</h5>

                            </div>
                        </div>
                    </li>
                </if>
                <!-- <li>
                    <div class="way radius4" id="cftid" zhekou="{:getPayConfig('TENPAY_WAP')}" paytype='TENPAY_WAP'>
                        <div class="lf"><img lazy="y" src="__IMG__/ic_cft.jpg"></div>
                        <div class="rt">
                            <h5>手机财付通</h5>
                            <p>1元={:getPayConfig('TENPAY_WAP')}{:C('SITECONFIG')['MONEY_NAME']}</p>
                        </div>
                    </span>
                </li> -->
            </ul>
        </div>
        <!--金额选择开始-->
        <div class="frame11 money clearfix">
            <ul>
                <if condition="session('priv') neq ''">
                    <li><span class="radius4" id="1id" money="1">1元测</span></li>
                </if>

                <li><span class="radius4" id="20id" money="20">20元</span></li>
                <li><span  class="radius4 active" id="30id" money="30">30元</span></li>
                <li><span  class="radius4" id="50id" money="50">50元</span></li>
                <li><span  class="radius4" id="100id" money="100">100元</span></li>
                <li><span class="radius4" id="500id" money="500">500元</span></li>
            </ul>
        </div>
        <!--金额选择结束-->
        <div class="cznum">
            <span id="czjine">充值30元，获得</span>
            <b>3000{:C('SITECONFIG')['MONEY_NAME']}</b>
        </div>
        <button class="radius4" id="vipbtn">去支付</button>

        <form method="POST" target="_self" action="" id="payform" style="display:none">
            <input type="hidden" name="uid" value="{$userinfo.uid}">
            <input type="hidden" name='username' value='{$userinfo.username}'>
            <input type="hidden" name="siteid" value="{$siteid}">
            <input type="hidden" name="fu" id="fu" value="{$M_forward}">
            <input type="hidden" name="amount" id="amount" value="<?php echo $amount; ?>">
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
        var cur_paytype = '<?php echo $is_wechat ? 'WEIXINPAY' : 'ALIPAY_WAP'; ?>';//当前选中充值类别
        function jsuan_hongshubi() {
            var hongshubi = parseInt(cur_zhekou) * parseInt(cur_money);
            $('.cznum b').html(hongshubi + '{:C('SITECONFIG')['MONEY_NAME']}');
        }


        $(".methods ul li div").bind('click', function () {
            $('div.methods ul li div').removeClass('active');
            cur_zhekou = $(this).attr('zhekou');
            cur_paytype = $(this).attr('paytype');
            $(this).addClass('active');
            jsuan_hongshubi();
        });

        $("div.frame11 ul li span").bind('click', function () {
            $('div.frame11 ul li span').removeClass('active');
            cur_money = $(this).attr('money');
            $(this).addClass('active');
            $('#czjine').html('充值'+cur_money+'元，获得')
            jsuan_hongshubi();
        });

        $(function () {
            $('.methods ul li').first().find('div').click();
            //jsuan_hongshubi();
        })

        Do.ready('lazyload', function () {
            Lazy.Load();
            document.onscroll = function () {
                Lazy.Load();
            };
        });

        $("#vipbtn").bind('click', function () {
            $(this).attr('disabled',true);
            pay_next_step();
        });
        function pay_next_step() {
            showloading();
            var nn="{$M_forward}";
            var openid="{$openid}";
            var string=nn.toLowerCase();
            /*if(!(string.indexOf('/buyviplist')>0)){
                var nn="__MOBDOMAIN__{:url('User/index')}";
            }*/
            $('#fu').val(nn);
            $('#amount').val(cur_money);
            if (cur_paytype == 'WEIXINPAY') {
                if(openid){
                    $("#vipbtn").removeAttr('disabled');
                    return doWeiXinPay();
                } else {
                    $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwap.do');
                }
            } else if (cur_paytype == 'ALIPAY_WAP') {
                <if condition="$is_wechat eq 1">
                var uid='{$userinfo.uid}';
                var username='{$userinfo.username}';
                var fu='{$M_forward}';
                var siteid='{:C('CLIENT.'.CLIENT_NAME.'.fromsiteid')}';
                var url=parseUrl({zhekou:cur_zhekou,jine:cur_money,uid:uid,username:username,siteid:siteid,fu:fu},'Alipyhelp/AlipayFromWexin');
                hg_gotoUrl(url);
                return;
                <else/>
                $('#payform').attr('action', '__PAYDOMAIN__/alipaywap/alipaywap.do');</if>
            } else if (cur_paytype == 'WEIXINPAY_QRCODE') {
                $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwapqrcode.do');
            }
            else if (cur_paytype == 'TENPAY_WAP') {
                $('#payform').attr('action', '__PAYDOMAIN__/tenpaywap/tenpaywap.do');
            }
            document.getElementById("payform").submit();
            endloading();
        }
        function doWeiXinPay(){
            var url = '__USERDOMAIN__/weixinpay/createorder.do';
            var data = {
                uid:'{$userinfo.uid}',
                username:'{$userinfo.username}',
                fu:'{$M_forward}',
                siteid:'{:C('CLIENT.'.CLIENT_NAME.'.fromsiteid')}',
                amount:cur_money,
                openid:'{$openid}'
            };
            $.ajax({
                url: url,
                type:"GET",
                data: data,
                dataType: 'jsonp',
                jsonpCallback: 'ordercallback',
                complete: function(){
                    endloading();
                },
                success: function(json){
                },
                error: function(json) {
                }
            });
            return false;
        }

        function ordercallback(json) {
            wx.ready(function(){
                if(json.status==1) {
                    wx.chooseWXPay({
                        timestamp: json.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                        nonceStr: json.nonceStr, // 支付签名随机串，不长于 32 位
                        package: json.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                        signType: json.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                        paySign: json.paySign, // 支付签名
                        success: function (res) {
                            // 支付成功后的回调函数

                            if(res.err_msg.toLowerCase().indexOf('ok')!==false) {
                                hg_Toast('充值成功！', 3);
                                location.reload();
                            }else{
                                hg_Toast('微信授权失败，请重试或者换一种充值方式');
//                                hg_Toast(JSON.stringify(res), 5);
//                                $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwap.do');
//                                document.getElementById("payform").submit();
                                return false;
                            }
                        },
                        cancel: function(res) {
                            hg_Toast('您取消了支付！');
                        },
                        fail: function(res) {
                            hg_Toast('fail:'+JSON.stringify(res), 5);
//                            $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwap.do');
//                            document.getElementById("payform").submit();
                            return false;
                        }
                    });
                } else {
                    hg_Toast(json.message);
                }
            });
        }
        wx.error(function (res) {
            hg_Toast('error:'+JSON.stringify(res), 5);
        });
    </script>
</block>