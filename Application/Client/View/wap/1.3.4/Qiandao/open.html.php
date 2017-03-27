<include file="Qiandao/head" />
<body class="bodybg2">
    <div class="hb2">
        <div class="hb2headercon"><span><img src="__IMG__/avater.jpg"/></span>

            <h1>红薯阅读</h1></div>
        <div class="hb2header">
            <div class="hb2headercon2">
                <div class="wenzi2">
                    <p>恭喜，您抢到</p>

                    <p class="red" style="font-size:36px;font-weight: bold;"> {$value}</p>

                    <p style="color:#cb2123;">{:C('SITECONFIG.EMONEY_NAME')}</p>
                </div>
                <span>{$premsg}</span>
                <div class="wenzi3" style="padding: 0 0 10px 0;">
                    <p style="font-size: 14px;line-height: 24px;">银币已塞入您的账户，当月有效</p>
                    <if condition="$last_read_url">
                        <p style="font-size: 14px;line-height: 24px;"><a href="{$last_read_url}">使用银币，继续阅读</a></p>
                        <else />
                        <p style="font-size: 14px;line-height: 24px;"><a href="{:url('User/index')}">查看我的账户</a></p>
                    </if>
                </div>
                <div class="wenzi3"  >
                    <img  src="__IMG__/yqk.jpg" style="max-width: 30%;" />
                    <p style="font-size:12px; font-weight: bold; color: red;">长按二维码关注，下次签到更方便</p>
                </div>
            </div>
        </div>


    </div>
<include file="Qiandao/booklist" />
</body>
</html>