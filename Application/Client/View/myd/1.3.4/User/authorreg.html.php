<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container bgf mtop10 clearfix">
        <div class="author">
            <div class="tit5"><div class="tit5_border"><h1>注册为作者</h1></div></div>
            <h5>我是网络小说写手，我要注册为{:C("SITECONFIG.SITE_NAME")}签约作者：</h5>
            <div class="author_con" table="1">
                <div class="form_item2">
                    <div class="lf"> <input name="user" type="text" placeholder="账号名" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span><span>长度为2到15位的英文，数字</span></p></div>
                    <span class="right" name="user" style="display:none;"></span>
                </div>
                <p class="wrong" name="user" style="display:none;"></p>
                <div class="form_item2">
                    <div class="lf"> <input name="pwd" type="password" placeholder="密码(不少于6位)" class="radius4" /><a href="javascript:void(0);" isshow="0" id="eye" class="eye"></a></div>
                    <div class="rt"><p><span class="cred">*</span>6-15个大小写英文字母、数字,符号</p></div>
                </div>
                <p class="wrong" name="pwd" style="display:none;"></p>
                <div class="form_item2">
                    <div class="lf"> <input name="writename" type="text" placeholder="作者笔名" class="radius4" /></div>
                    <div class="rt"><p class="hang2"><span class="cred">*</span>真实姓名或常用笔名均可(以后扬名立万就他了！:）</p><p class="hang2">长度为2到8位的中英文及数字，请勿使用火星文等文字法</p></div>
                </div>
                <div class="form_item2">
                    <div class="lf"> <input name="phone" type="text" placeholder="手机号" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                    <span class="right" name="phone" style="display:none;"></span>
                </div>
                <p class="wrong" name="phone" style="display:none;"></p>
                <div class="form_item2">
                    <div class="lf"> <input name="qq" type="text" placeholder="QQ" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                </div>
                <div class="form_item2">
                    <div class="lf"><input name="mail" type="text" placeholder="电子邮箱" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                    <span class="right" name="mail" style="display:none;"></span>
                </div>
                <p class="wrong" name="mail" style="display:none;">文字</p>
                <div class="form_item2"><div class="lf"><button id="zhuce" class="mainbtn radius4">免费注册</button></div></div>
                <p>点击注册即表明你同意<a href="{:url('Help/abouthelp',array('article_id'=>'yhxy'),'html')}" class="cblue">{:C("SITECONFIG.SITE_NAME")}服务条款</a></p>
            </div>
            <div class="author_con" table="2" style="display:none;">
                <div class="form_item2">
                    <div class="lf"> <input name="writename2" type="text" placeholder="作者笔名" class="radius4" /></div>
                    <div class="rt"><p class="hang2"><span class="cred">*</span>真实姓名或常用笔名均可(以后扬名立万就他了！:）</p><p class="hang2">长度为2到8位的中英文及数字，请勿使用火星文等文字法</p></div>
                </div>
                <div class="form_item2">
                    <div class="lf"> <input name="phone2" type="text" placeholder="手机号" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                    <span class="right" name="phone2" style="display:none;"></span>
                </div>
                <p class="wrong" name="phone2" style="display:none;"></p>
                <div class="form_item2">
                    <div class="lf"> <input name="qq2" type="text" placeholder="QQ" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                </div>
                <div class="form_item2">
                    <div class="lf"><input name="mail2" type="text" placeholder="电子邮箱" class="radius4" /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}的编辑会通过这个号码与您联系</p></div>
                    <span class="right" name="mail2" style="display:none;"></span>
                </div>
                <p class="wrong" name="mail2" style="display:none;">文字</p>
                <div class="form_item2"><div class="lf"><button id="zhuce2" class="mainbtn radius4">免费注册</button></div></div>
                <p>点击注册即表明你同意<a href="{:url('Help/abouthelp',array('article_id'=>'yhxy'),'html')}" class="cblue">{:C("SITECONFIG.SITE_NAME")}服务条款</a></p>
            </div>

        </div>
        <div class="ts author_ts">
            <!-- <p><a href="{:url('Help/article',array('article_id'=>'31'),'html')}" target="_blank">.喵阅读作者权益条款(渠道拓展福利计划)</a></p>
            <p><a href="{:url('Help/article',array('article_id'=>'31'),'html')}" target="_blank">.喵阅读作者福利</a></p> -->
            <p><a href="{:url('Help/abouthelp',array('article_id'=>'bqsm'),'html')}" target="_blank">.版权声明</a></p>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/user','functions'],function(user){
            $('#zhuce').on('click',function(){
                var fromuid="{$fromuid}";
                user.authorreg('user','pwd','writename','phone','qq','mail',fromuid);
            })
            $('input[name="user"]').on('blur',function(){
                user.checkauthorname('user');
            })
            $('#eye').on('click',function(){
                user.showhidepwd('pwd','#eye','isshow');
            })
            UserManager.addListener(function(userinfo){
                if(userinfo.islogin){
                    $('div[table="1"]').hide();
                    $('div[table="2"]').show();
                    $('#zhuce2').on('click',function(){
                        var fromuid="{$fromuid}";
                        user.authorreg2('writename2','phone2','qq2','mail2',fromuid);
                    })
                }
            })
        })
    </script>
</block>