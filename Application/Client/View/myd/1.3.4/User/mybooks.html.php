<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  mtop10 clearfix">
        <div  class="user">
            <div class="lf clearfix">
                <include file="tpl/user" />
            </div>
            <div class="rt">
                <div class="rt_unit rt_czjl clearfix">
                    <if condition="$isauthor eq 1">
	                    <div class="user_tit"><h1>我的作品</h1></div>
	                    <div class="work_con clearfix">
	                        <h3>您还没写过书，快来发布第一本大作吧！</h3>
	                        <a class="radius4" href="{:url('Client/User/Authorlogin',array('sign'=>1),'do')}" target="_blank">写一本</a>
	                    </div>
	                <else/>
		                <div class="user_tit"><h1>我的作品</h1></div>
	                    <div class="work_con clearfix">
	                        <h3>您还未注册作者！</h3>
	                        <a class="radius4" href="{:url('User/authorreg','','do')}" target="_blank">前往注册</a>
	                    </div>
	                </if>
                </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/user'],function(user){
            user.listmark();
        })
    </script>
</block>