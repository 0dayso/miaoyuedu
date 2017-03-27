<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<div id="muluclose">
<div class="main top20 bom40">
    <div class="main1200">
        <div class="read">
            <span class="rd_nav c9"><a href="{:url('Index/index','','html')}">首页</a> > <a href="{:url('Channel/search',array('classid'=>$bookinfo['classid']),'html')}">{$bookinfo.classname}</a> > <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a></span>
            <h1 class="c3 top50">{$chapterinfo.title}</h1>
            <div class="rd_xx c6 top20 bom40">
                <span>萌神：<a class="c6">{$bookinfo.author}</a></span>
                <span>字数：{$chapterinfo.charnum}</span>
                <span>更新时间：{$chapterinfo.updatetime}</span>
            </div>
            <div class="vipread clearfix" style="display:block">
		        <div class="vipreadtit" style="display:block" id="user">

		        </div>
                <php>
            $str = $orderinfo['need_money'] . C('SITECONFIG.MONEY_NAME');
            if ($egold_pay>0 && $userinfo['egold']>=$orderinfo['need_money']) {
            $str = $egold_pay . C('SITECONFIG.EMONEY_NAME');
            } else if ($egold_pay){
            $str = C('SITECONFIG.EMONEY_NAME').'抵' . $egold_pay . C('SITECONFIG.MONEY_NAME');
            }
        </php>
        <if condition="$orderinfo['need_money'] gt 0">
		        <h2 class="top20 bom30 lf20 yahei">很抱歉，此章为VIP章节，内容不在免费试读的范围内，需要订阅才可继续阅读</h2>
		            <php>
                if($orderinfo['pl_star_chapter']){
                    $chpid = $orderinfo['pl_star_chapter']['chapterid'];
                }else{
                    $chpid = $chapterinfo['chapterid'];
                }
              </php>
                    <div class="vipreadnr">
			            <ul>
			                <li onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>1,'chpid'=>$chpid),'do')}');"><a href="{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>1,'chpid'=>$chpid),'do')}"><div class="vipchapter rt20 xuanzhong1">单章订阅</div></a>
			                </li>
			                <li onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>10,'chpid'=>$chpid),'do')}');"><a href="{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>10,'chpid'=>$chpid),'do')}"><div class="vipchapter lf20 xuanzhong10" >后10章</div></a>
			                </li>
			                <li onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>40,'chpid'=>$chpid),'do')}');"><a href="{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>40,'chpid'=>$chpid),'do')}"><div class="vipchapter lf20 xuanzhong40">后40章</div></a>
			                </li>
			                <li onclick="hg_gotoUrl('{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>100,'chpid'=>$chpid),'do')}');"><a href="{:url('Book/buyVipList',array('bid'=>$bid,'pl_num'=>100,'chpid'=>$chpid),'do')}"><div class="vipchapter lf20 xuanzhong100">后已发章节</div></a>
			                </li>
			                <div class="vipform top10 bom30">
			                	<p class="c3">
			                    <label>
				                    <input id="chbAutoOrder" type="checkbox" checked="checked" class="checkbox">
				                </label>自动订阅下一章，不在提醒（可在个人中心随时取消）</p>
			                </div>
			                <p class="cb bom10">*{$buy_detail_msg}</p>
                            </if>
                            <if condition = "$need_pay">
			                <button class="vipbtn" style="margin: auto; display: block;" onClick="hg_gotoUrl('{:url('Pay/Index#maodian','','do')}')"><a target="_blank" href="{:url('Pay/Index#maodian','','do')}">余额不足请充值</a></button>
                            <else />
			                <button type="button" class="vipbtn" onclick="<if condition= "$orderinfo['order_count'] gt 1">
                                pl_submitBtn(this);
                        <else />
                            submitBtn(this);
                            </if>
                            ">确认订阅({$str})</button>
                        </if>
			               </ul>
		            </div>

       		</div>

            <div class="readbom" style="display:block;">
                <div class="readtextbtn" id="zhangjieinfo">
                    <ul class="top60 bom30">
                    <if condition="$chapterinfo['prev_chpid'] eq ''">
                    <li><a class="gray readtextbtnlast readtextbtnlast_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}" class="readtextbtnlast readtextbtnlast_01">上一章</a></li>
                </if>
                <li style="width: 240px;"></li>
                <if condition="$chapterinfo['next_chpid'] eq ''">
                    <li><a class="gray readtextbtnnext readtextbtnnext_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}" class="readtextbtnlast readtextbtnlast_01">下一章</a></li>
                </if>
                        <!-- <button onclick="$('#dashang').show();"><img src="__IMG__/hb.png">赏</button> -->
                    </ul>
                </div>
            </div>

            <div class="zuofudong">
                <button class="fang_rd" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}')"><img src="__IMG__/fengmian.png" ><a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">封面</a></button>
                <button class="fang_rd" onclick="openmulu();"><img src="__IMG__/mulu.png">目录</button>
                <button class="fang_rd" id="sc"><img src="__IMG__/shoucang.png">收藏</button>
                <button class="fang_rd" style="display:none;" id="ysc"><img src="__IMG__/yishoucang.png">已收藏</button>
                <!-- <button class="fang_rd"><img src="__IMG__/shezhi.png">设置</button>
                <button class="fang_rd"><img src="__IMG__/bangzhu.png">帮助</button> -->
            </div>
            <div class="muluopen" style="position: absolute; top: 70px; display: none;">
                <i class="arrow arrow-1"></i>
                <i class="arrow arrow-2"></i>
                <div class="muluopencon">
                            <ul id="lists">
                            </ul>
                </div>
            </div>
            <!-- <div class="zhongfudong">
                <button class="fang_rd"><img src="__IMG__/bofang.png">播放弹幕</button>
                <button class="fang_rd"><img src="__IMG__/tucao.png">发送弹幕</button>
            </div> -->
            <div class="youfudong empty" id="zhangjieinfo2">
            <if condition="$chapterinfo['prev_chpid'] eq ''">
                   <button class="shangyizhang gray" ><img src="__IMG__/shangyizhang.png">没有了</button>
                <else/>
                    <button class="shangyizhang" onclick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}');"><img src="__IMG__/shangyizhang.png"><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}">上一章</a></button>
                </if>
                <if condition="$chapterinfo['next_chpid'] eq ''">
                    <button class="xiayizhang gray"><img src="__IMG__/xiayizhang.png">没有了</button>
                <else/>
                    <button class="xiayizhang" onclick="hg_gotoUrl('{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}');"><img src="__IMG__/xiayizhang.png"><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}">下一章</a></button>
                </if>

            </div>
        </div>

    </div>
