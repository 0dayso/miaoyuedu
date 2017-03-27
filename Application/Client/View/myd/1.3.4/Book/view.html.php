<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
	<div class="container mtop10 clearfix">
	    <div class="col-3">
	        <div class="fm_top bgf mbom10 clearfix">
	            <div class="fm_top_tit"><h1>{$bookinfo.catename}</h1><a href="javascript:void(0);" target="_blank" class="fm_top_zz">作者：{$bookinfo.author}</a></div>
	            <div class="fm_bk">
	                <div class="lf"><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}" target="_blank"><img  src="{$bookinfo.cover}"  /></a></div>
	                <div class="rt">
	                    <div class="fm_top_tag"><span>类型：{$bookinfo.smallsubclassname}</span><span class="fm_top_tagword">{$bookinfo['tagsary'][0]}</span><span class="fm_top_tagword">{$bookinfo['tagsary'][1]}</span><span class="fm_top_tagword fm_top_tagword_last">{$bookinfo['tagsary'][2]}</span>
	                    <span>状态：<if condition="$bookinfo['lzinfo'] eq 1">完本<else/>连载</if></span><span>字数：<?php
                        if ($bookinfo['charnum'] > 10000) {
                            $bookinfo['charnum'] = round($bookinfo['charnum'] / 10000, 1) . "万";
                        }

                        echo $bookinfo['charnum'] . '字';
                        ?></span></div>
	                    <div class="fm_top_jj">
	                        {$bookinfo['intro']}
	                    </div>
	                </div>
	            </div>
	            <div class="fm_btn">
	                <div class="lf">
	                <if condition="$bookinfo['isvip_last'] eq 1">
		                <a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'do')}" target="_blank" class="readbtn radius4"><if condition="$bookinfo['lastreadchpid']">继续阅读<else/>开始阅读</if></a>
		            <else/>
			            <a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$bookinfo['lastreadchpid']),'html')}" target="_blank" class="readbtn radius4"><if condition="$bookinfo['lastreadchpid']">继续阅读<else/>开始阅读</if></a>
		            </if>    
		            </div>
	                <div class="frame01 fm_otherbtn">
	                    <ul>
	                        <li id="collect"><a href="javascript:void(0);" class="radius4"><span class="ic_fm_bkshelf"></span><span class="fm_otherbtn_word">加入书架</span></a></li>
	                        <li><a href="{:url('Book/chapterlist',array('bid'=>$bookinfo['bid']),'do')}" class="radius4"><span class="ic_fm_chapter"></span><span class="fm_otherbtn_word">章节目录</span></a></li>
	                        <li id="zan"><a href="javascript:void(0);" class="radius4"><span class="ic_fm_zan"></span><span class="fm_otherbtn_word">点赞</span></a></li>
	                        <li id="dashang"><a href="#gocomment" class="radius4"><span class="ic_fm_dashang"></span><span  class="fm_otherbtn_word">打赏</span></a></li>
	                     </ul>
	                </div>
	            </div>
	        </div>
	        <div class="frame02 fm_chapter bgf mbom10 clearfix">
	            <div class="fm_chapter_tit"><h4>最新章节</h4></div>
	            <ul id="updatelists">
	                <foreach name="bookinfo['newchap']" item="row">
		                <li>
		                    <p>
		                    <if condition="$row.isvip eq 1">
			                    <a href="{:url('Book/readVip',array('bid'=>$bookinfo['bid'],'chpid'=>$row['chapterid']),'do')}" target="_blank" class="fm_chapter_word ellipsis">{$row.title}</a><span class="vip"></span>
			                <else/>
			                    <a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$row['chapterid']),'html')}" target="_blank" class="fm_chapter_word ellipsis">{$row.title}</a>
			                </if>
			                    </p>
		                    <p class="fm_chapter_date">更新时间：{$row.updatetime}</p>
		                </li>
		            </foreach>
	            </ul>
	        </div>
	        <div class="comments bgf clearfix" page="comment">
	            <div class="tit"><a href="javascript:void(0)" class="comment_dashang" id="godashang">打赏(<span id="dsnum">0</span>)</a><span class="comment_zan" >点赞(<span id="dznum">0</span>)</span><a href="javascript:void(0);" class="titbg">最新评论</a><a href="#mycomment" class="iwantcommnent"><span>我要评论</span><span class="ic_pen"></span></a></div>
	            <div class="comments_con" >
	                <ul id="comments">
	                </ul>
	            </div>
	            <div class="comments_more clearfix"><a href="{:url('Book/comment',array('bid'=>$bookinfo['bid']),'html')}" class="comments_more_btn radius4">更多评论（{$total_comment}）</a></div>
	            <div class="mycomment bgf clearfix" id="mycomment">
	                <div class="mycomment_tit"><h4>我来评论这本书</h4><a name="nologin" href="{:url('User/login','','do')}">登录 |</a><a name="nologin" href="{:url('User/register','','do')}">注册</a></div>
	                <div class="mycomment_con">
	                    <div class="lf"><a href="javascript:void(0);" target="_blank" class="comment_avater"><img src="__IMG__/avatar/avater_small.jpg" /></a></div>
	                    <div class="rt">
	                        <textarea id="textall" class="ask_con_textarea radius4" placeholder="主人的文采这么棒，怎么可以没评论？"></textarea>
	                        <div class="ask_con_other">
	                            <a href="javascript:void(0);" id="bq" class="face"><img src="__IMG__/face/1.gif"></a><span class="ask_con_num"><span id="num">0</span>/<span id="totalnum">300</span></span>
	                            <div class="mtop10"><button class="ask_btn radius4 " id="tijiao">发表评论</button></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="comments bgf clearfix" page="dashang" style="display:none;">
		        <div class="tit disable_tit"><a href="javascript:void(0);" class="comment_dashang active">打赏(<span id="dsnum">0</span>)</a><span class="comment_zan" >点赞(<span id="dznum">0</span>)</span><a href="javascript:void(0);" id="gocomment" class="titbg ">最新评论</a></a></div>

		        <div class="dashang_con">
		            <div class="frame01 dashang_con_top  clearfix">
		                <ul>
			                <foreach name="propsList" item="row" key="k">
			                    <li class="<if condition='$k eq 0'>active</if>" name="gift" pid="{$row.id}" giftname={$row.name} type="{$row.unit}{$row.name}" money="{$row.price}"><img src="__IMG__/{$row.img}" /><p><span class="cpink2">1</span>{$row.unit}</p><p>{$row.name}</p></li>
		                    </foreach>
		                </ul>
		            </div>
		            <div class="dashang_con_center">
		                <div class="song_num">送<button class="btn_reduce" id="reduce">-</button><input name="gift" value="1" type="text" class="input_num radius4" /><button class="btn_add" id="add">+</button><span class="mrt10" id="type" pid="6" money="50">个毛球</span>价值<b class="cpink2" id="money">50</b>{:C("SITECONFIG.MONEY_NAME")}</div>
		                <textarea class="ask_con_textarea radius4" id="dashangtext" placeholder="主人，这是您的毛球，请笑纳！"></textarea>
		                <div class="ask_con_other">
		                    <div name="nologin">
		                    <a href="javascript:void(0);" id="login" class="ask_con_other_login">登录</a><span>|</span><a href="{:url('User/register','','do')}" class="ask_con_other_login">注册</a>
		                    </div><span class="ask_con_num"><span id="num2">0</span>/300</span>
		                    <span name="islogin" class="ask_logining" id="money_egold">剩余：<b class="cpink2">0</b>{:C("SITECONFIG.MONEY_NAME")},<b class="cpink2">0</b>{:C("SITECONFIG.EMONEY_NAME")}</span>  <a name="islogin" href="{:url('Pay/index','','do')}" class="ask_chongzhi cblue">去充值》</a>
		                </div>
		                <div class="mtop10"><button class="ask_btn radius4" id="dashangtijiao">提交</button></div>
		            </div>
		            <div class="dashang_con_bom">
		                <div class="dashang_con_bom_tit">打赏记录</div>
		                <ul id="dashanglist" totalnum="0">
		                    
		                </ul>
		                <div class="page" id="pagelist">
			            </div>
		            </div>
		        </div>
		    </div>
	    </div>
	    <div class="col-1 clearfix">
	        <div class="rank mbom10 ">
	            <div class="rank_tit2"><a href="{:url('Channel/copyright','','html')}" target="_blank" class="banquan">版权开发合作》</a> IP版权状态</div>
	            <div class="ipzsd clearfix">
	                <ul>
		                <foreach name="iplist" item="row" key="i">
		                    <li><span class="{$row.imgurl} <if condition='$row.isget eq 1'> active</if>" title="{$row.title}"></span></li>
		                </foreach>
	                </ul>
	            </div>
	        </div>
	        <div class="rank mbom10 ">
	            <div class="rank_tit2">作者专栏</div>
	            <div class="rank_author clearfix">
	                <div class="rank_author_tx"><a href="javascript:void(0);"><img src="{$bookinfo.avatar}"  /></a></div>
	                <p class="rank_author_name"><a href="javascript:void(0);" >{$bookinfo.author}</a> </p>
	                <p class="rank_author_zhuli">作者助理<if condition="$bookinfo['banzhu']"><a href="javascript:void(0);">助理：{$bookinfo['banzhu']}</a><else/><a href="javascript:void(0);" id="banzhu">+申请助理</a></if><if condition="$bookinfo['fubanzhu']"><a href="javascript:void(0);">副助理：<foreach name="bookinfo['fubanzhu']" item="row">&nbsp;{$row}</foreach></a><else/><a href="javascript:void(0);" id="fubanzhu">+申请副助理</a></if></p>
	            </div>
	        </div>
	        <div class="rank mbom10 ">
	            <div class="rank_tit2">作者的其他作品</div>
	            <div class="rank_fm2 clearfix">
	                <ul>
	                    <if condition="$otherauthorbooks">
		                    <foreach name="otherauthorbooks" item="row">
			                    <li>
			                        <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"><img src="{$row['cover']}"></a></div>
			                        <div class="rt"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="name ellipsis" target="_blank">{$row['catename']}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row['author']}</a></div>
			                    </li>
			                </foreach>
			            <else/>
				            <li>作者暂无其他书籍</li>
			            </if>
	                </ul>

	            </div>
	        </div>
	        <div class="rank mbom10 ">
	            <div class="rank_tit2">作者推荐的书</div>
	            <div class="rank_fm2 clearfix">
	                <ul>
		                <if condition="$authorrecommendbooks">
			                <foreach name="authorrecommendbooks" item="row">
			                    <li>
			                        <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"><img src="{$row['cover']}"></a></div>
			                        <div class="rt"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="name ellipsis" target="_blank">{$row['catename']}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row['author']}</a></div>
			                    </li>
			                </foreach>
			            <else/>
				            <li>作者暂无推荐书籍</li>
			            </if>
	                </ul>
	            </div>
	        </div>
	        <!-- <div class="rank mbom10 ">
	            <div class="rank_tit2">同类热门</div>
	            <div class="rank_fm2 clearfix">
	                <ul>
		                <foreach name="classhotbooks" item="row">
		                    <li>
		                        <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"><img src="{$row.cover}"></a></div>
		                        <div class="rt"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
		                    </li>
	                    </foreach>
	                </ul>

	            </div>
	        </div> -->
	        <div class="rank">
	            <div class="rank_tit2">强力推荐</div>
	            <div class="rank_con clearfix">
	            <php>$i=0;</php>
		            <Hongshu:bangdan name="class_nv_qianglirecom" items="10">
                        <if condition="$i eq 0">
			                <div class="rank_fm ">
			                    <span class="num num{$i+1}">{$i+1}</span>
			                    <div class="lf"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" target="_blank"><img src="{$row.face}"></a></div>
			                    <div class="rt"><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="name ellipsis" target="_blank">{$row.catename}</a><a href="javascript:void(0);" class="zz ellipsis" target="_blank">作者：{$row.author}</a></div>
			                </div>
			                <ul>
			            <else/>
		                    <li><span class="num num{$i+1}">{$i+1}</span><a href="{:url('Book/view',array('bid'=>$row['bid']),'html')}" class="ellipsis" target="_blank">{$row.catename}</a></li>
	                    </if>
	                    <php>$i++;</php>
                    </Hongshu:bangdan>
			                </ul>
	                <div class="rmore"><a href="{:url('Channel/search','','do')}">更多</a> </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="cover" name="logintk" style="display:none;"></div>
	<div class="tk_login radius4" name="logintk" style="display:none;" style="top:100px;">
	    <a href="javascript:void(0);" class="close2"></a>
	    <div class="tit4"><div class="tit4_border"><h1>欢迎您登录{:C("SITECONFIG.SITE_NAME")}</h1></div></div>
	    <p class="tk_login_ts">不用注册，您可以直接用微信号，QQ号，新浪微博号等登录。放心，我们不会记住您的这些账号密码</p>
	    <div class="frame03 other_login">
	        <ul>
	            <li><a href="{:url('Usercenter/Third/wechatlogin')}" class="radius4 weixin" ></a><span>微信</span></li>
	            <!-- <li><a href="__USERDOMAIN__/third/qq/login.html" class="radius4 qq"></a><span>QQ</span></li>
	            <li><a href="__USERDOMAIN__/third/sina/login.html" class="radius4 weibo"></a><span>新浪微博</span></li> -->
	        </ul>
	    </div>
	    <div class="login_con">
            <h5>使用{:C("SITECONFIG.SITE_NAME")}账号登陆：<a href="{:url('User/register','','do')}" class="cblue">注册{:C("SITECONFIG.SITE_NAME")}账号</a></h5>
            <div class="form_item"><input type="text" placeholder="注册手机号或电子邮箱" class="radius4" name="username"/> </div>
            <p class="wrong" name="username" style="display:none;"></p>
            <div class="form_item"><input type="password" placeholder="密码" class="radius4" name="password"/> </div>
            <p class="wrong" name="password" style="display:none;"></p>
            <div class="form_item"><a class="img_ewm"><img id="imgcode" src="{:url('User/imgcode','','do')}" /></a><input type="text" placeholder="请输入验证码" class="ewm radius4" name="yzm"/><span class="right" style="display:none;"></span></div>
            <p class="wrong" name="yzm" style="display:none;"></p>
            <div class="form_item2"><button class="mainbtn radius4" id="gologin">登录</button></div>
            <p><input id="checkbox" type="checkbox" checked />记住我<a href="{:url('User/losepwd','','do')}" class="forget_password">忘记密码？</a> </p>
	    </div>
	</div>
	<div id="bqb" style="display:none;position:absolute;"><include file="tpl/bqb"/></div>
