<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  mtop10 clearfix">
        <div  class="user">
            <div class="lf clearfix">
                <include file="tpl/user" />
            </div>
            <div class="rt">
                <div class="rt_unit rt_czjl clearfix">
                    <div class="user_tit"><h1>我的书架</h1></div>
                    <div class="conditions">
                        <select id="cate">
                            <option value="0">全部书架</option>
                            <option value="">默认书架</option>
                        </select>
                        <select id="order">
                            <option value="read">按阅读时间排序</option>
                            <option value="buy">按订阅时间排序</option>
                            <option value="update">按更新时间排序</option>
                        </select>
                        <span>我的书架上共有<span id="num">0</span>本书</span>
                    </div>
                    <div class="user_myshelf clearfix">
                        <ul id="shelflist">
                        </ul>

                    </div>
                    <div class="page commnnt_pages" id="pagelist">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- 弹框 -->
    <div class="tk_dashang tk_ts radius4" id="tank" style="display:none;">
        <div class="tk_dashang_tit"><a href="#" class="close"></a><span>提示</span></div>
        <div class="tk_ts_con">
            <h2>删除书籍</h2>
            <p>确定要删除本书吗？</p>
            <div class="tk_ts_btn mtop10"><button class="ask_btn radius4" id="sure" >确定</button><button class="ask_btn radius4" onclick="$('#tank').hide();">取消</button></div>
        </div>
    </div>
    <!-- 弹框 -->
</block>
<block name="script">
    <script type="text/html" id="shelf_tpl">
        {{each list as row i}}
            <li class="clearfix" bid="{{row.bid}}">
                <div class="lf"><a href="{{row.link}}" target="_blank"><img src="{{row.cover}}" /></a></div>
                <div class="rt">
                    <h2><a href="{{row.link}}" target="_blank">{{row.catename}}</a></h2>
                    <div class="user_bk_xinxi">
                        <p>作者： <a href="javascript:void(0);" target="_blank">{{row.author}}</a></p>
                        <p><span>{{if row.lzinfo == 1}}已完本{{else}}连载中{{/if}}</span>{{if row.isbookvip == 1}}<span class="vip"></span>{{/if}}</p>
                        <p>阅读进度：{{row.lastreadchporder}}/{{row.totalchapters}}</p>
                        <p>最新章节： {{row.lastupdatetitle}}（<span class="cblue">{{row.lastupdatetime}}</span>）</p>
                        <!-- <p>上次读到：{{row.lastreadchptitle}}</p> -->
                    </div>
                    <div class="user_bk_btn">
                        <a class="ic_delete" id="del{{row.bid}}"></a>
                        <a href="{{row.chplink}}" target="_blank"><button class="user_bkrd_btn radius4">{{if row.isread == 1}}继续阅读{{else}}开始阅读{{/if}}</button></a>
                        {{if row.autoorder ==1}}<span class="btn_zddy" id="auto{{row.bid}}">取消自动订阅<i></i></span>{{else}}<span class="btn_nozddy" id="auto{{row.bid}}">开启自动订阅<i></i></span>{{/if}}
                    </div>
                </div>
            </li>
        {{/each}}
    </script>
    <script type="text/javascript">
        require(['mod/user'],function(user){
            user.listmark();
            getlist();
            $('#cate').on('change',function(){
                getlist();
            })
            $('#order').on('change',function(){
                getlist();
            })
        })
        
        function getlist(pagenum){
            if(!pagenum){
                var pagenum = 1;
            }
            var cateid=$('#cate').val();
            var order=$('#order').val();
            var totalnum=$('#num').html();
            var url = parseUrl('','Userajax/getshelflist','do');
            var data = {
                pagenum:pagenum,
                pagesize:5,
                cateid:cateid,
                order:order,
                totalnum:totalnum,
                clientmethod:'getlist'
            }
            require(['api','template','functions','mod/user','mod/page'],function(api,template,func,user,page){
                api.getapi(url,data,function(data){
                    if(data.status==0){
                        $('#shelflist').html('暂无收藏书籍');
                        return;
                    }
                    for(var i in data.list){
                        data.list[i].link=parseUrl({bid:data.list[i].bid},'Book/view','html');
                        if(data.list[i].ischaptervip==1){
                            data.list[i].chplink=parseUrl({bid:data.list[i].bid,chapterid:data.list[i].lastreadchpid},'Book/readvip','do');
                        }else{
                            data.list[i].chplink=parseUrl({bid:data.list[i].bid,chapterid:data.list[i].lastreadchpid},'Book/read','html');
                        }
                    }
                    var html=template('shelf_tpl',data);
                    $('#shelflist').html(html);
                    page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist');
                    $('#num').html(data.totalnum);

                    //绑定事件
                    var Shelflist=$('#shelflist').find('li');
                    $.each(Shelflist,function(){
                        var bid=$(this).attr('bid');
                        //绑定删除事件
                        $(this).on('click','#del'+bid,function(){
                            $('#tank').show();
                            $('#sure').off('click');
                            $('#sure').on('click',function(){
                                user.deletebook(bid);
                            })
                        })

                        //绑定开启取消自动订阅
                        $(this).on('click','#auto'+bid,function(){
                            if($('#auto'+bid).hasClass('btn_zddy')){
                                user.autoorder(bid,0,function(data){
                                    if(data.status==1){
                                        $('#auto'+bid).removeClass('btn_zddy').addClass('btn_nozddy');
                                    }
                                });
                            }else{
                                user.autoorder(bid,1,function(data){
                                    if(data.status==1){
                                        $('#auto'+bid).removeClass('btn_nozddy').addClass('btn_zddy');
                                    }
                                });
                            } 
                        })
                    })

                })
            })
            
        }
    </script>
</block>