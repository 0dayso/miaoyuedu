<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container  mtop10 clearfix">
        <div  class="library">
            <div class="rt col-1">
                <div class="rank tag mbom10">
                    <div class="rank_tit"><span class="bar"></span>标签云</div>
                    <div class="tag_con">
                        <foreach name="tags" item="row">
                            <a href="javascript:void(0);" name="key">{$row.tags}</a>
                        </foreach>
                    </div>
                </div>
                <div class="rank">
                    <div class="rank_tit"><span class="bar"></span>完结榜</div>
                    <div class="rank_con clearfix">
                    <!-- <php>var_dump($index_wanjiebang);</php> -->
                        <volist name="index_wanjiebang" id="vo">
                            <if condition="$i eq 1">
                                 <div class="rank_fm">
                                     <span class="num num1">1</span>
                                     <div class="lf"><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank"><img src="{:getBookfacePath($vo['bid'], 'small')}" /></a></div>
                                     <div class="rt"><a  href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="name ellipsis" target="_blank">{$vo['catename']}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$vo['author']}</a></div>
                                 </div>
                                 <ul>
                            <else/>
                                <li><span class="num num{$i}">{$i}</span><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="ellipsis" target="_blank">{$vo['catename']}</a></li>
                            </if>
                        </volist>
                             </ul>
                        <div class="rmore"><a href="{:url('Channel/rank','','html')}" target="_blank">更多</a> </div>
                    </div>
                </div>
            </div>
            <div class="lf col-3 bgf">
                <div class="ss_top clearfix">
                    <div class="topsearch"><h1>书库</h1><input name="keyword" type="search" class="inputsearch" /><button class="ssbtn" id="searchbtn"></button><select class="ssselect" id="keywordtype">
                    <option value="1">综合</option>
                    <option value="2">作品</option>
                    <option value="3">作者</option>
                    <option value="4">简介</option>
                    <option value="5">标签</option>
                    </select></div>
                    <div class="ss_con clearfix">
                        <ul>
                            <li>
                                <span class="ss_con_tit">作品分类：</span>
                                <div class="ss_con_select"><a name="classids" href="#" class="active" classids=0>分类不限</a>
                                <foreach name="category" item="row" key="k">
                                    <a name="classids" href="javascript:void(0);" classids="{$row.classid}">{$row.title}</a>
                                </foreach>
                                </div>
                            </li>
                            <li>
                                <span class="ss_con_tit">是否免费：</span>
                                <div class="ss_con_select"><a name="free" href="javascript:void(0);" class="active" free=0>收费不限</a><a name="free" href="javascript:void(0);" free=1>免费</a><a name="free" href="javascript:void(0);" free=2>收费</a></div>
                            </li>
                            <li>
                                <span class="ss_con_tit">是否完结：</span>
                                <div class="ss_con_select"><a name="finish" href="javascript:void(0);" class="active" finish=0>进度不限</a><a name="finish" href="javascript:void(0);" finish=2>连载</a><a name="finish" href="javascript:void(0);" finish=1>完结</a></div>
                            </li>
                            <li>
                                <span class="ss_con_tit">作品篇幅：</span>
                                <div class="ss_con_select"><a name="charnum" href="javascript:void(0);" class="active" charnum=0>字数不限</a><a name="charnum" href="javascript:void(0);" charnum=1>30万以下</a><a name="charnum" href="javascript:void(0);" charnum=2>30-50万字</a><a name="charnum" href="javascript:void(0);" charnum=3>50-100万字</a><a name="charnum" href="javascript:void(0);" charnum=4>100万字以上</a></div>
                            </li>
                            <li>
                                <span class="ss_con_tit">作品更新时间：</span>
                                <div class="ss_con_select"><a name="updatetime" href="javascript:void(0);" class="active" updatetime=0>更新不限</a><a name="updatetime" href="javascript:void(0);" updatetime=1> 三日内</a><a name="updatetime" href="javascript:void(0);" updatetime=2>七日内</a><a name="updatetime" href="javascript:void(0);" updatetime=3>半月内</a><a name="updatetime" href="javascript:void(0);" updatetime=4>一月内</a></div>
                            </li>

                        </ul>
                    </div>
                    <div class="ss_results" id="biaoqian">
                        
                    </div>
                </div>
                <div class="conditions">
                    <select id="order">
                        <option value="4">按热度排序</option>
                        <option value="0">按相关度排序</option>
                        <option value="1">按更新时间排序</option>
                        <option value="2">按点击量排序</option>
                        <option value="3">按字数排序</option>
                    </select>
                    <span>根据搜索条件，共有<span id="num"></span>个结果</span>
                </div>
                <div class="library_shelf_tit">书名</div>
                <div class="user_myshelf library_shelf clearfix">
                    <ul id="booklist">
                    </ul>

                </div>
                <div class="page commnnt_pages" id="pagelist">
                    
                </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/html" id="search_tpl">
        {{each bookinfo as row i}}
            <li class="clearfix">
                <div class="lf"><a href="{{row.booklink}}" target="_blank"><img src="{{row.bookface}}" /></a></div>
                <div class="rt">
                    <h2><a href="{{row.booklink}}" target="_blank">{{row.catename}}</a></h2>
                    <div class="user_bk_xinxi">
                        <p>作者： <a href="#" target="_blank">{{row.authorname}}</a></p>
                        <p>类别：{{row.classname}}</p>
                        <p>{{if row.lzinfo == 1}}完本{{else}}连载{{/if}}|{{row.charnum}}字|{{row.totalchpnum}}章|更新时间： <span class="cblue">{{row.lastupdatetime}}</span></p>
                        <P class="tag">标签：{{each row.tag as rows i}}<a href="javascript:void(0);">{{rows}}</a>{{/each}}</P>
                    </div>
                    <div class="user_bk_btn">
                        <a href="{{row.booklink}}" target="_blank"><button class="user_bkrd_btn radius4">开始阅读</button></a>
                    </div>
                </div>
            </li>
        {{/each}}
    </script>
    <script type="text/javascript">
        //全局参数
        var data = {
            classids:0,
            free:0,
            charnum:0,
            updatetime:0,
            pagesize:10,
            order:4,
            keyword:'',
            finish:0,
            keywordtype:1
        }
        bind('a[name="classids"]','classids');
        bind('a[name="free"]','free');
        bind('a[name="charnum"]','charnum');
        bind('a[name="updatetime"]','updatetime');
        bind('a[name="finish"]','finish');
        
        
        $('#order').on('change',function(){
            var order=$('#order').val();
            changedata('order',order);
        })
        $('#searchbtn').on('click',function(){
            var keyword = $('input[name="keyword"]').val();
            data['order'] = 0 ;
            $('#order').val(0);
            changedata('keyword',keyword);
        })
        $('a[name="key"]').on('click',function(){
            var keyword = $(this).html();
            data['order'] = 0 ;
            $('#order').val(0);
            $('input[name="keyword"]').val(keyword);
            changedata('keyword',keyword);
        })
        $('#keywordtype').on('change',function(){
            var keywordtype=$('#keywordtype').val();
            data['keywordtype'] = keywordtype ;
        })
        //绑定选择  tag 点击位  name  获取参数
        function bind(tag,name){
            $(tag).on('click',function(){
                var param = $(this).attr(name);
                var text = $(this).html();
                $(tag).removeClass('active');
                $(this).addClass('active');
                var box = $('#biaoqian').find('a#'+name).html();
                if(!box){
                    $('#biaoqian').append('<span name='+name+'><a href="javascript:void(0);" class="lf" id="'+name+'">'+text+'</a><a class="close3" onclick="remove(\''+name+'\');"></a></span>');
                }else{
                    $('#biaoqian').find('a#'+name).html(text);
                }
                changedata(name,param);
            })
        }
        //移除标签
        function remove(name){
            $('span[name="'+name+'"]').remove();
            $('a[name="'+name+'"]').removeClass('active');
            $('a[name="'+name+'"]:first').addClass('active');
            changedata(name,0);
        }
        function changedata(name,param){
           data[name] = param ;
           getlist();
        }
        function getlist(pagenum){
            if(!pagenum){
                var pagenum = 1;
            }
            data.pagenum=pagenum;
            require(['api','functions','template','mod/page'],function(api,func,template,page){
                var url = parseUrl('','Bookajax/search','do');
                api.postapi(url,data,function(data){
                    if(data.status==1){
                        for(var i in data.bookinfo){
                            data.bookinfo[i].booklink=parseUrl({bid:data.bookinfo[i].bid},'Book/view','html');
                        }
                        var html = template('search_tpl',data);
                        $('#booklist').html(html);
                        page.changepage('getlist',data.pagenum,data.pageliststart,data.pagecount,'#pagelist');
                        $('#num').html(data.totalcount);
                    }else{
                        hg_Toast(data.message);
                        $('#booklist').html('');
                        $('#pagelist').html('');
                        $('#num').html('0');
                    }
                },true)    
            })
            
        }
        var keyword='{$_GET["keyword"]}';
        var finishtag='{$_GET["finish"]}';
        require(['mod/head'],function(head){
            //绑定头部导航切换
            head.navhead();
            //接收关键词
            if(keyword){
                data['order'] = 0 ;
                $('#order').val(0);
                changedata('keyword',keyword);
                $('input[name="keyword"]').val(keyword);
            }else if(finishtag){
                changedata('finish',1);
                $('a[name="finish"]').removeClass('active');
                $('a[finish="1"]').addClass('active');
            }else{
                getlist();
            }
        })
    </script>
</block>