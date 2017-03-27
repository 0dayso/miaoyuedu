<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<div class="shuku top30">
  <div class="shuku1200 ">
    <div class="shuquantit">
      <p class="c9">
        <a class="cb" href="{:url('Index/index')}">首页</a> > <a class="cb" href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a> > <a class="cb" href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}">书圈</a> > <span class="c9">评论详情</span>
      </p>
    </div>
    <div class="main1138">
      <div class="sqleft">
        <div class="pl790 top30">
          <div class="tx50rq">
            <div class="tx50">
              <if condition="$commentinfo.avatar">
                <img src="{$commentinfo.avatar}" width="50" height="50">
              <else/>
                <img src="__IMG__/ic_person.jpg" width="50" height="50">
              </if>
            </div> 
          </div>
          
          <div class="pl_zt flrt">
            <a class="pl_zt_id c6">{$commentinfo.nickname}</a>
            <if condition="$commentinfo.forbidden_flag eq 1">
            <p class="cf00 xzhs">评论待审中...</p>
            </if>
            <p class="c3">{$commentinfo.content}</p>
            <div class="pl_xq" style="text-align: right;">
              <span class="c9">{$commentinfo.time}</span>
              <a class="c9 lf30">回复<span class="cb">({$replycount})</span></a>
              <if condition="$commentinfo.islight eq 1">
              <span class="cb lf30"><img class="fllf top3 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（{$commentinfo.zan_amount}）</span>
              <else/>
              <span class="cb lf30" id="dl" onclick="dianliang({$commentinfo.comment_id})"><img class="fllf top3 rt5" src="__IMG__/dp_g.png" width="10" height="16"><a class="c9">点亮</a>（{$commentinfo.zan_amount}）</span>
              <span class="cb lf30" id="ydl" style="display:none;"><img class="fllf top3 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（<span id="lnum">{$commentinfo.zan_amount}</span>）</span>
              </if>
            </div>
            <div class="bom10" style="overflow: hidden;">
              <div class="tx50rq top10">
                <div class="tx50" id="topavatar">
                  <img src="__IMG__/ic_person.jpg" width="50" height="50">
                </div> 
              </div>
              
              <div class="commentform  top10 clearfix " style="padding: 0; border: none; display: inline-block; float: right;">
                  <div id="pinglunkuang">
                      <textarea class="textarea04" placeholder="回复内容要在4-300字之间" id="text" onkeyup="checknum();"></textarea>
                  </div>
                  
                  <div class="plkd top5">
                    
                    <div class="plkd_z fllf">
                      
                    </div>
                      <div class="plkd_y flrt">
                        <a class="rt25 c9"><span id="num">0</span>/300</a>
                        <button id="topsend">发表回复</button>
                    </div>
                  </div>
              </div>
            </div>
            

            <div id="replylist">
              
            </div>
            <div class="pages top30 bom10 clearfix" style="text-align: right;" id="fenye">
            </div>
            
            
          
          </div>
          
        </div>
        
        
        
        
      </div>
      <div class="sqright flrt">
        <div class="pl_y_fm top30">
          
          <div class="pl_y_fmtp">
          <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']))}">
            <img src="{$bookinfo.bookface}"/ width="250" height="354"></a>
          </div>
          
            <div class="fm_zz bom20 top20">
              <div class="fm_zz_tx"><img src="{$bookinfo.author_avatar}" width="60" height="60"></div>
              <p>作者：<b>{$bookinfo.author}</b></p>
              
            </div>
            <p class="c6">作品类型：{$bookinfo.classname}</p>
            <p class="c6">最新更新：{$bookinfo.alllast_updatetime}</p>
            <p class="c6">开始连载：{$bookinfo.lzstart}</p>
            <p class="c6">作品字数：<php>echo floor($bookinfo['charnum']/10000);</php>万</p>
            <!-- <p class="c6">热度：{$bookinfo.fansval}</p>
            <p class="c6">点击数：{$bookinfo.total_hit}</p> -->
            <p class="c6">签约状态：<if condition="$bookinfo['shouquaninfo'] eq 9">上架<else/><if condition="$bookinfo['shouquaninfo'] gt 3">签约<else/>驻站</if></if></p>
        </div>
        
        
      </div>
    </div>
    
    
    
    
  </div>
