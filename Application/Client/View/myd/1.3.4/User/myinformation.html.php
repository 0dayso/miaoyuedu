<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  mtop10 clearfix">
        <div  class="user xiaoxi">
            <div class="lf clearfix">
                <include file="tpl/user" />
            </div>
            <div class="rt">
                <div class="rt_unit rt_czjl clearfix">
                    <div class="user_tit"><h1>我的消息</h1></div>
                    <div class="frame02 xiaoxi_con clearfix">
                        <ul>
                            <li>
                                <a href="{:url('User/mybookreview',array('type'=>'1'),'do')}">我的评论</a><span>{$mycommentnum}</span>
                            </li>
                            <li>
                                <a href="{:url('User/mybookreview',array('type'=>'2'),'do')}">回复我的</a><span>{$replycommetnum}</span>
                           </li>
                        </ul>
                    </div>
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