<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">

<!--反馈问题开始-->
<div class="commentbd clearfix">
    <div class="tit4">问题描述：</div>
    <!-- <form>  -->
        <textarea placeholder="请写下您对我们网站的意见和建议，帮助我们做的更好，我们可能无法及时回复您提交的信息，但我们一定会仔细考虑您提出的需求，并在适当的时候作出修改。" onfocus="clearPlaceholder();" class="comcon radius4" id="text"></textarea>
        <p class="mtop10"><h5 class="tit4">联系方式：</h5></p>
        <p ><input type="text" class="radius4" id="contact"/></p>
        <p><span><input name="checkbox" type="checkbox" class="checkbox" value="1" id="remb">记住联系方式</span></p>

        <p class="mtop20"><button type="button" class="radius4" id="tijiao" onclick="submit();">提交</button></p>
    <!-- </form>  -->
</div>

<!--反馈问题结束-->
<div class="unit">
    <div class="hotproblem noborder">
        <ul>
            <li onClick="hg_gotoUrl('{:url('Help/index',array('sex_flag'=>$sex_flag),'html')}')"><a href="{:url('Help/index',array('sex_flag'=>$sex_flag),'html')}"><h4>读者常见问题({$popularnum})</h4></a></li>
            <li onClick="hg_gotoUrl('{:url('Feedback/getfeedback',array('type'=>'private','sex_flag'=>$sex_flag),'do')}')"><a href="{:url('Feedback/getfeedback',array('type'=>'private','sex_flag'=>$sex_flag),'do')}"><h4>我的问题(<span id="change">{$mynum}</span>)</h4></a></li>
        </ul>
    </div>
</div>
<!--底开始-->
</block>
<block name="script">
<script type="text/javascript">
//清楚placeholder
function clearPlaceholder(){
	$("#text").attr("placeholder",'');
}

//问题总数
var totalnum = '{$mynum|intval}';
function submit(){
	var contact = false;
	var checked = parseInt($('#remb:checked').val());
	if(checked === 1){
		contact = $("#contact").val();		
		if(contact == ''){
			hg_Toast("联系方式不能为空");
			return false;
		}
	}
	var message = $("#text").val();
	//三星I509和小米手机在部分ROM下 英文+ 无法正常提交,将英文+替换成全角+
    message = message.replace(/\+/g,'＋');
    //add by jyhe 20120207
    message = message.replace(/&/g, '');
    message = message.replace(/</g, '').replace(/>/g, '');
    message = message.replace(/\'/g, '').replace(/\"/g, '');
    var str = message.replace(/\s+/g,"");
	if(!str.length){
		hg_Toast("问题不能为空");
		return false;
	}
	var url = "{:url('Feedbackajax/saveaddfeedback','','do')}";
	var data;
	if(contact){
		data={
			contactway:contact,
			content:message,
		}
	}else{
		data={
			content:message,
		}
	}
	$.ajax({
    	type: "POST",
    	url: url,
    	data:data,
    	success: function(data){
    		if(data.status == 1){
				hg_Toast("提交成功!");
				totalnum ++;
				$("#change").html(totalnum); 
                $('#text').val('');
                //判断是否清空联系方式
                if(checked === 1){
                	$("#contact").val(contact);	
                }else{
                	$("#contact").val('');
                }
        	}else{
				hg_Toast("提交失败!");
            }
		}
	});
}
</script>
</block>