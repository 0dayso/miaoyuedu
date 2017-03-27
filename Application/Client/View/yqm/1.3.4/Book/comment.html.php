<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<div class="login_tc" style="display:none;">
    <div class="">
                <div class="flrt" onclick="$('.login_tc').hide();"><img class="guanbi" src="__IMG__/guanbi.png" height="20" width="20"></div>
                <div class="form-warp clearfix ">
                    <form method="post" action="" onsubmit="">
                        <div class="form-tit3 bom20 clearfix c6">
                            <h3>请登录后再操作 <a style="font-size: 14px;" class="cb lf15" href="{:url('User/register')}">注册</a></h3>
                        </div>
                        <p class="clearfix">
                            <input class="" type="text" id="input_user" placeholder="注册用户名" autocomplete="off" onkeyup="checklogin();">
                        </p>
                        
                        <p class="passwordp clearfix">
                            <a class="eye-open" onclick="showpwd();"></a>
                            <input class="password" type="password" name="password" id="input_pwd" maxlength="20" placeholder="密码" autocomplete="off" onkeyup="checklogin();">
                        </p>
                        
                        <p class="clearfix">
                            <input name="url" type="hidden" value="">
                            <input name="sign" type="hidden" value="">
                            <input name="logining" type="hidden" value="">
                            <input type="button" value="登录" class="submitbtn" id="denglu" onclick="login(this);">
                        </p>
                        <p class="forgot">
                            <label style="line-height: 25px; height: 25px">
                                <input type="checkbox" tabindex="1" name="remeber" id="box" class="checkbox"> 记住我
                            </label>
                            <!-- <a class="forgot-password" target="_blank" href="#">忘记密码？</a> -->
                        </p>
                        <input type="hidden" name="__hash__" value="">
                    </form>
                </div>
                
                
                
            </div>
