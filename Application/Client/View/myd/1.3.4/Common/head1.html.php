<block name="header">
    <div class="header clearfix">
        <div class="container ">
            <div class="logo"><a href="{:url('Index/index','','html')}"><img src="__IMG__/logo.png" /></a></div>
            <div class="topnav">
                     <a href="{:url('Channel/fuli','','html')}"><img src="__IMG__/ic_topnav_fuli.png"  />福利</a>
                     <a href="#"><img src="__IMG__/ic_topnav_phone.png"  />喵手机</a>
                     <a href="{:url('Pay/index','','do')}"><img src="__IMG__/ic_topnav_chongzhi.png"  />充值</a>
                    <a href="{:url('User/index','','do')}" class="tx on" name="islogin" style="display:none;"><img src="__IMG__/avatar/avater_small.jpg" id="useravatar" /><!-- <span class="reddot"></span> --></a>
                    <a href="{:url('User/logout','','do')}" class="user" name="islogin" style="display:none;">退出</a>
                    <a href="{:url('User/login')}" class="tx" name="nologin"><img src="__IMG__/ic_topnav_tx.png" /></a>
                    <a href="{:url('User/login','','do')}" class="user" name="nologin" name="islogin">登录</a>
            </div>
        </div>
    </div>
    <div class="nav clearfix">
        <div class="container ">
            <div class="mainnav"><a href="{:url('Index/index','','html')}" id="nav_index">首页</a><a href="{:url('Channel/search','','do')}" id="nav_search">书库</a><a href="{:url('Channel/rank','','html')}" id="nav_rank">排行榜</a><a href="{:url('Channel/search',array('finish'=>1),'do')}">完本</a> <a href="{:url('Channel/copyright','','html')}">版权专区</a></div>
            <div class="topsearch"><input type="search" id="headsearch" class="inputsearch" /><button class="ssbtn" onclick="searchkeyword();"></button></div>
        </div>
    </div>
    <script type="text/javascript">
        function searchkeyword(){
            var keyword = $('#headsearch').val();
            require(['functions'],function(){
                var url = parseUrl('','Channel/search','do');
                if(keyword){
                    url += url.indexOf("?")>0 ? '&':'?';
                    url += 'keyword='+keyword;
                }
                window.location.href = url
            })
            
        }
    </script>
</block>