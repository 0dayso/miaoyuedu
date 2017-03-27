<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10 clearfix">
         <div class="col-3">
             <div class="col-3-lf ">
                 <div class="focus_pic">
                     <ul>
                         <li>
                             <div class="pic">
                                   <a id="aFoucsLarge" href="{:url('Book/view',array('bid'=>$index_exrecom[0]['bid']),'html')}" target="_blank" title="{$index_exrecom[0]['catename']}"> <img src="{:getBookfacePath($index_exrecom[0]['bid'], 'large')}" alt="{$index_exrecom[0]['catename']}"> </a>
                              </div>
                              <div class="summary top10">
                                   <a  class="summary_tit ellipsis" id="a_catename" href="{:url('Book/view',array('bid'=>$index_exrecom[0]['bid']),'html')}" target="_blank" title="{$index_exrecom[0]['catename']}">{$index_exrecom[0]['catename']}</a>
                                   <p class="jj"><a id="a_intro" href="{:url('Book/view',array('bid'=>$index_exrecom[0]['bid']),'html')}" target="_blank" title="{$index_exrecom[0]['intro']}">{:strip_tags($index_exrecom[0]['intro'])}</a></p>
                              </div>
                         </li>
                     </ul>
                 </div>
                <p id="p_s_images" class="handles">
                    <volist name="index_exrecom" id="vo">
                        <img src="{:getBookfacePath($vo['bid'], 'small')}" data-collect-index="{$i}" class="<if condition=" $i eq 1 ">active</if>" author="{:escape($vo['author'])}" catename="{:escape($vo['catename'])}" intro="{:escape($vo['intro'])}" bid="{$vo['bid']}" style="cursor: pointer;" authorid="{$vo['authorid']}"/>
                   </volist>
                </p>
             </div>
             <div class="col-3-rt">
                 <div class="recommended clearfix">
                     <ul> 
                        <li>
                            <h2 class="ellipsis"><a href="{:url('Book/view',array('bid'=>$index_recom1[0]['bid']),'html')}" target="_blank">{$index_recom1[0]['catename']}</a></h2>
                            <p><span class="cpink">[{$index_recom1[1]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom1[1]['bid']),'html')}" target="_blank">{$index_recom1[1]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom1[2]['bid']),'html')}" target="_blank">{$index_recom1[2]['catename']}</a></p>
                            <p><span class="cpink">[{$index_recom1[3]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom1[3]['bid']),'html')}" target="_blank">{$index_recom1[3]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom1[4]['bid']),'html')}" target="_blank">{$index_recom1[4]['catename']}</a></p>
                        </li>
                        <li>
                            <h2 class="ellipsis"><a href="{:url('Book/view',array('bid'=>$index_recom2[0]['bid']),'html')}" target="_blank">{$index_recom2[0]['catename']}</a></h2>
                            <p><span class="cpink">[{$index_recom2[1]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom2[1]['bid']),'html')}" target="_blank">{$index_recom2[1]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom2[2]['bid']),'html')}" target="_blank">{$index_recom2[2]['catename']}</a></p>
                            <p><span class="cpink">[{$index_recom2[3]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom2[3]['bid']),'html')}" target="_blank">{$index_recom2[3]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom2[4]['bid']),'html')}" target="_blank">{$index_recom2[4]['catename']}</a></p>
                        </li>
                        <li>
                            <h2 class="ellipsis"><a href="{:url('Book/view',array('bid'=>$index_recom3[0]['bid']),'html')}" target="_blank">{$index_recom3[0]['catename']}</a></h2>
                            <p><span class="cpink">[{$index_recom3[1]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom3[1]['bid']),'html')}" target="_blank">{$index_recom3[1]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom3[2]['bid']),'html')}" target="_blank">{$index_recom3[2]['catename']}</a></p>
                            <p><span class="cpink">[{$index_recom3[3]['smallsubclassname']}]</span><a href="{:url('Book/view',array('bid'=>$index_recom3[3]['bid']),'html')}" target="_blank">{$index_recom3[3]['catename']}</a>|<a href="{:url('Book/view',array('bid'=>$index_recom3[4]['bid']),'html')}" target="_blank">{$index_recom3[4]['catename']}</a></p>
                        </li>
                     </ul>
                 </div>
                 {$static_index_gonggao}
             </div>
         </div>
         <div class="col-1 ">
             <div class="rank">
                 <div class="rank_tit"><span class="bar"></span>完结榜</div>
                 <div class="rank_con clearfix">
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
                     <div class="rmore"><a href="{:url('Channel/rank','','do')}" target="_blank">更多</a> </div>
                 </div>
             </div>
         </div>
    </div>
    {$static_index_banner}
    <div class="container bgf mbom10 clearfix">
        <div class="tit"><span class="titbg">热度精选</span></div>
        <div class="frame01 fm4 clearfix">
            <ul>
                <volist name="index_jingxuan" id="vo">
	                <li>
	                    <a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="fm4_name ellipsis" target="_blank">{$vo['catename']}</a>
	                    <p class="fm4_tag"><a href="#" target="_blank">总裁</a><a href="#" target="_blank">豪门</a><a href="#" target="_blank">宠文</a><a href="#" target="_blank">蜜爱</a></p>
	                    <a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="fm" target="_blank"><img src="{:getBookfacePath($vo['bid'], 'middle')}" /></a>
	                </li>
	            </volist>
            </ul>
        </div>
    </div>
    <div class="container bgf mbom10 clearfix">
        <div class="tit"><span class="titbg">大神专区</span></div>
        <div class="fm6 clearfix">
            <ul>
                <volist name="index_dashen" id="vo">
	                <li>
	                    <a class="fm6_fm" href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank"><img src="{:getBookfacePath($vo['bid'], 'middle')}" /></a>
	                    <p ><a class="fm6_name ellipsis" href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank">{$vo['catename']}</a></p>
	                    <p><span class="fm6_zz" target="_blank">作者：{$vo['author']}</span></p>
	                </li>
	            </volist>
            </ul>
        </div>
    </div>
    <div class="container mbom10 clearfix">
        <div class="col-3 bgf">
            <div class="tit"><span class="titbg">免费推荐</span></div>
            <div class="frame02 fm4word clearfix">
                <ul>
                    <volist name="index_freerecom" id="vo">
                        <if condition="$i lt 5">
		                    <li>
		                        <div class="lf" ><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank"><img src="{:getBookfacePath($vo['bid'], 'small')}" /></a></div>
		                        <div class="rt">
		                            <a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="fm4word_name ellipsis" target="_blank">{$vo['catename']}</a>
		                            <a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="fm4word_jj" target="_blank">{$vo['intro']}</a>
		                            <p class="cgrey">作者：<a href="javascript:void(0)"  class="cpink">{$vo['author']}</a></p>
		                        </div>
		                    </li>
	                    </if>
	                </volist>
                </ul>
            </div>
            <div class="frame02 word8 clearfix">
                <ul>
                    <volist name="index_freerecom" id="vo">
                        <if condition="$i gt 4 and $i lt 13">
	                    <li><a href="{:url('Channel/search',array('classid'=>$vo['classid']),'do')}" target="_blank" class="cpink">[{$vo['subclassname']}]</a><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank">{$vo['catename']}</a></li>
                        </if>
                    </volist>
                </ul>
            </div>
        </div>
        <div class="col-1 ">
            <div class="rank">
                <div class="rank_tit"><span class="bar"></span>免费榜</div>
                <div class="rank_con clearfix">
                    <volist name="index_free" id="vo">
                        <if condition="$i eq 1">
		                    <div class="rank_fm ">
		                        <span class="num num1">1</span>
		                        <div class="lf"><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" target="_blank"><img src="{:getBookfacePath($vo['bid'], 'small')}" /></a></div>
		                        <div class="rt"><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="name ellipsis" target="_blank">{$vo['catename']}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$vo['author']}</a></div>
		                    </div>
		                    <ul>
                        <else/>
	                        <li><span class="num num{$i}">{$i}</span><a href="{:url('Book/view',array('bid'=>$vo['bid']),'html')}" class="ellipsis" target="_blank">{$vo['catename']}</a></li>
                        </if>
	                        </ul>
	                </volist>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container clearfix">
        <div class="col-3">
            <div class="tit  bgf">
                <span class="titbg">最新更新</span>
                <div class="table_tab"><a href="javascript:void(0);" class="active" type="all">最新更新小说</a><a href="javascript:void(0);" type="vip">VIP小说更新</a><!-- <a href="javascript:void(0);" type="free">免费小说更新</a> --></div>
            </div>
            <div class="table  bgf">
                <table width="100%" border="0" cellpadding="0" padding="0" margin="0">
                    <thead>
                        <tr>
                           <th width="74">分类</th>
                           <th width="42">VIP</th>
                           <th width="400">书名/最新章节</th>
                           <th width="140">作者</th>
                           <th width="84">时间</th>
                        </tr>
                    </thead>
                    <tbody id="updatebooks" name="all">
                        <volist name="index_last_new" id="row">
                            <tr>
                                <td width="74" ><a href="{:url('Channel/search',array('classid'=>$row['classid']),'do')}"  target="_blank" class="table_tag cpink">[{$row.smallsubclassname}]</a></td>
                                <td width="42">
                                <if condition="$row.isvip eq 1">
                                    <span class="vip"></span>
                                <else/>
                                    <span class="free"></span>
                                </if>
                                </td>
                                <td width="400"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"  class="table_name ellipsis">{$row.catename}</a>
                                <if condition="$row.isvip eq 1">
                                    <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'do')}" target="_blank"  class="table_chapter ellipsis">
                                <else/>
                                    <a href="{:url('Book/read',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'html')}" target="_blank"  class="table_chapter ellipsis">
                                </if>{$row.lastupdatetitle}</a></td>
                                <td width="140"><a class="table_zz ellipsis" target="_blank">{$row.author}</a> </td>
                                <td width="84" class="table_date">
                                    {$row.lastupdatetime|date="m-d H:i",###}
                                </td>
                            </tr>
                        </volist>
                    </tbody>
                    <tbody name="vip" style="display:none;">
                        <volist name="index_last_vip" id="row">
                            <tr>
                                <td width="74" ><a href="{:url('Channel/search',array('classid'=>$row['classid']),'do')}"  target="_blank" class="table_tag cpink">[{$row.smallsubclassname}]</a></td>
                                <td width="42">
                                <if condition="$row.isvip eq 1">
                                    <span class="vip"></span>
                                <else/>
                                    <span class="free"></span>
                                </if>
                                </td>
                                <td width="400"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"  class="table_name ellipsis">{$row.catename}</a>
                                <if condition="$row.isvip eq 1">
                                    <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'do')}" target="_blank"  class="table_chapter ellipsis">
                                <else/>
                                    <a href="{:url('Book/read',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'html')}" target="_blank"  class="table_chapter ellipsis">
                                </if>{$row.lastupdatetitle}</a></td>
                                <td width="140"><a class="table_zz ellipsis" target="_blank">{$row.author}</a> </td>
                                <td width="84" class="table_date">
                                    {$row.lastupdatetime|date="m-d H:i",###}
                                </td>
                            </tr>
                        </volist>
                    </tbody>
                    <tbody name="free" style="display:none;">
                        <volist name="updatebooks_free" id="row">
                            <tr>
                                <td width="74" ><a href="{:url('Channel/search',array('classid'=>$row['classid']),'do')}"  target="_blank" class="table_tag cpink">[{$row.smallsubclassname}]</a></td>
                                <td width="42">
                                <if condition="$row.isvip eq 1">
                                    <span class="vip"></span>
                                <else/>
                                    <span class="free"></span>
                                </if>
                                </td>
                                <td width="400"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"  class="table_name ellipsis">{$row.catename}</a>
                                <if condition="$row.isvip eq 1">
                                    <a href="{:url('Book/readVip',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'do')}" target="_blank"  class="table_chapter ellipsis">
                                <else/>
                                    <a href="{:url('Book/read',array('bid'=>$row['bid'],'chapterid'=>$row['lastupdatechpid']),'html')}" target="_blank"  class="table_chapter ellipsis">
                                </if>{$row.lastupdatetitle}</a></td>
                                <td width="140"><a class="table_zz ellipsis" target="_blank">{$row.author}</a> </td>
                                <td width="84" class="table_date">
                                    {$row.lastupdatetime|date="m-d H:i",###}
                                </td>
                            </tr>
                        </volist>
                    </tbody>
                </table>
            </div>
        <div class="table_more"><a href="{:url('Channel/search','','do')}">更多精品小说》</a></div>
        </div>
        <div class="col-1">
            <div class="rank mbom10" id="orderrank">
                <div class="rank_tit"><span class="bar"></span>订阅榜
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayorder">周</a><a href="javascript:void(0);" type="week" name="weekorder">月</a><!-- <a href="javascript:void(0);" type="month" name="monthorder">月</a> --></div>
                </div>
                <div class="rank_con clearfix" name="dayorder">
                    <php>$i=0;</php>
                    <!-- auto_index_nv_weekhotsales -->
                    <Hongshu:bangdan name="rank_order1" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                            </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekorder" style="display:none;">
                    <php>$i=0;</php>
                    <!-- auto_index_nv_monthhotsales -->
                    <Hongshu:bangdan name="rank_order2" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                            </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
                <!-- <div class="rank_con clearfix" name="monthorder" style="display:none;">
                    <foreach name="orderlist['month']" item="row" key="i">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.cover}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                    </foreach>
                            </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div> -->
            </div>
            <div class="rank mbom10" id="clickrank">
                <div class="rank_tit"><span class="bar"></span>点击榜
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayclick">周</a><a href="javascript:void(0);" type="week" name="weekclick">月</a><a href="javascript:void(0);" type="month" name="monthclick">总</a></div>
                </div>
                <div class="rank_con clearfix" name="dayclick">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_weekhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                        </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekclick" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_monthhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                        </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="monthclick" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_totalhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <span class="num num{$i+1}">{$i+1}</span>
                                <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a></div>
                                <div class="rt"><a  href="{:url('Book/view',array('bid'=>$row['bid']))}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
                            </div>
                            <ul>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']))}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                        </ul>
                    <div class="rmore"><a href="{:url('Channel/rank','','do')}">更多</a> </div>
                </div>
            </div>
            {$static_index_lxkefu}
        </div>
    </div>
    <div class="container">
        <div class="link clearfix">
            <ul>
                <li >
                    <h5 >友情链接：</h5>
                </li>
                {$index_link}                
            </ul>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/index','mod/rank','mod/head'],function(index,rank,head){
            //绑定最新更新切换事件
            index.bindupdate('.table_tab a','tbody');
            //启动焦点图切换
            index.initFoucsMap('#p_s_images','#a_catename','#a_intro','#aFoucsLarge');
            //绑定排行榜条件切换时间
            rank.bindbangdan('order','a','div');
            rank.bindbangdan('click','a','div');
            //绑定头部导航切换
            head.navhead();
        })
	</script>
</block>