</div>
</div>
    </block>
    <block name="script">
    <script type="text/javascript">
    Do.ready('common','functions',function(){
        var plflag = '{$plnum|default=1}';
            plflag = parseInt(plflag);
            if(plflag>1){
                $('.xuanzhong'+plflag).addClass('active');
                $('#chbAutoOrder').removeAttr('checked');
            }else if(plflag=1){
                $('.xuanzhong'+plflag).addClass('active');
                $('#chbAutoOrder').attr('checked','checked');
            }
    });
</script>
    <script type="text/html" id="tpl1">
        {{each list as row i}}
          {{if row.isvip == 0}}
          <li isvip="0" onclick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','html'}}')">
          <div class="left"><a><span>{{row.title}}</span></a></div>
          <div class="right">免费</div>
          {{else}}
          <li isvip="0" onclick="hg_gotoUrl('{{ {bid:row.bid,chpid:row.chapterid} | router:'Book/read','do'}}')">
          <div class="left"><a><span>{{row.title}}</span></a></div>
           <div class="right">vip</div>
           {{/if}}
        </li>
        {{/each}}
    </script>
    <script type="text/html" id="shangyizhang">
<ul class="top60 bom30">
    {{if prechapter.chpid == ''}}
        <li><a class="readtextbtnlast readtextbtnlast_01 gray">没有了</a></li>
    {{else}}
        {{if prechapter.isvip == 0}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{else}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{/if}}上一章</a></li>
    {{/if}}
        <li style="width: 240px;"></li>
    {{if nextchapter.chpid == ''}}
        <li><a class="readtextbtnnext readtextbtnnext_01 gray">没有了</a></li>
    {{else}}
        {{if nextchapter.isvip == 0}}
            <li><a href="javascript:hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{else}}
             <li><a href="javascript:hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')" class="readtextbtnlast readtextbtnlast_01">
        {{/if}}下一章</a></li>
    {{/if}}
      <!-- <button onclick="$('#dashang').show();"><img src="__IMG__/hb.png">赏</button>
