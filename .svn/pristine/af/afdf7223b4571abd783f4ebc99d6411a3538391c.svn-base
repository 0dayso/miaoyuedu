<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container"><div class="tit2"><div class="tit2_border"><h1>排行榜</h1></div></div></div>
    <div class="container clearfix">
        <div class="allrank clearfix" id="allrank">
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">点击榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayclick">周</a><a href="javascript:void(0);" type="week" name="weekclick">月</a><a href="javascript:void(0);" type="month" name="monthclick">总</a></div>
                </div>
                <div class="rank_con clearfix" name="dayclick">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_weekhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekclick" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_monthhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="monthclick" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_index_nv_totalhit" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
            </div>
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">订阅榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayorder">周</a><a href="javascript:void(0);" type="week" name="weekorder">月</a><!-- <a href="javascript:void(0);" type="month" name="monthorder">月</a> --></div>
                </div>
                <div class="rank_con clearfix" name="dayorder">
                    <php>$i=0;</php>
                    <!-- auto_index_nv_weekhotsales -->
                    <Hongshu:bangdan name="rank_order1" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekorder" style="display:none;">
                    <php>$i=0;</php>
                    <!-- auto_index_nv_monthhotsales -->
                    <Hongshu:bangdan name="rank_order2" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <!-- <div class="rank_con clearfix" name="monthorder" style="display:none;">
                    <foreach name="orderlist['month']" item="row" key="i">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.cover}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                    </foreach>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div> -->
            </div>
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">收藏榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayfav">周</a><a href="javascript:void(0);" type="week" name="weekfav">月</a><a href="javascript:void(0);" type="month" name="monthfav">总</a></div>
                </div>
                <div class="rank_con clearfix" name="dayfav">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_weekfav" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekfav" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_monthfav" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="monthfav" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_totalfav" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
            </div>
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">更新榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayupdate">周</a><a href="javascript:void(0);" type="week" name="weekupdate">月</a><!-- <a href="javascript:void(0);" type="month" name="monthupdate">月</a> --></div>
                </div>
                <div class="rank_con clearfix" name="dayupdate">
                    <php>$i=0;</php>
                    <!-- auto_class_nv_weekupdate -->
                    <Hongshu:bangdan name="rank_order1" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekupdate" style="display:none;">
                    <php>$i=0;</php>
                    <!-- auto_class_nv_monthupdate -->
                    <Hongshu:bangdan name="rank_order2" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <!-- <div class="rank_con clearfix" name="monthupdate" style="display:none;">
                    <foreach name="updatelist['month']" item="row" key="i">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.cover}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                    </foreach>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div> -->
            </div>
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">点赞榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="dayzan">周</a><a href="javascript:void(0);" type="week" name="weekzan">月</a><a href="javascript:void(0);" type="month" name="monthzan">总</a></div>
                </div>
                <div class="rank_con clearfix" name="dayzan">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_weekflower" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekzan" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_monthflower" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="monthzan" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_totalflower" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
            </div>
            <div class="rank">
                <div class="rank_tit">
                    <div class="rank_tit_word">打赏榜</div>
                    <div class="rank_tab"><a href="javascript:void(0);" class="active" type="day" name="daydashang">周</a><a href="javascript:void(0);" type="week" name="weekdashang">月</a><a href="javascript:void(0);" type="month" name="monthdashang">总</a></div>
                </div>
                <div class="rank_con clearfix" name="daydashang">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_weekbookpro" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="weekdashang" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_monthbookpro" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
                <div class="rank_con clearfix" name="monthdashang" style="display:none;">
                    <php>$i=0;</php>
                    <Hongshu:bangdan name="auto_class_nv_totalbookpro" items="10">
                        <if condition="$i eq 0">
                            <div class="rank_fm ">
                                <a href="{:url('Book/view',array('bid'=>$row['bid']))}" target="_blank"><img src="{$row.face}" /></a>
                            </div>
                            <ul>
                                <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                        <else/>
                            <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
                            </ul>
                        </if>
                        <php>$i++;</php>
                    </Hongshu:bangdan>
                    <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
                </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/rank','mod/head'],function(rank,head){
            //绑定排行榜日周月切换
            rank.bindbangdan('order','a','div');
            rank.bindbangdan('click','a','div');
            rank.bindbangdan('dashang','a','div');
            rank.bindbangdan('zan','a','div');
            rank.bindbangdan('fav','a','div');
            rank.bindbangdan('update','a','div');
            //绑定头部导航切换
            head.navhead();
        })
    </script>
</block>