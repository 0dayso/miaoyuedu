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
<!--封面-->
<div class="main top25 bom60">
    <div class="main1200">

        <div class="fm_left">
            <div class="fmy bom60">
                <div class="fmtp rt25">
                    <img src="{$bookinfo['bid']|getBookfacePath=###,'middle'}" width="200" height="282" />
                </div>
                <div class="fmwz">
                    <h1 class="c3">{$bookinfo.catename}</h1>
                    <a class="c8">萌神：{$bookinfo['author']}</a>
                    <p class="c6">{$bookinfo['intro']}</p>
                </div>
            </div>
            <php>var_</php>
            <div class="zhangjie">
                <p class="c3">{$bookinfo.catename} -目录</p>
                <div id="lists" pagenum="1"></div>


                <div id="fenye" class="top20 bom5 clearfix">
                    <!-- <a href="#" class="current">查看完整章节</a> -->
                </div>

            </div>

            <div class="chahua top30" id="chahua">
                <p class="c3">{$bookinfo.catename} -插画</p>
                <div class="chahuapt" id="list2">
                </div>
            </div>

            <div class="zuixinzhangjie top30">
                <p class="c3 zjtit">{$bookinfo.catename} -最新章节</p>
                <div id="list3">
                    <a class="gdbtn flrt top10" href="{:url('Book/read', array('bid'=>$bookinfo['bid'], 'chpid'=>0),'html')}">开始阅读 &gt;</a>
                </div>
            </div>
        </div>
        
        <div class="fm_right" >
         <div id="list4"></div>
         
                
            
            <div class="fm_plq top30">
                <p>评论区：</p>
                <foreach name="comments" item="row">
                <div class="fm_pl top15">
                    <p>{$row['content']}</p>
                    <div class="plyh">
                        <span class="lf10">{$row['nickname']}</span>
                        <if condition="$row.islight eq 1">
                        <span class="flrt rt10"><img class="fllf top6 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（{$row['zan_amount']}）</span>
                        <else/>
                        <span class="flrt rt10 empty" id="l{$row['comment_id']}" data-id="{$row['comment_id']}"><img class="fllf top6 rt5" src="__IMG__/dp_g.png" width="10" height="16"><a class="c6">点亮</a>（{$row['zan_amount']}）</span>
                        <span class="flrt rt10" id="ydl{$row['comment_id']}" style="display:none;"><img class="fllf top6 rt5" src="__IMG__/dp_b.png" width="10" height="16"><a class="cb">已点亮</a>（<span id="znum{$row['comment_id']}">{$row['zan_amount']}</span>）</span>
                        </if>
                    </div>
                </div>
                </foreach>
                <div class="gdbtn top15 rt20 flrt" onclick="hg_gotoUrl('{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}')"><a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}">更多评论 ></a></div>
            </div>
            
        </div>
    </div>
</div>

</block>
<block name="script">
<script type="text/html" id="tpl1">
    {{each list as row i}}
    {{if row.juantitle}}
    </ul>
    <span>{{row.juantitle}}</span>
    <ul>
    {{/if}}
        {{if row.isvip==1}}
        <li onclick="doChild('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/readvip','do'}}')">{{row.title}}<div class="fm_biaoshi">{{if row.renshe}}<img src="__IMG__/fm_rs.png" width="20" height="20">{{/if}}{{if row.chahua}}<img src="__IMG__/fm_ch.png" width="20" height="20">{{/if}}</div></li>
        {{else}}
        <li onclick="doChild('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','html'}}')">{{row.title}}<div class="fm_biaoshi">{{if row.renshe}}<img src="__IMG__/fm_rs.png" width="20" height="20">{{/if}}{{if row.chahua}}<img src="__IMG__/fm_ch.png" width="20" height="20">{{/if}}</div></li>
        {{/if}}
    {{/each}}
        </ul>
</script>
<script type="text/html" id="tpl2">
    {{each imglist as row i}}
    <a href=""><img src="{{row.picurl_thumb}}"/ width="180"></a>
    {{/each}}
