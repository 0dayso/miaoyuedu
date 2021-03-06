<extend name="Common/base" />
<block name="header">
    <include file="Common/head1" />
</block>
<block name="body">
<link href = "__STATICURL__/Public/Client/common/js/mod/croppic/croppic.css" rel="stylesheet" type="text/css">
<link href = "__STATICURL__/Public/Client/common/js/mod/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<style type="text/css">
    .uploadify-button {
      background-image:url(<if condition="$user['userimg']">"{$user['userimg']}"<else/>"__IMG__/ic_person.jpg"</if>);
      background-repeat: no-repeat;
      background-size: 144px 144px;
    }
    #cropContainerPreload{ width:200px; height:200px; position: relative; border:1px solid #ccc;}

    .cropControls i{
      display:block;
      float:left;
      margin:0;
      cursor:pointer;
      background-image:url(__STATICURL__/Public/Client/common/js/mod/croppic/cropperIcons.png);
      width:30px;
      height:30px;
      text-align:center;
      line-height:20px;
      color:#FFF;
      font-size:13px;
      font-weight: bold;
      font-style: normal;
    }
</style>
    <div class="container bgf mtop10 clearfix">
        <div class="ziliao">
            <div class="tit5"><div class="tit5_border"><h1>个人资料设置</h1></div></div>
            <div class="ziliao_con">
                <div class="form_item2">
                    <label>注册账号：</label>
                    <div class="lf"> <input type="text" placeholder="{$userinfo.username}" class="radius4 disable"  readonly /></div>
                    <div class="rt"><p><span class="cred">*</span>{:C("SITECONFIG.SITE_NAME")}账号来自第三方不能修改</p></div>
                    <span class="right"></span>
                </div>
                <div class="form_item2">
                    <label>用户昵称：</label>
                    <div class="lf"> 
                        <if condition="$user['nickallow']">
                            <input type="text" placeholder="{$userinfo.nickname}" class="radius4 disable" readonly/>
                        <else/>
                            <input name="nickname" type="text" placeholder="{$userinfo.nickname}" class="radius4" />
                        </if>
                    </div>
                    <div class="rt"><p class="hang2"><span class="cred">*</span>昵称只能修改一次,请慎重,新昵称不能和其他用户的昵称重复,禁止使用违反法律和道德良俗的昵称</p></div>
                </div>
                <div class="form_item2" >
                    <label>用户头像：</label>
                    <div id="divUserFace" name="divUserFace" style="float:left;">
                        <p id="selectPic"><img src="{$userinfo.avatar}" /></p>
                        <p id="cropContainerPreload" style="display:none"></p>
                    </div>
                    <input id="hidTimespan" type="hidden" value="{$timespan}" />
                    <input id="hidToken" type="hidden" value="{:md5($user['uid'] . 'unique_salt' . $timespan)}" />
                    <input id="hidUid" type="hidden" value="{$user['uid']}" />
                    <p class="ziliaots">上传头像（图片将会被自动缩放到110*110大小），请尽量使用正方形图片，以免缩放后变形：</p>
                    <p class="warn"> 注意：新的头像会替代现有的头像，仅支持文件大小为50KB以内的jpg，png，gif图片上传，上传后如果图片无法显示，请刷新页面。</p>
                    <p class="warn">PS：头像上传即生效，无需保存设置。</p>
                </div>
                <div class="form_item2">
                    <label>密码：</label>
                    <div class="lf"> <input id="old" type="text" placeholder="输入老密码" class="radius4" /></div>
                    <div class="rt"><p class="hang2"><span class="cred">*</span>输入老密码</p></div>
                </div>
                <div class="form_item2">
                    <label></label>
                    <div class="lf"> <input id="new" type="text" placeholder="输入新密码" class="radius4" /></div>
                    <div class="rt"><p class="hang2"><span class="cred">*</span>新密码:6-15个大小写英文字母、数字,符号</p></div>
                    <p class="warn">使用微信或QQ等第三方账号注册，无法修改密码</p>
                </div><div class="form_item2">
                <label></label>
                <div class="lf"><button class="mainbtn radius4" id="baocun">保存设置</button></div>
            </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
        require(['mod/user','croppic/croppic.min','uploadify/jquery.uploadify.min'],function(user){
            user.initUpload('#selectPic','#hidTimespan','#hidToken','#hidUid');
            $('#baocun').on('click',function(){
                user.savepermessage('nickname','#old','#new');
            })
        })

        /**
         * 上传头像后裁剪功能
         *
         */
        function initCropimage(url,picname) {
            var picurl = url;
            require(['functions','croppic/croppic.min','uploadify/jquery.uploadify.min'],function(){
                var cropurl = parseUrl({picname:picname}, 'Upload/imgcrop');
            
                var croppicContainerPreloadOptions = {
                        cropUrl: cropurl,
                        loadPicture:picurl+'?'+Math.random(),
                        enableMousescroll:true,
                        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
                        onBeforeImgUpload: function(){ },
                        onAfterImgUpload: function(){ },
                        onImgDrag: function(){ },
                        onImgZoom: function(){ },
                        onBeforeImgCrop: function(){ },
                        onAfterImgCrop:function(data){
                            $("#cropContainerPreload").html("");
                            $("#cropContainerPreload").hide();
                            $(".uploadify-button").css({"background-image":'url('+data.url+'?'+Math.random()+')'});
                            $("#avatarsmall").attr("src", data.url+'?'+Math.random());
                            $("#avatarbig").attr("src", data.url+'?'+Math.random());
                            $("#selectPic").show();
                            hg_Toast("通知：新的头像已经生效。如果头像没有立即更新，请刷新页面");
                        },
                        onReset:function(){
                            $("#cropContainerPreload").html("");
                            $("#cropContainerPreload").hide();
                            $("#selectPic").show();
                        },
                        onError:function(errormessage){ }
                }
                var cropContainerPreload = new Croppic('cropContainerPreload', croppicContainerPreloadOptions);

                setTimeout("$('#selectPic').hide();$('#cropContainerPreload').show();", 1500);
            })
        }
    </script>
</block>