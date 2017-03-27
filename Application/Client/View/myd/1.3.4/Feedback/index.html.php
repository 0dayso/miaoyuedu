<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10 clearfix">
        <div class="crumbs crumbs2"><span href="javascript:void(0);">您的位置</span>><a href="{:url('Index/index','','html')}">首页</a>><span>反馈意见</span></div>
        <div class="news">
            <include file="tpl/newslist" />
            <div class="rt">
                <div class="news_tit"><h2>反馈意见</h2></div>

                    <div class="author helpform clearfix">
                        <div>如果您没找到我们的问题答案，可以给我留言</div>
                        
                            <p class="newexplain">请选择问题类型</p>
                            <select id="type">
                                <option value="0">未选择</option>
                                <option value="9">版权或抄袭投诉</option>
                                <option value="10">内容不当举报</option>
                                <option value="11">充值问题</option>
                                <option value="12">网页使用的问题</option>
                                <option value="13">手机使用问题</option>
                                <option value="14">读者建议</option>
                                <option value="15">作者建议</option>
                                <option value="16">服务态度投诉</option>
                                <option value="17">其他问题</option>
                            </select>
                            <p class="newexplain">问题描述(300字以内)</p>
                            <textarea rows="6" id="text" ></textarea>
                            <p class="newexplain2"><span ><span id="num">0</span>/300</span></p>
                            <div class="form_item2">
                                <input class="img_ewm_btn radius4" type="button" value="登录留言">
                                <input type="checkbox" id="checkbox" checked="">
                                <span >匿名留言</span>
                            </div>
                            <div class="form_item2"><input type="text" name="email" placeholder="电子邮箱" class="ewm radius4" ><span class="newexplain mlf10">便于与我们联系</span> </div>
                            <div class="form_item2"><a href="javascript:void(0);" class="img_ewm"><img id="pic" src="{:url('User/imgcode','','do')}"></a><input type="text" id="yzm" placeholder="请输入验证码" class="ewm radius4"></div>
                            <div class="form_item2"><input id="tijiao" type="button" value="提交"  class="mainbtn radius4"></div>
                    </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
	<script type="text/javascript">
		require(['mod/comment','mod/user','api','functions'],function(com,user,api){
	        $('#text').on('keydown',function(){
	        	com.checknum('#text','#num');
	        })
	        $('#pic').on('click',function(){
	        	user.refreshimg('#pic');
	        })
	        $('#tijiao').on('click',function(){
	        	var length = $('#text').val().length;
	        	if(length>300 || length<1){
	        		hg_Toast('反馈问题要在300字以内');
		            return;
	        	}
	            var content = $('#text').val();
	            var email = $('input[name="email"]').val();
	            var type = $('#type').val();
	            var niming = $('#checkbox:checked').val();
	            var imgcode = $('#yzm').val();
	            if(niming){
	            	var isanony = 1;
	            }else{
	            	var isanony = 2;
	            }
	            var url = "{:url('Feedbackajax/addFeedback','','do')}";
	            var data = {
	            	type:type,
	            	content:content,
	            	isanony:isanony,
	            	imgcode:imgcode,
	            	email:email
	            }
	            api.postapi(url,data,function(data){
	                if(data.status==1){
	                	hg_Toast('提交成功！');
	                }else{
	                	hg_Toast(data.message);
	                }
	            })
	        })
		})
	</script>
</block>
