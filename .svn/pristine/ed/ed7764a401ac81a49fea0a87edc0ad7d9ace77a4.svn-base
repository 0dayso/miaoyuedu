<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  clearfix">
        <div class="crumbs"><a href="{:url('Index/index','','html')}">首页</a>><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a>><span>全部评论</span></div>
        <div class="col-3">
            <div class="comments bgf clearfix">
                <div class="comments_con">
                    <ul>
                        <li>
                            <div class="lf"><a href="javascript:void(0);" target="_blank" class="comment_avater"><img src="{$commentinfo.avatar}" /></a></div>
                            <div class="rt">
                                <h5 class="comments_con_name"><a target="_blank">{$commentinfo.nickname}</a>
                                <if condition="$commentinfo.doublesort eq 1"><img src="__IMG__/icon/comment_zd.png" /></if>
                                <if condition="$commentinfo.highlight_flag eq 1"><img src="__IMG__/icon/comment_jh.png" /></if>
                                <if condition="$commentinfo.lcomment eq 1"><img src="__IMG__/icon/lcomment.png" /></if>
                                </h5>
                                <p class="comment">{$commentinfo.content}  </p>
                                <div class="comment_xinxi"><span class="comment_date">{$commentinfo.time}</span><a href="javascript:void(0);" class="comment_dianzan" id="zan">点赞(<span id="za{$commentinfo['comment_id']}">{$commentinfo.zan_amount}</span>)</a><a href="javascript:void(0);"  class="reply">回复({$replycount})</a></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <if condition="$isbanzhu">
                    <div class="management"><span class="cblue">书评管理</span><a href="#" class="radius4">置顶</a><a href="#" class="radius4">加精华</a><a href="#" class="radius4">飘红</a><a href="#" class="radius4">设长评</a><a href="#" class="radius4">屏蔽书评</a><a href="#" class="radius4">锁帖</a><a href="#" class="radius4"> 用户禁言</a><a href="#" class="radius4">删除</a></div>
                </if>
                <div class="reply_comments">
                    <div class="reply_comments_tit">全部评论回复</div>
                    <div class="comments_con">
                        <ul id="replys">
                            
                        </ul>
                    </div>
                    <div class="page commnnt_pages" id="pagelist">
                       
                    </div>
                </div>

                <div class="mycomment bgf border0 clearfix" id="mycomment" >
                    <div class="mycomment_tit"><h4>回复</h4><a name="nologin" href="{:url('User/login','','do')}">登录&nbsp;&nbsp;或</a><a name="nologin" href="{:url('User/register','','do')}">注册</a></div>
                    <div class="mycomment_con">
                        <div class="lf"><a href="javascript:void(0);" target="_blank" class="comment_avater"><img src="__IMG__/avatar/avater_small.jpg" /></a></div>
                        <div class="rt">
                            <textarea class="ask_con_textarea radius4" id="text" placeholder="回复"></textarea>
                            <div class="ask_con_other">
                                <a href="javascript:void(0);" class="face" id="bq" isshow="0"><img src="__IMG__/face/1.gif"></a><span class="ask_con_num"><span id="num">0</span>/300</span>
                                <div class="mtop10"><button class="ask_btn radius4" id="tijiao">回复</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-1 clearfix">
            <div class="rank mbom10">
                <div class="fmtag">
                    <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}" ><img  src="{$bookinfo.cover}"  /></a>
                    <p>作品标签：<span>{$bookinfo.tags}</span></p>
                </div>
            </div>
            <div class="rank mbom10 ">
                <div class="rank_tit2">作者公告</div>
                <div class="rank_authorgonggao clearfix">
                    <p>{$bookinfo['note']}</p>
                </div>
            </div>

        </div>
    </div><div id="bqb" style="display:none;position:absolute;"><include file="tpl/bqb"/></div>
</block>
<block name="script">
    <script type="text/html" id="reply_tpl">
        {{each list as row i}}
            {{if row.forbidden_flag == 1}}
                <li>
                    <div class="lf"><a href="javascript:void(0);" target="_blank" class="comment_avater"><img src="{{row.avatar}}" /></a></div>
                    <div class="rt">
                        <h5 class="comments_con_name"><a href="javascript:void(0);" target="_blank">{{row.nickname}}</a></h5>
                        <p class="comment shenhe">评论审核中  </p>
                        <div class="comment_xinxi"><span class="comment_date">{{row.time}}</span><span class="comments_lou">{{row.floor}}楼</span></div>
                    </div>
                </li>
            {{else}}
                <li>
                    <div class="lf"><a href="javascript:void(0);" target="_blank" class="comment_avater"><img src="{{row.avatar}}" /></a></div>
                    <div class="rt">
                        <h5 class="comments_con_name"><a href="javascript:void(0);" target="_blank">{{row.nickname}}</a></h5>
                        <p class="comment">{{#row.content}}  </p>
                        <div class="comment_xinxi"><span class="comment_date">{{row.time}}</span><span class="comments_lou">{{row.floor}}楼</span></div>
                    </div>
                </li>
            {{/if}}
        {{/each}}
    </script>
    <script type="text/javascript">
        var bid = '{$bookinfo.bid}';
        var cid = "{$commentinfo['comment_id']}";
        
        require(['mod/comment','mod/book','functions'],function(com,book){
            $('#zan').on('click',function(){
                book.sendzan(bid,cid,'#za'+cid);
            });
            $('#textall').on('keydown',function(){
                com.checknum('#textall','#num');
            })
            $('#tijiao').on('click',function(){
                var length = $('#textall').val().length;
                if(length>300 || length<4){
                    hg_Toast('回复字数要在4-300字之间~');
                    return;
                }
                var content = $('#textall').val();
                com.addreply(bid,cid,content,'reply_tpl','#replys','#zan','#textall');
            })
            $('#bq').on('click',function(e){
                com.showhidebqb('#bqb','isshow',e,'all');
            })
        })
        
        getlist();
        function getlist(pagenum){
            if(!pagenum){
                var pagenum = 1;
            }
            require(['api','template','mod/page','functions'],function(api,template,page){
                var url = "{:url('Bookajax/replyComment','','do')}";
                api.getapi(url,{bid:bid,pagesize:10,comment_id:cid,pagenum:pagenum},function(data){
                    if(data.status==1){
                       var html2=template('reply_tpl',data);
                       $('#replys').html(html2);
                       page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist');
                    }else{
                        hg_Toast(data.message);
                    }
                })
            })
        }
    </script>
</block>
