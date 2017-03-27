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
                <div class="rt_unit rt_top clearfix">
                    <div class="lf"><img src="{$userinfo['avatar']}" /></div>
                    <div class="rt">
                        <h1>{$userinfo['nickname']}</h1>
                        <p class="user_id">ID ：{$userinfo['uid']}</p>
                        <p class="user_set"><a href="{:url('User/personal','','do')}"><span>设置个人资料</span><span class="ic_user_pen"></span></a></p>
                    </div>
                    <p class="time">{$userinfo['nickname']}，您好！欢迎光临{:C("SITECONFIG.SITE_NAME")}！您上次登录的时间是：{$userinfo['lastlogin']}</p>
                </div>
                <div class="rt_unit rt_bom clearfix">
                    <div class="user_tit"><h1>我的账号</h1><a href="{:url('Pay/index','','do')}" class="user_tit_btn">充值</a></div>
                    <div class="frame02 user_rt_account clearfix">
                        <ul>
                            <li><h1>{$userinfo['money']}</h1><span>{:C("SITECONFIG.MONEY_NAME")}</span></li>
                            <li><h1>{$userinfo['egold']}</h1><span>{:C("SITECONFIG.EMONEY_NAME")}</span></li>
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