</div>

</block>
<block name="script">
<script type="text/html" id="tpl_reply">
  {{each list as rows i}}
          <div class="pl_pl">
              <div class="tx24rq top5">
                <div class="tx24">
                  {{if rows.avatar}}
                  <img src="{{rows.avatar}}" width="24" height="24">
                  {{else}}
                  <img src="__IMG__/ic_person.jpg" width="24" height="24">
                  {{/if}}
                </div> 
              </div>
              <div class="pl_pl680 flrt">
                <a class="c6 rt5">{{rows.nickname}}</a>
                <p onmouseout="$(this).find('span').hide();" onmouseover="$(this).find('span').show();">{{#rows.content}}<span class="cb" style="display:none;" onclick="$('#text').attr('nickname','{{rows.nickname}}');$('#text').attr('placeholder','@{{rows.nickname}}');"><a class="cb" target="_self" href="#text">回复</a></span></p>
                <div class="pl_pl_xq">
                  <span class="c9">{{rows.time}}</span>
                </div>
              </div>
            </div>
  {{/each}}
</script>
<script type="text/javascript">
Do.ready('common','functions','template',function(){
  getreply();
   UserManager.addListener(function(user){
     if(user.islogin){
        $('#topavatar').html('<img src="'+user.avatar+'" width="50" height="50">');
        $('#topsend').on('click',function(){
           sendtucao();
        });
     }else{
        $('#topsend').on('click',function(){
           hg_Toast('请先登录');
        });
        $('#pinglunkuang').html('<div class="xq_wdl"><p class="c6"><a class="cb rt5" href="{:url('User/login')}">登录</a>后参与回复</p></div>');
     }
  });
});
function sendtucao(){
  var nickname=$('#text').attr('nickname');
  console.log(nickname);
  var content=$('#text').val();
  if(content.length<4 || content.length>300){
    hg_Toast("评论字数要在4~300字以内哟~");
    return;
  }
  var data = {
     bid:'{$commentinfo.bid}',
     comment_id:'{$commentinfo.comment_id}',
     content:content,
     nickname:nickname
  }
   var url = "{:url('Bookajax/addreply',array(),'do')}";
       $.ajax({
            type: "POST",
            url: url,
            data: data,
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                hg_Toast('吐槽成功！');
                Do.ready('template',function(){
                   var htmls=template('tpl_reply',data);
                   $('#replylist').append(htmls);
                   $('#text').val('');
                });
              }else{
                hg_Toast(data.message);
              }
           }
        });
}
  function getreply(pagenum){
    if (!pagenum) {
       var pagenum=1;
    }
   var data = {
     bid:'{$commentinfo.bid}',
     comment_id:'{$commentinfo.comment_id}',
     clientmethod:'getreply',
     pagenum:pagenum,
     pagesize:10,
     type:3
  }
   var url = "{:url('Bookajax/replyComment',array(),'do')}";
       $.ajax({
            type: "GET",
            url: url,
            data: data,
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                $('#fenye').html(data.pagelist)
                Do.ready('template',function(){
                   var htmls=template('tpl_reply',data);
                   $('#replylist').html(htmls);
                });
              }else{
                $('#replylist').html("<p class='c9'>暂无回复，沙发是你的！</p>");
              }
           }
        });
}
function dianliang(){
   var data = {
     bid:'{$commentinfo.bid}',
     comment_id:'{$commentinfo.comment_id}'
  }
   var url = "{:url('Bookajax/sendZan',array(),'do')}";
       $.ajax({
            type: "GET",
            url: url,
            data: data,
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                hg_Toast('点亮成功！');
                $('#lnum').html(data.zan_amount);
                $('#dl').hide();
                $('#ydl').show();  
              }else{
                hg_Toast('点亮失败！');
              }
           }
        });
}
function checknum(){
   var num=$('#text').val().length;
   $('#num').html(num);
   if(num>300 || num<4){
      /*hg_Toast("评论字数要在4~300字以内哟~");*/
      return;
   }
}

</script>
</block>