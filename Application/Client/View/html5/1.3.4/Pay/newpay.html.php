<extend name="Common/base" />
<block name="style">
    <style>
        .mu2{ background-color: rgba(0,0,0,.5);position: fixed;;z-index: 998; top: 0;left:0;width:100%;height:100%;}
        .tk{ width:280px;position: fixed; margin: auto; left:50%;top:50%;margin-top: -90px; margin-left: -140px;z-index: 999; background-color: rgba(255,255,255,1);display:table-cell; vertical-align:middle;}
        .tk_btn a{ width:50%;float:left;display:inline-block;height:42px;line-height: 42px; text-align: center; font-size: 14px; color: #666;box-sizing:-moz-border-box;box-sizing:-webkit-border-box;box-sizing:border-box;}
        .tk_btn{ border-top:1px solid rgba(0,0,0,.1);width:100%;}
        .tk_btn a.closebtn{ border-right:1px solid rgba(0,0,0,.1);}
        /*.tk_btn a:hover{ background-color: rgba(0,0,0,.05); }*/
        .tk_btn a.zhifu{ color:#707fc0;}
        .tk_con{ padding:10px 15px; font-size: 14px; line-height: 20px; }
        .tk_con h2{ color:#707fc0;font-size:16px;padding:5px 0 10px 0;;width:100%;display:inline-block; text-align: center;}
        .tk_con p{ margin-bottom: 5px; line-height: 1.2em; color:#333;}
        .radius4{ -moz-border-radius: 4px; -webkit-border-radius: 4px;border-radius: 4px; }
    </style>
</block>
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
	            <foreach name='paylists' item='_pay' key='_paytype'>
                <li>
                    <div class="way radius4" zhekou="{$_pay.scale}" paytype='{$_paytype}'>
                        <div class="lf"><img lazy="y" src="__IMG__/{$_pay.logo}"/></div>
                        <div class="rt">
                            <h5>{$_pay.title}</h5>
                        </div>
                    </div>
                </li>
                </foreach>
            </ul>
        </div>
        <!--金额选择开始-->
        <div class="frame11 money clearfix">
            <ul>
	            <volist name='allow_money' id='_money'>
                <li><span class="radius4" id="{$_money}id" money="{$_money}">{$_money}元</span></li>
                </volist>
            </ul>
        </div>
        <!--金额选择结束-->
        <div class="cznum">
            <span id="czjine">充值{$money}元，获得</span>
            <b>3000{:C('SITECONFIG')['MONEY_NAME']}</b>
        </div>
        <button class="radius4" id="vipbtn">去支付</button>

        <form method="POST" target="_self" action="" id="payform" style="display:none">
            <input type="hidden" name="uid" value="{$userinfo.uid}">
            <input type="hidden" name='username' value='{$userinfo.username}'>
            <input type="hidden" name="siteid" value="{$siteid}">
            <input type="hidden" name="fu" id="fu" value="{$M_forward}">
            <input type="hidden" name="amount" id="amount" value="<?php echo $amount; ?>">
            <input type="hidden" name="payway" id="payway" value="0">
            <input type="submit">
        </form>
    </div>
    <!--充值方式结束-->
    <!--注释开始-->
    <div class="explain"><p>注：如果使用微信充值提示充值失败,请试用微信扫码支付,或使用支付宝充值</p></div>
    <!--注释结束-->

    <div class="mu2 weixin_show" style="display:none"></div>

    <div class="tk radius4 weixin_show" style="display:none">
        <div class="tk_con">
            <h2>支付确认</h2>
            <p>1、请在微信内完成支付，支付成功页面自动跳转</p>
            <p>2、如果您未支付，请点击“去支付”完成支付</p>
            <p>3、同一个订单号不能重复发起支付请求</p>
            <p>4、如果您未安装微信6.0.2版本及以上版本客户端，请先安装并登陆微信完成支付</p>
        </div>
        <p class="tk_btn"><a href="#" onclick="return hide_tk()" class="closebtn">关闭</a><a href="#" id="weixin_pay_url" class="zhifu">去支付</a></p>
    </div>

</block>
<block name="script">
    <script>
    	var paylists = {$paylists|json_encode};
        var cur_zhekou = 100;//当前选中充值的折扣;
        var cur_money = {$money};//当前选中充值金额;
        var cur_paytype = '<?php echo $is_wechat ? 'WEIXINPAY' : 'ALIPAY_WAP'; ?>';//当前选中充值类别
        function jsuan_hongshubi() {
            var hongshubi = parseInt(cur_zhekou) * parseInt(cur_money);
            $('#'+cur_money+'id').addClass('active');
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

            $('#fu').val(nn);
            $('#amount').val(cur_money);
            if(!cur_paytype || !paylists.hasOwnProperty(cur_paytype)) {
            	hg_Toast('请选择一个支付方式');
				endloading();
	            $('#vipbtn').removeAttr('disabled');
            	return false;
            }
            var _cnf = paylists[cur_paytype];
            if(_cnf.hasOwnProperty('third')) {
                $('#payway').val(_cnf.payway);
                var action = 'wap';
                if(cur_paytype=='WEIXINPAY_QRCODE') {
                    action = 'qrcode';
                } else if (cur_paytype=='WEIXINPAY') {
                    $("#vipbtn").removeAttr('disabled');
                    <if condition="$is_wechat">
                    action = 'wechatwap';
                    <else/>
                    return doIpayNowWeiXinPay();
                    </if>
                } else if (cur_paytype=='ALIPAY_WAP') {
                    <if condition="$is_wechat eq 1">
                        var uid='{$userinfo.uid}';
                        var username='{$userinfo.username}';
                        var fu='{$M_forward}';
                        var siteid='{:C('CLIENT.'.CLIENT_NAME.'.fromsiteid')}';
                        var type='third';
                        var url=parseUrl({zhekou:cur_zhekou,jine:cur_money,uid:uid,username:username,siteid:siteid,fu:fu,type:type},'Alipyhelp/AlipayFromWexin');
                        hg_gotoUrl(url);
                        return;
                    </if>
                }
                $('#payform').attr('action', '__PAYDOMAIN__/'+_cnf['third']+'/'+action+'.do');
            } else {
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
                    	$('#payform').attr('action', '__PAYDOMAIN__/alipaywap/alipaywap.do');
                    </if>
                } else if (cur_paytype == 'WEIXINPAY_QRCODE') {
                    $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwapqrcode.do');
                }
            }
            endloading();
            document.getElementById("payform").submit();
        }
        var timer = payid = 0;
        function doIpayNowWeiXinPay(){
            var url = '__PAYDOMAIN__/Nowpay/wap.do';
            var data = {
                uid:'{$userinfo.uid}',
                username:'{$userinfo.username}',
                fu:'{$M_forward}',
                siteid:'6',
                payway:13,
                amount:cur_money,
                iswechat:{$is_wechat|intval}
            };
            $.ajax({
                url: url,
                type:"GET",
                data: data,
                dataType: 'jsonp',
                jsonpCallback: 'openweixin',
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
        function openweixin(json){
            if(typeof(json)=='string') {
                json=JSON.parse(json);
            }
            if(json.hasOwnProperty('url')) {
                var url = json.url;
                payid = json.payid;
                $('#weixin_pay_url').attr('href', url);
                $('.weixin_show').show();
                setTimeout("window.location.href='"+url+"'", 1000);
                timer = setTimeout(function(){ startTime() }, 500);
            } else {
                hg_Toast('调用微信失败，请稍候再试！');
            }

        }
        function hide_tk(){
            var url = '{$M_forward}';
            if(!url) url = '/';
            window.location = url;
            $('.weixin_show').hide();
            return false;
        }
        function startTime() {
            $.ajax({
                url:"__PAYDOMAIN__/Nowpay/getOrderStatus",
                data:{payid: payid, ajax: 1},
                dataType:'jsonp',
                jsonpCallback: 'getOrderStatus',
                success:function(data){
                    if(data.status==1){
                        clearTimeout(timer);
                        hide_tk();
                    } else if (data.status==-1) {
                        clearTimeout(timer);
                        hide_tk();
                    }
                }
            });
            timer = setTimeout(function(){ startTime() }, 500);
        }
        function getOrderStatus(data) {
            if(data.status==1){
                clearTimeout(timer);
                hide_tk();
            } else if (data.status==-1) {
                clearTimeout(timer);
                hide_tk();
            }
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
//                            hg_Toast('fail:'+JSON.stringify(res));
                            $('#payform').attr('action', '__USERDOMAIN__/weixinpay/wechatwap.do');
                            document.getElementById("payform").submit();
                            return false;
                        }
                    });
                } else {
                    hg_Toast(json.message);
                }
            });
        }
    </script>
</block>