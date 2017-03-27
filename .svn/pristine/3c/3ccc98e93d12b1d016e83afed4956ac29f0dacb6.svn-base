<extend name="Common/base" />
<block name="body">
<div class="unit mtop10">
    <div class="tit"><h1>免费新书</h1><span class="mlf10"></span></div>

    <div class="frame">
        <ul>
        <Hongshu:bangdan name="android_xinshu{$sex_flag}_xinshumianfendu" items="8">
            <li onClick="doChild('{:url('Book/view',array('bid'=>$row['bid']))}')">
                <div class="lf"><img src="{$row.face}"/></div>
                <div class="rt"><h1 class="hidden">{$row.catename}</h1>

                    <div class="tejia clearfix"><h5 class="fllf ">{$row.author}</h5></div>
                    <p class="h38em">{$row.intro}</p></div>
            </li>
        </Hongshu:bangdan>
        </ul>
    </div>
<!--我喜欢开始-->
<div class="unit">
    <div class="like mbom40"><if condition="$sex_flag eq 'nan'"><a href="javascript:change_like('nv');"  class="flrt" ><else/><a href="javascript:change_like('nan');"  class="flrt" ></if><span class="ic_set  fllf" ><img src="__IMG__/ic_set.png" /></span>我喜欢看<if condition="$sex_flag eq 'nan'">女生<else/>男生</if>小说></a></div>
</div>
<!--我喜欢结束-->
</div>
</block>