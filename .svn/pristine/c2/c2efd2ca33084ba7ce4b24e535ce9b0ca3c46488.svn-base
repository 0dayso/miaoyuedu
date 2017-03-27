<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--动态开始-->
<div  class="unit mtop10">
    <div class="tit7">
        <if condition="$userinfo['islogin']">余额：<span id="money">{$userinfo.money}</span>{:C('SITECONFIG.MONEY_NAME')}<span id="egold">{$userinfo.egold}</span>{:C('SITECONFIG.EMONEY_NAME')}<a href="javascript:hg_gotoUrl('{:url('Pay/payform')}');" class="cred mlf10">充值</a>
        <else/>
        <a href="javascript:hg_gotoUrl('{:url('User/login')}');" class="cred mlf10">登录</a>
        </if>
    </div>
    <div class="dscon clearfix">
        <ul>
         <foreach name="properties" item="row">
            <li class="empty" jiage="{$row.price}" id="{$row.id}"><img src="__IMG__/{$row.img}" /><span>赏{$row.name}</span></li>
        </foreach>
        </ul>
    </div>
    <div class="dscon borderbom clearfix">
        <ul>
            <li class="empty1"><img src="__IMG__/ic_rose.png" /><span>赏鲜花</span> </li>
            <li class="empty1"><img src="__IMG__/ic_ticket.png" /><span>赏红票</span> </li>
        </ul>
    </div>
    <div class="tit6">最高赞赏榜</div>
    <div class="dsdt">
        <ul>
        <foreach name="proListPrice" item="row">
            <li><div class="lf hidden">{$row.nickname}</div><div class="rt hidden">{$row.name}×{$row.num}</div> </li>
        </foreach>
        </ul>
    </div>
    <div class="tit6 ">最近赞赏动态</div>
    <div class="dsdt">
        <ul>
        <foreach name="proListTime" item="row">
            <li><div class="lf hidden">{$row.nickname}</div><div class="rt hidden">{$row.name}×{$row.num}</div> </li>
        </foreach>
        </ul>
    </div>
</div>
<!--动态结束-->
<!--弹出内容开始-->
<div id="dstk1" class="dstk" style="display:none;">
    <div class="dstkcon">
        <div class="dstkcontop">
            <a class="close" onclick="$('#dstk1').hide();"></a>
            <div id="a1" class="dstkcontop01"><img src="__IMG__/avater.jpg"/><p>赏红包</p></div>
            <div id="a2" class="dstkcontop02 mtop10"><h1><span id="name1">红包</span>×<span id="num1">1</h1><p><span id="jine">600</span>{:C('SITECONFIG.MONEY_NAME')}</p></div>
            <div class="dstkcontop03 mtop10"><button class="reduce radius4" onclick="change(0);">减少</button><button class="add radius4" onclick="change(1);">增加</button></div>
        </div>
        <!--发表评论开始-->

        <div class="commentbd mtop10 clearfix">
                <p><a href="javascript:void(0);" onClick="hg_gotoUrl('{:url('User/login')}')" class="flrt cred">登录</a></p>
                <textarea id="text1" class="comcon radius4" onkeyup="initcheck();"></textarea>
                <p class="commentbdp"><span><em id="num3">0</em>/{:C('COMMENTMAXSIZE')}</span></p>
                <button type="button" class="radius4 <if condition='$userinfo[islogin]'>active<else/>disable</if>" id="tijiao">确定</button>
        </div>

        <!--发表评论结束-->
    </div>