</ul> -->
</script>
<script type="text/html" id="xiayizhang">
    {{if prechapter.chpid == ''}}
        <button class="shangyizhang gray" ><img src="__IMG__/shangyizhang.png">没有了</button>
    {{else}}
        {{if prechapter.isvip == 0}}
            <button class="shangyizhang" onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/read','html'}}')">
        {{else}}
            <button class="shangyizhang" onclick="hg_gotoUrl('{{ {bid:prechapter.bid,chpid:prechapter.chpid} | router:'Book/readVip','do'}}')">
        {{/if}}<img src="__IMG__/shangyizhang.png">上一章</button>
    {{/if}}
    {{if nextchapter.chpid == ''}}
        <button class="xiayizhang gray"><img src="__IMG__/xiayizhang.png">没有了</button>
    {{else}}
        {{if nextchapter.isvip == 0}}
            <button class="xiayizhang" onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/read','html'}}')">
        {{else}}
             <button class="xiayizhang" onclick="hg_gotoUrl('{{ {bid:nextchapter.bid,chpid:nextchapter.chpid} | router:'Book/readVip','do'}}')">
        {{/if}}<img src="__IMG__/xiayizhang.png">下一章</button>
    {{/if}}
</script>
<script type="text/javascript">
//获取上下章
function getPreNextChapter(){
    var data = {
        bid:{$bookinfo.bid|intval},
        chpid:{$chapterinfo.chapterid|intval}
    }
    var url = "{:url('Bookajax/getPreNextChapter','','do')}";
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
            //console.log(data);
            if(data.status == 1){
               var html1=template('shangyizhang',data);
               $('#zhangjieinfo').html(html1);
               var html2=template('xiayizhang',data);
               $('#zhangjieinfo2').html(html2);
            }
        }
    })
}
//添加到书架
function InsertFav(bid){
    if(!bid){
        hg_Toast('缺少参数');
        return false;
    }
    var data = {
        bid:bid,
    }
    var url = "{:url('Userajax/insertfav',array(),'do')}";
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
            if(data.status == 1){
               hg_Toast(data.message);
               $('#sc').hide();$('#ysc').show();
            }else{
				hg_Toast(data.message);
            }
        }
    })
}

Do.ready('common','template', function(){
    getPreNextChapter();
    UserManager.addListener(function(user){
        //这里判断user.islogin用来处理登录后的事件
        if(user.islogin){
            checkshoucang(user.uid);
            var htmls="<span>"+user.nickname+"</span>，你还有<span class='cb'> {$userinfo.money} </span> {:C('SITECONFIG.MONEY_NAME')}<a target=\"_blank\" href=\"{:url('Pay/index#maodian','','do')}\" class='vipbtn01  lf10'>充值</a>";
            $('#user').html(htmls);
        } else {
            $('sc').show();$('ysc').hide();
            $('sc').on('click',function(){
                hg_Toast('请先登录！');
            });
            var htmls="<a onclick=\"hg_gotoUrl('{:url('User/login','','do')}')\" class='vipbtn01  lf10'>登录</a>";
            $('#user').html(htmls);
        }
    });
});

function checkshoucang(uid){
    var url = "{:url('Bookajax/checkfav',array(),'do')}";
    var bid='{$bookinfo.bid}';
    var data = {
        bid:bid,
        uid:uid
    }
    $.ajax({
        type:'post',
        url: url,
        data:data,
        success: function (data){
            if(data.isfav == 1){
               $('#ysc').show();$('#sc').hide();
            }else{
               $('#sc').show();$('ysc').hide();
               $('#sc').on('click',function(){
                   InsertFav(bid);
               })
            }
        }
    })
}
function getchp(bid){
    var url = "{:url('Bookajax/getallchapter',array(),'do')}";
    var bid='{$bookinfo.bid}';
    var data = {
        bid:bid,
    }
    $.ajax({
        type:'get',
        url: url,
        data:data,
        success: function (data){
        Do.ready('template',function(){
            if(data.status == 1){
               var htmls=template('tpl1',data);
               $('#lists').html(htmls);
            }else{
               $('#lists').html('网络状态不佳，请刷新页面重试！');
               }
            })
        }
    })
}
 function openmulu(){
    var obj = $('.muluopen');
    if(obj.hasClass('opened')){
        closemulu();
        return false;
    }
    $('.muluopen').show();
    obj.addClass('opened');
    setTimeout(function(){$('#muluclose').bind('click', closemulu);}, 500);

 }
 function closemulu(){
    var obj = $('.muluopen');
    obj.removeClass('opened');
    $('#muluclose').unbind('click');
    $('.muluopen').hide();
 }
 Do.ready('common','functions',function(){
        getchp();
    });
