<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10  clearfix">
        <div class="col-3 bgf">
            <div class="chapter_top">
                <h1><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}" target="_blank">{$bookinfo.catename}</a></h1>
                <p>作者： <a href="javascript:void(0);" target="_blank" class="cpink2">{$bookinfo.author}</a></p>
                <p><span>章节数：{$totalnum}(免费：{$freechapternum})</span><span>总字数：<?php
                        if ($bookinfo['charnum'] > 10000) {
                            $bookinfo['charnum'] = round($bookinfo['charnum'] / 10000, 1) . "万";
                        }

                        echo $bookinfo['charnum'] . '字';
                        ?>

                        </span><span>更新时间：{$bookinfo.lastupdatetime}</span></p>
            </div>
            <div class="chapter_con_tit0">目录</div>
            <div id="chapterlist">
            <volist name="allchapters" id="row">
                <if condition="$row.juantitle neq ''">
                    <div class="chapter_con_tit" name="title" isshow="1">{$row.juantitle}</div>
                    <div class="frame02 chapter_con clearfix">
                    <ul>
                        <li><div>
                            <if condition="$row['isvip'] eq 1">
                                <if condition="$row['isorder'] eq 1">
                                    <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'do')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor unlock"></span>
                                <else/>
                                    <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'do')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor lock"></span>
                                </if>
                            <else/>
                                <a href="{:url('Book/read',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'html')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor">免费</span>
                            </if>
                        </div></li>
                        <if condition="$row['juanlastchapter'] eq 1"></ul></div></if>
                <else/>
                    <li><div>
                        <if condition="$row['isvip'] eq 1">
                            <if condition="$row['isorder'] eq 1">
                                <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'do')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor unlock"></span>
                            <else/>
                                <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'do')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor lock"></span>
                            </if>
                        <else/>
                            <a href="{:url('Book/read',array('bid'=>$row['bid'],'chpid'=>$row['chapterid']),'html')}" class="ellipsis" title="{$row.title}">{$row.title}</a><span class="moneyor">免费</span>
                        </if>
                    </div></li>
                    <if condition="$row['juanlastchapter'] eq 1 or $row.alllastchapter eq 1"></ul></div></if>
                </if>
            </volist>
            </div>
        </div>
        <div class="col-1 clearfix">
            <div class="rank mbom10">
                <div class="fmtag">
                    <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}" ><img  src="{$bookinfo.cover}"/></a>
                    <p>作品标签：<span>{$bookinfo.tags}</span></p>
                </div>
            </div>
            <div class="rank mbom10 ">
                <div class="rank_tit2">作者公告</div>
                <div class="rank_authorgonggao clearfix">
                    <p><if condition="$bookinfo['note']">{$bookinfo['note']}<else/>暂无公告</if></p>
                </div>
            </div>

        </div>
    </div>
    <div class="returntop" style="position: fixed; right: 0; bottom: 90px; display: block;"><a href="javascript:scroll(0,0)"></a></div>
</block>
<block name="script">
    <script type="text/html" id="chapter_tpl">
        {{each list as row i}}
            {{if row.juantitle != ''}}
                <div class="chapter_con_tit" name="title" isshow="1">{{row.juantitle}}</div>
                <div class="frame02 chapter_con clearfix">
                <ul>
                    <li><div>
                        <a href="{{row.link}}" class="ellipsis" title="{{row.title}}">{{row.title}}</a>
                        {{if row.isvip == 1}}
                            {{if row.isorder == 1}}
                                <span class="moneyor unlock"></span>
                            {{else}}
                                <span class="moneyor lock"></span>
                            {{/if}}
                        {{else}}
                            <span class="moneyor">免费</span>
                        {{/if}}
                    </div></li>
                    {{if row.juanlastchapter == 1}}</ul></div>{{/if}}
            {{else}}
                <li><div>
                    <a href="{{row.link}}" class="ellipsis" title="{{row.title}}">{{row.title}}</a>
                    {{if row.isvip == 1}}
                        {{if row.isorder == 1}}
                            <span class="moneyor unlock"></span>
                        {{else}}
                            <span class="moneyor lock"></span>
                        {{/if}}
                    {{else}}
                        <span class="moneyor">免费</span>
                    {{/if}}
                </div></li>
                {{if row.juanlastchapter == 1 || row.alllastchapter == 1}}</ul></div>{{/if}}
            {{/if}}
        {{/each}}
    </script>
    <script type="text/javascript">
        var bid="{$bookinfo.bid}";
        require(['mod/book'],function(book){
            book.bindhide('div','title','isshow');
            book.getmululist(bid,'chapter_tpl','#chapterlist');
        })
    </script>
</block>