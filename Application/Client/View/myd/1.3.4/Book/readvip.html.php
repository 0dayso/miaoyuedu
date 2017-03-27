<extend name="Common/base" />
<block name="header">
    <include file="Common/head2" />
</block>
<block name="body">
    <div class="read " style="display:none;">
        <div class="container">
            <div class="crumbs"><a href="{:url('Index/index','','d')}">首页</a>><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a>><a href="javascript:void(0);">{$chapterinfo.title}</a></div>
            <div class="rdcon">
                <div class="rdcon_tit"><h1>{$chapterinfo.title}</h1></div>
                <div class="rdcon_con clearfix">
                    {$chapterinfo['content']}
                </div>
                <div class="rdcon_dashang">
                    <a href="javascript:void(0);" id="reward" isshow="0">打赏</a>
                </div>
                <if condition="$chapterinfo[author_memo]">
                    <div class="rdcon_auhhorsay radius4" >
                        <div class="lf"><a href="javascript:void(0);" target="_blank"><img src="{$chapterinfo['author_avatar']}"  /></a></div>
                        <div class="rt"><p>作者<a href="javascript:void(0);"  class="cpink">“{$bookinfo['author']}”</a>{$chapterinfo['author_memo']}</p></div>
                    </div>
                </if>
                <div class="frame03 rdcon_mulu clearfix">
                    <ul id="updown1">
                        <li><a href="#">上一章</a><span class="rdcon_mulu_jishi">键盘快捷键“←”</span></li>
                        <li><span  class="rd_mulu" ><b class="ic_mulu">135/256</b></span><span class="rdcon_mulu_jishi">键盘快捷键“Enter”</span></li>
                        <li><a href="#" class="disable">下一章</a><span class="rdcon_mulu_jishi">键盘快捷键“→”</span></li>
                    </ul>
                </div>
            </div>
            <div class="fm6 tuijian  clearfix">
                <ul>
                    <Hongshu:bangdan name="class_nv_readrecom" items="6">
                        <li>
                            <a class="fm6_fm" href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"><img src="{$row.face}" /></a>
                            <p ><a class="fm6_name ellipsis" href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank">{$row.catename}</a></p>
                        </li>
                    </Hongshu:bangdan>
                </ul>
            </div>
        </div>
        <div class="rdset_lf">
            <a href="javascript:void(0);" hide="1" id="mulu" isshow="0"><span class="ic_set_mulu"></span>目录</a>
            <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}"><span class="ic_set_fm"></span>返回书页</a>
            <a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'do')}" class="set_comments"><span class="ic_set_comments"></span>评论({$commentnum})</a>
            <!-- <a href="#"><span class="ic_set_phone"></span>转到手机</a> -->
            <a href="javascript:void(0);" hide="1" id="set" isshow="0"><span class="ic_set_set"></span>设置</a>

        </div>
        <div class="rdset_rt"><div id="updown2"><a href="#" class="rdset_up"><span class="ic_set_up"></span>上一章</a><a href="#" class="rdset_down"><span class="ic_set_down disable"></span>下一章</a></div><a href="javascript:scroll(0,0);"><span class="ic_set_top"></span>返回顶部</a></div>
        <div class="rdset_lf_muluopen" style="display: none;" hide="1" name="mulu">
            <div class="rdset_chapter">
                <i class="arrow arrow-1"></i>
                <i class="arrow arrow-2"></i>
                <div class="rdset_chapter_con clearfix">
                    <ul name="mulu">
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="rdset_lf_setopen" style="display: none;" hide="1" name="set">
            <div class="rdset_set">
                <i class="arrow arrow-1"></i>
                <i class="arrow arrow-2"></i>
                <div class="rdset_set_con radius4">
                    <div class="setunit clearfix">
                        <div class="lf"><span  class="seticon01"></span><span>主题</span></div>
                        <div class="rt">
                            <ul>
                                <li><a href="javascript:void(0);" class="rdcolor00" type="rdcolor0">默认</a></li>
                                <li><a href="javascript:void(0);" class="rdcolor01" type="rdcolor1">颜色2</a></li>
                                <li><a href="javascript:void(0);" class="rdcolor02 " type="rdcolor2">颜色3</a></li>
                                <li><a href="javascript:void(0);" class="rdcolor03 " type="rdcolor3">颜色4</a></li>
                                <li><a href="javascript:void(0);" class="rdcolor04 " type="rdcolor4">颜色5</a></li>
                                <li><a href="javascript:void(0);" class="rdcolor05 " type="rdcolor5">颜色6</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="setunit clearfix">
                        <div class="lf" ><span class="seticon02"></span>字体大小</div>
                        <div class="rt">
                            <input type="button" value="-" id="reduce"  class="setreduce"/>
                            <input type="text" id="size" class="setinputtext radius4" value="16" />
                            <input type="button" value="+" id="add"  class="setadd"/>
                        </div>
                    </div>
                    <div class="setunit  clearfix">
                        <div class="lf"><span class="seticon03"></span>夜间模式</div>
                        <div class="rt">
                            <div class="autopaybtn2"><a href="javascript:void(0);" id="mode"><span class="btn_nozddy" mode="white"><i></i></span></a></div>
                        </div>
                    </div>
                    <div class="setunit clearfix">
                        <div class="lf"><span  class="seticon04"></span>分享</div>
                        <div class="rt setshare"><a href="#" target="_blank" class="ic_rdset_weixin"></a> <a href="#" target="_blank" class="ic_rdset_pyq"></a><a href="#" target="_blank" class="ic_rdset_weibo"></a></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="cover" name="dashang" style="display:none;"></div>
    <div class="tk_dashang radius4" name="dashang" style="display:none;">
        <div class="tk_dashang_tit"><a href="javascript:void(0);" class="close"></a><span>打赏</span></div>
        <div class="frame01 dashang_con_top  clearfix">
            <ul>
                <foreach name="propsList" item="row" key="k">
                    <li class="<if condition='$k eq 0'>active</if>" name="gift" pid="{$row.id}" giftname="{$row.name}" type="{$row.unit}{$row.name}" money="{$row.price}"><img src="__IMG__/{$row.img}" /><p><span class="cpink2">1</span>{$row.unit}</p><p>{$row.name}</p></li>
                </foreach>
            </ul>
        </div>
        <div class="dashang_con_center">
            <div class="song_num">送<button class="btn_reduce" id="reduceds">-</button><input name="gift" value="1" type="text" class="input_num radius4" /><button class="btn_add" id="addds">+</button><span class="mrt10" id="type" pid="6" money="50">个毛球</span>价值<b class="cpink2" id="money">50</b>{:C("SITECONFIG.MONEY_NAME")}</div>
            <textarea class="ask_con_textarea radius4" placeholder="主人，这是您的毛球，请笑纳！" id="dashangtext"></textarea>
            <div class="ask_con_other">
                <span class="ask_con_num"><span id="num">0</span>/300</span>
                <span class="ask_logining">剩余：<b class="cpink2">{$userinfo.money}</b>{:C("SITECONFIG.MONEY_NAME")},<b class="cpink2">{$userinfo.egold}</b>{:C("SITECONFIG.EMONEY_NAME")}</span>  <a href="{:url('Pay/index','','do')}" class="ask_chongzhi cblue">去充值》</a>
            </div>
            <div class="mtop10"><button class="ask_btn radius4" id="dashangtijiao">提交</button></div>
        </div>
    </div>
