<extend name="Common/base" />
<block name="body">
<!--注册开始-->
<!--登录开始-->
<div class="login login2">
        <p class="yz2"><input type="text" placeholder="请输入手机号" class="radius4" id="input_user"/><span class="correct" id="c1"></span></p>
        <p class="yz" style="display:none;" id="sbm"><input type="text" placeholder="请输入识别码" class="radius4" id="input_sbm" /><span class="correct" id="c2"></span><a class="yzmimg radius4"><img src="{:url('User/imgcode')}" lazy='y' id='imgcode' onclick='return refreshimg();'/></a></p>
        <p class="yz" style="display:none;" id="yzm"><input type="text" id="input_pwd" placeholder="请输入密码" class="radius4"  /><span class="correct" id="c3"></span><button class="yzm radius4" id="yzbutton" onclick="register();">获取密码</button></p>
        <p id="button1"><button type="button" class="disable radius4" disabled="disabled" onclick="check();">下一步</button></p>
        <p id="button2" style="display:none;"><button type="button" class="disable radius4" onclick="checksbm();" disabled="disabled">下一步</button></p>
        <p id="button3" style="display:none;"><button id="zc" type="button" disabled="disabled" class="disable radius4" onclick="login();">注册</button></p>
</div>
</block>
<block name="script">
<script type="text/javascript">
    Do.ready('lazyload',function(){
    Lazy.Load();
    $('#input_user').on('keyup', function(){
       if (isPhone($(this).val())){
           $('#button1').find('button').removeAttr('disabled').removeClass('disable');
       } else {
           $('#button1').find('button').attr('disabled', true).addClass('disable');
       }
    });
    $('#input_sbm').on('keyup', function(){
       var length=$('#input_sbm').val().length;
       if(length==4){
           $('#button2').find('button').removeAttr('disabled').removeClass('disable');
       } else {
           $('#button2').find('button').attr('disabled', true).addClass('disable');
       }
    });
    $('#input_pwd').on('keyup', function(){
       var length=$('#input_pwd').val().length;
       if(length==6){
           $('#zc').removeAttr('disabled').removeClass('disable');
       } else {
           $('#zc').attr('disabled', true).addClass('disable');
       }
    });
    document.onscroll = function(){
        Lazy.Load();
    };
});

</script>
<script type="text/javascript">
function check(){
    var userName=$("#input_user").val();
    if(userName==""){
    hg_Toast('请输入手机号');
    $("#input_user").focus();
    return false;
   }
   if(!userName.match(/^1[3|4|5|8][0-9]\d{4,8}$/)){
     hg_Toast('手机号格式不正确');
    $("#input_user").focus();
    return false;
   }
   $('#sbm').show();
   $('#button1').hide();
   $('#button2').show();
   $('#c1').html('<img src="__IMG__/ic_correct.png" />');
}

function refreshimg(){
    var url = '{:url("User/imgcode")}';
    url+=url.indexOf('?')>0?'&':'?';
    url+=Math.random(10);
    $('#imgcode').attr('src', url);
}

function checksbm(){
    var sbm=$('#input_sbm').val();
   if (sbm.length<4) {
      hg_Toast('验证码不正确');
      return false;
   }
   var url='{:url('Userajax/checkimgcode')}'
   $.ajax({
        url: url,
        type: "POST",
        data: {sbm:sbm},
        success: function(data){
            if(data.status == 1){
               $('#button2').html('');
               $('#button3').show();
               $('#yzm').show();
               $('#c2').html('<img src="__IMG__/ic_correct.png" />');
            }else{
               refreshimg();
               hg_Toast(data.message);
            }
        }});
}
var intfffff='';
function register(obj){
  check();
   var userName=$("#input_user").val();
    var sbm=$('#input_sbm').val();
    if (!sbm) {
        hg_Toast('请输入识别码');
    }
    url='{:url('Userajax/verifyCode')}';
    $.ajax({
        url: url,
        type: "POST",
        data: {mobileId:userName,sbm:sbm},
        dataType: 'json',
        success: function(data){
            if(data.status == 1){
               hg_Toast(data.message);
               $('#yzbutton').html('60');
               $('#yzbutton').addClass('active');
               $('#yzbutton').attr('disabled', true);
               intfffff=setInterval("showtime()",1000);
            }else{
              hg_Toast(data.message);
            }
        }});
}

function showtime(){
   var num=$('#yzbutton').html();
    if (num>0) {
        num--;$('#yzbutton').html(num);
    }else{
        num=0;
        clearInterval(intfffff);
        $('#input_sbm').val('');
        $('#c2').html('');
        $('#yzbutton').removeClass('active');
        $('#yzbutton').removeAttr('disabled');
        $('#yzbutton').html('重新获取');
        refreshimg();
    }

}

var forward_url='{$M_forward}';
function login(obj){
    check();
    var userName=$("#input_user").val();
    var sbm=$('#input_sbm').val();
    var pwd=$("#input_pwd").val();
    lockbutton(obj);
    url='{:url('Userajax/saveRegister')}';
    $.ajax({
        url: url,
        type: "POST",
        data: {mobileId:userName,password:pwd,sbm,sbm},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
        },
        success: function(json){
            if (json.status == 1 && json.usercode != '') {
                doClient('{"Action":"saveP30","P30":"' + json.usercode + '","username":"' + json.nickname + '","uid":"' + json.uid + '","nickname":"' + json.nickname + '","viplevel":"' + json.viplevel + '","groupid":"' + json.groupid + '","avatar":"' + json.avatar + '","isauthor":"' + json.isauthor + '","money":"' + json.money + '","groupname":"' + json.groupname + '"}');
                if(forward_url==='webview'){
                    doChild('{:url('User/index')}');
                } else {
                    hg_gotoUrl(forward_url, json.usercode);
                }
            }
            else {
                doClient('Toast', '{"Message":"' + json.message + '","Showtime":2}');
            }
        }});
}

function lockbutton(obj) {
    $(obj).attr("onclick","");
    $(obj).html("请稍候…");
}

function unlockbutton(obj) {
    $(obj).attr("onclick","login(this)");
    $(obj).html("注册");
}

</script>
</block>