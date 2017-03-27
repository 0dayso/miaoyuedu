{{each list as rows k}}
            <div class="rank_tit"><span class="bar"></span>{{rows.name}}
                <div class="rank_tab" id="{{k}}"><a href="javascript:void(0);" class="{{if rows.type == 'day'}}active{{/if}}" name="{{k}}" type="day">日</a><a href="javascript:void(0);" class="{{if rows.type == 'week'}}active{{/if}}" name="{{k}}" type="week">周</a><a href="javascript:void(0);" class="{{if rows.type == 'month'}}active{{/if}}" name="{{k}}" type="month">月</a></div>
            </div>
            <div class="rank_con clearfix">
                {{each rows as row i}}
                    {{if i == 0}}
                        <div class="rank_fm ">
                            <span class="num num1">1</span>
                            <div class="lf"><a href="{{ {bid:row.bid} | router:'Book/view'}}" target="_blank"><img src="{{row.cover}}" /></a></div>
                            <div class="rt"><a  href="{{ {bid:row.bid} | router:'Book/view'}}" class="name ellipsis" target="_blank">{{row.catename}}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{{row.author}}</a></div>
                        </div>
                        <ul>
                    {{else}}
                        <li><span class="num num{{i+1}}">{{i+1}}</span><a href="{{ {bid:row.bid} | router:'Book/view'}}" class="ellipsis" target="_blank">{{row.catename}}</a></li>
                    {{/if}}
                {{/each}}
                </ul>
                <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
            </div>
        {{/each}}


    <script type="text/html" id="update_tpl">
        {{each list as row i}}
            <tr>
                <td width="74" ><a href="{{ {classid:row.classid} | router:'Channel/search','do'}}"  target="_blank" class="table_tag cpink">[{{row.subclassname}}]</a></td>
                <td width="42">
                {{if row.isvipchapter == 1}}
                    <span class="vip"></span>
                {{else}}
                    <span class="free"></span>
                {{/if}}
                </td>
                <td width="42"></td>
                <td width="400"><a href="{{ {bid:row.bid} | router:'Book/view'}}" target="_blank"  class="table_name ellipsis">{{row.catename}}</a><a href="{{ {bid:row.bid,chapterid:row.lastupdatechpid} | router:'Book/view'}}" target="_blank"  class="table_chapter ellipsis">{{row.lastupdatetitle}}</a></td>
                <td width="140"><a class="table_zz ellipsis" target="_blank">{{row.author}}</a> </td>
                <td width="84" class="table_date">
                    {{row.lastupdatetime}}
                </td>
            </tr>
        {{/each}}
    </script>