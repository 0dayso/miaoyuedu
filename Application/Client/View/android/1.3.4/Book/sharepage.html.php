<extend name="Common/base" />
<block name="style">
    <style>
        @charset "utf-8";
        /* CSS Document */
        body, div, p, ul, h1, h2, h3, h4, h5,span , img{ margin: 0; padding: 0; }
        body { font-family: Helvetica,'微软雅黑'; -webkit-text-size-adjust: none; background:#fff; font-size: 14px; color: #313131; }
        h1, h2, h3, h4, h5 { font-weight: normal; font-size: 14px; }
        em { font-style: normal; }
        ul, ol, li { list-style: none }
        img { border: 0 none }
        .clearfix:after{content:".";display:block;height:0;clear:both;visibility:hidden}.clearfix{display:inline-block}
        .main{ width:820px; margin:0 auto;border:1px solid #6ABFBE; margin-top:50px;}
        .main{ margin-top:60px;  height:100%;}
        .top{width:100%; height:5px; overflow:hidden;}
        .left{ width:500px; margin:20px; float:left;border-right:1px solid #ddd;padding-right:30px; margin-right:10px;}
        .cover{ width:500px;}
        .fm{ float:left;}
        .fm a , .fm a:hover{text-decoration:none; display:block;}
        .fm img {height: 200px;width: 160px;}
        .nr{ float:left; margin-left:20px; display:inline;}
        .nr a.sm{ color:#333; font-size:20px; line-height:52px; text-decoration:none;}
        .nr a.sm:hover{ color:#429C9B;}
        .nr p{ color:#888; font-size:16px; line-height:42px; text-decoration:none; text-indent:10px;}
        .djbtn{width:180px; height:42px;line-height:42px; color:#fff; display:block;text-align:center;text-decoration:none; margin-top:20px; background-color:#6ABFBE; font-size:16px;}
        .djbtn:hover{ background-color:#499C9B;}
        .jj{ width:100%; float:left; margin-top:10px;}
        .jj p{ font-size:12px; color:#f60; line-height:36px;}
        .jj h3{  font-weight:bold; font-size:16px; color:#000; margin-top:20px;}
        .jjnr { border-top:1px solid #ddd; margin-top:10px; padding-top:10px; font-family:"宋体";max-height:217px;overflow:hidden; }
        .jjnr p{line-height:22px; font-size:12px;color:#888; margin-bottom:10px;}
        .right{ float:right; width:240px; text-align:center; margin-right:15px;}
        .logo{ margin:20px 0 0 0px; width:224px; height:82px; display:inline-block;}
        .right p{ text-align:center; font-size:18px; color:#333; line-height:30px;}
        .androidbtn{ background:url(__IMG__/androidbtn.png); width:198px; height:60px; display:inline-block; margin-top:50px; }
        .ewm{ display:inline-block; width:100%; margin-top:15px;}
        .ewm p{ font-size:12px; color:#404040; line-height:36px;}
    </style>
</block>
<block name="body">
    <!--开始-->
    <div class="main ">
        <div class="top"></div>
        <div class="left">
            <div class="cover">
                <div class="fm"><a onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$bookinfo['bid']))}')" target="_blank"><img  width="200" height="250" src="{$bookinfo['bid']|getBookfacePath=###,'middle'}" /></a></div>
                <div class="nr"><a onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$bookinfo['bid']))}')" target="_blank" class="sm">{$bookinfo.catename}</a>
                    <p>{$bookinfo.author}</p>
                    <p>总字数：<if condition = "$bookinfo.charnum gt 10000">{:round($bookinfo['charnum'] / 10000, 1)}万字<else/>{$bookinfo['charnum']}字</if></p>
                    <a onClick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid']))}')" target="_blank" class="djbtn">点击阅读</a></div>
            </div>
            <div class="jj">
                <p>手机端打开此页面链接，即可立即免费下载书籍 </p>
                <h3>简介</h3>
                <div class="jjnr">
                    {$bookinfo.intro}
                </div>
            </div>
        </div>
        <div class="right">
            <div class="logo"><img src="__IMG__/logo.png" width="200" height="83" /></div>
            <p>{:C('SITECONFIG.SITE_NAME')}Android客户端</p>
            <p style="margin:10px 0;">版本号：{$client_version}</p>
            <a class="androidbtn" href=" __HOMEDOMAIN__/download_client.php?type=android"></a>
            <div class="ewm"><img src="__IMG__/ewm.png" width="140"  height="140" />
                <p>扫描二维码下载</p>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="bon"></div>
    </div>
    <!--结束-->
</block>