</block>
<block name="footer">
</block>
<block name="script">
    <include file="tpl/mulutpl"/>
    <include file="tpl/chaptertpl"/>
    <script type="text/javascript">
        var bid = "{$bookinfo.bid}";
        var chpid = "{$chapterinfo.chapterid}";
        $('.container').on('click',function(){
            $('div[hide="1"]').hide();
            $('a[hide="1"]').attr('isshow','0');
        })
        require(['mod/book','mod/comment'],function(book,com){
            book.getmululist(bid,'mulu_tpl','ul[name="mulu"]');
            book.getprenextchp(bid,chpid,'updown_tpl1','updown_tpl2','#updown1','#updown2');
            book.listenclick('#prechp','#rd_mulu','#nextchp');
            book.forbiddenclick();
            //更新阅读记录
            updateReadLog(bid,chpid,0,true);
            $('#mulu').on('click',function(){
                com.showhide('#mulu','div[name="mulu"]','isshow');
            })
            $('#set').on('click',function(){
                com.showhide('#set','div[name="set"]','isshow');
            })
            $('#reward').on('click',function(){
                com.showhide('#reward','div[name="dashang"]','isshow');
            })
            $('#mode').on('click',function(){
                var mode=$('#mode>span').attr('mode');
                if(mode=='black'){
                    $('#mode>span').removeClass('btn_zddy').addClass('btn_nozddy');
                    book.changetype('rdcolor0');
                    $('#mode>span').attr('mode','white');
                }else{
                    $('#mode>span').removeClass('btn_nozddy').addClass('btn_zddy');
                    book.changetype('rdcolor5');
                    $('#mode>span').attr('mode','black');
                }
            })
            $('.close').on('click',function(){
                $('div[name="dashang"]').hide();
            })
            $('a[type^="rdcolor"]').on('click',function(){
                $('a[type^="rdcolor"]').removeClass('active');
                $(this).addClass('active');
                var type = $(this).attr('type');
                book.changetype(type);
            })
            $('#reduce').on('click',function(){
                book.changesize(0,'.rdcon_con p','#size');
            })
            $('#add').on('click',function(){
                book.changesize(1,'.rdcon_con p','#size');
            })
            //取当前主题色和字体
            book.getthemecolor('body','a','type','rdcolor0');
            book.gettextsize('.rdcon_con p','#size');
            //开启黑夜模式
            var color=cookieOperate('themecolor');
            if(color=='rdcolor5'){
                $('#mode>span').removeClass('btn_nozddy').addClass('btn_zddy').attr('mode','black');
            }
            $('.read').show();
        })
        //加减道具
        require(['mod/reward'],function(reward){
            $('#addds').on('click',function(){
                reward.addreduce(1,'input[name="gift"]','#type','#money');
            })
            $('#reduceds').on('click',function(){
                reward.addreduce(0,'input[name="gift"]','#type','#money');
            })
        })
        
        //选择打赏道具
        $('li[name="gift"]').on('click',function(){
            $('li[name="gift"]').removeClass('active');
            $(this).addClass('active');
            var pid=$(this).attr('pid');
            var type=$(this).attr('type');
            var money=$(this).attr('money');
            var giftname=$(this).attr('giftname');
            $('#type').html(type);
            $('#type').attr('pid',pid);
            $('#type').attr('money',money);
            $('#money').html(money);
            $('input[name="gift"]').val(1);
            $('#dashangtext').attr('placeholder','主人，这是您的'+giftname+'，请笑纳！')
        })

        //打赏
        require(['mod/reward'],function(){
            $('#dashangtijiao').on('click',function(){
                var content = $('#dashangtext').val();
                var num = $('input[name="gift"]').val();
                var pid = $('#type').attr('pid');
                var unit= $('#type').html();
                if(!content){
                    var content = user.nickname+"送您"+num+unit+"，望再接再厉争取更大胜利！"
                }
                if(num==0){
                    hg_Toast('道具数量不能为0');
                    return;
                }
                var data = {
                    bid: parseInt('{$bookinfo['bid']|intval}'),
                    content: content,
                    num: num,
                    pid: pid,
                    sexflag: '{$sex_num}'
                }
                reward.sendreward(data);
            })
        })
    </script>
    <script src="http://author.miaoyuedu.com/hit.php?bid={$bookinfo['bid']}&nojs=1"></script>
</block>