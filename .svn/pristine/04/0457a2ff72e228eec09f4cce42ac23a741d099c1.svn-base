<extend name="Common/base" />
<block name="body">
<div class="login">
        <p><input id="text" type="text" placeholder="{$userinfo['nickname']}" class="radius4" name="username" id="input_user"/></p>
        <div class="explain" style="padding:0;"><p>注意：昵称只能修改一次，且不能违反法律和道德良俗</p></div>
        <p><if condition="$ischanged eq FALSE"><button type="button" class="radius4" onclick="check();">修改</button><else/><button type="button" class="radius4 disable">修改</button></if></p>
</div>
<!--登录结束-->
</block>
<block name="script">
<script type="text/javascript">
    function check(){
    length=$('#text').val().length;
    if (length == 0) {
        hg_Toast('昵称不能为空');
        return false;
    }else{
    var data = {
        nickname:$('#text').val(),
        uid:'{$userinfo['uid']|intval}'
    }
    var url = "{:url('Userajax/changenickname')}";
    $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data){
            hg_Toast(data.message);
        }
    })
}
}
Do.ready('lazyload',function(){
    Lazy.Load();
    document.onscroll = function(){
        Lazy.Load();
    };
});
</script>
</block>