<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="body">
<Hongshu:bangdan name="static_yqm_topbanner">
{$row}
</Hongshu:bangdan>
<div class="main top30 bom15">
    <div class="main1200">
        <div class="title bom20">
            <img src="__IMG__/zxtj.png"/>
            <span class="c0 lf10">精选作品</span>
            <!-- <a class="cb flrt" href="{:url('Channel/search','','html')}">更多>></a> -->
        </div>

        <div class="book_rongqi" id="lists">
        <Hongshu:bangdan name="yqm_index_toprecom" items="4">
            <if condition="$row['bid']">
            <div class="book bom20" onClick="doChild('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div class="fm bom5">
                    <img src="{$row.face}" width="200" height="282"/>
                    <div class="fm_mb" onclick="" style="display:none;">
                        <div class="touxiang64 fllf">
                            <img src="__IMG__/zztx_200.jpg"/>
                        </div>
                        <span class="cf lf10">{$row.author}</span>
                        <p class="cf top60">字数：{$row.charnum}</p>
                        <!-- <p class="cf">点击：{$row.total_hit}</p> -->
                        <p class="cf">收藏：{$row.total_fav}</p>
                        <!-- <p class="cf">打赏：{$row.total_pro}</p>
                        <p class="cf">元气弹：{$row.redticket}</p> -->
                    </div>
                </div>
                <p class="lf5 c3" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')"><a href="{:url('Book/view',array('bid'=>$row['bid']))}">{$row.catename}</a></p>
                <span class="c3 lf5">萌神：{$row.author}</span>
                <span class="cb lf5">{$row.intro}</span>
            </div>
            </if>
        </Hongshu:bangdan>
        </div>
        <div class="title bom20">
            <img src="__IMG__/mfxs.png"/>
            <span class="c0 lf10">免费新书</span>
            <a class="cb flrt" href="{:url('Channel/search','','html')}">更多>></a>
        </div>

        <div class="book_rongqi" id="lists">
        <Hongshu:bangdan name="yqm_index_recommend" items="8">
            <if condition="$row['bid']">
            <div class="book bom20" onClick="doChild('{:url('Book/view',array('bid'=>$row['bid']),'html')}')">
                <div class="fm bom5">
                    <img src="{$row.face}" width="200" height="282"/>
                    <div class="fm_mb" onclick="" style="display:none;">
                        <div class="touxiang64 fllf">
                            <img src="__IMG__/zztx_200.jpg"/>
                        </div>
                        <span class="cf lf10">{$row.author}</span>
                        <p class="cf top60">字数：{$row.charnum}</p>
                        <!-- <p class="cf">点击：{$row.total_hit}</p> -->
                        <p class="cf">收藏：{$row.total_fav}</p>
                        <!-- <p class="cf">打赏：{$row.total_pro}</p>
                        <p class="cf">元气弹：{$row.redticket}</p> -->
                    </div>
                </div>
                <p class="lf5 c3" onClick="hg_gotoUrl('{:url('Book/view',array('bid'=>$row['bid']),'html')}')"><a href="{:url('Book/view',array('bid'=>$row['bid']))}">{$row.catename}</a></p>
                <span class="c3 lf5">萌神：{$row.author}</span>
                <span class="cb lf5">{$row.intro}</span>
            </div>
            </if>
        </Hongshu:bangdan>
        </div>

    </div>
</div>

<!--原创征稿-->
<div class="main bom50">
    <div class="main1200">
        <div class="title bom20">
            <!-- <img src="__IMG__/yczg.png"/>
            <span class="c0 lf10">原创征稿</span> -->
        </div>

        <div class="zhenggao">
            <div class="zhenggao860">
                <p class="cf">最有趣的轻小说阅读创作社区</p>
                <h1 class="cf top30">英雄招募贴</h1>
                <h2 class="cf top30 lf100">更多福利 &nbsp;&nbsp;&nbsp;&nbsp; 更高平台</h2>
                <h2 class="cf top6 lf100">首发原创 &nbsp;&nbsp;&nbsp;&nbsp; 版权共赢</h2>
                <a class="cf top6" href="http://img1.q.hongshu.com/static/zhuanti/all/20160531/index.html" target="_blank">详情>></a>

                <a href="{:url('User/authorreg')}"><button onclick="doChild('{:url('User/authorreg')}');">萌神大大入驻</button></a>
            </div>
        </div>

    </div>