</div>
<div class="shuku top30">
  <div class="shuku1200 ">
    <div class="shuquantit">
      <h1 class="c3">书圈：<a class="cb" href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">《{$bookinfo.catename}》</a></h1>
      <p class="c9 top10">
        <span>作者：<a class="cb" href="#">{$bookinfo.author}</a></span>
        <!-- <span class="lf20">圈主：<a class="cb" href="#">XXXXXXXXXXX</a></span>
        <span class="lf20">副圈主：<a class="cb" href="#">XXXXXXXXXXX</a><a class="cb lf15" href="#">XXXXXXXXXXX</a></span> -->
      </p>
    </div>

    <div class="shuquanbiaoqian top20">
      <a class="c3 active" onclick="$('#dls').removeClass('active');$(this).addClass('active');getcomments(1);" id="mr">按默认</a>
      <a class="c3 lf20" onclick="$(this).addClass('active');$('#mr').removeClass('active');getcomments(1,'zan');" id="dls">按点亮数</a>
      <span class="c9 flrt">粉丝（{$bookinfo.fansnum}）</span>
      <span class="c9 flrt rt20">讨论（{$bookinfo.commentnum}）</span>
    </div>

    <div class="main1138 top20">
      <div class="sqleft">
        <div class="pl790 top30">

          <div class="tx50rq">
            <div class="tx50" id="topavatar">
              <img src="__IMG__/ic_person.jpg" width="50" height="50">
            </div>
          </div>

          <div class="commentform  bom20 clearfix " style="padding: 0; border: none; display: inline-block; float: right;">

              <div id="pinglunkuang">
                  <textarea class="textarea01" placeholder="评论内容要在4-1000字之间" id="text" onkeyup="checknum();"></textarea>
              </div>

              <div class="plkd top5">

                <div class="plkd_z fllf">
                  <a class="c9" type="0" id="ywz">_(:з」∠)_ 颜文字</a>
                  <foreach name="tags" item="row">
                  <a class="c9 empty">#{$row}#</a>
                  </foreach>
                  <div class="biaoqing_box" id="biaoqing_box" style="display:none;"><a>(⌒▽⌒)</a><a>（￣▽￣）</a><a>(=・ω・=)</a><a>(｀・ω・´)</a>
                    <a>(〜￣△￣)〜</a><a>(･∀･)</a><a>(°∀°)ﾉ</a><a>(￣3￣)</a><a>╮(￣▽￣)╭</a><a>( ´_ゝ｀)</a>
                    <a>←_←</a><a>→_→</a><a>=。=</a><a>(;¬_¬)</a><a>("▔□▔)/</a><a>(ﾟДﾟ≡ﾟдﾟ)!?</a>
                    <a>Σ(ﾟдﾟ;)</a><a>Σ( ￣□￣||)</a><a>(´；ω；`)</a><a>（/TДT)/</a><a>(^・ω・^ )</a>
                    <a>(｡･ω･｡)</a><a>(●￣(ｴ)￣●)</a><a>ε=ε=(ノ≧∇≦)ノ</a><a>(´･_･`)</a><a>(-_-#)</a><a>（￣へ￣）</a>
                    <a>(￣ε(#￣) Σ</a><a>ヽ(`Д´)ﾉ</a><a>(╯°口°)╯(┴—┴</a><a>（#-_-)┯━┯</a><a>_(:3」∠)_</a><a>(￣︶￣)</a>
                  </div>
                  <div class="biaoqing_box" id="biaoqian_box" style="display:none;"></div>
                </div>
                  <div class="plkd_y flrt">
                    <a class="rt25 c9"><span id="num">0</span>/1000</a>
                    <button id="topsend">发表评论</button>
                </div>

              </div>

          </div>

        </div>
      <div >

       <div id="commentlist">
      </div><span id="zhuangtai"></span>

        <div class="pages top30 bom5 clearfix" style="text-align: right;" id="fenye">

        </div>



      </div>
    </div>
    <div class="sqright flrt">
        <!-- <div class="tag top20" style="padding: 15px; background-color: #f2f2f2; border-radius: 6px;">
          <p>TAG:</p>
          <foreach name="bookinfo['tagsary']" item="row">
          <span>{$row}</span>
          </foreach>
        </div> -->

        <ul class="fsb top30">
          <p>粉丝榜：</p>
          <foreach name="bookinfo['fanslist']" item="row" key="k">
            <li>
            <if condition="$k lt 3">
              <span class="fsb_q3 fllf rt20">
            <else/>
              <span class="fsb_hq fllf rt20">
            </if>{$k+1}</span>
              <div class="tx36rq fllf rt20">
                <div class="tx36">
                   <if condition="$row.avatar neq ''">
                    <img src="{$row.avatar}" width="36" height="36">
                   <else/>
                    <img src="__IMG__/ic_person.jpg" width="36" height="36">
                   </if>
                </div>
              </div>
              <a>{$row.nickname}</a>
            </li>
          </foreach>
        </ul>

      </div>



  </div>
</div>
</block>
<block name="script">
<script type="text/html" id="tpl_comments">
{{each list as row i}}
<div class="pl790 top20 mk_comments" data-id="{{row.comment_id}}">
    <div class="tx50rq">
       <div class="tx50">
       {{if row.avatar}}
          <img src="{{row.avatar}}" width="50" height="50">
       {{else}}
          <img src="__IMG__/ic_person.jpg" width="50" height="50">
       {{/if}}
       </div>
    </div>

    <div class="pl_zt flrt">
       <a class="pl_zt_id c6">{{row.nickname}}</a>
       {{if row.forbidden_flag == 1}}
          {{if row.uid == uid && uid>0}}
           <p class="cf00 xzhs">评论待审中...</p>
           <p class="c3 xzhs" id="nr{{row.comment_id}}"><a class="cb xzhs_xq" onClick="doChild('{{ {bid:{$bookinfo['bid']},comment_id:row.comment_id} | router:'Book/replyComment','do'}}')" style="display:none;" id="xqrk{{row.comment_id}}">详情</a>{{#row.content}}</p>
          {{else}}
           <p class="cf00 xzhs">评论待审中...</p>
          {{/if}}
       {{else}}
         <p class="c3 xzhs" id="nr{{row.comment_id}}"><a class="cb xzhs_xq" onClick="doChild('{{ {bid:{$bookinfo['bid']},comment_id:row.comment_id} | router:'Book/replyComment','do'}}')" style="display:none;" id="xqrk{{row.comment_id}}">详情</a>{{#row.content}}</p>
       {{/if}}
       <div class="pl_xq">
          <!-- <span class="c9">#{{row.floor}}</span> -->
          <span class="c9">{{row.time}}</span>
          <a class="c9 lf30 mk_hf" type="0" id="hf{{row.comment_id}}">回复<span class="cb">(<span id="znum{{row.comment_id}}">{{row.reply_amount}}</span>)</span></a>
          {{if row.islight == 1}}
          <span class="cb lf30"><img class="fllf top3 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（{{row.zan_amount}}）</span>
          {{else}}
          <span class="cb lf30 mk_dl" id="dl{{row.comment_id}}" ><img class="fllf top3 rt5" src="__IMG__/dp_g.png" width="10" height="16"><a class="c9">点亮</a>（{{row.zan_amount}}）</span>
          <span class="cb lf30 mk_ydl" id="ydl{{row.comment_id}}" style="display:none;"><img class="fllf top3 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（<span id="l{{row.comment_id}}">{{row.zan_amount}}</span>）</span>
          {{/if}}
       </div>
       <!-- <div id="rzj{{row.comment_id}}"></div> -->
       <div id="r{{row.comment_id}}">
          <!-- {{each row.replylist as rows i}}
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
                <p onmouseout="$(this).find('span').hide();" onmouseover="$(this).find('span').show();">{{rows.content}}<span class="cb" style="display:none;" onclick="showhuifukuang({{rows.comment_id}},'{{rows.nickname}}');"> 回复</span></p>
                <div class="pl_pl_xq">
                  <span class="c9">{{rows.time}}</span>
                </div>
              </div>
            </div>
          {{/each}} -->
        </div>
        {{if row.reply_amount>2}}
          <div style="overflow: hidden;">
          <a class="cb flrt mk_zk" id="zk{{row.comment_id}}" style="display:none;">更多回复 ↓</a>
          <a class="cb flrt mk_sq" id="sq{{row.comment_id}}" style="display:none;">收起↑</a>
          </div>
          {{/if}}
        <div id="k{{row.comment_id}}" style="display:none;">
              <div class="tx50rq top5">
                <div class="tx50 emptyavatar">
                  <img src="__IMG__/ic_person.jpg" width="50" height="50">
                </div>
              </div>

              <div class="commentform  top5 clearfix " style="padding: 0; border: none; display: inline-block; float: right;">
                  <div id="plk{{row.comment_id}}">
                      <textarea class="textarea02" placeholder="请输入内容" id="text{{row.comment_id}}"></textarea>
                  </div>

                  <div class="plkd top5">

                    <div class="plkd_z fllf">

                    </div>
                      <div class="plkd_y flrt">
                        <button id="tucao{{row.comment_id}}">发表回复</button>
                    </div>

                  </div>
              </div>
            </div>

            </div>


          </div>
</div>
{{/each}}
</script>
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
                <p onmouseout="$(this).find('span').hide();" onmouseover="$(this).find('span').show();">{{#rows.content}}<span class="cb" style="display:none;" onclick="showhuifukuang({{rows.comment_id}},'{{rows.nickname}}');"><a class="cb" onclick="$('#text{{rows.comment_id}}').focus();">回复</a></span></p>
                <div class="pl_pl_xq">
                  <span class="c9">{{rows.time}}</span>
                </div>
              </div>
            </div>
  {{/each}}
  {{if type==0}}
         <div class="pages top10 clearfix" style="text-align: right;">{{#pagelist}}</div>
  {{/if}}
</script>
<script type="text/javascript">
Do.ready('common', function(){
    $('.login_tc').keyup(function(event) {
        switch (event.keyCode) {
            case 13:
                login();
                break;
        }
    });
});
function checklogin(){
    $('#denglu').addClass('gray');
    $('#denglu').attr('disabled',true);
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    if(userName&&pwd){
        $('#denglu').removeClass('gray');
        $('#denglu').removeAttr('disabled');
        return;
    }
    
}
function login(obj){
    var userName=$("#input_user").val();
    var pwd=$("#input_pwd").val();
    var url="{:url('Userajax/login',array(),'do')}";
    var checked = $("#box").is(":checked");
    if(checked){
        remember=1;
    }else{
        remember=0;
    }
    lockbutton(obj);
    $.ajax({
        url: url,
        type: "POST",
        data: {username:userName,password:pwd,remember:remember},
        dataType: 'json',
        complete:function(){
           unlockbutton(obj);
        },
        success: function(json){
            if(json.status==1){
                window.location.reload();
            }
            else {
                hg_Toast(json.message);
            }
        }});
}
function lockbutton(obj) {
    $(obj).attr("onclick","");
    $(obj).val("请稍候…");
}

function unlockbutton(obj) {
    $(obj).attr("onclick","login(this)");
    $(obj).val("登录");
}
function showpwd(){
    var type=$('#input_pwd').attr('type');
    if (type=="password") {
       $('#input_pwd').attr('type','text');
       $('.eye-open').removeClass('eye-open').addClass('eye-closed');
    }else{
       $('#input_pwd').attr('type','password');
       $('.eye-closed').addClass('eye-open').removeClass('eye-closed');
    }
}
Do.ready('common','functions',function(){
  $('#ywz').on('click',function(){
    var type=$('#ywz').attr('type');
    if (type==0) {
      $('#ywz').attr('type','1');
      $('#biaoqing_box').show();
    }else{
      $('#ywz').attr('type','0');
      $('#biaoqing_box').hide();
    }
  });
  $('#biaoqing_box').find('a').on('click',function(){
     var biaoqing=$(this).html();
     var htmls=$('#text').val()+biaoqing;
     $('#text').val(htmls);
  });
  $('.empty').on('click',function(){
    var biaoqian=$(this).html();
    var htmls=$('#text').val()+biaoqian;
    $('#text').val(htmls);
  });
  Do.ready('template',function(){
     getcomments();
  });
   UserManager.addListener(function(user){
    islogin=user.islogin;
    uid=user.uid;
     if(user.islogin){
        $('#topavatar').html('<img src="'+user.avatar+'" width="50" height="50">');
        $('.emptyavatar').html('<img src="'+user.avatar+'" width="50" height="50">');
        $('#topsend').on('click',function(){
           sendtucao();
        });
     }else{
        $('#topsend').on('click',function(){
           hg_Toast('请先登录');
           $('.login_tc').show();$('#input_pwd').focus();
        });
        $('#pinglunkuang').html('<div class="pl_wdl"><p class="c6"><a class="cb rt5" href="{:url('User/login')}">登录</a>后参与评论</p></div>');
     }
  });
});


function getcomments(pagenum){
  var type="";
  if($('#dls').hasClass('active')){
    type="zan";
  }
  var data = {
     bid:'{$bookinfo.bid}',
     pagesize:10,
     type:type,
     clientmethod:'getcomments',
  }
  data[hsConfig.PAGEVAR]=pagenum;
   var url = "{:url('Bookajax/getComments',array(),'do')}";
       $.ajax({
            type: "GET",
            url: url,
            data: data,
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                data['uid']=uid;
                var htmls=template('tpl_comments',data);
                $('#commentlist').html(htmls);
                replyevents();
                $('#fenye').html(data.pagelist);
              }else{
                $('#zhuangtai').html('暂无评论');
              }
           }
        });

}

function checknum(){
   var num=$('#text').val().length;
   $('#num').html(num);
   if(num>1000 || num<4){
      /*hg_Toast("评论字数要在4~1000字以内哟~");*/
      return;
   }
}
function sendtucao(){
  var content=$('#text').val();
  if(content.length<4 || content.length>1000){
    hg_Toast("评论字数要在4~1000字以内哟~");
    return;
  }
  var data = {
     bid:'{$bookinfo.bid}',
     content:content
  }
   var url = "{:url('Bookajax/addComment',array(),'do')}";
       $.ajax({
            type: "POST",
            url: url,
            data: data,
            timeout: 9000,
            success: function (data) {
              if(data.status == 1){
                hg_Toast('吐槽成功！');
                data['uid']=uid;
                /*console.log(data);*/
                Do.ready('template',function(){
                   var htmls=template('tpl_comments',data);
                   $('#commentlist').prepend(htmls);
                   $('#text').val('');
                   $('#biaoqing_box').hide();
                   $('#zhuangtai').hide();
                });
              }else{
                hg_Toast(data.message);
              }
           }
        });
}

function showhuifukuang(commentid,nickname){
   $('#k'+commentid).show();
   $('#k'+commentid).attr('nickname',nickname);
   $('#k'+commentid).find('textarea').attr('placeholder','@'+nickname);
}
/**
 *
 * @param int pagenum 页码
 * @param int commentid 评论ID
 * @param unknow type ?
 */
function getreplies(pagenum, param) {
    //var param = {commentid: commentid, type: type};
    var commentid = param.commentid;
    $('#r'+param.commentid).find('.pages').remove();
    var type = param.type;
    var data = {
        ajax: 1,
        bid:'{$bookinfo.bid}',
        comment_id:commentid,
        type:type,
        clientmethod:'getreplies',
        pagepara: param,
        pagesize:5
    }
    data[hsConfig.PAGEVAR]=pagenum;
    //console.log(data);
    var url = "{:url('Bookajax/replyComment',array(),'do')}";
    $.ajax({
        type: "GET",
        url: url,
        data: data,
        timeout: 9000,
        success: function (data) {
            if(data.status == 1){
                Do.ready('template',function(){
                    var htmls=template('tpl_reply',data);
                    if(data.pagenum==1){
                    $('#r'+commentid).html(htmls);
                  }else{
                    $('#r'+commentid).append(htmls);
                  }
                });
            }else{
                /*hg_Toast(data.message);*/
            }
            if (data.type==1) {
                $('#zk'+commentid).show();
                $('#sq'+commentid).hide();
                $('#r'+commentid).removeClass('pl_pl_rq');
                $('.pl_pl660').addClass('pl_pl680');
                $('.pl_pl660').removeClass('pl_pl660');
            }else{
                $('#zk'+commentid).hide();
                $('#sq'+commentid).show();
                $('#r'+commentid).addClass('pl_pl_rq');
                $('.pl_pl680').addClass('pl_pl660');
                $('.pl_pl680').removeClass('pl_pl680');
            }
        }
    });
}

function replyevents(){
 //绑定评论事件
    var Commentlist=$('#commentlist').find('.mk_comments');
    $.each(Commentlist,function(){
       var commentid=$(this).attr('data-id');
       //绑定回复按钮事件
       $(this).on('click','.mk_hf',function(){
          var type=$('#hf'+commentid).attr('type');
          if(type==0){
              $('#zk'+commentid).show();
              $('#k'+commentid).show();
              getreplies(1,{commentid:commentid,type:1});
              $('#hf'+commentid).attr('type','1');
          }else{
              $('#zk'+commentid).hide();
              $('#k'+commentid).hide();
              $('#r'+commentid).html('');
              $('#hf'+commentid).attr('type','0');
          }
          if(!islogin){
             $('#plk'+commentid).html('<div class="hf_wdl"><p class="c6"><a class="cb rt5" href="{:url('User/login')}">登录</a>后参与回复</p></div>');
             return;
          } 
          
       });

       //绑定点亮事件
       $(this).on('click','.mk_dl',function(){
          if(!islogin){
              hg_Toast('请先登录！');
              $('.login_tc').show();$('#input_pwd').focus();
              return;
          }
          var data = {
             bid:'{$bookinfo.bid}',
             comment_id:commentid,
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
                        $('#l'+commentid).html(data.zan_amount);
                        $('#dl'+commentid).hide();
                        $('#ydl'+commentid).show();
                      }else{
                        hg_Toast('点亮失败！');
                      }
                   }
            });
       });

       //显示详情入口
       $('#nr'+commentid).on('mouseout',function(){
           $('#xqrk'+commentid).hide();
       });
       $('#nr'+commentid).on('mouseover',function(){
           $('#xqrk'+commentid).show();
       });

       //绑定展开收起回复事件
       $(this).on('click','.mk_zk',function(){
           getreplies(1, {commentid:commentid,type:0});
       });
       $(this).on('click','.mk_sq',function(){
           getreplies(1, {commentid:commentid,type:1});
       });

       //绑定发表回复事件
       $('#tucao'+commentid).on('click',function(){
           var content=$('#text'+commentid).val();
           var nickname=$('#k'+commentid).attr('nickname');
           console.log(nickname);
           if(content.length<4 || content.length>300){
            hg_Toast("评论字数要在4~300字以内哟~");
            return;
          }
          var data = {
             bid:'{$bookinfo.bid}',
             comment_id:commentid,
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
                           $('#r'+commentid).append(htmls);
                           $('#text'+commentid).val('');
                           //$('#k'+commentid).hide();
                           var num=parseInt($('#znum'+commentid).html())+1;
                           $('#znum'+commentid).html(num);
                        });
                      }else{
                        hg_Toast(data.message);
                      }
                   }
              });
       });




    });
}
</script>
</block>