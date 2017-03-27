
<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<!--主体-->
<div class="user top25">
  <div class="user1200 top10 ">
    <ul class="user_kazu">
        <li class="user_ka rt16" onclick="hg_gotoUrl('{:url('User/login')}')"><a href="{:url('User/login')}">登录</a></li>
        <li class="user_ka rt16 active" onclick="hg_gotoUrl('{:url('User/register')}')"><a href="{:url('User/register')}">注册</a></li>
        <li class="user_ka flrt" onclick="hg_gotoUrl('{:url('User/authorreg')}')"><a href="{:url('User/authorreg')}">注册为萌神</a></li>
        
    </ul>
    <div class="user1060">
      <div class="login960">
        
        <div class="form-warp clearfix bom30">
              <div class="form-tit3 bom20 clearfix top20 c6">
                  <h3>免费注册元气萌，开启不一样的世界</h3>
              </div>
              <p class="clearfix">
                <input id="input_user" class="" type="text" name="username" placeholder="账号名" autocomplete="off" id="" value="" onkeyup="check();">
                <span id="ex1" class="explain" category="">*长度为2到15位的中英文，数字</span>
                <span id="true1" class="explain correct" category="" style="display:none;"></span>
            </p>
            <div id="wrong1" class="wrong" style="display:inline-block;"></div>
            <!-- <p class="clearfix">
                <input id="input_phone" class="" type="text" name="param" placeholder="注册手机号或电子邮箱" autocomplete="off" id="" value="" onkeyup="check();">
                 <span id="ex2" category="pemailTip" class="explain">*手机号或电子邮箱</span>
                 <span id="true2" category="pemailRight" class="explain correct" style="display:none"></span></p>
            <div id="wrong2" class="wrong" style="display:inline-block;"></div> -->
            <p class="passwordp clearfix">
                <a onclick="showpwd();" class="eye-open"></a>
                <input class="password " type="password" name="password" maxlength="20" placeholder="密码(不少于6位)" id="input_pwd" value="" onkeyup="check();">
                <span id="ex3" category="" class="explain">*6-15个大小写英文字母、数字,符号</span>
                <span id="true3" category="" class="explain correct" style="display:none"></span>
              </p>
            <div id="wrong3" class="wrong" style="display:inline-block;"></div>
            <p class="code clearfix">
                <input class="codeword " type="text" name="validcode" maxlength="4" placeholder="请输入验证码" id="input_yzm" value="" onkeyup="check();">
                <a class="codeimg lf20" href="javascript:refreshimg()" title="看不清楚？点击换一张">
                  <img id="imgcode" src="{:url('User/imgcode')}" style="cursor:pointer;" lazy="y">
                </a>
            <div id="wrong4" class="wrong" style="display:inline-block;"></div>
            <p class="clearfix">
                <input type="submit" onclick="register();" value="免费注册" class="submitbtn gray" style="cursor:pointer" disabled="disabled" id="denglu">
            </p>
            <p class="agree"> 点击注册即表明你同意<a class="terms" target="_blank" href="#">元气萌轻小说服务条款</a></p>		
			</div>
		</div>
	</div>
</div>

</block>
<block name="script">
<script type="text/javascript">
Do.ready('common', function(){
    $('body').keyup(function(event) {
        switch (event.keyCode) {
            case 13:
                register();
                break;
        }
    });
});
function check(){
    $('#denglu').addClass('gray');
    $('#denglu').attr('disabled',true);
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    var phone_email=$("#input_phone").val();
    var yzm=$("#input_yzm").val();
    if(userName&&pwd){
        if(yzm){
        $('#denglu').removeClass('gray');
        $('#denglu').removeAttr('disabled');
        return;
      }
    }
}

    function register(obj){
    var userName=$("#input_user").val();
    var phone_email=$("#input_phone").val();
    var pwd=$("#input_pwd").val();
    var yzm=$("#input_yzm").val();
    if(userName.length<2 || userName.length>15){
        $('#wrong1').html('账号格式不正确');
        $('#true1').hide();
        $('#ex1').show();
        return;
    }else{
        $('#true1').show();
        $('#ex1').hide();
        $('#wrong1').html('');
    }
    if(pwd.length<6 ||pwd.length>15){
        $('#wrong3').html('密码格式不正确');
        $('#ex3').show();
        $('#true3').hide();
        return;
    }else{
        $('#true3').show();
        $('#ex3').hide();
        $('#wrong3').html('');
    }
    if (yzm.length<4) {
       $('#wrong4').html('验证码不正确');
        return;
    }else{
        $('#wrong4').html('');
    }
    /*if (!isPhone(phone_email) && !StringUtil.checkEmail(phone_email)) {
        $('#wrong2').html('联系方式格式不正确');
        $('#true2').hide();
        $('#ex2').show();
        return;
    }else{
        $('#true2').show();
        $('#wrong2').html('');
        $('#ex2').hide();
    }*/
    lockbutton(obj);
    var url="{:url('Userajax/readerReg',array(),'do')}";
    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,yzm:yzm,contactway:phone_email},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
           refreshimg();
        },
        success: function(json){
            if(json.status==1){
                hg_gotoUrl(json.url);
            }
            else {
                hg_Toast(json.message);
            }
            unlockbutton(obj);
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

function refreshimg(){
    var url = "{:url('User/imgcode',array(),'do')}";
    url+=url.indexOf('?')>0?'&':'?';
    url+=Math.random(10);
    $('#imgcode').attr('src', url);
}

function showpwd(){
    var type=$('#input_pwd').attr('type');
    if (type=="password") {
       $('#input_pwd').attr('type','text');
       $('.eye-open').removeClass('eye-open').addClass('eye-closed');
    }else{
       $('#input_pwd').attr('type','password');
       $('.eye-closed').addClass('eye-open').removeClass('eye-closed');
    }
}
</script>
</block>