</block>
<block name="script">
    <include file="tpl/commenttpl" />
    <include file="tpl/replytpl" />
    <script type="text/html" id="dashang_tpl">
    	{{each list as row i}}
	        <li>
                <div class="lf"><span class="cblue">{{row.nickname}}：</span>打赏了本书<span class="cpink2">{{row.num}}{{row.unit}}{{row.name}}</span></div>
                <div class="rt">{{row.addtime}}</div>
            </li>
    	{{/each}}
    </script>
    <script type="text/html" id="update_tpl">
    	{{each updateList as row i}}
	        <li>
                <p>
                   <a href="{{row.link}}" target="_blank" class="fm_chapter_word ellipsis">{{row.title}}</a>
                {{if row.isvip == 1}}
                    <span class="vip"></span>
                {{/if}}
                </p>
                <p class="fm_chapter_date">更新时间：{{row.updatetime}}</p>
            </li>
    	{{/each}}
    </script>	
    <script type="text/javascript">
	    var bid = "{$bookinfo.bid}";

	    function getcomments(){
	        var url = "{:url('Bookajax/multipleData','','do')}";
	        require(['api','template','functions'],function(api,template){
	        	var data = {
	        		bid:bid,
                    CommentNum:5,
                    ChapterNum:4
	        	}
	            api.getapi(url,data,function(data){
	            	$('#dsnum').html(data.pronum);
	            	$('#dznum').html(data.total_flower);
		            if(data.status==1){
		            	for(var i in data.list){
		            		data.list[i].link=parseUrl({bid:bid,comment_id:data.list[i]['comment_id']},'Book/replyComment','do');
		            	}
		                var html1=template('comment_tpl',data);
		        	    $('#comments').html(html1);
		        	    clickevents();
		        	    for(var i in data.updateList){
		        	    	if(data.updateList[i].isvip==1){
		        	    		data.updateList[i].link=parseUrl({bid:bid,chpid:data.updateList[i]['chapterid']},'Book/readVip','do');
		        	    	}else{
		        	    		data.updateList[i].link=parseUrl({bid:bid,chpid:data.updateList[i]['chapterid']},'Book/read','html');
		        	    	}
		            		
		            	}
		        	    var htmlnew=template('update_tpl',data);
		        	    $('#updatelists').html(htmlnew);
		            }else{
		            	//hg_Toast(data.message);
		            }
				})
	        })
			
	    }

	    function clickevents(){
	    	var Commentlist=$('#comments').find('.empty');
		    $.each(Commentlist,function(){
			    var cid=$(this).attr('cid');
		        //绑定回复按钮事件
		        $(this).on('click','#r'+cid,function(){
		        	getreply(cid);
		        	require(['mod/comment'],function(com){
			        	com.showhide(this,'#ro'+cid,'isshow');
			        })
		        });
               
	            //绑定弹出回复框
		        $(this).on('click','#tk'+cid,function(){
		        	require(['mod/comment'],function(com){
			        	com.showhide(this,'#k'+cid,'isshow');
			        })
		        });
		        //绑定弹出表情包
		        $(this).on('click','#bq'+cid,function(e){
		        	require(['mod/comment'],function(com){
			        	com.showhidebqb('#bqb','isshow',e,cid);
			        })
		        })
		        
		        //绑定检测字数
		        $(this).on('keydown','#text'+cid,function(){
		        	require(['mod/comment'],function(com){
			        	com.checknum('#text'+cid,'#num'+cid);
			        })
		        })
		       
		        
		        if(userinfo.islogin){
		        	//绑定点赞事件
	               	$(this).on('click','#z'+cid,function(){
	               		require(['mod/book'],function(book){
	               			console.log('dianzan');
				            book.sendzan(bid,cid,'#za'+cid);
				        })
		            });
		        	//绑定提交回复
		        	$(this).on('click','#send'+cid,function(){
			        	var length=$('#text'+cid).val().length;
			        	require(['api','template','mod/comment','functions'],function(api,template,com){
				        	if(length>300 ||length<4){
				        		hg_Toast('回复内容字数要在4-300之间');
				        		return;
				        	}
				        	var content = $('#text'+cid).val();
	                        com.addreply(bid,cid,content,'reply_tpl','#rbox'+cid,'#za'+cid,'#text'+cid);
				        })
	                })
		        }else{
		        	$(this).on('click','#send'+cid,function(){
			        	$('div[name="logintk"]').show();
	                })
	                $(this).on('click','#z'+cid,function(){
	               		$('div[name="logintk"]').show();
		            });
		        }
		        
		    });
	    }

	    function getreply(cid){
	    	var url = "{:url('Bookajax/replyComment','','do')}";
	    	require(['api','template','functions'],function(api,template){
	    		api.getapi(url,{bid:bid,pagesize:5,comment_id:cid,pagenum:1},function(data){
		            if(data.status==1){
		               var html2=template('reply_tpl',data);
		        	   $('#rbox'+cid).html(html2);
		            }
				})
	    	})
	    	
	    }
        //获取打赏列表
	    function getlist(pagenum){
	        if(!pagenum){
	        	var pagenum = 1;
	        }
	        var totalnum = $('#dashanglist').attr('totalnum');
	        require(['api','template','mod/page'],function(api,template,page){
	        	var url = "{:url('Bookajax/rewardList','','do')}";
	        	var data = {
	        		bid:bid,
	        		pagenum:pagenum,
	        		pagesize:10,
	        		totalnum:totalnum
	        	}
	        	api.getapi(url,data,function(data){
	                if(data.status==1){
                        var html = template('dashang_tpl',data);
                        $('#dashanglist').html(html);
                        $('#dashanglist').attr('totalnum',data.totalnum);
                        page.changepage('getlist',data.pagenum,data.pageliststart,data.totalpage,'#pagelist')
	                }else{
	                	$('#dashanglist').html('暂无打赏记录');
	                }
	        	})
	        })
	    }
	    //加减道具
	    require(['mod/reward'],function(reward){
	        $('#add').on('click',function(){
	            reward.addreduce(1,'input[name="gift"]','#type','#money');
		    })
		    $('#reduce').on('click',function(){
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
        //切换评论和打赏
	    $('#godashang').on('click',function(){
	    	$('div[page="comment"]').hide();
	    	$('div[page="dashang"]').show();
	    })
	    $('#dashang').on('click',function(){
	    	$('div[page="comment"]').hide();
	    	$('div[page="dashang"]').show();
	    })
	    $('#gocomment').on('click',function(){
	    	$('div[page="comment"]').show();
	    	$('div[page="dashang"]').hide();
	    })
		//切换长评
        $('input[name="changping"]').on('click',function(){
            var check = $('input[name="changping"]:checked').val();
            if(check){
                $('#totalnum').html('1000');
            }else{
                $('#totalnum').html('300');
            }
        })
        
        require(['mod/comment','mod/book','mod/reward'],function(com,book,reward){
            $('#textall').on('keydown',function(){
                com.checknum('#textall','#num');
            });
            $('#bq').on('click',function(e){
	        	com.showhidebqb('#bqb','isshow',e,'all');
            })
            $('#dashangtext').on('keydown',function(){
	        	com.checknum('#dashangtext','#num2');
	        })
            
            var bid="{$bookinfo['bid']}";
			var banzhu="{$bookinfo['banzhu']}";
			var fubanzhu="{$bookinfo['fubanzhu']}";
            //判断登录
            UserManager.addListener(function(user){
            	userinfo=user;
            	if(!user.islogin){
            		$('textarea').addClass('disable').attr('readonly',true);
            		$('div[name="nologin"]').show();
            		$('span[name="islogin"]').hide();
            		$('a[name="islogin"]').hide();
            		$('#collect').on('click',function(){
            			$('div[name="logintk"]').show();
            		})
            		$('#zan').on('click',function(){
            			$('div[name="logintk"]').show();
            		})
            		$('#banzhu').on('click',function(){
	            		$('div[name="logintk"]').show();
	            	})
                    $('#fubanzhu').on('click',function(){
	            		$('div[name="logintk"]').show();
	            	})
	            	$('#tijiao').on('click',function(){
	            		$('div[name="logintk"]').show();
	            	})
	            	$('#dashangtijiao').on('click',function(){
	            		$('div[name="logintk"]').show();
	            	})
            	}else{
            		$('span[name="islogin"]').show();
            		$('a[name="islogin"]').show();
            		$('div[name="nologin"]').hide();
            		book.bindfav('#collect',bid,'span:last');
		            book.bindzan('#zan',bid);
		            if(!banzhu){
		            	$('#banzhu').on('click',function(){
		            		book.applyBanzhu(1,bid);
		            	})
		            }
		            if(!fubanzhu){
		            	$('#fubanzhu').on('click',function(){
		            		book.applyBanzhu(2,bid);
		            	})
		            }
		            //提交评论
			        $('#tijiao').on('click',function(){
			            var length = $('#textall').val().length;
			            var totalnum = parseInt($('#totalnum').html());
			                if(length<4 || length>totalnum){
			                    hg_Toast('评论字数不符合要求');
			                    return;
			                }
			                var content = $('#textall').val();
			                com.addcomment(bid,content,'comment_tpl','#comments','#empty','#textall');
			        })
			        //打赏
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
            	}
            	//获取评论列表
            	getcomments();
            	//获取打赏列表
            	getlist();
            })
            
        })
        //登录事件
        require(['mod/user'],function(user){
        	$('#login').on('click',function(){
        		$('div[name="logintk"]').show();
        	})
	        $('.close2').on('click',function(){
	        	$('div[name="logintk"]').hide();
	        })
	        $('#imgcode').on('click',function(){
                user.refreshimg('#imgcode');
            })
            var fu=window.location.href;
            $('#gologin').on('click',function(){
                user.login('username','password','yzm',fu,'#gologin');
            })
        });
	</script>
	<script src="http://author.miaoyuedu.com/hit.php?bid={$bookinfo['bid']}&nojs=1"></script>
</block>