</script>

<script>
var hg_bookName="{$bookinfo['catename']}";
var hg_bid="{$bookinfo.bid}";
var hg_baseUrl="__HOMEDOMAIN__";
<php>
	//批量订阅
	if($orderinfo['order_count']>1){
		$readurl = url('Book/read',array('bid'=>$bid,'chpid'=>$orderinfo['pl_star_chapter']['chapterid']));
</php>
	function pl_submitBtn(obj){
	lockbutton(obj);
	var url="{:url('Bookajax/orderChapter', '', 'do')}";
	//判断是否选择自动订阅
	var autobook = $(".checkbox").is(':checked');
	if(autobook){
    	var data = {
    			bid:{$bid},
	    		chpid:{$orderinfo['pl_star_chapter']['chapterid']},
	    		pl_num:{$orderinfo['order_count']},
	    		autoorder:"Y",
    	}
	}else{
		var data = {
	    		bid:{$bid},
	    		chpid:{$orderinfo['pl_star_chapter']['chapterid']},
	    		pl_num:{$orderinfo['order_count']},
	    }
	}
	$.ajax({
		url: url,
		type:"POST",
		data: data,
		dataType: 'json',
		success: function(json){

			if(json.status==1){
				if(json.message!=""){
					hg_Toast(json.message);
				}
				setTimeout(function () {
					window.location.href ="<?php echo $readurl?>";
				}, 500);
				//pl_unlockbutton(obj);
				//并发控制
			}else if (json.status==-1) {
				hg_Toast("处理中,请等待...");
			}else{
				hg_Toast(json.message);
				pl_unlockbutton(obj);
				if(json.url!=""){
					setTimeout(function () {
						window.location.href = json.url;
					},2000);
				}
			}
			pl_unlockbutton(obj);
		}
	});
}
<php>
	}elseif($orderinfo['order_count']==1 && $chapterinfo['chapterid']){
</php>
function submitBtn(obj){
	lockbutton(obj);
	var url="{:url('Bookajax/orderChapter', '', 'do')}";
    var iswarn='N';
    var autobook = $(".checkbox").is(':checked');
    if(autobook){
        iswarn='Y';
    }
    var data = {
		bid:{$bid},
		chpid:{$chapterinfo['chapterid']},
		autoorder:iswarn,
	}
	$.ajax({
		url: url,
		type: "POST",
		data: data,
		dataType: 'json',
		success: function(json){

			if(json.status==1){
				if(json.message!=""){
					hg_Toast(json.message);
				}
				setTimeout(function () {
					window.location.href = "{:url('Book/read',array('bid'=>$bid,'chpid'=>$chapterinfo['chapterid']))}";
				}, 500);
				//unlockbutton(obj);
				//并发控制
			}else if (json.status==-1) {
				hg_Toast("处理中,请等待...");
			}else{
				hg_Toast(json.message);
				if(json.url!=""){
					setTimeout(function () {
						window.location.href = json.url;
					},2000);
				}
				unlockbutton(obj);
			}
		}
	});
}
<?php }?>
function lockbutton(obj) {
	$(obj).attr("onclick","");
	$(obj).html("请稍候…");
}

function unlockbutton(obj) {
	$(obj).attr("onclick","submitBtn(this);");
	$(obj).html("确认单章购买");
}
function pl_unlockbutton(obj) {
	$(obj).attr("onclick","pl_submitBtn(this);");
	$(obj).html("确认批量购买");
}
</script>


</block>