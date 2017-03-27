<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
<form name="form" method="post" action="{:url('Userajax/saveautodingyue','','do')}">
   <div class="unit">
    <div class="frame5 nohover">
        <ul>
         <li class="autobg" >请勾选需要取消自动订阅的书籍<span><input type="checkbox" value="1"  onClick="select_all(this.checked)" class="checkbox02" /></li>
        </ul>
    </div>
   </div>
    <div class="unit">
    <div class="frame5 nohover">
            <ul id="autodingyuelist">
            </ul>
    </div>
    </div>
    <div class="unit more2" style="text-align:center;">
        <a class="reviewmore" id="loadviewmore" href="javascript:void(0);" totalnum="0" total_page='0' pagenum='1' onClick="loadMoreautodingyuelist('{:U('Userajax/autodingyue')}');"></a>
    </div>
    <div class="btn_input login">
        <ul>
            <li><a  href="javascript:void(0);" class="mainbtn button radius4" onclick="updatebtn(this);">取消自动订阅</a></li>
        </ul>
    </div>
    <br><br><br>
</form>
</block>
<block name="script">
<script type="text/javascript">
    var is_load = 0;
    function select_all(status) {
        for (i = 0; i < document.form.length; i++) {
            if (document.form.elements[i].name == "bid[]")
                document.form.elements[i].checked = status;
        }
    }

    function loadMoreautodingyuelist(url) {
        if (is_load) {
            return false;
        }
        if (url == "" || url == undefined) {
            url = "{:url('Userajax/autodingyue','','do')}";
        }
        var pagenum = $("#loadviewmore").attr("pagenum");
        var total_page = $("#loadviewmore").attr("total_page");
        var totalnum = $("#loadviewmore").attr("totalnum");
        var parameter = {total_page:total_page,totalnum:totalnum};
		parameter[hsConfig.PAGEVAR]=pagenum;

        $("#loadviewmore").html('获取中&nbsp;<img id="viewMoreLoading" style="margin-bottom:-4px;" src="__STATICURL__/img/html5/loading.gif"></img>');
        var gotourl = url;
        is_load = 1;
        $.ajax({
            type: "GET",
            url: gotourl,
            data:parameter,
            timeout: 9000,
            success: function (data) {
                if (data['list'] == "" || data['list'] == null) {
                    is_load = 0;
                    $('.mainbtn').hide();
                    $("#loadviewmore").html('<span>暂时没有您的自动订阅记录</span>');
                    return false;
                }
                var lengths = data['list'].length;
                for (var i = 0; i < lengths; i++) {
                    var htmlstr = '';
                    htmlstr += ' <li><em class="hidden">';
                    htmlstr += '《' + data['list'][i]['catename'] + '》</em><span>';
                    htmlstr += ' <input type="checkbox" name="bid[]" value="' + data['list'][i]['bid'] + '" class="checkbox02" />';

                    htmlstr += '</li>';
                    $("#autodingyuelist").append(htmlstr);
                }



                //console.log(htmlstr);
                //$("#chapterlist").append(htmlstr);
                $("#loadviewmore").html('');
                if (data['pagenum'] < data['totalpage']) {
                    $("#loadviewmore").attr("pagenum", data['nextpagenum']);
                    $("#loadviewmore").attr("total_page", data['totalpage']);
                    $("#loadviewmore").attr("totalnum", data['totalnum']);
                } else {
                    $("#loadviewmore").html('');
                    return;
                }
                is_load = 0;
            },
            error: function () {
                is_load = 0;
                $("#loadviewmore").html('<span>网络超时，请稍后重试</span>');
            },
        });
    }
    $(document).ready(function () {
        loadMoreautodingyuelist('');
    });
    function lockbutton(obj) {
        $(obj).attr("onclick", "");
        $(obj).html("请稍候…");
    }

    function unlockbutton(obj) {
        $(obj).attr("onclick", "updatebtn(this)");
        $(obj).html("取消自动订阅");
    }
    function updatebtn(obj) {
        var bidstr = "";
        var bidarray = new Array;
        for (i = 0; i < document.form.length; i++) {
            if (document.form.elements[i].name == "bid[]" && document.form.elements[i].checked == 1) {
                bidarray.push(document.form.elements[i].value);
            }
        }

        if (bidarray.length == 0) {
            $('#posting').html("请选择书籍");
            $('.warning').show();
            return false;
        }
        lockbutton(obj);
        var gotourl = "{:U('Userajax/saveautodingyue','','do')}";
        $.ajax({
            type: "POST",
            url: gotourl,
            data: "bid=" + bidarray,
            timeout: 9000,
            dataType:"json",
            success: function (data) {
                if (data['status'] == 0) {
                    $('#posting').html(data['message']);
                    if (data['url'] != "") {
                        setTimeout(function () {
                            window.location.href = data['url'];
                        }, 2000);
                    }

                } else if (data['status'] == 1) {
                    window.location.reload();
                } else {
                    $('#posting').html("出错");
                }
                unlockbutton(obj)
                setTimeout(function () {
                    $('#posting').hide();
                }, 3000);
            },
            error: function () {
                unlockbutton(obj)
                $('#posting').html("网络超时，请点击重试!");
                $('#posting').show();
            },
        });
    }

    Do.ready('lazyload', function () {
        document.onscroll = function () {
            var loadviewdiv = document.getElementById("loadviewmore");
            var footHeight =20;
            var iScroll = scrollTop();
            if((iScroll + $(window).height()+footHeight)>=$(document).height()){
                loadMoreautodingyuelist("{:url('Userajax/autodingyue','','do')}");
                Lazy.Load();
            }
        }
    });
</script>
</block>