</script>
<script type="text/html" id="tpl3">
    <div class="zuixin">
    <h1>{{chapterlist.title}}</h1>
    {{#chapterlist.content}}</div>
    {{if chapterlist.isvip ==1}}
    <div class="gdbtn flrt top10" onClick="doChild('{{ {bid:chapterlist.bid,chpid:chapterlist.chapterid} | router:'Book/read','do'}}')">
    {{else}}
    <div class="gdbtn flrt top10" onClick="doChild('{{ {bid:chapterlist.bid,chpid:chapterlist.chapterid} | router:'Book/read','html'}}')">
    {{/if}}继续阅读 ></div>
</script>
<script type="text/html" id="tpl4">
    <div class="fm_zz bom20">
            <div class="fm_zz_tx">{{if taglist.authorimg}}<img width="60" height="60" src="{{taglist.authorimg}}">{{else}}<img width="60" height="60" src="__IMG__/zztx_200.jpg">{{/if}}</div>
                <p>萌神：<b>{{taglist.authorname}}</b></p>

            </div>
            <p>作品类型：{{taglist.classname}}</p>
            <p>最新更新：{{taglist.lastupdatetime}}</p>
            <p>开始连载：{{taglist.lzdate}}({{if taglist.lzinfo==1}}完本{{else}}连载{{/if}})</p>
            <p>作品字数：{{taglist.charnum}}万字</p>
            <!-- <p>热度：{{taglist.fansnum}}</p>
            <p>点击数：{{taglist.totalhit}}</p> -->
            <p>签约状态：{{if taglist.shouquaninfo==9}}上架{{else if taglist.shouquaninfo>3&&taglist.shouquaninfo!=9}}签约{{else}}驻站{{/if}}</p>
            <!-- <p>最高排名：{$ranking}</p> -->
            <!-- <p class="top15">吐槽（<span class="cdy">{$tucao}</span>） 收藏（<span class="cdy">{$shoucang}</span>）</p> -->

            <div class="fm_right_btn top25">
                {{if taglist.isvip==1}}
                <div class="manhua_btn" style="margin-right: 20px;" onClick="doChild('{{ {bid:taglist.bid,chpid:taglist.lastreadchpid} | router:'Book/read','do'}}')">
                {{else}}
                <div class="manhua_btn" style="margin-right: 20px;" onClick="doChild('{{ {bid:taglist.bid,chpid:taglist.lastreadchpid} | router:'Book/read','html'}}')">
                {{/if}}
                    {{if taglist.isread == 1}}<span>继续阅读</span>{{else}}<span>立即阅读</span>{{/if}}
                    <img src="__IMG__/gd.png">
                </div>
                {{if taglist.isfav}}
                <div class="sc_btn" style="display:block;" id="ysc">
                    <img src="__IMG__/ysc.png" style="margin-left: 9px;">
                    <span>已收藏</span>
                </div>
                {{else}}
                <div class="sc_btn" style="display:block;" id="sc">
                    <img src="__IMG__/sc.png">
                    <span>收藏</span>
                </div>
                <div class="sc_btn" style="display:none;" id="ysc">
                    <img src="__IMG__/ysc.png" style="margin-left: 9px;">
                    <span>已收藏</span>
                </div>
                {{/if}}

            </div>
            <div class="bom30" onClick="doChild('{{ {bid:taglist.bid,chpid:taglist.lastupdatechpid} | router:'Book/read'}}')"><span class="flrt rt25 c9">{{taglist.lastudtime}}更新></span></div>
            {{if taglist.tags}}
            <div class="tag">
              <p>TAG:</p>
              {{each taglist.tags as row i}}
                <span>{{row}}</span>{{/each}}
            </div>
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
    function refreshchp(pagenum){
        var url="{:url('Bookajax/chapterlist',array(),'do')}";
        if(!pagenum){
        var pagenum=parseInt($('#lists').attr('pagenum'));
     }
        var nextpagenum = pagenum + 1;
        var Data = {
            bid:'{$bookinfo.bid}',
            pagesize:20,
            clientmethod:'refreshchp',
            ajax:1
        }
		Data[hsConfig.PAGEVAR]=pagenum;
        $.ajax({
            url:url,
            //dataType:'get',
            data:Data,
            error:function(data){
                hg_Toast('没有找到合适的记录！');
            },
            success:function(data){
                //console.log(data);
                Do.ready('lazyload','template', function(){
                    if(parseInt(data.totalpage) > 0){
                        $('#fenye').html(data.pagelist);
                        var htmls = template('tpl1',data);
                        $('#lists').html(htmls);
                        if(pagenum <= data.totalpage){
                            $("#lists").attr('pagenum',nextpagenum);
                        }else{
                            hg_Toast('没有更多了哟~');
                        }
                    }else{
                        $('#lists').html('暂无最新章节');
                    }
                });
            }
        });
    }
    function authorinfo(){
        var url="{:url('Bookajax/view',array(),'do')}";
        var Data = {
            bid:'{$bookinfo.bid}',
            imgnum:4,
        }
        $.ajax({
            url:url,
            //dataType:'get',
            data:Data,
            error:function(data){
                hg_Toast('没有找到合适的记录！');
            },
            success:function(data){
            	if(data.status==1 && data['taglist']){
                data['taglist']['charnum']=Math.floor(data['taglist']['charnum']/10000);
                }
                //console.log(data['taglist']['charnum']);
                Do.ready('lazyload','template', function(){
                    if(data.status == 1){
                        var html4 = template('tpl4',data);
                        $('#list4').html(html4);
                        var html3 = template('tpl3',data);
                        $('#list3').html(html3);
                        if(data['imglist'].length!=0){
                           var html2= template('tpl2',data);
                           $('#list2').html(html2);
                       }else{
                           $('#chahua').hide();
                       }
                        if(data.uid){
                          $('#sc').on('click',function(){
                             shoucang();
                          });
                       }else{
                        $('#sc').on('click',function(){
                          hg_Toast('请先登录！');
                      });
                        }
                    }else{
                        hg_Toast('没有找到合适的记录！');
                    }
                });
            }
        });
    }

     function dianliang(commentid){
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
                        $('#l'+commentid).hide();
                        $('#ydl'+commentid).show();
                        var num=parseInt($('#znum'+commentid).html())+1;
                        $('#znum'+commentid).html(num);
                      }else{
                        hg_Toast('点亮失败！');
                      }
                   }
            });
     }

     function shoucang(){

        var url="{:url('Userajax/insertfav',array(),'do')}";
        var Data = {
            bid:'{$bookinfo.bid}',
        }
        $.ajax({
            url:url,
            //dataType:'get',
            data:Data,
            success:function(data){
                if(data.status == 1){
                   hg_Toast('收藏成功！');
                   $('#ysc').show();$('#sc').hide();
                }else{
                    hg_Toast('网络故障，请稍后重试！');
                }
            }
        });
    }
Do.ready("common",'template',function(){
    refreshchp();
         authorinfo();
    UserManager.addListener(function(userinfo){
       $('#sc').on('click',function(){
   });
       $('.empty').on('click',function(){
         if(userinfo.islogin){
             var commentid=$(this).attr('data-id');
             dianliang(commentid);
         }else{
            hg_Toast('请先登录！');
            $('.login_tc').show();$('#input_pwd').focus();
         }
       });
});
    });


</script>
</block>
