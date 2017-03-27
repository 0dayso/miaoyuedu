<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
    <div class="container mtop10 clearfix">
        <div class="crumbs crumbs2"><span>您的位置</span>><a href="{:url('Index/index','','html')}">首页</a>><span>{$articleinfo.title}</span></div>
        <div class="news news2">
            <div class="rt">
                <div class="news_tit news2_tit">
                    <h2>{$articleinfo.title}</h2>
                    <div class="share"><span class="news2_tit_name">编辑名：{$articleinfo.admin_name}</span><span class="news2_tit_name">{$articleinfo.date}</span>
                        <span class="news2_tit_time" id="share" isshow="0">分享</span>
                        <div class="sharemask radius4" id="sharebox" style="display: none;">
                             <i class="arrow arrow-1"></i> <i class="arrow arrow-2"></i>
                             <a href="#" target="_blank" class="ic_rdset_weixin"></a>
                             <a href="#" target="_blank" class="ic_rdset_pyq"></a>
                             <a href="#" target="_blank" class="ic_rdset_weibo"></a>
                       </div>
                    </div>
                </div>
                <div class="newsrt_con">
                    {$articleinfo.content}
                    <div class="news2_zan share"><a href="javascript:void(0);" class="news2_zanbtn" id="zan">点赞({$articleinfo.love})</a><a href="javascript:void(0);" id="share2"  class="news2_zanbtn" isshow="0">分享</a> <div class="sharemask radius4" id="sharebox2" style="display: none;">
                        <i class="arrow arrow-1"></i> <i class="arrow arrow-2"></i>
                        <a href="#" target="_blank" class="ic_rdset_weixin"></a>
                        <a href="#" target="_blank" class="ic_rdset_pyq"></a>
                        <a href="http://v.t.sina.com.cn/share/share.php?title={:urlencode($article['title'])}&amp;url={:urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])}" title="分享到新浪微博" target="_blank" class="ic_rdset_weibo"></a>
                    </div></div>
                </div>

            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/help'],function(help){
            $('#share').on('click',function(){
                help.showsharebox('#sharebox','isshow');     
            })
            $('#share2').on('click',function(){
                help.showsharebox('#sharebox2','isshow');     
            })
            $('#zan').on('click',function(){
                help.sendzan('{$articleinfo.article_id}','#zan');     
            })
        })
    </script>
</block>