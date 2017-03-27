<extend name="Common/base" />
<block name="title">
    <title><notempty name="pageTitle">{$pageTitle}-{:C('CLIENT.'.CLIENT_NAME.'.name')}
<else />{:C('CLIENT.'.CLIENT_NAME.'.name')}
</notempty></title>
    <meta name="keywords" content="{$bookinfo.catename},{$bookinfo.smallsubclassname},{$bookinfo.author},{$bookinfo.tags|str_replace=' ',',',###}" />
    <meta name="description" content="{$bookinfo['intro']|strip_tags}" />
</block>
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<!--发表评论开始-->

    <div class="commentbd clearfix">
        <div class="tit5" >《<a href="{:url('Book/view', array('bid'=>$bookinfo['bid']))}">{$bookinfo['catename']}</a>》全部书评</div>
        <div class="tit4">发表评论<span id="iflogin"></span></div>

        <!-- <form action="" method="post"> -->
        <textarea placeholder="请输入评论" id="text" class="comcon radius4" onkeyup="initcheck();"></textarea>
        <p class="commentbdp"><span><em id="num">0</em>/200</span></p>
        <button type="button" class="disable radius4" id="tijiao">发表评论</button>
        <!-- </form> -->
    </div>

<!--发表评论结束-->
<!--评论开始-->
<div class="unit">
    <div class="tit "><h1>全部书评</h1><span  class="more" >共{$bookinfo['commentnum']}条</span></div>
    <div class="frame10 comments">
        <ul id="lists">
        </ul>
    </div>
    <!--更多按钮开始-->
    <div class="more2" id="loadmore"></div>
    <!--更多按钮结束-->
</div>
<!--评论结束-->
</block>
<block name="script">
<script>
    var uid="{$userinfo.uid}";
    var uid=parseInt(uid);
</script>
<!--底开始-->
<script type="text/javascript">

    function niceIn(prop){
        prop.find('i').addClass('niceIn');
        setTimeout(function(){
            prop.find('i').removeClass('niceIn');
        },1000);
    }
    $(function () {

        $.extend($,{
            tipsBox: function (options) {
                options = $.extend({
                    obj: null, //jq对象，要在那个html标签上显示
                    str: "+1", //字符串，要显示的内容;也可以传一段html，如: "<b style='font-family:Microsoft YaHei;'>+1</b>"
                    startSize: "12px", //动画开始的文字大小
                    endSize: "30px",  //动画结束的文字大小
                    interval: 600, //动画时间间隔
                    color: "red",  //文字颜色
                    callback: function () { }  //回调函数
                }, options);
                $("body").append("<span class='num'>" + options.str + "</span>");
                var box = $(".num");
                var left = options.obj.offset().left + options.obj.width() / 2;
                var top = options.obj.offset().top - options.obj.height();
                box.css({
                    "position": "absolute",
                    "left": left + "px",
                    "top": top + "px",
                    "z-index": 9999,
                    "font-size": options.startSize,
                    "line-height": options.endSize,
                    "color": options.color
                });
                box.animate({
                    "font-size": options.endSize,
                    "opacity": "0",
                    "top": top - parseInt(options.endSize) + "px"
                }, options.interval, function () {
                    box.remove();
                    options.callback();
                });
            }
        });


    });
