<!--顶部开始-->
<div class="ttop ttop2 nav{$sex_flag|default='nv'} mbom0">
    <div class="lf"><a class="returnbtn" href="javascript:history.back(-1)"></a><h1 class="hidden" style="width:78%"><if condition="$secondTitle">{$secondTitle}<else/>{$pageTitle}</if></h1></div>
    <div class="rt" id="head2_userinfo">
     <a href="__ROOT__/nv.html"><img src="__IMG__/ic_store.png" lazy="y"/></a>
        <a href="{:url('Channel/search',array('sex_flag'=>'nv','classid'=>0))}" class="ic_ss"><img src="__IMG__/ic_search_white.png" lazy="y"/></a>

    </div>
</div>
<!--顶部结束-->
<script type="text/html" id="userinfo_tpl">
       <a href="__ROOT__/{{sex_flag}}.html"><img src="__IMG__/ic_store.png" lazy="y"/></a>
		<a href="{{ {sex_flag:sex_flag,classid:0} | router:'Channel/search','html'}}" class="ic_ss"><img src="__IMG__/ic_search_white.png" lazy="y"/></a>
        {{if islogin}}
            <a href="{{ {sex_flag:sex_flag} | router:'User/index','do'}}" class="ic_ss"><img src="{{ uid | get_user_avatar_url }}" default="__IMG__/avatar.jpg" /></a>
            {{else}}
            <a href="{{ {sex_flag:sex_flag} | router:'User/login','do'}}" class="ic_per"><img src="__IMG__/ic_person_white.png" lazy="y"/></a>
     {{/if}}
</script>
