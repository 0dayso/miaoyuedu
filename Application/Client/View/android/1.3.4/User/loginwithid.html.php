<extend name="Common/base" />
<block name="body">
<!--登录开始-->
<div class="login login2">
        <p><input type="text" placeholder="请在这里输入账号或手机号" class="radius4" id="input_user" /></p>
        <p class="password"><span id="show"><input type="password" placeholder="请在这里输入密码" class="radius4" id="input_pwd"/></span><span id="hide" ><a class="openeye" onclick="showpwd();"></a></span></p>
        <p class="yz" id="sbm"><input type="text" placeholder="请输入识别码" class="radius4" id="input_sbm" /><span class="correct" id="c2"></span><a class="yzmimg radius4"><img src="{:url('User/imgcode','','do')}" lazy='y' id='imgcode' onclick='return refreshimg();'/></a></p>
        <p><button type="button" class="radius4" onclick="login(this);">登录</button></p>
    <p><span><input name="checkbox" type="checkbox" checked="checked" class="checkbox" >记住登录密码</span><a onClick="hg_gotoUrl('{:url('User/losepwd')}');" class="more">忘记密码？</a> </p>
</div>
<!--登录结束-->

</block>
<block name="script">
<script type="text/javascript">
    Do.ready('lazyload',function(){
    Lazy.Load();
    document.onscroll = function(){
        Lazy.Load();
    };
});

</script>
<script type="text/javascript">
function refreshimg(){
    var url = '{:url("User/imgcode","","do")}';
    url+=url.indexOf('?')>0?'&':'?';
    url+=Math.random(10);
    $('#imgcode').attr('src', url);
}
var forward_url='<?php if($M_forward=='/'){echo url('User/index');}else{echo $M_forward;}?>';
function login(obj){
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
     var yzm=$("#input_sbm").val();
    if(!userName){
        hg_Toast('请输入账号');
        return;
    }
    if(!pwd){
        hg_Toast('请输入密码');
        return;
    }
    if(!yzm){
        hg_Toast('请输入识别码');
        return;
    }
    lockbutton(obj);
    url='{:url('Userajax/login')}';
    checked=$('.checkbox').attr('checked');
    if(checked){
        remember=1;
    }else{
        remember=0;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,frommobile:0,remember:remember,fu:forward_url,yzm:yzm},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
           refreshimg();
        },
        success: function(json){
            if (json.status == 1 && json.usercode != '') {
                window['params'] = param = {
                    Action:'saveP30',
                    P30: json.usercode,
                    fu: json.fu,
                    uid: json.uid,
                    username: json.username,
                    nickname: json.nickname,
                    viplevel: json.viplevel,
                    groupid: json.groupid,
                    avatar: json.avatar,
                    isauthor: json.isauthor,
                    groupname:json.groupname,
                    money:json.money
                };
                doClient(param);
                            //doClient('{"Action":"saveP30","P30":"' + json.usercode + '","username":"' + json.nickname + '","uid":"' + json.uid + '","nickname":"' + json.nickname + '","viplevel":"' + json.viplevel + '","groupid":"' + json.groupid + '","avatar":"' + json.avatar + '","isauthor":"' + json.isauthor + '","money":"' + json.money + '","groupname":"' + json.groupname + '"}');
                            if(forward_url=='webview' || json.fu=='webview'){
//                                 doChild(hg_signUrl('{:url('User/index')}', json.usercode));
								doChild('{:url("User/index",array(),"do")}');
                            } else {
                                hg_gotoUrl(json.fu, json.usercode);
                            }
                        }
                        else {
                            doClient('Toast', '{"Message":"' + json.message + '","Showtime":2}');
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
    $(obj).html("登录");
}

function showpwd(){
    var type=$('#input_pwd').attr('type');
    if (type=="password") {
       $('#input_pwd').attr('type','text');
       $('.openeye').removeClass('openeye').addClass('offeye');
    }else{
       $('#input_pwd').attr('type','password');
       $('.offeye').addClass('openeye').removeClass('offeye');
    }
}

</script>
</block>