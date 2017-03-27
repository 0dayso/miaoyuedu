<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--动态开始-->
<div  class="unit mtop10">
    <div class="tit7">
        <if condition="$userinfo['islogin']">余额：<span id="money">{$userinfo['money']}</span>{:C('SITECONFIG.MONEY_NAME')}<span id="egold">{$userinfo.egold}</span>{:C('SITECONFIG.EMONEY_NAME')}<a href="{:url('Pay/index',array(),'do')}" class="cred mlf10">充值</a>
        <else/>
        <a href="{:url('User/login','','do')}" class="cred mlf10">登录</a>
        </if>
    </div>
    <div class="dscon clearfix">
        <ul>
         <foreach name="properties" item="row" key="k">
            <li onclick="$('.dsopen').hide();$('#r{$row.id}').show();" id="{$row.id}"><span class="dsdx">赏{$row.name}</span>
            <div class="dsopen empty{$k}" id="r{$row.id}" style="display:none;">
                <div class="dsopen01">
                    <button class="disable" id="re{$row.id}" onclick="change(0,'{$row.id}','{$row.price}','{$row.name}');">-</button>
                    <span class="dsnum" id="num{$row.id}">1</span>
                    <button id="ad{$row.id}" onclick="change(1,'{$row.id}','{$row.price}','{$row.name}');">+</button>
                    <span class="dshsb"><span id="jiage{$row.id}">{$row.price}</span>{:C('SITECONFIG.MONEY_NAME')}</span>
                </div>
                <div class="dsopen02"><textarea id="text{$row.id}">赏{$row.name}x1，望再接再厉，争取更大胜利!</textarea>
                    <button onclick="tijiao('{$row.id}');">赏了</button>
                </div>
            </div>
            </li>
        </foreach>
        <li onclick="$('.dsopen').hide();$('#xianhua').show();"><span class="dsdx">赏鲜花</span>
            <div class="dsopen" id="xianhua" style="display:none;">
                <div class=" dsopen01">
                    <button class="disable" onclick="xhchange(0);" id="rexh">-</button>
                    <span class="dsnum" id="dsxh">1</span>
                    <button id="adxh" onclick="xhchange(1);">+</button>
                    <span class="dshsb">还剩<span id="syxh">{$userinfo.tmp_flower}</span>朵鲜花</span>
                </div>
                <div class=" dsopen03">
                    <button id="xhtijiao">赏了</button>
                </div>
            </div>
            </li>
            <li onclick="$('.dsopen').hide();$('#hongpiao').show();"><span class="dsdx">赏红票</span>
            <div class="dsopen" id="hongpiao" style="display:none;">
                <div class=" dsopen01">
                    <button class="disable" onclick="hpchange(0);" id="rehp">-</button>
                    <span class="dsnum" id="dshp">0</span>
                    <button id="adhp" onclick="hpchange(1);">+</button>
                    <span class="dshsb">还剩<span id="syhp"></span>张红票</span>
                </div>
                <div class="dsopen03">
                    <button id="fabiao">赏了</button>
                </div>
            </div>
            </li>
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
</block>
<block name="script">
<script type="text/javascript">
var user = {
    uid:parseInt('{$userinfo.uid|intval}'),
	flower:parseInt('{$userinfo.tmp_flower|intval}'),
	red:parseInt('{$totalDayCount|intval}'),
};
function tijiao(id){
    if(!user.uid){
        hg_Toast('请先登录');
        return;
    }
    var length = $('#text'+id).val().length;
    if (length > 1000) {
        hg_Toast("评论字数要在1000以内哦！");
        return false;
    } else if (length <= 0) {
        hg_Toast("内容不能为空!");
        return false;
    }

    var url = "{:url('Bookajax/dashang','','do')}";
    var content = $('#text'+id).val();
    var num = $('#num'+id).html();
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
            //console.log(data);
            if (data.status == 1) {
                hg_Toast(data.message);
                history.go(0);
            } else {
                hg_Toast(data.message);
            }
        }
    });
}

function change(type,id,price,kind) {
    if(!user.uid){
        hg_Toast('请先登录');
        return;
    }
    var num = $('#num'+id).html();
    if (type == 1) {
        num++;
        $('#re'+id).removeClass('disable');
        $('#num'+id).html(num);
    } else if (type == 0) {
        if (num > 2) {
            num = num - 1;
        } else {
            num = 1;
            $('#re'+id).addClass('disable');
        }
        $('#num'+id).html(num);
    }
    var zongjia = price * num;
    $('#jiage'+id).html(zongjia);
    htmls = '赏' + kind + '×' + num + '，望再接再厉，争取更大胜利!';
    $('#text'+id).val(htmls);
}

function xhchange(type) {
    var num = $('#dsxh').html();
    var shuliang = user.flower;
    if (shuliang==0) {
        return;
    }
    if (type == 1) {
       if (num < shuliang - 1) {
                num++;
                $('#rexh').removeClass('disable');
                $('#adxh').removeClass('disable');
                $('#dsxh').html(num);
            } else if(num==shuliang - 1) {
                num = shuliang;
                $('#adxh').addClass('disable');
                $('#rexh').removeClass('disable');
                $('#dsxh').html(num);
            }
    } else if (type == 0) {
        if (num > 2) {
            num = num - 1;
            $('#adxh').removeClass('disable');
        } else {
            num = 1;
            $('#rexh').addClass('disable');
            $('#adxh').removeClass('disable');
        }
        $('#dsxh').html(num);
    }
    $('#syxh').html(shuliang-num);
}
function hpchange(type) {
    var num = $('#dshp').html();
    var shuliang = user.red;
    if(shuliang==0){
        return;
    }
    if (type == 1) {
       if (num < shuliang - 1) {
                num++;
                $('#rehp').removeClass('disable');
                $('#adhp').removeClass('disable');
                $('#dshp').html(num);
            } else if(num==shuliang - 1) {
                num = shuliang;
                $('#adhp').addClass('disable');
                $('#rehp').removeClass('disable');
                $('#dshp').html(num);
            }
    } else if (type == 0) {
        if (num > 1) {
            num = num - 1;
            $('#adhp').removeClass('disable');
        } else {
            num = 0;
            $('#rehp').addClass('disable');
            $('#adhp').removeClass('disable');
        }
        $('#dshp').html(num);
    }
    $('#syhp').html(shuliang-num);
}

$('#xhtijiao').on('click',function(){
    var num = $('#dsxh').html();
    var url = "{:url('Bookajax/sendFlower','','do')}";
    var data = {
        bid: parseInt('{$bookinfo['bid']|intval}'),
        num: num
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
})

$('#fabiao').on('click',function() {
    if(!user.uid){
        hg_Toast('请先登录');
        return;
    }
    var num = $('#dshp').html();
    if(num==0){
        hg_Toast('打赏数量不正确！');
        return;
    }
    /*var length = $('#texthp').val().length;
    if (length > 1000) {
        hg_Toast("评论字数要在1000以内哦！");
        return false;
    } else if (length <= 0) {
        hg_Toast("内容不能为空!");
        return false;
    }*/

    var url = "{:url('Bookajax/sendRedTicket','','do')}";
    var content = $('#texthp').val();
    var data = {
        bid: parseInt('{$bookinfo['bid']|intval}'),
        num: num
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

Do.ready('functions','common',function(){
   var xh=$('#syxh').html();
   if(xh>0){
    var xh=xh-1;
  }else{
    var xh=0;
  }
   
   $('#syxh').html(xh);
   $('#syhp').html(user.red);
   if(user.red==0){
     $('#adhp').addClass('disable');
   }
   $('.empty0').show();
});
</script>
</block>
