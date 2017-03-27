<extend name="Common/base" />
<block name="style">
    <style>
        body { margin:0; background-color: #fff;}
        .intro1 { padding:10px 15px 15px;border-bottom: 0px solid #ccc; }
        .intro1 { padding:10px 15px 15px;    position: relative; }
        .intro-content { line-height:1.5;text-align:justify }
        .intro-content p { text-indent:2em }
        .intro .button,.intro .submit { -webkit-box-shadow:0 1px 2px #dedede }
        .intro-chapter { height:47px;line-height:47px;padding:0 15px; border-top:1px solid #d6d6d6; border-bottom:1px solid #d6d6d6; }
        .intro-chapter:active { background-color:#f0f0f0; }
        .intro-chapter a { background:url(__STATICURL__/img/android/v1/ll.png) no-repeat right center;-webkit-background-size:17px 13px;padding-right:25px }
        .intro-price { color:#6c6c6c }
        .book-info { padding:0px  0 0 110px; }
        .book-info h3 { color:#313131;overflow:hidden;font-size: 20px;line-height: 20px;margin:10px 0; }
        .book-info p { color:#858585;margin-top:8px;line-height:1.8; font-size:14px; }
        .book-info-1 p { line-height:1.8;font-size:14px; margin:10px 0; }
        .book-cover { position:absolute;width:100px;height:125px; top:20px;left:20px; }
        .book-cover img { width:92px;height:115px; }
        .read_btn { margin:40px 0 30px 0; }
        .jj { background-color:#fff; padding:10px; margin-top:5px;}
        .jj h3 { font-size:14px; font-weight:bold; line-height:1.2; border-bottom:1px solid #ccc; padding:0 0 10px 0; margin:0px 0 10px 0; background-color:#fff; padding:0 5px 5px 5px; }
        .jj p { color:#666; font-size:12px; line-height:1.5;}
        .openjj { text-align:right; color:#f63; text-decoration:underline; font-size:18px; line-height:1.3; float:right; margin-top:10px; }
        .top { width:100%; height:74px; border-bottom:1px solid #ddd;-moz-box-shadow:0px 2px 5px rgba(51, 51, 51, 0.14); -webkit-box-shadow:0px 2px 5px rgba(51, 51, 51, 0.14); box-shadow:0px 2px 5px rgba(51, 51, 51, 0.14); line-height:1.5; font-size:18px; color:#555; padding:10px 80px 10px 20px;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box; background:url("__IMG__/jiandou.png") no-repeat 94% center; background-size:55px 42px; }
        .top span { color:#f00; }
        .top p { margin:0; color:#404040; }
        .box { height:140px;overflow:hidden;padding-top:10px; }
    </style>
</block>
<block name="body">
    <div class="top">
        <p>点击右上角，选择<span>浏览器打开</span>即可免费下载书籍！</p>
    </div>
    <div class="intro1">
        <div class="box">
            <div class="book-cover"> <img src="{$bookinfo['bid']|getBookfacePath=###,'middle'}" width="92" height="115"></div>
            <div class="book-info book-info-1">
                <h3> {$bookinfo.catename}</h3>
                <p style="margin-top:8px;">{$bookinfo.author}</p>
                <p>字数：<if condition = "$bookinfo.charnum gt 10000">{:round($bookinfo['charnum'] / 10000, 1)}万字<else/>{$bookinfo['charnum']}字</if></p>
            </div>
        </div>
        <div class="jj" >
            <h3>简介</h3>
            <p>
                {$bookinfo.intro}
            </p>
        </div>
        <div style="clear:both;"></div>
    </div>
</block>
