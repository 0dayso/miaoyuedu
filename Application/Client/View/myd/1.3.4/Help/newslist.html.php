<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10 clearfix">
        <div class="crumbs crumbs2"><span href="javascript:void(0);">您的位置</span>><a href="{:url('Index/index','','html')}">首页</a>><span>新闻公告</span></div>
        <div class="news">
            <include file="tpl/newslist" />
            <div class="rt newslist">
                <ul>
                    <volist name="newslist" id="vo">
                        <li>
                            <h2><a href="{:url('Help/newsdetail',array('articleid'=>$vo['article_id']),'html')}" target="_blank" class="ellipsis">{$vo.title}</a></h2>
                            <p class="ellipsis newsjj">{$vo.shortContent}</p>
                            <p class="newstime"><span>{$vo.admin_name}</span><span>{$vo.date}</span></p>
                        </li>
                    </volist>
                </ul>
                <div category="npageList" index="1" class="page commnnt_pages" <if condition="$totalpage eq 1"> style="display:none;"</if>>
                    <form method="post" action="{:url('Help/newslist')}" name="npageform">
                        <if condition="$pagenum eq 1">
                            <a class="radius4 disable" style="cursor:pointer">|&lt;</a>
                            <else/>
                            <a href="javascript:changeNPage(1);" class="radius4">|&lt;</a>
                        </if>
                        <if condition="$pagenum eq 1">
                            <a class="disable radius4" style="cursor:pointer">&lt;</a>
                            <else/>
                            <a href="javascript:preNPage();" class="radius4">&lt;</a>
                        </if>

                        <?php
                            if ($totalpage <= 9) {
                                for ($i=1; $i < $totalpage + 1; $i++) { 
                                    if($pagenum == $i) {
                                        echo '<a class="radius4 active" onclick="changeNPage('.$i.');">'.$i.'</a>';
                                    }                                                
                                    else {
                                        echo '<a class="radius4" href="javascript:changeNPage('.$i.');">'.$i.'</a>';
                                    }
                                }
                            }
                            else{
                                if ($pagenum + 9 <= $totalpage) {
                                    for ($i=$pagenum; $i < $pagenum + 9; $i++) { 
                                        if($pagenum == $i) {
                                            echo '<a class="radius4 active" onclick="changeNPage('.$i.');">'.$i.'</a>';
                                        }                                                
                                        else {
                                            echo '<a class="radius4" href="javascript:changeNPage('.$i.');">'.$i.'</a>';
                                        }
                                    }
                                }
                                else {
                                    for ($i=$pagenum - ($pagenum + 9 - $totalpage) + 1; $i < $totalpage + 1; $i++) { 
                                        if($pagenum == $i) {
                                            echo '<a class="radius4 active" onclick="changeNPage('.$i.');">'.$i.'</a>';
                                        }                                                
                                        else {
                                            echo '<a class="radius4" href="javascript:changeNPage('.$i.');">'.$i.'</a>';
                                        }
                                    }
                                }
                            }
                        ?>

                        <if condition="$pagenum eq ($totalpage)">
                            <a class="disable radius4" style="cursor:pointer">&gt;</a>
                            <else/>
                            <a class="radius4" href="javascript:nextNPage({$totalpage});">&gt;</a>
                        </if>

                        <if condition="$pagenum eq ($totalpage)">
                            <a class="disable radius4" style="cursor:pointer">&gt;|</a>
                            <else/>
                            <a class="radius4" href="javascript:changeNPage({$totalpage});">&gt;|</a>
                        </if>
                        
                         <input name="article_id" type="hidden" value="{$article_id}" />
                        <input name="pagesize" type="hidden" value="{:C('COMMENTSIZE')}" />
                        <input id="txtnPage" name="pagenum" type="hidden" value="{$pagenum}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        //翻页
        function changeNPage(page) {
            $("#txtnPage").val(page);
            npageform.submit();
        }
        function nextNPage(totalpage) {
            var page = parseInt($("#txtnPage").val());
            if(page >= totalpage) {
                return;
            }
            $("#txtnPage").val(page + 1);
            npageform.submit();
        }
        function preNPage() {
            var page = parseInt($("#txtnPage").val());
            if(page > 1) {
                $("#txtnPage").val(page - 1);
                npageform.submit();    
            }    
        }
        require(['mod/help'],function(help){

        })
    </script>
</block>