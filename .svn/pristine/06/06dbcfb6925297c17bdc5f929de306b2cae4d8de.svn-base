<!--导航-->
<div class="nav">
    <div class="nav1200">
        <div class="logo" onclick="doChild('{:url('Index/index')}');">
            <img src="__IMG__/logo.png"/>
            <p>红薯中文网</br>旗下轻小说网站</p>
        </div>
        <div class="daohang" >
            <a href="{:url('Index/index','','html')}" id="nav_index">首页</a>
            <a href="{:url('Channel/search','','html')}" id="nav_search">书库</a>
            <span id="head2_userinfo"><a href="{:url('User/authorreg','','do')}" id="nav_author">萌神入驻</a></span>
            <a href="http://img1.q.hongshu.com/static/zhuanti/all/20160531/index.html" target="_blank">萌神福利</a>
        </div>
        <div class="nav_right">
            <div class="input-group">
                <input id="txtSkeyWords" class="subsearch" value="" type="text" placeholder="" maxlength="250" autocomplete="off">
                <span class="input-group-btn yahei" onclick="goToSearch();"><img src="__IMG__/fdj.png"/></span>
            </div>
            <div id="head1_userinfo"></div>
        </div>
    </div>
</div>
<script type="text/html" id="userinfo_tpl2">
    <a href="{:url('User/authorLogin',array('sign'=>1),'do')}" id="nav_author">萌神中心</a>
</script>
<script type="text/html" id="userinfo_tpl">
        <div class="grzx">
            {{if islogin}}
             <div style="width:38px;height:38px;border-radius:38px;overflow:hidden;" lazy="y" onclick="doChild('{:url('User/shelf','','do')}')">{{if avatar}}<img data-src="{{avatar}}" id="avatarsmall" width="38" height="38" onload="getRedDot();" />{{else}}<img src="__IMG__/ic_person.jpg" id="avatarsmall" width="38" height="38" lazy="y" onload="getRedDot();"/>{{/if}}
             <span class="red_shuzi" id="shuzi" style="display:none;"></span>
             </div>
            {{else}}
            <div onclick="doChild('{:url('User/login','','do')}')" style="width:38px;height:38px;border-radius:38px;overflow:hidden;"> 
                <img src="__IMG__/grzx.png" width="38" height="38"/>
            </div>
            {{/if}}
        </div>    
            <!-- <div class="down" onclick="hg_gotoUrl('{:url('User/shelf')}')">
                <img src="__IMG__/down.png"/>
            </div>  -->
      
</script>

