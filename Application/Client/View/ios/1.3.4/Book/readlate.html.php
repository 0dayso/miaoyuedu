<extend name="Common/base" />
<block name="body">
<style>
        blockquote,body,code,dd,div,dl,dt,fieldset,form,h1,h2,h3,h4,h5,h6,input,legend,li,ol,p,pre,td,textarea,th,ul{margin:0;padding:0}fieldset,img{border:0}address,caption,cite,code,dfn,em,th,var{font-weight:400;font-style:normal}ol,ul{list-style:none}caption,th{text-align:left}q:after,q:before{content:''}abbr,acronym{border:0;font-variant:normal}sup{vertical-align:text-top}sub{vertical-align:text-bottom}input,select,textarea{font-weight:inherit;font-size:inherit;font-family:inherit}.clearfix:after,.clearfix:before{display:table;content:"";line-height:0}.clear,.clearfix:after{clear:both}table{border-collapse:collapse;border-spacing:0}
        ul,ol,li{list-style:none}
        h1,h2,h3,h4,h5,h6{ font-weight: normal;}
        .clearfix { *zoom: 1;}
        .clearfix:before,.clearfix:after {display: table;line-height: 0;content: "";}
        .clearfix:after {clear: both;}
        html{ -webkit-text-size-adjust:none;}
        body{font-family:"微软雅黑",helvetica,arial;-webkit-text-size-adjust:none; background-color: #f2f2f2;color:#404040; font-size: 12px; line-height:1.33em; }
        a{color:#404040; text-decoration: none;}
        body{background-color: #f2f0ed;}
    /*阅读尾页*/
        .hidden {overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
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
        .tuijian{ background-color: #fff;border:1px solid #ddd;padding:10px;display:inline-block;width:100%;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;}
        .ztimg{width:100%;position: relative; }
        .ztimg img{width:100%;height:auto;}
        .ztimg p{position: absolute;left:0;bottom:0;height:32px;line-height:32px; background-color: rgba(0,0,0,.8); -moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;color: #fff;font-size: 14px;width:100%;padding:0 5px;}
        .tuijian li{width:100%;position: relative;border-bottom:1px solid #eee;padding:10px 0; margin:0;}
        .tuijian li .lf{width:100%;padding:3px 0 10px 0;height:45px;padding-right:65px; font-size: 14px;line-height:20px;overflow:hidden;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box; text-align:left; }
        .tuijian li .rt{position: absolute;top:10px;right:10px;}
        .tuijian li .rt img{width:45px;height:45px;}
        .tuijian li:last-child{border:0;}
        .tuijian li:hover{ background-color: #eee;}
        .overcon01 {border-bottom: none;}
    @media screen and (max-width: 320px) {
        .rdend .tit h1, .rdend .tit {font-size: 14px;font-weight: bold; padding: 0;height: 20px;line-height: 20px;}
    }
    </style>
<!--阅读开始-->
<div class="rdcon rdend">
    <div class="overcon01">
       <if condition="$bookinfo['lzinfo'] eq 1">
        <h1>全本完</h1>
         <p >你已经读完整本书！您可以<a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>！</p>
         <p>也许你会喜欢以下书籍！</p>
     <else/>
        <h1>待续...</h1>
        <p >你已经读完最新章节，作者正在努力码字中，请等待作者上传更新，一般作者会坚持每日更新！</p>
        <p >您可以<a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="cpink">发书评</a>，鼓励作者<b>{$bookinfo['author']}</b>努力更新码字！</p>
    </if>
    </div>

    <!--火热推荐开始-->
    <div>
        <Hongshu:bangdan name="static_ios_readlater">
            {$row}
        </Hongshu:bangdan>
    </div>
    <!--火热推荐结束-->



</div>
<!--阅读结束-->

</block>