</script>
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
            {{if row.forbidden_flag == 1}}
                {{if row.uid == uid && uid>0}}
            <li>
                <div class="lf">{{if row.avatar}}<img src="{{row.avatar}}" />{{else}}<img src="__IMG__/avater.jpg" />{{/if}}</div>
                <div class="rt">
                    <h4>{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if></h4>
                     <p class="cred">内容正在审核中</p>
                        <p class="cred">{{#row.content}}</p>
                    <p class="combom"><span class="fllf">{{row.time}}</span><!-- <a href="#" class="detail">详情</a><a href="#" class="reply">回复({row.})</a></p> -->
                </div>
            </li>
                {{else}}
              <li>
                <div class="lf">{{if row.avatar}}<img src="{{row.avatar}}" />{{else}}<img src="__IMG__/avater.jpg" />{{/if}}</div>
                <div class="rt">
                    <h4>{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if></h4>
                     <p class="cred">内容正在审核中</p>
                     <p class="combom"><span class="fllf">{{row.time}}</span></p>
                </div>
            </li>
                {{/if}}
            {{else}}
            <li>
                <div class="lf">{{if row.avatar}}<img src="{{row.avatar}}" />{{else}}<img src="__IMG__/avater.jpg" />{{/if}}</div>
                <div class="rt">
                    <h4 onClick="hg_gotoUrl('{{ {comment_id:row.comment_id,bid:row.bid} | router:'Book/replyComment','do'}}')">{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if></h4>
                     <p onClick="hg_gotoUrl('{{ {comment_id:row.comment_id,bid:row.bid} | router:'Book/replyComment','do'}}')">{{#row.content}}</p>
                     <p class="combom"><span class="fllf">{{row.time}}</span><a class="detail btn" id="zan_{{row.comment_id}}" data-id="{{row.comment_id}}">赞(<span>{{row.zan_amount}}</span>)</a><a onClick="hg_gotoUrl('{{ {comment_id:row.comment_id,bid:row.bid} | router:'Book/replyComment','do'}}')" class="reply">回复({{row.reply_amount}})</a></p>
                </div>
            </li>
            {{/if}}
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
var url = "{:url('Bookajax/comment', array('bid'=>$bookinfo['bid']),'do')}";
function zanClick(data) {
    Lazy.Load();
    if(data.hasOwnProperty('list') && data.list.length){
        for(var i=0;i<data.list.length;i++){
            var url = "{:url('Bookajax/sendZan','','do')}";
            $('#zan_'+data.list[i].comment_id).bind('click', function(){
                var that = this;
                var data = {
                    comment_id: $(this).attr('data-id')
                }
                var _obj = $(this);
                $.ajax({
                    type: 'get',
                    url: url,
                    data: data,
                    success: function(data) {
                        if (data.status == 1) {

                                $.tipsBox({
                                    obj: _obj,
                                    str: "+1",
                                    callback: function() {}
                                });
                                niceIn(_obj);
                            var zan = parseInt(_obj.find('span').html())+1;
                              _obj.find('span').html(zan);
                        } else {
                            hg_Toast(data.message);
                        }
                        $(that).unbind('click');
                    }
                });
            });
        }
    }
}
    Do.ready('lazyload','functions','template',function(){
      $('#text').on('keyup', function(){
       var length=$('#text').val().length;
       if (length==0){
           $('#tijiao').attr('disabled', true).addClass('disable');
       } else {
           $('#tijiao').removeAttr('disabled').removeClass('disable');
       }
       });
      $('.mu3').on('click',function(){
          $('.empty').hide();
      });
        loadMore(url,'lists','huanyihuan_tpl','append','first',{_callback:zanClick});
        document.onscroll = function(){
            //var footHeight = $('#footer').height();
            var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                loadMore(url,'lists','huanyihuan_tpl','append','next', {_callback:zanClick});
            }        }
    });
var is_reload = 1;

  function initcheck(){
    //$('#tijiao').removeAttr('disabled');
      var length=$('#text').val().length;
      $('#num').html(length);
  }
  //检查字数
  function checknum(){
       var length=$('#text').val().length;
       $('#num').html(length);
       if (length > 200) {
          $('#tijiao').attr('disabled','disable');
          hg_Toast("评论字数要在200以内哦！");
          $("#tijiao").removeAttr("disabled");
       }else if(length <= 0){
         $('#tijiao').attr('disabled','disable');
           hg_Toast("内容不能为空!");
       }else{
          $('#tijiao').removeAttr('disabled');
       }
  }
  function checknum2(){
       var length=$('#text2').val().length;
       if (length > 200) {
          $('#tijiao2').attr('disabled',true);
          hg_Toast("评论字数要在200以内哦！");
          $("#tijiao2").removeAttr("disabled");
       }else if(length <= 0){
         $('#tijiao2').attr('disabled',true);
           hg_Toast("内容不能为空!");
       }else{
          $('#tijiao2').removeAttr('disabled');
       }
  }
  function submit(){
    checknum();
      var url = "{:url('Bookajax/addComment','','do')}";
      var content = $('#text').val();
      var data = {
         bid:{$bookinfo['bid']},
         content:content,
      }
       $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data) {
            if(data.status == 1){
          hg_Toast(data.message);
          window.location.replace(data.url);
            }else{
        hg_Toast(data.message);
            }
        }
    });
  }
  function submit2(){
    checknum2();
      var url = "{:url('Bookajax/addComment','','do')}";
      var content = $('#text2').val();
      var data = {
         bid:{$bookinfo['bid']},
         content:content,
      }
       $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data) {
            if(data.status == 1){
          hg_Toast(data.message);
          window.location.replace(data.url);
            }else{
        hg_Toast(data.message);
            }
        }
    });
  }
  Do.ready("common",function(){
     UserManager.addListener(function(userinfo){
        $('#tijiao').unbind('click').bind('click',function(){
            if(userinfo.islogin){
              submit();
          }else{
             hg_Toast('请先登录');
          }
        });
        if(userinfo.islogin){
           var htmls='<span class="cpink flrt" style="font-size: 12px; text-align: right;width:120px;overflow:hidden;">{$userinfo['nickname']}</span>';
       }else{
           var htmls='<span class="cgray flrt" style="font-size: 12px; text-align: right;width:120px;overflow:hidden;">请<a href="{:url('User/login')}" class="cpink" >登录</a>后，才可评论</span>'
       }
       $('#iflogin').html(htmls);
 });
});
</script>
</block>