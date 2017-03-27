<extend name="Common/base" />
<block name="body">
<!--充值记录开始-->
<div class="unit mtop10">
    <div class="frame7">
        <ul id="lists">

        </ul>
    </div>
</div>
<!--充值记录结束-->
<!--底开始-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
        {{each list as row i}}
            <li class=" borderbom"><h4>{{row.money}}元</h4><p><span class="lf">{{row.payname}}{{if row.payflag > 1}}支付成功{{else if row.today == 'yes'}}处理中{{else}}支付失败{{/if}}</span><span class="rt">{{row.addtime}}</span></p></li>
        {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
    var url="{:url('Userajax/paylogs')}";
    Do.ready('lazyload','functions','template',function(){
       loadMore(url,'lists','huanyihuan_tpl','append','first', {_callback:Lazy.Load});
        document.onscroll = function(){
            var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                loadMore(url,'lists','huanyihuan_tpl','append','next', {_callback:Lazy.Load});
            }
        }
    });
</script>
</block>