</div>
</block>
<block name="script">
<script src="__JS__/jquery/jquery-1.11.0.min.js"></script>
    <script src="__JS__/jquery/imagesloaded.js"></script>

    <script src="__JS__/jquery/jquery.skidder.js"></script>

<script type="text/javascript">
$('.fm').mouseover(function(){
    $(this).find('.fm_mb').show();
});
$('.fm').mouseout(function(){
    $(this).find('.fm_mb').hide();
});
        $('.slideshow').each( function() {
              var $slideshow = $(this);
              $slideshow.imagesLoaded( function() {
                $slideshow.skidder({
                  slideClass    : '.slide',
                  animationType : 'css',
                  scaleSlides   : true,
                  maxWidth : 1300,
                  maxHeight: 500,
                  paging        : true,
                  autoPaging    : true,
                  pagingWrapper : ".skidder-pager",
                  pagingElement : ".skidder-pager-dot",
                  swiping       : true,
                  leftaligned   : false,
                  cycle         : true,
                  jumpback      : true,
                  speed         : 400,
                  autoplay      : true,
                  autoplayResume: true,
                  interval      : 3000,
                  transition    : "slide",
                  afterSliding  : function() {},
                  afterInit     : function() {}
                });
              });
        });
        // $('.slideshow-nocycle').each( function() {
        //   var $slideshow = $(this);
        //   $slideshow.imagesLoaded( function() {
        //     $slideshow.skidder({
        //       slideClass    : '.slide',
        //       scaleSlides   : true,
        //       maxWidth : 1300,
        //       maxHeight: 500,
        //       leftaligned   : true,
        //       cycle         : false,
        //       paging        : true,
        //       swiping       : true,
        //       jumpback      : false,
        //       speed         : 400,
        //       autoplay      : false,
        //       interval      : 4000,
        //       afterSliding  : function() {}
        //     });
        //   });
        // });
//      $(window).smartresize(function(){
//            $('.slideshow').skidder('resize');
//      });
    </script>
    <!-- <script type="text/html" id="huanyihuan_tpl">
    {{if bookinfo}}
    {{each bookinfo as row i}}
    <div class="book bom20" onClick="hg_gotoUrl('{{ {bid:row.bid} | router:'Book/view'}}')">
                <div class="fm bom5">
                    <img src="{{row.bookface}}" width="200" height="282"/>
                    <div class="fm_mb" onclick="" style="display:none;">
                        <div class="touxiang64 fllf">
                            {{if row.authorimg}}<img src="{{row.authorimg}}"/>{{else}}<img src="__IMG__/ic_person.jpg"/>{{/if}}
                        </div>
                        <span class="cf lf10">{{row.authorname}}</span>
                        <p class="cf top60">字数：{{row.charnum}}</p>
                        <p class="cf">点击：{{row.total_hit}}</p>
                        <p class="cf">收藏：{{row.favnum}}</p>
                        <p class="cf">打赏：{{row.pronum}}</p>
                        <p class="cf">元气弹：{{row.redticket}}</p>
                    </div>
                </div>
                <p class="lf5 c3"><a href="fm2.html">{{row.catename}}</a></p>
                <span class="c3 lf5">萌神：{{row.authorname}}</span>
                <span class="cb lf5">{{if row.tag}}{{row['tag'][0]}}，{{row['tag'][1]}}，{{row['tag'][2]}}{{/if}}</span>
            </div>
        {{/each}}
    {{/if}}
</script>
    <script type="text/javascript">
        Do.ready('common',function(){
        loadmore();
    });
        function loadmore(){
        var url = "{:url('Bookajax/search',array(),'do')}";

        $.ajax({
            type: "GET",
            url: url,
            data: {method:'search',order:1,pagesize:8},
            timeout: 9000,
            dataType:'jsonp',
            success: function (data) {
                Do.ready('template', 'lazyload', function(){
                    if(data.bookinfo){
                    if(data.bookinfo.length>0){
                        var htmls = template('huanyihuan_tpl',data);
                        $('#lists').html(htmls);
                        $('.fm').mouseover(function(){
                        $(this).find('.fm_mb').show();
                    });
                    $('.fm').mouseout(function(){
                        $(this).find('.fm_mb').hide();
                    });
                        Lazy.Load();
                    }else{
                        Do.ready('functions', function(){
                            hg_Toast(data.message);
                        });
                    }
                }
                });
            }
        });
   }



    </script> -->
    <include file="Common/foot2" />
</block>