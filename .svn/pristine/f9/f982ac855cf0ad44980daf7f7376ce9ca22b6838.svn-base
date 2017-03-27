<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
<style type="text/css">
    .sp{      word-wrap: break-word;width: 260px;word-break: break-all;padding:0;}
</style>
    <div class="container  mtop10 clearfix">
        <div  class="user">
            <div class="lf clearfix">
                <include file="tpl/user" />
            </div>
            <div class="rt">
                <div class="rt_unit rt_czjl clearfix">
                    <div class="user_tit"><h1>我的书评</h1></div>
                    <div class="conditions">
                        <a href="javascript:void(0);" id="mycom">我的评论</a>
                        <a href="javascript:void(0);" id="reply">回复我的</a>
                    </div>
                    <div class="xfjl clearfix">
                        <table width="682" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th width="300">主题</th>
                                <th width="50" >回应</th>
                                <th width="78">时间</th>
                                <th width="188">书评区</th>
                                <th width="66">操作</th>
                            </tr>
                            </thead>
                            <tbody id="commentslist" totalnum=0>
                            
                            </tbody>
                        </table>
                    </div>


                    <div class="page commnnt_pages" id="pagelist">
                    </div>
                </div>

            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/html" id="comment_tpl">
        {{each commentlist as row i}}
            {{if row.forbidden_flag == 1}}
                <tr>
                    <td><p class="sp cred">待审中...</p></td>
                    <td >0</td>
                    <td >{{row.creation_date}}</td>
                    <td class="czfs"><a class="xf_chapter ellipsis" href="{{row.booklink}}" target="_blank">《{{row.catename}}》</a></td>
                    <td><a href="javascript:void(0);" target="_blank">待审</a> </td>
                </tr>
            {{else}}
                <tr>
                    <td><p class="sp">{{#row.content}}</p></td>
                    <td >{{row.reply_amount}}</td>
                    <td >{{row.creation_date}}</td>
                    <td class="czfs"><a class="xf_chapter ellipsis" href="{{row.booklink}}" target="_blank">《{{row.catename}}》</a></td>
                    <td><a href="{{row.link}}" target="_blank">回复</a> </td>
                </tr>
            {{/if}}
        {{/each}}
    </script>
    <script type="text/javascript">
        var url = "{:url('Userajax/myComments','','do')}";
        require(['mod/user','functions'],function(user){
            user.listmark();
            $('#mycom').on('click',function(){
                $(this).addClass('active');
                $('#reply').removeClass('active');
                url = parseUrl('','Userajax/myComments','do');
                $('#pagelist').html('');
                getlist(1);
            })
            $('#reply').on('click',function(){
                $(this).addClass('active');
                $('#mycom').removeClass('active');
                url = parseUrl('','Userajax/myCommentReplies','do');
                $('#pagelist').html('');
                getlist(0);
            })
            var types='{$type}';
            if(types==2){
                $('#reply').addClass('active');
                url = parseUrl('','Userajax/myCommentReplies','do');
                $('#pagelist').html('');
                getlist(0);
            }else{
                $('#mycom').addClass('active');
                url = parseUrl('','Userajax/myComments','do');
                $('#pagelist').html('');
                getlist(1);
            }
        })

        function getlist(pagenum){
            if(!pagenum){
                var pagenum=1;
            }
            var totalnum=$('#commentslist').attr('totalnum');
            require(['api','template','mod/page'],function(api,template,page){
                var data={
                    pagenum:pagenum,
                    pagesize:10,
                    totalnum:totalnum,
                    clientmethod:'getlist',
                    pagelistsize:10
                }
                api.getapi(url,data,function(data){
                    if(data.status==1){
                        for(var i in data.commentlist){
                            data.commentlist[i].booklink=parseUrl({bid:data.commentlist[i].bid},'Book/view','html');
                            data.commentlist[i].link=parseUrl({bid:data.commentlist[i].bid,comment_id:data.commentlist[i]['comment_id']},'Book/replycomment','do');
                        }
                        var html=template('comment_tpl',data);
                        $('#commentslist').html(html);
                        page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist');
                        $('#commentslist').attr('totalnum',data.totalnum);
                    }else{
                        $('#commentslist').html('<tr><td colspan="5">暂无评论</td></tr>');
                    }
                })
            })
        }
    </script>
</block>