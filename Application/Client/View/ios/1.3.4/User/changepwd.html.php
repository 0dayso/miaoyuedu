<extend name="Common/base" />
<block name="body">
<!--登录开始-->
<div class="login">
        <p class="password"><input type="password" placeholder="原密码" class="radius4" id="old" /><span><a class="openeye" id="open1" onclick="showpwd();"></a></span></p>
        <p class="password"><input type="password" placeholder="新密码" class="radius4" id="new"/><span><a class="openeye" id="open2" onclick="hidepwd();"></a></span></p>
        <p><button type="button" class="radius4" onclick="return submit(this);">修改</button></p>
</div>
<!--登录结束-->
</block>
<block name="script">
<script type="text/javascript">
	function showpwd(){
    var type=$('#old').attr('type');
    if (type=="password") {
       $('#old').attr('type','text');
       $('#open1').removeClass('openeye').addClass('offeye');
    }else{
       $('#old').attr('type','password');
       $('#open1').addClass('openeye').removeClass('offeye');
    }
}
  function hidepwd(){
    var type=$('#new').attr('type');
    if (type=="password") {
       $('#new').attr('type','text');
       $('#open2').removeClass('openeye').addClass('offeye');
    }else{
       $('#new').attr('type','password');
       $('#open2').addClass('openeye').removeClass('offeye');
    }
}
  function check(){
  	var oldp=$('#old').val().length;
  	var newp=$('#new').val().length;
  	if (oldp<6 || newp<6) {
  		hg_Toast('密码格式错误');
      return false;
  	}
  }
  function submit(){
  	check();
  	var url="{:url('Userajax/savepassword')}";
  	var oldp=$('#old').val();
  	var newp=$('#new').val();
    $.ajax({
        url: url,
        type: "POST",
        data: {oldpwd:oldp,newpwd:newp},
        dataType: 'json',
        success: function(json){
            console.log(json);
            if(json.status==1){
                doClient({Action:"saveP30",P30:"",fu:json.url});
            }
            else {
                hg_Toast(json.message);
            }
        }});

  }
</script>
</block>