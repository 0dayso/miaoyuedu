<extend name="Common/base" />
<block name="body">
<!--热门问题开始-->
<div class="unit">
    <div id="lists">
        <div class="hotproblem">
            <ul>
                <li ><h4>{$msginfo.title}</h4><p>{$msginfo.creation_date}</p></li>
            </ul>
        </div>
    </div>
    <!--发表评论开始-->

    <div class="commentbd clearfix">
            <textarea placeholder="回复评论"  class="comcon radius4" id="text"></textarea>
            <button type="button" class="radius4 mtop20" onclick="return submit();" id="tijiao">回复</button>
    </div>

    <!--发表评论结束-->
</div>

<!--热门问题结束-->
</block>
<block name="script">
<script type="text/html" id="huanyihuan_tpl">
    {{if list}}
    {{each list as row i}}
    <div class="hotproblem2" style="border-top:1px solid #eee;">
        <h2>{{row.replyname}}</h2>
        <p>{{row.content}}</p>
        <p class="time">{{row.reply_date}}</p>
    </div>
    {{/each}}
    {{/if}}
</script>
<script type="text/javascript">
    $(document).ready(function () {
        LoadMore();
    });
    function LoadMore() {
        var url = "{:url('Feedbackajax/getfeedbackreplylist')}";
        var data = {
        mid:'{$mid|intval}'
    }
    $.ajax({
    type:'get',
        url: url,
        data:data,
        success: function (data) {

            Do.ready('template', 'lazyload', function () {
                if (data.status == 1) {
                var htmls = template('huanyihuan_tpl',data);
                $('#lists').append(htmls);
                }
            });
        }
    });
    }
    function checknum() {
        var length = $('#text').val().length;
        if (length == 0) {
            $('#tijiao').attr('disabled', 'disabled');
            return false;
        } else {
            $('#tijiao').removeAttr('disabled');
        }
    }

    function submit() {
        checknum();
        var url = "{:url('Feedbackajax/saveaddreplycommun')}";

        var content = $('#text').val();
        var data = {
            mid:'{$mid|intval}',
            content:content,
        }
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                if (data.status == 1) {
                    hg_Toast(data.message);
                    history.go(0);
                } else {
                    hg_Toast(data.message);
                }
            }
        });
        return false;
    }
</script>
</block>