</div>
<!--弹出内容结束-->
<!--弹出内容开始-->
<div id="dstk2" class="dstk" style="display:none;">
    <div class="dstkcon">
        <div class="dstkcontop">
            <a class="close" onclick="$('#dstk2').hide();"></a>
            <div id="b1" class="dstkcontop01"><img src="__IMG__/avater.jpg"  /><p>赏鲜花</p></div>
            <div id="b1" class="dstkcontop02 mtop10"><h1><span id="name2">鲜花</span>×<span id="num2">1</h1><p>你还剩<span id="xh"></span></p></div>
            <div class="dstkcontop03 mtop10"><button class="reduce dis radius4" onclick="change1(0);">减少</button><button class="add radius4" onclick="change1(1);">增加</button></div>
        </div>
        <!--发表评论开始-->

        <div class="commentbd mtop10 clearfix">
                <textarea id="text2" class="comcon radius4" onkeyup="initcheck1();"></textarea>
                <p class="commentbdp"><span><em id="num4">0</em>/{:C('COMMENTMAXSIZE')}</span></p>
                <button type="button" class="radius4" id="fabiao">确定</button>
        </div>

        <!--发表评论结束-->
    </div>
</div>
<!--弹出内容结束-->
</block>
<block name="script">
<script type="text/javascript">
var user = {
        uid:parseInt('{$userinfo.uid|intval}'),
	flower:parseInt('{$userinfo.tmp_flower|intval}'),
	red:parseInt('{$totalDayCount|intval}'),
};
Do.ready('template',function(){
  $('#text1').on('keydown', function(){
    var length=$('#text1').val().length;
       if (length==0){
           $('#tijiao').attr('disabled', true).addClass('disable');
       } else {
           $('#tijiao').removeAttr('disabled').removeClass('disable');
       }
});
$('#text2').on('key', function(){
    var length=$('#text2').val().length;
       if (length==0){
           $('#fabiao').attr('disabled', true).addClass('disable');
       } else {
           $('#fabiao').removeAttr('disabled').removeClass('disable');
       }
});
});
$('.empty').on('click', function() {
    $('#num1').html(1);
    $('#dstk1').find('.reduce').removeClass('dis');
    $('#dstk1').find('.add').removeClass('dis');



    var lei = $(this).find('span').html();
    var src = $(this).find('img').attr('src');
    kind = lei.substring(1, 3);
    var num = parseInt($('#num1').html());
    jiage = $(this).attr('jiage');
    id = parseInt($(this).attr('id'));

    $('#dstk1').show();
    $('#a1').find('img').attr('src', src);
    $('#a1').find('p').html(lei);



    $('#name1').html(kind);
    var zongjia = jiage * num;
    $('#jine').html(zongjia);
    htmls = '赏' + kind + '×' + num + '，望再接再厉，争取更大胜利!';
    $('#text1').val(htmls);
});

$('.empty1').on('click', function() {
    var lei = $(this).find('span').html();
    var src = $(this).find('img').attr('src');
    kind = lei.substring(1, 3);
    var num = parseInt($('#num2').html());
    $('#num2').html(1);
    $('#dstk2').find('.reduce').removeClass('dis');
    $('#dstk2').find('.add').removeClass('dis');
    if(!user.uid){
        hg_Toast('请先登录！');
        return false;
    }
    if (kind == "鲜花") {
        if(user.flower<1){
            hg_Toast('您的鲜花不足！');
            return false;
        }
        $('#xh').html(user.flower+'朵鲜花');
        if (user.flower==1) {
        $('#dstk2').find('.add').addClass('dis');
    }
    } else {
        if(user.red<1){
            hg_Toast('您的红票不足！');
            return false;
        }
        $('#xh').html(user.red+'张红票');
        if (user.red==1) {
        $('#dstk2').find('.add').addClass('dis');
    }
    }



    $('#dstk2').show();
    $('#b1').find('img').attr('src', src);
    $('#b1').find('p').html(lei);
    $('#name2').html(kind);
    htmls = '赏' + kind + '×1，望再接再厉，争取更大胜利!';
    $('#text2').val(htmls);
});


