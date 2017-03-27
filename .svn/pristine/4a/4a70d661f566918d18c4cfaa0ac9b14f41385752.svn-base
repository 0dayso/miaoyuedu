<extend name="Common/base" />
<block name="body">
<div class="tit5" >《{$bookinfo['catename']}》全部书评</div>
<!--评论开始-->
<div class="unit">
    <div class="frame10 comments">
        <ul>
            <li >
                <div class="lf"><img src="__IMG__/avater.jpg" /></div>
                <div class="rt">
                    <h4>{$commentinfo.nickname}<if condition="$commentinfo.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$commentinfo.lcomment eq 1"><span class="ic_cp">长评</span></if></h4>
                    <p>{$commentinfo.content} </p>
                    <p class="combom"><span class="fllf">{$commentinfo.time}</span></p>
                </div>
            </li>
        </ul>
    </div>
</div>
<!--回复开始-->
<div class="unit">
<div class="tit "><h1>回复<span  class="more"  style="font-size: 12px;">({$replycount})</span></h1></div>
    <div class="frame10 comments">
        <ul id="lists">
        </ul>
    </div>
</div>
<!--评论结束-->
<!--发表评论开始-->
<div class="pinglun">
    <div class="plcon">
    <div class="lf"></div>
        <div class="center center2"><textarea class="pltext radius4" id="text2" placeholder="说说你的看法"></textarea></div>
        <div class="rt"><button class="plbtn radius2" disabled="disabled" id="tijiao2" onclick="submit2();">回复</button></div>
    </div>
</div>
<!--发表评论结束-->
</block>
<block name="script">
<script>
    var uid="{$userinfo.uid}";
    var uid=parseInt(uid);
</script>
<!--底开始-->
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
            {{if row.forbidden_flag == 1}} 
                {{if row.uid == uid}}
            <li>
                <div class="lf"><img src="__IMG__/avater.jpg" /></div>
                <div class="rt">
                    <h4>{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if><span class="lou flrt">{{row.floor}}楼</span></h4>
                     <p class="cred">内容正在审核中</p>
                        <p class="cred">{{row.content}}</p>
                        <p class="combom"><span class="fllf">{{row.time}}</span></p>
                </div>
            </li>
                {{else}}
                            <li>
                <div class="lf"><img src="__IMG__/avater.jpg" /></div>
                <div class="rt">
                    <h4>{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if><span class="lou flrt">{{row.floor}}楼</span></h4>
                     <p class="cred">内容正在审核中</p>
                     <p class="combom"><span class="fllf">{{row.time}}</span>
                </div>
            </li>
                {{/if}}
            {{else}}
            <li>
                <div class="lf"><img src="__IMG__/avater.jpg" /></div>
                <div class="rt">
                    <h4>{{row.nickname}}<if condition="$row.highlight_flag eq 1"><span class="ic_jh">精华</span></if><if condition="$row.lcomment eq 1"><span class="ic_cp">长评</span></if><span class="lou flrt">{{row.floor}}楼</span></h4>
                     <p>{{row.content}}</p>
                     <p class="combom"><span class="fllf">{{row.time}}</span></p>
                </div>
            </li>
            {{/if}}
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
var url = "{:url('Bookajax/replyComment', array('bid'=>$bookinfo['bid'],'comment_id'=>$commentinfo['comment_id']))}";

    Do.ready('lazyload','functions','template',function(){
      $('#text2').on('keyup', function(){
       var length=$('#text2').val().length;
       if (length==0){
           $('#tijiao2').attr('disabled', true).addClass('disable');
       } else {
           $('#tijiao2').removeAttr('disabled').removeClass('disable');
       }
       });
        loadMore(url,'lists','huanyihuan_tpl','append','first');
        document.onscroll = function(){
            //var footHeight = $('#footer').height();
            var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                loadMore(url,'lists','huanyihuan_tpl','append','next');
            }
            Lazy.Load();
        }
    });
var is_reload = 1;
  //检查字数
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
  function submit2(){
    checknum2();
      var url = "{:url('Bookajax/addreply')}";
      var content = $('#text2').val();
      var data = {
         bid:{$bookinfo['bid']},
         comment_id:{$commentinfo['comment_id']},
         content:content,
      }
       $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data) {
          console.log(data);
            if(data.status == 1){
          hg_Toast(data.message);
          window.location.href=data.url;
            }else{
          hg_Toast(data.message);
            }
        }
    });
  }
</script>
</block>