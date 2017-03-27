<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">

<!--反馈问题开始-->
<div class="main top20 bom40">
	<div class="help1200">
		<div class="helplist960">
			<p class="help_nav"><a class="c9" href="{:url('Index/index','','html')}">首页</a> > <a class="c9" href="{:url('Help/index','','html')}">帮助中心</a> > <a class="c9">反馈</a></p>
			<h4 class="c3 bom10">反馈与意见</h4>
			<span class="c9">为了更好地帮助元气萌成长，感谢您提供宝贵意见！</span>
		
			<div class="fankui">
				<div>
					<p class="c6 top30">问题描述（300字以内）：</p>
					<div>
						<textarea class="textarea10" placeholder="请输入内容" id="text" onkeyup="checknum();"></textarea>
					</div>
					
					<!-- <button class="top10">登录留言</button> -->
					<label style="line-height: 25px; height: 25px">
	                    <input type="checkbox" tabindex="1" name="remeber" id="checkbox" class="checkbox"> 匿名反馈
	                </label>
					<span class="c9 flrt top5" id="num">0/300</span>
				</div>
				
				<div class="form-warp clearfix  top30">
                        <p class="clearfix">
			              <input style="width: 250px;" class="" type="text" name="" placeholder="电子邮箱" autocomplete="" id="email" value="" maxlength="">
			              <span class="explain" category="">*必要时，便于网站工作人员与您联系</span>
			            </p>
			            <p class="code clearfix bom30">
                            <input class="codeword " type="text" name="" maxlength="4" placeholder="请输入验证码" id="yzm" value="">
                            <a class="codeimg lf20" title="看不清楚？点击换一张" onclick="refreshimg();">
                            	<img id="imgcode" src="{:url('User/imgcode')}" style="cursor:pointer;">
                            </a>
                            <span category="validRight" class="explain correct" style="display:none"></span>
			            </p>
			            <p class="clearfix">
			              <input type="submit" value="提交问题" class="submitbtn" onclick="submit();" style="cursor: pointer">
			            </p>
            	</div>
			</div>
		</div>
	</div>
</div>
<!--反馈问题结束-->
<!--底开始-->
</block>
<block name="script">
<script type="text/javascript">
function refreshimg(){
    var url = '{:url("User/imgcode")}';
    url+=url.indexOf('?')>0?'&':'?';
    url+=Math.random(10);
    $('#imgcode').attr('src', url);
}

function checknum(){
   var num=$('#text').val().length;
   $('#num').html(num+"/300");
   if(num>300){
      hg_Toast("反馈字数要在300字以内哟~");
      return;
   }
}
//清楚placeholder
function clearPlaceholder(){
	$("#text").attr("placeholder",'');
}

//问题总数
function submit(){
	var niming=0;
	var checked = parseInt($('#checkbox:checked').val());
	if(checked === 1){
		niming = 1;		
	}
	var content=$('#text').val();
	var yzm=$('#yzm').val();
	var email=$('#email').val();
	if(!content){
		hg_Toast("问题不能为空");
		return false;
	}
	if (!email) {
		hg_Toast("请输入正确信息，方便与您联系");
		return false;
	}
	if(!yzm){
		hg_Toast("请输入验证码");
	}
	var url = "{:url('Feedbackajax/saveaddfeedback')}";
	
	var	data={
			niming:niming,
			content:content,
			email:email,
			yzm:yzm
		}
	$.ajax({
    	type: "POST",
    	url: url,
    	data:data,
    	success: function(data){
    		if(data.status == 1){
				hg_Toast("提交成功!");
                $('#text').val('');
                refreshimg();
        	}else{
				hg_Toast("提交失败!");
            }
		}
	});
}
</script>
</block>