function initcheck() {
    //$('#tijiao').removeAttr('disabled');
    var length = $('#text1').val().length;
    $('#num3').html(length);
}
//检查字数
function checknum(shu) {
  if (shu==1) {
    var length = $('#text1').val().length;
    $('#num3').html(length);
  }else{
    var length = $('#text2').val().length;
    $('#num4').html(length);
  }
    if (length > 1000) {
        hg_Toast("评论字数要在1000以内哦！");
        return false;
    } else if (length <= 0) {
        hg_Toast("内容不能为空!");
        return false;
    }
    return true;
}
$('#tijiao').on('click',function() {
    if(!user.uid){
        $('#tijiao').attr('disabled', true);
    }
    checknum(1);
    var url = "{:url('Bookajax/dashang')}";
    var content = $('#text1').val();
    var num = $('#num1').html();
    var data = {
        bid: parseInt('{$bookinfo['bid']|intval}'),
        content: content,
        num: num,
        pid: id,
        sexflag: '{$sex_num}'
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        success: function(data) {
            if (data.status == 1) {
                hg_Toast(data.message);
                history.go(0);
            } else {
                hg_Toast(data.message);
            }
        }
    });
});

function change(type) {
    if(!user.uid){
        hg_Toast('请先登录');
        $('#dstk1').find('.add').addClass('dis');
        return;
    }
    var num = $('#num1').html();
    if (type == 1) {
        num++;
        $('#dstk1').find('.reduce').removeClass('dis');
        $('#dstk1').find('.add').removeClass('dis');
        $('#num1').html(num);
    } else if (type == 0) {
        if (num > 1) {
            num = num - 1;
        } else {
            num = 0;
            $('#dstk1').find('.reduce').addClass('dis');
            $('#dstk1').find('.add').removeClass('dis');
        }
        $('#num1').html(num);
    }
    var zongjia = jiage * num;
    $('#jine').html(zongjia);
    htmls = '赏' + kind + '×' + num + '，望再接再厉，争取更大胜利!';
    $('#text1').val(htmls);
}

function change1(type) {
    var num = $('#num2').html();
    if (type == 1) {
        fangshi = $('#name2').html();
        if (fangshi == "鲜花") {
            var shuliang = user.flower;
        } else {
            var shuliang = user.red;
        }
       if (num < shuliang - 1) {
                num++;
                $('#dstk2').find('.reduce').removeClass('dis');
                $('#dstk2').find('.add').removeClass('dis');
                $('#num2').html(num);
            } else if(num==shuliang - 1) {
                num = shuliang;
                $('#dstk2').find('.add').addClass('dis');
                $('#dstk2').find('.reduce').removeClass('dis');
                $('#num2').html(num);
            }

    } else if (type == 0) {
        if (num > 1) {
            num = num - 1;
            $('#dstk2').find('.add').removeClass('dis');
        } else if(num==1) {
            num = 0;
            $('#dstk2').find('.reduce').addClass('dis');
            $('#dstk2').find('.add').removeClass('dis');
        }
        $('#num2').html(num);
    }
    htmls = '赏' + kind + '×' + num + '，望再接再厉，争取更大胜利!';
    $('#text2').val(htmls);
}
function initcheck1() {
    //$('#tijiao').removeAttr('disabled');
    var length = $('#text2').val().length;
    $('#num4').html(length);
}
$('#fabiao').on('click',function() {
    checknum(2);
    var zhonglei=$('#name2').html();
    if (zhonglei=='鲜花') {
        var url = "{:url('Bookajax/sendFlower')}";
    }else{
        var url = "{:url('Bookajax/sendRedTicket')}";
    }

    var content = $('#text2').val();
    var num = $('#num2').html();
    var data = {
        bid: parseInt('{$bookinfo['bid']|intval}'),
        content: content,
        num: num
    }
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        success: function(data) {
            if (data.status == 1) {
                hg_Toast(data.message);
            } else {
                hg_Toast(data.message);
            }
        }
    });
});
Do.ready('common', function(){
    UserManager.addListener(function(user){
        //这里判断user.islogin用来处理登录后的事件
        if(user.islogin){
            $('#money').html(user.money);
            $('egold').html(user.egold);
        } else {
            $('#money').html('0');
            $('egold').html('0');
        }
    });
});
</script>
</block>
