<extend name="Common/base" />
<block name="script">
<script>
            Do.ready('functions', function () {
                $('#main').show();
            });
            var forward_url = '{$M_forward}',
                p28 = '{$deviceInfo['device']}',
                p29 = '{$deviceInfo['UUID']}';
            //alert(p28);
            //alert(p29);

            function auto_login(obj) {
                lockbutton(obj);
                var url = '{:C('ROOT_URL')}/Userajax/autoregister';
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {action: 'get', P29: p29, P28: p28},
                    dataType: 'json',
                    complete:function(){
                        unlockbutton(obj);
                    },
                    error:function(xhr, errorType, error){
                        hg_Toast('网络超时，请稍后再试。');
                    },
                    success: function (json) {
                        if(json===null){
                            hg_Toast('网络错误，请稍后再试', 2);
                        } else if ( json.hasOwnProperty('status') && json.status == 1 && json.usercode != '') {
                            var params = {
                                P30: json.usercode,
                                username: json.nickname,
                                fu: forward_url,
                                uid: json.uid,
                                nickname: json.nickname,
                                viplevel: json.viplevel,
                                groupid: json.groupid,
                                avatar: json.avatar,
                                isauthor: json.isauthor
                            };
                Do.ready('functions', function(){
                            doClient('saveP30', params);
				});
                            //hg_gotoUrl(forward_url);
                        }
                        else {
                            hg_Toast(json.message, 2);
                        }
                    }
                });
                return false;
            }

            function lockbutton(obj) {
                $(obj).attr("onclick", "");
                $(obj).html("请稍候…");
            }

            function unlockbutton(obj) {
                $(obj).attr("onclick", "auto_login(this)");
                $(obj).html("直接使用");
            }

        </script>
</block>
<block name="body">
        <div class="mu2"></div>
            <div class="tkkj" id="main" style="display: none;">
                <div class="tk radius4"><div class="tkcon"><h2>登录后更方便</h2><p>登录后可以在多个设备上同步数据，要先登录吗？</p></div>
                <p class="tkbtn"><a href="javascript:void(0);" onClick="auto_login(this);" class="ok">直接使用</a><a href="javascript:void(0);" class="no" onClick="hg_gotoUrl('{:C('ROOT_URL')}/User/login.do?P31={$_GET[P31]}&action=login_form&fu=' + escape(forward_url));">登录</a></p>
                </div>
            </div>
</block>
