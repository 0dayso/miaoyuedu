<extend name="Common/base" />

<block name="style">
    <style>
        .list-box { margin:15px ;display:-webkit-box;-webkit-box-sizing:border-box;border:1px solid #067;height:30px;line-height:30px;text-align:center;background:#d3e6e5;-webkit-border-radius:3px}
        .list-box li { -webkit-box-flex:1;text-align:center;border-right:1px solid #067;color:#067;}
        .list-box li.active  , .list-box li:active { background:#76bab2;}
        .list-box li:last-child { border-right:0;}
        .list-box_girl { border:1px solid #b23636;background:#feeff1;}
        .list-box_girl li { border-right:1px solid #b23636;}
        .list-box_girl li.active  , .list-box_girl li:active { background:#ff9fad;}
        .list-box_free { border:1px solid #001b72;background:#e4e7ec;}
        .list-box_free li { border-right:1px solid #001b72}
        .list-box_free li.active , .list-box_free li:active { background:#c5d6e8;}
        #scroll .v3_view { overflow: hidden;position: relative;left: 0;top: 0;z-index: 10;}
        #scroll .v3_imgs { width: 100%;margin: 0;-webkit-transition: left .4s ease;}
        .v3_view ul { overflow: hidden;}
        .v3_view ul li { position: relative;}
        .v3_view img { width: 100%;}
        #scroll .v3_imgs  img { height:auto; width:100%;}
        #scroll .v3_dot { height: 10px;z-index: 100000;display: table;position: absolute;bottom: 4px;right: 8px;}
        .v3_dot { text-align: center;}
        #scroll .v3_dot_r { background-color:#808080;-webkit-border-radius: 4px;width: 6px;height: 6px;display: table-cell;float: left;margin: 0 2px;display: none;}
        #scroll .v3_cur { background-color:#f30;}
        .xlb { height: 50px;padding:0 15px 0 28px; border-bottom:1px solid #dedede;color:#FF7100; background:url(__IMG__/laba.gif) no-repeat 10px center;-webkit-background-size:15px 15px; background-color:#eee;overflow:hidden; font-size:14px;}
        .xlb ul { height: 35px;padding-top: 10px;overflow:hidden;}
        .xlb li { height:30px; line-height:32px;}
        .ztpic { margin: 0px 15px 0px 15px; padding-bottom:10px; }
        .ztpic ul { width: 100%;margin: 0 auto;}
        .ztpic ul li { display: block;width: 50%;padding: 0 5px;float: left;text-align: center;box-sizing: border-box;-webkit-box-sizing: border-box;}
        .ztpic ul li:first-child { padding: 0 5px 0 0;}
        .ztpic ul li img { width: 100%;height: auto;border: none;}
        .title-sj { font-size:18px;}
        .book-items li .book-item-cover { width: 84px;height: 104px;}
        .book-items li .book-item-cover img { width: 80px;height: 100px;}
        .book-items li .book-item-cover img { box-shadow: 0px 0px 0px #e0e0e0;}
        .book-items li h3, .book-items2 li h3 { font-size: 18px; color:#333;}
        .ztpic { padding: 8px; margin:0;}
        .ztpic a { width: 49%;float: left;}
        .ztpic a:nth-child(2n) { margin-left: 2%;}
        .ztpic a { display: block;position: relative;}
        .ztpic img { width: 100%;height: 74px;}
        .zttitbg { border-bottom: 1px solid #d6d6d6;}
        .zttit { line-height: 32px;padding: 8px 15px 0;font-size: 17px;line-height: 39px;color: #4e4c4a;}
        .zttitmore { float: right;position: relative;padding-right: 14px;font-size: 14px;font-weight: normal;}
        .zttitmore:after { content: ' ';position: absolute;width: 10px;height: 10px;border: #4e4e4e solid;border-width: 1px 1px 0 0;-webkit-transform: rotate(45deg);margin-top: 13px;}
        .bk3 { padding:12px;}
        .bk3 ul { padding:0; margin:0;}
        .bk3 li { float: left;  width:33.333%;}
        .bk3 li div:active { color:#e82d2d;}
        .bk3 li div:active span.clickbtn { background-color:#000; position:absolute; top:0; left:0; width:100px; height:125px;opacity:0.2; z-index:9998;}
        .bk3 li div span.tag { position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 10;overflow: hidden;}
        .bk3 li div span.tag em { display: block;text-align: center;color: #fff;font-size: 14px;width: 60px;background-color: #6C6;margin-top: 3px;margin-left: -16px;-webkit-transform: rotate(-45deg);line-height: 20px;}
        .bk3 li div span.tag01 em { background-color:#e8554d;}
        .bk3 li div { width:100px; float:left; height:155px; position:relative; }
        .bk3 li div img { width:100px; height:125px; }
        .bk3 li div p { height:30px; line-height:30px; font-size:12px; overflow:hidden; }
        .bk3 li:nth-child(2) div {  margin:0 auto; float:none;}
        .bk3 li:nth-child(3) div { float:right; }
        .ztpic2 { padding:0;margin:0; width:100%; border-top:1px solid #dedede;border-bottom:1px solid #dedede;}

        .ztpic2 li { width:50%; background-color:#fff;  overflow:hidden; display:inline; float:left; height:105px; }
        .ztpic2 li div { border-right:1px solid #dedede;  border-bottom:1px solid #dedede; padding-left:20px; height:79px; padding-top:25px;}
        .ztpic2 li div p:nth-child(1) { color:#000; font-size:16px; line-height:30px;}
        .ztpic2 li div p:nth-child(2) { color:#9c9c9c; font-size:10px; line-height:30px;}

        .ztpic2 li:nth-child(2n) div { border-right:0;}
        .ztpic2 li:nth-child(1) div { background:url(__IMG__/ztpic001.png) no-repeat right center;}
        .ztpic2 li:nth-child(2) div { background:url(__IMG__/ztpic002.png) no-repeat right center;}
        .ztpic2 li:nth-child(3) div , .ztpic2 li:nth-child(4) div { border-bottom:0; height:79px;}
        .ztpic2 li:nth-child(3) div { background:url(__IMG__/ztpic003.png) no-repeat right center;}
        .ztpic2 li:nth-child(4) div { background:url(__IMG__/ztpic004.png) no-repeat right center;}
        .ztpic4 li:nth-child(1) div { background:url(__IMG__/ztpic006.png) no-repeat right center; border-bottom:0;}
        .ztpic4 li:nth-child(2) div { background:url(__IMG__/ztpic007.png) no-repeat right center; border-bottom:0;}
        .click-icon { background:none;}
        .ztpic2 li:hover { background-color:#eee;}
        .bk3 li div { height: 145px;width: 92px;}.bk3 li div img { width: 92px;height: 115px;}
    </style>

</block>

<block name="banner">
    <Hongshu:bangdan name="static_android_ad_jp">
        <div class="banner">
            {$row}
        </div>
    </Hongshu:bangdan>
</block>

<block name="title">
    <title>首页</title>
</block>

<block name="body">
    <div class="wrap ">
        <div>
            <div class="xlb" >
                <ul>
                    <li><a href="javascript:hg_gotoUrl('{:url('User/qiandao', array('action'=>'form'))}');" style="color:#FF7100;">每日签到,{:C('SITECONFIG.EMONEY_NAME')}免费得!</a></li>
                </ul>
            </div>
            <div class="title">
                <div class="title-bom"><span class="title-sj">原创男生</span></div>
            </div>
            <div class="bk3 clearfix">
                <ul>
                    <php>$sex_flag='nan';</php>
                    <Hongshu:bangdan name="android_{$sex_flag}_qianli" items="3">
                        <li onClick="hg_gotoUrl('{:url('Book/index', array('bid'=>$row[bid]))}')">
                            <div><img data-src="{$row.face}" width="100" height="125" />
                                <p>{$row.catename}</p>
                            </div>
                        </li>
                    </Hongshu:bangdan>
                </ul>
            </div>
            <div class="viewmore bb" onClick="hg_gotoUrl('{:url('Channel/nan')}');" style=" padding-top:0;"><span>进入男生频道</span></div>
            <div class="title">
                <div class="title-bom"><span class="title-sj">原创女生</span></div>
            </div>
            <div class="bk3 clearfix">
                <ul>
                    <Hongshu:bangdan name="android_nv_chongbang" items="3">
                        <li onClick="hg_gotoUrl('{:url('Book/index', array('bid'=>$row[bid]))}')">
                            <div>
                                <img data-src="{$row.face}" width="100" height="125" />
                                <p>{$row.catename}</p>
                            </div>
                        </li>
                    </Hongshu:bangdan>
                    <Hongshu:bangdan name="android_nv_qianli" items="3">
                        <li onClick="hg_gotoUrl('{:url('Book/index', array('bid'=>$row[bid]))}')">
                            <div><img data-src="{$row.face}" width="100" height="125" />
                                <p>{$row.catename}</p>
                            </div>
                        </li>
                    </Hongshu:bangdan>
                </ul>
            </div>
            <div class="viewmore bb" onClick="hg_gotoUrl('{:url('Channel/nv')}');" style="border-bottom:0; padding-top:0;"><span>进入女生频道</span></div>
        </div>
    </div>
</block>

<block name="script">
    <script type="text/javascript">
        /**控制榜单和广告行为的js**/
        Do.ready('touchslider', function(){
            var p2 = 0;
            var tt = new TouchSlider({
                id: 'slider1',
                auto: '0',
                fx: 'ease-out',
                direction: 'left',
                speed: 300,
                timeout: 4500,
                client: true,
                before: function (index) {
                    var as2 = $('#dot>div');
                    if ((typeof p2) !== 'undefined') {
						if(p2<=as2.length && p2>=0){
							$(as2[p2]).addClass('v3_dot_r').removeClass('v3_cur');
						}
                    }
                    p2 = index;
					if(p2<=as2.length && p2>=0){
						$(as2[p2]).addClass('v3_dot_r').addClass('v3_cur');
					}
                }
            });
            window.addEventListener('load', function() {
                touchEvent('li');
            }, false);
        });
        function touchstartremove(classname) {
            $("." + classname).css({"background-image": "none"});
        }
        function touchendadd(classname) {
            $("." + classname).css({"background-image": "url(__IMG__/splitline1.png)"});
        }

        Do.ready('lazyload', function () {
            Lazy.Load();
            document.onscroll = function () {
                Lazy.Load();
            };
        });
    </script>
</block>
