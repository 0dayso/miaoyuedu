
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
        <li class="user_ka rt16" onclick="hg_gotoUrl('{:url('User/register')}')"><a href="{:url('User/register')}">注册</a></li>
        <li class="user_ka flrt active" onclick="hg_gotoUrl('{:url('User/authorreg')}')"><a href="{:url('User/authorreg')}">注册为萌神</a></li>
        
    </ul>
    <div class="user1060">
        <div class="login960">
            <div class="form-warp clearfix bom30" id="zhuce">
                <div class="form-tit3 bom20 clearfix top20 c6">
                     <h3>欢迎成为元气萌世界の神 <(￣ˇ￣)/</h3>
                     <p class="c9">神说要有光，于是我站在这里准备创造世界！</p>
                </div>               
                <p class="clearfix">
                  <input class="" type="text" name="username" placeholder="账号名" autocomplete="off" id="input_user" value="" maxlength="15" onkeyup="check();">
                  <span id="ex1" class="explain" category="">*长度为2到15位的英文，数字</span>
                  <span id="true1" class="explain correct" category="" style="display:none"></span>
                </p>
                <div id="wrong1" class="wrong" style="display:inline-block;"></div>
                
                <p class="passwordp clearfix">
                    <a class="eye-open" onclick="showpwd();"></a>
                    <input class="password " type="password" name="password" maxlength="20" placeholder="密码(不少于6位)" id="input_pwd" value="" onkeyup="check();">
                    <span id="ex2" category="" class="explain">*6-15个大小写英文字母、数字,符号</span>
                    <span id="true2" category="" class="explain correct" style="display:none"></span>
                  </p>
                <div id="wrong2" class="wrong" style="display:inline-block;"></div>
    
                <p class="clearfix">
                  <input class=" " type="text" name="authorname" maxlength="8" placeholder="萌神笔名"  value="" id="input_authorname" onkeyup="check();">
                  <span id="ex3" category="" class="explain3">*真实姓名或常用笔名均可(以后扬名立万就他了！:）长度为2到8位的中英文及数字，请勿使用火星文等文字法</span>
                  <span id="true3" category="" class="explain3 correct" style="display:none"></span>
                </p>
                <div id="wrong3" class="wrong" id="" style="display:inline-block;"></div>
                <p class="clearfix">
                  <input id="input_phone" class="" type="text" name="phone" placeholder="手机号" autocomplete="off" id="" maxlength="16" value="" onkeyup="check();">
                  <span id="ex4" category="" class="explain2"><b class="cyellow">*</b>元气萌创世者会通过这个号码与您联系</span>
                  <span id="true4" category="" class="explain2 correct" style="display:none"></span>
                </p>
                <div class="wrong" id="wrong4" style="display:inline-block;"></div>
    
                <p class="clearfix">
                  <input class="" type="text" name="qq" placeholder="QQ号" autocomplete="off" id="input_qq" value="" onkeyup="check();">
                  <span id="ex5" class="explain2" category=""><b class="cyellow">*</b>元气萌创世者会通过这个号码与您联系</span>
                  <span id="true5" class="explain2 correct" category="" style="display:none"></span>
                </p>
                <div id="wrong5" class="wrong" style="display:inline-block;"></div>
    
                <p class="clearfix">
                  <input class="" type="text" name="email" placeholder="电子邮箱" autocomplete="off" id="input_email" maxlength="30" value="" onkeyup="check();">
                  <span id="ex6" category="" class="explain2"><!-- <b class="cyellow">*</b>长度为2到15位的中英文，数字 --></span>
                  <span id="true6" category="" class="explain2 correct" style="display:none"></span>
                </p>
                <div id="wrong6" class="wrong" style="display:inline-block;"></div>
    
               
    
                <p class="clearfix">
                  <input type="submit" value="开始元气萌萌神生涯" class="submitbtn gray" onclick="register();" style="cursor: pointer" disabled="disabled" id="denglu">
                </p>
                <p class="agree"> 点击注册即表明你同意<a class="terms" target="_blank" href="">元气萌服务条款</a></p>

            </div>
        </div>
    </div>
</div>



</block>
<block name="script">
<script type="text/html" id="tpl1">
    <div class="form-tit3 bom20 clearfix top20 c6">
         <h3>欢迎成为元气萌世界の神 <(￣ˇ￣)/</h3>
         <p class="c9">神说要有光，于是我站在这里准备创造世界！</p>
    </div>
    <p class="c6">@{{nickname}}，你好！不要辜负了你的才华，注册为元气萌萌神吧！</p>
    <p class="clearfix">
                  <input class=" " type="text" name="authorname" maxlength="8" placeholder="萌神笔名"  value="" id="input_authorname" onkeyup="check1();">
                  <span id="ex3" category="" class="explain3">*真实姓名或常用笔名均可(以后扬名立万就他了！:）长度为2到8位的中英文及数字，请勿使用火星文等文字法</span>
                  <span id="true3" category="" class="explain3 correct" style="display:none"></span>
                </p>
                <div id="wrong3" class="wrong" id="" style="display:inline-block;"></div>
                <p class="clearfix">
                  <input id="input_phone" class="" type="text" name="phone" placeholder="手机号" autocomplete="off" id="" maxlength="16" value="" onkeyup="check1();">
                  <span id="ex4" category="" class="explain2"><b class="cyellow">*</b>元气萌创世者会通过这个号码与您联系</span>
                  <span id="true4" category="" class="explain2 correct" style="display:none"></span>
                </p>
                <div class="wrong" id="wrong4" style="display:inline-block;"></div>
    
                <p class="clearfix">
                  <input class="" type="text" name="qq" placeholder="QQ号" autocomplete="off" id="input_qq" value="" onkeyup="check1();">
                  <span id="ex5" class="explain2" category=""><b class="cyellow">*</b>元气萌创世者会通过这个号码与您联系</span>
                  <span id="true5" class="explain2 correct" category="" style="display:none"></span>
                </p>
                <div id="wrong5" class="wrong" style="display:inline-block;"></div>
    
                <p class="clearfix">
                  <input class="" type="text" name="email" placeholder="电子邮箱" autocomplete="off" id="input_email" maxlength="30" value="" onkeyup="check1();">
                  <span id="ex6" category="" class="explain2"><!-- <b class="cyellow">*</b>长度为2到15位的中英文，数字 --></span>
                  <span id="true6" category="" class="explain2 correct" style="display:none"></span>
                </p>
                <div id="wrong6" class="wrong" style="display:inline-block;"></div>
    
               
    
                <p class="clearfix">
                  <input type="submit" value="开始元气萌萌神生涯" class="submitbtn gray" onclick="register1();" style="cursor: pointer" disabled="disabled" id="denglu">
                </p>
                <p class="agree"> 点击注册即表明你同意<a class="terms" target="_blank" href="">元气萌服务条款</a></p>
</script>
<script type="text/javascript">
function check(){
    $('#denglu').addClass('gray');
    $('#denglu').attr('disabled',true);
    var userName=$("#input_user").val();
    var phone=$("#input_phone").val();
    var pwd=$("#input_pwd").val();
    var email=$("#input_email").val();
    var qq=$("#input_qq").val();
    var authorname=$("#input_authorname").val();
    if(userName&&pwd){
        if(phone&&email){
            if(qq&&authorname){
        $('#denglu').removeClass('gray');
        $('#denglu').removeAttr('disabled');
        return;}
    }
  }    
}
function check1(){
    $('#denglu').addClass('gray');
    $('#denglu').attr('disabled',true);
    var phone=$("#input_phone").val();
    var email=$("#input_email").val();
    var qq=$("#input_qq").val();
    var authorname=$("#input_authorname").val();
        if(phone&&email){
            if(qq&&authorname){
        $('#denglu').removeClass('gray');
        $('#denglu').removeAttr('disabled');
        return;}
    }   
}

    function register(obj){
    var userName=$("#input_user").val();
    var phone=$("#input_phone").val();
    var pwd=$("#input_pwd").val();
    var email=$("#input_email").val();
    var qq=$("#input_qq").val();
    var authorname=$("#input_authorname").val();
    if(userName.length<2 || userName.length>15){
        $('#wrong1').html('账号格式不正确');
        $('#ex1').show();$('#true1').hide();
        return;
    }else{
        $('#wrong1').html('');
        $('#ex1').hide();$('#true1').show();
    }
    if(pwd.length<6 ||pwd.length>15){
        $('#wrong2').html('密码格式不正确');
        $('#ex2').show();$('#true2').hide();
        return;
    }else{
        $('#wrong2').html('');
        $('#ex2').hide();$('#true2').show();
    }
    if (authorname.length<2 || authorname.length>8) {
       $('#wrong3').html('笔名格式不正确');
       $('#ex3').show();$('#true3').hide();
        return;
    }else{
        $('#wrong3').html('');
        $('#ex3').hide();$('#true3').show();
    }
    if (!isPhone(phone)) {
        $('#wrong4').html('手机号格式不正确');
        $('#ex4').show();$('#true4').hide();
        return;
    }else{
        $('#wrong4').html('');
        $('#ex4').hide();$('#true4').show();
    }
    if (!qq) {
        $('#wrong5').html('QQ号不为空');
        $('#ex5').show();$('#true5').hide();
        return;
    }else{
        $('#wrong5').html('');
        $('#ex5').hide();$('#true5').show();
    }
    if (!StringUtil.checkEmail(email)) {
        $('#wrong6').html('邮箱格式不正确');
        $('#ex6').show();$('#true6').hide();
        return;
    }else{
        $('#wrong6').html('');
        $('#ex6').hide();$('#true6').show();
    }
    lockbutton(obj);
    var url="{:url('Userajax/authorReg',array(),'do')}";
    var fu ="{:url('User/authorLogin',array('sign'=>1)),'do'}";

    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,qq:qq,phone:phone,email:email,authorname:authorname,fu:fu},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
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
function register1(obj){
    var fromuid='{$fromuid}';
    var phone=$("#input_phone").val();
    var email=$("#input_email").val();
    var qq=$("#input_qq").val();
    var authorname=$("#input_authorname").val();
    if (authorname.length<2 || authorname.length>8) {
       $('#wrong3').html('笔名格式不正确');
       $('#ex3').show();$('#true3').hide();
        return;
    }else{
        $('#wrong3').html('');
        $('#ex3').hide();$('#true3').show();
    }
    if (!isPhone(phone)) {
        $('#wrong4').html('手机号格式不正确');
        $('#ex4').show();$('#true4').hide();
        return;
    }else{
        $('#wrong4').html('');
        $('#ex4').hide();$('#true4').show();
    }
    if (!qq) {
        $('#wrong5').html('QQ号不为空');
        $('#ex5').show();$('#true5').hide();
        return;
    }else{
        $('#wrong5').html('');
        $('#ex5').hide();$('#true5').show();
    }
    if (!StringUtil.checkEmail(email)) {
        $('#wrong6').html('邮箱格式不正确');
        $('#ex6').show();$('#true6').hide();
        return;
    }else{
        $('#wrong6').html('');
        $('#ex6').hide();$('#true6').show();
    }
    lockbutton(obj);
    var url="{:url('Userajax/authorRegWithReferee',array(),'do')}";
     var fu ="{:url('User/authorLogin',array('sign'=>1)),'do'}"
    $.ajax({
        url: url,
        type: "POST",
        data: {qq:qq,phone:phone,email:email,authorname:authorname,fromuid:fromuid,fu:fu},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
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

Do.ready('common','template',function(){
  UserManager.addListener(function(user){
       if(user.islogin){
          var htmls=template('tpl1',user);
          $('#zhuce').html(htmls);
       }

   });
});
</script>
<include file="Common/foot2" />
</block>