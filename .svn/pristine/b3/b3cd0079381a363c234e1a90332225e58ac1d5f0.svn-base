<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="style">
<style>
    .barrage_box p {
        height: 22px;
        line-height: 22px;
        overflow: hidden;
    }
</style>
</block>
<block name="body">
<div class="main top20 bom40">
    <div class="main1200">
        <div class="read">
            <span class="rd_nav c9"><a href="{:url('Index/index','','html')}">首页</a> > <a href="{:url('Channel/search',array('classid'=>$bookinfo['classid']),'html')}">{$bookinfo.classname}</a> > <a href="{:url('Book/view',array('bid'=>$bookinfo['bid']),'html')}">{$bookinfo.catename}</a></span>
            <h1 class="c3 top80">{$chapterinfo.title}</h1>
            <div class="rd_xx c6 top20 bom150">
				<p>很抱歉，此章萌神正在修订，敬请期待...</p>
				<p>您可以继续阅读后续章节</p>
			</div>
       
            <div class="readbom" style="display:block;">
                <div class="readtextbtn">
                    <ul class="top60 bom30">
                    <if condition="$chapterinfo['prev_chpid'] eq ''">
                    <li><a class="readtextbtnlast readtextbtnlast_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['prev_chpid']))}" class="readtextbtnlast readtextbtnlast_01">上一章</a></li>
                </if>
                <li style="width: 240px;"></li>
                <if condition="$chapterinfo['next_chpid'] eq ''">
                    <li><a class="readtextbtnnext readtextbtnnext_01">没有了</a></li>
                <else/>
                    <li><a href="{:url('Book/read',array('bid'=>$bookinfo['bid'],'chpid'=>$chapterinfo['next_chpid']))}" class="readtextbtnlast readtextbtnlast_01">下一章</a></li>
                </if>
                        <!-- <button><img src="__IMG__/hb.png">赏</button> -->
                    </ul>
                </div>
            </div>

          
        </div>

    </div>
</div>
    </block>