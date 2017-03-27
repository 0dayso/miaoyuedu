<extend name="Common/base" />
<block name="header">
    <include file="Common/head2" />
</block>
<block name="body">
<style>
body{ background-color:#fff; font-size:16px; color:#999; font-family:"微软雅黑";}
.frame{width:100%; background-color:#fff;border-bottom:1px solid #eee;}
.frame2{width:100%; background-color:#f9f9f9;border-bottom:1px solid #eee;}
.tit1{ text-align:right;height:3em;line-height:3em;font-size:1.875em; color:#333;}
.tit2{ text-align:left;height:3em;line-height:3em;font-size:1.875em; color:#333;}
.framecon{width:980px;margin:0 auto;}
.fm5 li{width:20%; background:url(__IMG__/fm.png) no-repeat 0 0;float:left; margin-bottom:30px;}
.fm5 img{width:132px;height:165px; margin: 6px 0 0 1px;}
.fm5a{width:134px;height:172px;display:inline-block;}
.fm5tit{display:inline-block;width:70%; height:1.5em;line-height:1.5em; font-size:1em;overflow:hidden;white-space:nowrap;text-overflow: ellipsis;float:left;}
.fm5p{display:inline-block;width:70%; height:2.2em;line-height:1.5em; font-size:0.75em;float:left; color:#999;overflow:hidden;white-space:nowrap;text-overflow: ellipsis;}
.per5 {position:relative;}
.ic_pre{position:absolute;top:200px;left:-50px;z-index:999;width:50px;height:100px;display:inline-block; background:url(__IMG__/ic_pre.png) no-repeat 0 center; background-color:#ddd;filter:alpha(opacity=40);opacity:0.4;}
.ic_next{position:absolute;top:200px;right:-50px;z-index:999;width:50px;height:100px;display:inline-block; background:url(__IMG__/ic_next.png) no-repeat 0 center; background-color:#ddd;filter:alpha(opacity=40);opacity:0.4;}
.ic_next:hover , .ic_pre:hover{filter:alpha(opacity=60);opacity:0.6;}
.per5 li{width:180px;padding:0 32px;border-right:1px dotted #ddd;float:left; margin-bottom:40px; }
.per5 li:last-chlid {border-right:0;}
.per5 img{width:128px;height:128px;border:1px solid #ccc;border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%; margin-left:25px;}
.per5 .zzjj{ font-size:0.75em; line-height:1.5em; margin-bottom:10px; }
.per5 .zzs{ font-size:0.75em; line-height:1.5em; text-align:center;}
.per5 h4{ font-size:0.875em;text-align:center;width:100%; color:#404040; font-weight:normal;}
.fm6 .half{width:50%;float:left;background:url(__IMG__/fm.png) no-repeat 0 0;float:left; margin-bottom:2.5em;}
.fm6cover img{width:132px;height:165px;}
.fm6cover{float:left;width:135px;margin:6px 35px 0 1px;height:166px;}
.fm6 .rt{width:290px;float:left;}
.fm6 .rt img{width:38px;height:38px;border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%;-moz-box-shadow:0px 2px 2px #999; -webkit-box-shadow:0px 2px 2px #999; box-shadow:0px 2px 2px #999;}
.fm6 .rt h1{color:#333;line-height:2.25em;}
.fm6 .rt  span{display:inline-block;width:100%;}
.fm6 .rt  span img{float:left; margin-right:10px;}
.fm6 .rt b{ font-weight:normal;font-size:0.75em;}
.fm6 p{ font-size:0.75em;line-height:1.5em;width:100%;height:4.5em;overflow:hidden; margin-top:0.625em;}
.cpink{padding-left:15px;}
.cpink li{width:40%;float:left;color:#e49999; font-size:0.75px; list-style-type:disc;}
.hezimg{ background:url(__IMG__/hez.jpg) no-repeat center top; background-size:100% 423px;height:423px;border:0;}
.hezimg .framecon{height:423px;}
.hezcon{float: right;margin-top: 60px;background-color: rgba(0,0,0,.3);padding: 60px 20px;border-radius: 4px; margin-right: 60px;}
.xinxi{padding:40px 0;}
.xinxi .lf{width:490px;float:left;border-right:1px dotted #ddd;height:10em; display: inline-block;}
.xinxi .lf li{line-height:1.85em; font-size:0.875em; list-style-type:disc;margin-left:20px; }
.xinxi .lf li a{width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;}
.xinxi h1{ font-size:1.875em; line-height:2.25em; font-weight:normal;color:#333;} 
.xinxi .rt{width:459px;float:left; font-size:0.875em;line-height:1.25em;padding-left:30px;}
.btn2 a{width:160px;height:56px;line-height:56px;font-size:1.2em;color:#fff;border-radius:10px;-webkit-border-radius:10px;-moz-border-radius:10px; text-align:center;display:inline-block;}
.btn2 .first{border:1px solid #e39b00; background-color:#e39b00; margin-right:80px; } 
.btn2 .first:hover{ background-color:#be8725;}
.btn2 .sed{border:1px solid #12c578; background-color:#12c578; } 
.btn2 .sed:hover{ background-color:#15935c;}
.btn2{ margin-top:20px;}
.w980{width:980px; margin:0 auto;}  
.bnimg01{background:url(__IMG__/1.jpg) 0 0 repeat;} 
.bnimg01{text-align:center; color:#fff;}
.bnimg01 h1{font-size:56px;line-height:100px; letter-spacing:5px;}
.bnimg01 h3{font-size:32px;line-height:100px; margin-top:20vh; fontweight:normal;letter-spacing:2px;}
.bnimg01 .s8{ text-align:center;}
.bnimg01 .s8 p{width:100%;}
.bnimg01 .s8 p span{ margin:0 20px 20px 20px;}
.bnimg01 .cred2{ color:#f47373; margin-top:10px;}
.bnimg01 .btn2{text-align:center; width:680px;margin:0 auto;display:inline-block;height:60px;overflow:hidden; margin-top:50px;}
.bnimg01 .first , .bnimg01 .sed{width:160px; float:left; margin:0 80px;}
.bnimg01 .first:hover{background-color:#be8725;}
.bnimg01 .sed:hover{ background-color:#15935c;}
.bnimg02{background:url(__IMG__/1.jpg) 0 0 repeat; text-align:center;} 
.bnimg03{background:url(__IMG__/bnimg03.jpg) 0 0 no-repeat; background-size:cover; } 
.bnimg04{background:url(__IMG__/bnimg04.jpg) 0 0 no-repeat; background-size:cover; } 
.img003{width:100%;height:100%; background:url(__IMG__/003.png) no-repeat center 80px;}
.img004{width:100%;height:100%; background:url(__IMG__/004.png) no-repeat center 10px;}
.footercon .left a , .footercon p{font-size:0.75em;}
.hzlogo img{width:150px;height:80px;border-right:1px solid #ddd;display:inline-block;float:left;border-bottom:1px solid #ddd;}
.borderlr{border-top:1px solid #ddd;border-left:1px solid #ddd;width: 906px;display: inline-block;margin-left: 27px; margin-bottom:30px;}
.per .right { width: 120px;float: right; }
.per .left {font-size:0.75em;}
.mainnav2 { padding-top: 0px;}
.per {padding-top: 10px;}
.logo2 {padding-top: 0;}
.pertop2con{width:90%;margin:0 5%;}
.on{width:250px;}
.on .left { width: 120px; text-align: right;}
.per .left{ margin-right:5px;}
.hyz{    float: right; font-size: 14px;background-color: #fff;display: inline-block; border: 1px solid #ddd;width: 80px;height: 30px;line-height: 30px;text-align: center;border-radius: 4px;margin-top: 30px;}
.hezcon p{color: #fff;font-weight: normal;}
.footer .rt a , .footer{font-size: 12px;}
</style>
<style>
.flexslider{margin: 0px auto;position: relative;width: 100%;height: 100%;overflow: hidden;zoom: 1;}
.flexslider .slides li{width: 100%;height: 100%;padding-top:30px;}
.flex-direction-nav a{width: 70px;height: 100%;display: block;position: absolute;top: 50%;z-index: 10;cursor: pointer;opacity: 0;filter: alpha(opacity=0);-webkit-transition: all .3s ease;border-radius: 35px;}
.flex-direction-nav .flex-next{right: 0;    top: 0;background:url(__IMG__/ic_navigate_after.png) no-repeat center center; background-size:150%;  background-color:rgba(0,0,0,.5);font-size:0;}
.flex-direction-nav .flex-prev{left: 0;    top: 0;background:url(__IMG__/ic_navigate_before.png) no-repeat center center; background-size:150%; background-color:rgba(0,0,0,.5);font-size:0;}
.flexslider:hover .flex-next{opacity: 0.8;filter: alpha(opacity=25);}
.flexslider:hover .flex-prev{opacity: 0.8;filter: alpha(opacity=25);}
.flexslider:hover .flex-next:hover,
.flexslider:hover .flex-prev:hover{opacity: 1;filter: alpha(opacity=50);}
.flex-control-nav{width: 100%;position: absolute;bottom: 10px;text-align: center;}
.flex-control-nav li{margin: 0 2px;display: inline-block;zoom: 1;*display: inline;}
.flex-control-paging li a{background: url(__IMG__/dot.png) no-repeat 0 -16px;display: block;height: 16px;overflow: hidden;text-indent: -99em;width: 16px;cursor: pointer;}
.flex-control-paging li a.flex-active,
.flex-control-paging li.active a{background-position: 0 0;}
.flexslider .slides a img{width: 100%;height: 482px;display: block;}
.yuan{position:absolute;z-index:999999999999;left:50%;bottom:20px; margin-left:-30px;width:60px;height:60px; }
.yuan a{width:58px;height:58px;display:inline-block;background:url(__IMG__/ic_navigate_bom.png) no-repeat center center; background-size:60px;fnt-size:0;}
/*.djbtncon{ position:relative;width:100%;height:100%;}
.djbtn{width:160px;height:60px;line-height:60px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;   
      background-image: -webkit-linear-gradient(-90deg,#7acdd4,#549ed2);border:1px solid #f00; text-align:center; font-size:20px;color:#fff;display:inline-block; position:absolute;left:50%;margin-left:100px;bottom:200px;}*/
</style>

<!-- <div class="frame clearfix">
  <div class="framecon hzlogo">
    <div class="tit2">版权合作单位</div>
    <div class="borderlr"><img src="__IMG__/01.jpg" /><img src="__IMG__/02.jpg" /><img src="__IMG__/03.jpg" /><img src="__IMG__/04.jpg" /><img src="__IMG__/05.jpg" /><img src="__IMG__/06.jpg" /><img src="__IMG__/07.jpg" /><img src="__IMG__/08.jpg" /><img src="__IMG__/09.jpg" /><img src="__IMG__/10.jpg" /><img src="__IMG__/11.jpg" /><img src="__IMG__/12.jpg" /><img src="__IMG__/13.jpg" /><img src="__IMG__/14.jpg" /><img src="__IMG__/15.jpg" /><img src="__IMG__/16.jpg" /><img src="__IMG__/17.jpg" /><img src="__IMG__/18.jpg" /><img src="__IMG__/19.jpg" /><img src="__IMG__/20.jpg" /><img src="__IMG__/21.jpg" /><img src="__IMG__/22.jpg" /><img src="__IMG__/23.jpg" /><img src="__IMG__/24.jpg" /></div>
  </div>
</div> -->
  <div class="frame hezimg clearfix">
    <div class="framecon hez">
      <div class="hezcon">
        <h1>联系合作</h1>
        <p>联系人：安安</p>
        <p>咨询ＱＱ：313269122</p>
        <p>电子邮箱：313269122@qq.com</p>
      </div>
    </div>
  </div>
</div>
</block>
<block name="script">
<script src="__JS__/slider.js"></script>
<script type="text/javascript">
/**
 * slider插件可悬停控制
 */
; $(function ($, window, document, undefined) {

    Slider = function (container, options) {
        /*
        options = {
            auto: true,
            time: 3000,
            event: 'hover' | 'click',
            mode: 'slide | fade',
            controller: $(),
            activeControllerCls: 'className',
            exchangeEnd: $.noop
        }
        */

        "use strict"; //stirct mode not support by IE9-

        if (!container) return;

        var options = options || {},
            currentIndex = 0,
            cls = options.activeControllerCls,
            delay = options.delay,
            isAuto = options.auto,
            controller = options.controller,
            event = options.event,
            interval,
            slidesWrapper = container.children().first(),
            slides = slidesWrapper.children(),
            length = slides.length,
            childWidth = container.width(),
            totalWidth = childWidth * slides.length;

        function init() {
            var controlItem = controller.children();

            mode();

            event == 'hover' ? controlItem.mouseover(function () {
                stop();
                var index = $(this).index();

                play(index, options.mode);
            }).mouseout(function () {
                isAuto && autoPlay();
            }) : controlItem.click(function () {
                stop();
                var index = $(this).index();

                play(index, options.mode);
                isAuto && autoPlay();
            });

            isAuto && autoPlay();
        }

        //animate mode
        function mode() {
            var wrapper = container.children().first();

            options.mode == 'slide' ? wrapper.width(totalWidth) : wrapper.children().css({
                'position': 'absolute',
                'left': 0,
                'top': 0
            })
                .first().siblings().hide();
        }

        //auto play
        function autoPlay() {
            interval = setInterval(function () {
                triggerPlay(currentIndex);
            }, options.time);
        }

        //trigger play
        function triggerPlay(cIndex) {
            var index;

            (cIndex == length - 1) ? index = 0 : index = cIndex + 1;
            play(index, options.mode);
        }

        //play
        function play(index, mode) {
            slidesWrapper.stop(true, true);
            slides.stop(true, true);

            mode == 'slide' ? (function () {
                if (index > currentIndex) {
                    slidesWrapper.animate({
                        left: '-=' + Math.abs(index - currentIndex) * childWidth + 'px'
                    }, delay);
                } else if (index < currentIndex) {
                    slidesWrapper.animate({
                        left: '+=' + Math.abs(index - currentIndex) * childWidth + 'px'
                    }, delay);
                } else {
                    return;
                }
            })() : (function () {
                if (slidesWrapper.children(':visible').index() == index) return;
                slidesWrapper.children().fadeOut(delay).eq(index).fadeIn(delay);
            })();

            try {
                controller.children('.' + cls).removeClass(cls);
                controller.children().eq(index).addClass(cls);
            } catch (e) { }

            currentIndex = index;

            options.exchangeEnd && typeof options.exchangeEnd == 'function' && options.exchangeEnd.call(this, currentIndex);
        }

        //stop
        function stop() {
            clearInterval(interval);
        }

        //prev frame
        function prev() {
            stop();

            currentIndex == 0 ? triggerPlay(length - 2) : triggerPlay(currentIndex - 2);

            isAuto && autoPlay();
        }

        //next frame
        function next() {
            stop();

            currentIndex == length - 1 ? triggerPlay(-1) : triggerPlay(currentIndex);

            isAuto && autoPlay();
        }

        //init
        init();

        //expose the Slider API
        return {
            prev: function () {
                prev();
            },
            next: function () {
                next();
            }
        }
    };

}(jQuery, window, document));</script>
<script type="text/javascript">
$(function() {
  var bannerSlider = new Slider($('#banner_tabs'), {
    time: 8000,
    delay: 400,
    event: 'hover',
    auto: true,
    mode: 'fade',
    controller: $('#bannerCtrl'),
    activeControllerCls: 'active'
  });
  $('#banner_tabs .flex-prev').click(function() {
    bannerSlider.prev()
  });
  $('#banner_tabs .flex-next').click(function() {
    bannerSlider.next()
  });
})
</script>
<script type="text/javascript">
$(document).ready(function() {

    _resizewin();
    var myScroll = function(direct, delta){
        var afterScrollTop = $(document).scrollTop();
        var newpos = -1;
        if(direct==0){
            //向下滚动
            if(afterScrollTop<$(window).height()-afterScrollTop) {
                newpos = $(window).height() + delta + 20;
            }
        } else {
            //向上滚动
            if(afterScrollTop<$(window).height()+20) {
                newpos = 0;
            }
        }
        if(newpos>=0){
        //$('body').animate({scrollTop: newpos+'px'}, 1000);
      //$(document).scrollTop(newpos);
        }
    };
    var scrollFunc = function (e) {
        var direct = 0, delta = 0;
        e = e || window.event;
        if (e.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件
            if (e.wheelDelta > 0) { //当滑轮向上滚动时
                direct = 1;
            }
            if (e.wheelDelta < 0) { //当滑轮向下滚动时
                direct = 0;
            }
      delta = e.wheelDelta;
        } else if (e.detail) {  //Firefox滑轮事件
            if (e.detail> 0) { //当滑轮向下滚动时
                direct = 0;
            }
            if (e.detail< 0) { //当滑轮向下滚动时
                direct = 1;
            }
      delta = -e.detail*40;
        }
        myScroll(direct, delta);
    }
    //给页面绑定滑轮滚动事件
    if (document.addEventListener) {
        document.addEventListener('DOMMouseScroll', scrollFunc, false);
    }
    //滚动滑轮触发scrollFunc方法
    window.onmousewheel = document.onmousewheel = scrollFunc;
    window.onresize = document.onresize = _resizewin;
});

function _resizewin(){
     $('.flexslider').height($(window).height());
    $('.flex-direction-nav').height($(window).height());
    $('.slides').find('li').width($(window).width()).height($(window).height());
}
</script>
</block>


