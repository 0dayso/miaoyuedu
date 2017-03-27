<extend name="Common/base" />
<block name="body">
<style type="text/css">
        blockquote,body,code,dd,div,dl,dt,fieldset,form,h1,h2,h3,h4,h5,h6,input,legend,li,ol,p,pre,td,textarea,th,ul{ margin:0;padding:0}fieldset,img{border:0}address,caption,cite,code,dfn,em,th,var{font-weight:400;font-style:normal}ol,ul{list-style:none}caption,th{text-align:left}q:after,q:before{content:''}abbr,acronym{border:0;font-variant:normal}sup{vertical-align:text-top}sub{vertical-align:text-bottom}input,select,textarea{font-weight:inherit;font-size:inherit;font-family:inherit}.clearfix:after,.clearfix:before{display:table;content:"";line-height:0}.clear,.clearfix:after{clear:both}table{border-collapse:collapse;border-spacing:0}
        ul,ol,li{list-style:none}
        h1,h2,h3,h4,h5,h6{ font-weight: normal;}
        .clearfix { *zoom: 1;}
        .clearfix:before,.clearfix:after {display: table;line-height: 0;content: "";}
        .clearfix:after {clear: both;}
        html{ -webkit-text-size-adjust:none;}
        body{font-family:"微软雅黑",helvetica,arial;-webkit-text-size-adjust:none; background-color: #f2f2f2;color:#404040; font-size: 12px; line-height:1.33em; }
        a{color:#404040; text-decoration: none;}
        body{background-color: #f2f0ed;}
        .overcon01{border-bottom:1px solid #ddd;margin-bottom: 10px;padding-bottom: 10px;}
        .overcon01 h1 { font-size: 26px;line-height: 1.6em;text-align: center;width: 100%;display: inline-block;}/*阅读尾页*/
        .overcon01 p {text-align: left; font-size: 12px; margin: 5px 0;}
        .rdend { padding: 10px;margin: 0; position: relative;}
        .rdend .frame2{padding:10px 0;}
        .rdend .tit h1 , .rdend .tit { font-size: 14px; font-weight:bold;padding:0;height:20px;line-height: 20px;}
        .rdbt{width:100%;height:20px; line-height: 20px; overflow:hidden;}/*标题*/
        .rdbt a , .rdbt span{ float:left;}
        .rdbt span{ color:#666; margin: 0 10px;}
        .rdbt .rdname{max-width:100px;}
        .frame2 li { width: 33.333%;text-align: center;float: left; }
        .frame2 img{ background-color: #eee;}
        .frame2 p {height: 30px;overflow: hidden;text-align: left;}
        .frame img, .frame2 img {width: 92px;height: 115px; }
        .frame2 li:nth-child(3n-2) div{ float:left;}
        .frame2 li:nth-child(3n) div{ float:right;}
        .frame2 div{width:92px;overflow:hidden;padding:5px; margin:0 auto; margin-bottom: 5px;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;}
        .frame2 div:hover{ background-color: #e0e0e0;}
        @media screen and (max-width: 320px){
            .rdend .tit h1, .rdend .tit {font-size: 14px;font-weight: bold;padding: 0;height: 20px;line-height: 20px;}
            .rdend .frame2 {padding: 10px 0;}
            .frame img, .frame2 img {width: 80px; height: 100px;}
        }
    </style>
<!--阅读开始-->
<div class="rdcon rdend">
    <div class="overcon01">
    <if condition="$bookinfo['lzinfo'] eq 1">
        <h1>全本完</h1>
         <p >你已经读完整本书！您可以<a onclick="go_url('{$bookinfo['bid']}','Book/comment')" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>！</p>
         <p>也许你会喜欢以下书籍！</p>
     <else/>
        <h1>待续...</h1>
        <p >你已经读完最新章节，作者正在努力码字中，请等待作者上传更新，一般作者会坚持每日更新！</p>
        <p >您可以<a onclick="go_url('{$bookinfo['bid']}','Book/comment')" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>努力更新码字！</p>
    </if>
    </div>

    <!--火热推荐开始-->
    <div >
        <div class="tit"><h1>火热推荐</h1></div>
        <div class="frame2">
            <ul>
              <Hongshu:bangdan name="android_{$style}_qianli" items="6">
                <li onclick="go_url('{$row['bid']}','Book/view')">
                <div><img src="{$row.face}"/>
                 <p>{$row['catename']}</p></div>
                </li>
                </Hongshu:bangdan>
            </ul>
        </div>
    </div>
    <!--火热推荐结束-->


</div>
<!--阅读结束-->

</block>
<block name="script">
<script type="text/javascript">
    function go_url(bid,gourl){
        var url=parseUrl({bid:bid},gourl,'open//','html');
        doClient(url);
    }
</script>
</block>