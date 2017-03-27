<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
    <style>
        .exchange{padding:20px 0; margin-top:10px;width:100%;}
        .exchangediv{display:inline-block; text-align: center;width:100%;}
        .exchange img{width:100px; height:100px;}
        button.exchangelist{width:100px;height:36px;line-height:36px;background-color:#fcfcfc;font-size:14px;color:#555;border:1px solid #ccc;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;color:#333;}
        .pic{text-align: center;width:100%;}
        .pic img{width:100px; height:100px; margin-bottom:20px;}
        .pic p{width:100%; font-size:14px;color:#333;}
        .pic p.picnum{font-size:36px;line-height:48px;font-weight:600;}
        .ts{padding:20px 15px;width:100%;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;text-align:left;}
        .ts p{font-size:12px;color:#666; line-height:16px; margin-bottom:0;}
        .ictop img {width: 17px;height: 17px;display: inline-block;float: left;
}
    </style>
<div id="card">
    <div class="exchange">
        <div class="exchangediv" >
            <img src="__IMG__/ic_redeem.png" />
        </div>
        <div class="exchangediv" onclick="hg_gotoUrl('{:url('User/cardrecord')}')"><button class="exchangelist">兑换记录</button></div>
    </div>

    <div class="login">
            <p><input id="text" type="text" placeholder="请输入您获得的红薯卡兑换码" class="radius4" /></p>
            <p><button type="button" class="radius4" onclick="submit()">立即兑换</button></p>
    </div>
    <div class="ts">
        <p>1，红薯卡是优惠码，您可以通过兑换获得不等的红薯银币；</p>
        <p>2，红薯卡有兑换期限，仅限可兑换月份兑换，过期作废，未到兑换月份也不可兑换；</p>
        <p>3，红薯卡兑换得的红薯银币仅限当月使用，每月底自动清零。</p>
    </div>
</div>
<div id="result" style="display:none;">
    <div class="exchange">
        <div class="pic" >
            <img src="__IMG__/ic_right.png" />
        <div>
        <div class="pic">
            <p>恭喜您获得</p><p class="picnum" id="hsb"></p><p>红薯银币</p>
        </div>
    </div>
    <div class="ts">
        <p>您获得的红薯币已存入您的账户</p>
        <p>（查看<a href="{:url('User/index','','do')}" style="color:blue;text-decoration:underline;">我的账户</a>）</p>
        <p>红薯币有效期至本月底，感谢使用。</p>
    </div>
</div>
</block>
<block name="script">
<script type="text/javascript">
    function submit(){
        var card=$('#text').val();
        var url = '{:url("Userajax/changecard","","do")}';
        if(!card){
            hg_Toast('请输入您获得的红薯卡兑换码');
            return;
        }
        $.ajax({
        url: url,
        type: "POST",
        data: {card:card},
        success: function(json){
            if(json.status==1){
                $('#card').hide();
                $('#result').css('display','block');
                $('#hsb').html(json.amount);
            }
            else {
                hg_Toast(json.message);
            }
        }});
    }
</script>
</block>