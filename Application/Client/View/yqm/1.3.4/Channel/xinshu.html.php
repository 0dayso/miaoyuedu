<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<div class="unit mtop10">
    <div class="tit"><h1>免费新书</h1><span class="mlf10"></span></div>

    <div class="frame nan">
        <ul>
        <Hongshu:bangdan name="android_xinshu{$sex_flag}_xinshumianfendu" items="8">
            <li onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img src="{$row.face}"/></div>
                <div class="rt"><h1 class="hidden">{$row.catename}</h1>

                    <div class="tejia clearfix"><h5 class="fllf ">{$row.author}</h5></div>
                    <p class="h38em">{$row.intro}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul> 
    </div>
<!--我喜欢开始-->
<if condition="$sex_flag eq 'nan'">
<div class="unit nan">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nv'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看女生小说></a></div>
</div>
<else/>
<div class="unit nv">
    <div class="like mbom40"><a href="javascript:hg_gotoUrl('{:url('', array('sex_flag'=>'nan'))}')"  class="flrt" ><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看男生小说></a></div>
</div>
</if>
<!--我喜欢结束-->
</div>
</block>
