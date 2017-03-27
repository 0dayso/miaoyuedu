<extend name="Common/base" />
<block name="header">
<include file="Common/head1" />
</block>
<block name="style">
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
</block>
<block name="body">
<include file="Common/head2" />
<div class="user1060">
            <div class="user960">
                <p class="c6">账号：{$userinfo.username}<span class="lf20">用户ID：{$userinfo.uid}</span></p>
                <p class="c9 lf30 bom40">（注册时间：{$user.regdate} 通过<span class="cb"> {:C('LOGIN_TYPE')[$user['ologin']]['title']} </span>登录）</p>
                <div style="overflow: hidden;">
                    <span class="c6 fllf top6">用户昵称：</span>
                    <if condition="$user['nickallow']">
                    <input class="subsearch lf10" value="" disabled="disabled" type="text" placeholder="{$userinfo.nickname}" maxlength="250" autocomplete="off">
                    <else/>
                    <input class="subsearch lf10" value="" type="text" placeholder="{$userinfo.nickname}" maxlength="250" autocomplete="off" id="nickname" onkeyup="checknickname();">
                    </if><if condition="$user['nickallow']">
                   <button class="nichenbtn lf30 gray">已修改</button>
                <else/>
                <button class="nichenbtn lf30 gray" disabled="disabled" onclick="submit();" id="tijiao">确认</button></if>
                </div>
                <span class="zhushi c9 lf80">*昵称只能修改一次，请慎重考虑。新昵称不能与其他用户的昵称重复，禁止使用违反法律和道德的昵称。</span>
                <!--
                <div class="top30" style="overflow: hidden;">
                    <span class="c6 fllf">用户头像：</span>
                    <img class="lf10" src="__IMG__/ic_person.jpg"/ width="144" height="144">
                </div>
                -->
                <!-- 用户头像 -->
                <div id="divUserFace" name="divUserFace" class="top15 clearfix">
                  <span class="c6">用户头像：</span>
                  <div class="usertx lf80">
                    <div class="left rt20" id="selectPic">
                    </div>

                    <div id="cropContainerPreload" style="display:none"></div>
                  </div>
                </div>
                <input id="hidTimespan" type="hidden" value="{$timespan}" />
                <input id="hidToken" type="hidden" value="{:md5($user['uid'] . 'unique_salt' . $timespan)}" />
                <input id="hidUid" type="hidden" value="{$user['uid']}" />
                <!-- 用户头像 -->
                <span class="zhushi c9 lf80">*新的头像会代替原有头像，仅支持文件大小在 1MB 以内的 jpg格式 图片上传。</span>
            </div>
        </div>
    </div>
</div>
</block>
<block name="script">
<include file="Common/foot2" />
<script type="text/javascript">
Do.ready('core',function(){
	Do.add('crop', {
		path: "__STATICURL__/Public/Client/common/js/mod/croppic/croppic.min.js"
	});
	Do.add('upload', {
		path: "__STATICURL__/Public/Client/common/js/mod/uploadify/jquery.uploadify.min.js"
	});
	Do.ready('upload', 'crop', function(){
		initUpload();
	});
})
/**
 * 初始化上传头像
 *
 */
 function checknickname(){
    var nickname=$('#nickname').val();
    $('#tijiao').addClass('gray');
    $('#tijiao').attr('disabled',true);
    if(nickname){
       $('#tijiao').removeClass('gray');
       $('#tijiao').removeAttr('disabled');
    }
 }
function initUpload() {
    if ($('#selectPic').html() == undefined) {
        return;
    }
    $('#selectPic').uploadify({
        'fileSizeLimit': '1MB',
        'fileTypeExts': '*.jpg',
        'formData': {
          'timestamp': $("#hidTimespan").val(),
          'token': $("#hidToken").val(),
          'uid': $("#hidUid").val()
        },
        'swf':'/uploadify.swf',
        'uploader': "{:url('Upload/upload')}",
        'cancelImg': '__STATICURL__/Public/Client/common/js/mod/uploadify/uploadify-cancel.png',
        'buttonText': '',
        'multi': false,
        'width':144,
        'height':144,
        'method':'post',
        'rollover':true,
        'auto':true,
        'removeCompleted':true,
        'removeTimeout':1,
        'onUploadSuccess': function(file, data, response) {
            var obj = eval("(" + data + ")");
            initCropimage(obj.url,obj.savename);
            hg_Toast("请注意：您新的头像已经上传成功，下一步您需要对头像进行裁剪");


        }
    });
}

/**
 * 上传头像后裁剪功能
 *
 */
function initCropimage(url,picname) {
    var picurl = url;
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
}

function submit(){
    var url="{:url('Userajax/changenickname',array(),'do')}";
    var nickname=$('#nickname').val();
    $.ajax({
    url: url,
    type: "POST",
    data: {nickname:nickname},
    success: function(json){
        if(json.status==1){
            hg_gotoUrl(json.message);
            hg_gotoUrl(json.url);
        }
        else {
            hg_Toast(json.message);
        }
    }});
}
</script>
</block>