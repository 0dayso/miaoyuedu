<extend name="Common/base" />
<block name="script">
<script>
var moneyname = "{:C('SITECONFIG.EMONEY_NAME')}";
Do.ready('functions', function(){
	doClient('parentreload', {});
});
<?php if($is_need_lingjiang>0){?>
function flip_button(idx,egold){

	$("#backimg"+idx).attr('src',"__IMG__/ic_bi2.png");
	$("#backtext"+idx).html(egold+moneyname);
	var obj = 	$("#idx"+idx);
	obj.addClass('rotated');

}
function flip_button_zj(idx,egold){
	$("#backimg"+idx).attr('src',"__IMG__/ic_bi1.png");
	$("#backtext"+idx).html(egold+moneyname);
	var obj = 	$("#idx"+idx);
	obj.addClass('rotated');
	//hg_Toast('中奖了,idx:'+idx+',egold:'+egold);
}
function do_choujiang(sel_num){


	var url=hg_signUrl("{:url('Userajax/lingjiang')}");
	var id='<?php echo $id;?>';
	var uuid='<?php echo $uuid;?>';
	$.ajax({
		url: url,
		type: "POST",
		data: {action:'lingjiang',id:id,idx:sel_num,uuid:uuid,t:Math.random()},
		dataType: 'json',
		success: function(json){

			if(json.status==1){
				var data = json;

				$(".qdwz").html('<p>恭喜,您获得了'+json['lj_egold']+'个'+moneyname);
				var len = json['lj_set_ary'].length;
				var zj_idx = json['lj_idx'];
				var zj_egold = json['lj_egold'];
				for(var j = 0;j <len;j++){
					if(zj_idx == j){
						flip_button_zj(j,data['lj_set_ary'][j]);
					}
					else {
						flip_button(j,data['lj_set_ary'][j]);
					}
				}
			}
			if (json.status==-1) {
         //并发控制
				 hg_Toast("处理中,请等待...");
			}
			else{
				hg_Toast(json.message);
			}
		}});
}

<?php }else{?>
function do_choujiang(sel_num){
            hg_Toast('您今天的抽奖机会已用完,请明天继续参与');
    }
<?php }?>

</script>
</block>
<block name="style">
<style>
  .tuijian li { margin: 5px 0;}.tuijian li p { height:20px;}
.qdwz{  padding:15px; font-size:22px; line-height:28px; color:#471a06;}
#container{ width: 33.3333%;margin: 0px;text-align: center;float: left;margin-top: 10px;list-style-type: none;text-align:center;-webkit-perspective: 500;}
.db{
  -webkit-transform-style: preserve-3d;  width:82px; height:92px;-webkit-border-radius:4px; display:inline-block; margin-bottom:20px;-webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));
}
.block { border:1px solid #b7babb;position: absolute;height: 90px;width: 80px;left: 0px;top: 0px;-webkit-transform-style: preserve-3d;-webkit-transition: -webkit-transform 0.8s;border-radius:4px; -webkit-border-radius:4px;-moz-border-radius:4px;}
.rotated,.mover .block:hover{ -webkit-transform: rotateY(180deg);}
.block .front{ z-index: 50;border-radius:4px; -webkit-border-radius:4px;-moz-border-radius:4px;}
.block .back{ z-index: 10;}
.rotated .back, .mover .block:hover .back{ z-index: 99;}
.side{ position: absolute; background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));  -webkit-backface-visibility: hidden;}
.front{ color: #fff;background-color: #000;height: 90px;width: 80px; }
.front img{  margin:0px 0 0 2px;width: 80px;height: 90px;}
.back{ background-color: #fff;height: 90px;width: 80px;-webkit-transform: rotateY(180deg);border-radius:4px; -webkit-border-radius:4px;-moz-border-radius:4px;}
.back img{  margin:0px 0 10px 0;width: 80px;height: 55px;}
.back p{  font-size:12px;}
.qdwz p{ padding-top: 15px;font-size: 18px;line-height: 28px;color: rgba(0,0,0,.87);}
.tuijian li p{ text-align: center;width: 88%;display: inline-block;line-height: 20px;height: 40px;overflow: hidden;font-size:12px;padding:10px 0;}
</style>
</block>
<block name="body">
<body class="bgce">
<div class="qdwz"><p><if condition="$is_need_lingjiang neq 0">点击问号开始抽奖<else/>您今天的抽奖机会已用完</if>


</p></div>

<div  class="rq">
  <ul>
    <li id="container">
      <div class="db">
        <div id="idx0" class="block" onclick="do_choujiang('0');">
            <div class="front side"> <img src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img  id="backimg0" src="" />
                <p id="backtext0">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>
    <li id="container">
      <div class="db">
        <div id="idx1" class="block" onclick="do_choujiang('1');">
            <div class="front side"> <img src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img id="backimg1" src="" />
                <p id="backtext1">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>
    <li id="container">
      <div class="db">
        <div id="idx2" class="block" onclick="do_choujiang('2');">
            <div class="front side"> <img  src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img  id="backimg2" src="" />
                <p id="backtext2">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>
    <li id="container">
      <div class="db">
        <div id="idx3" class="block" onclick="do_choujiang('3');">
            <div class="front side"> <img  src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img  id="backimg3" src="" />
                <p id="backtext3">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>
    <li id="container">
      <div class="db">
        <div id="idx4" class="block" onclick="do_choujiang('4');">
            <div class="front side"> <img  src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img  id="backimg4" src="" />
                <p id="backtext4">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>
    <li id="container">
      <div class="db">
        <div id="idx5" class="block" onclick="do_choujiang('5');">
            <div class="front side"> <img  src="__IMG__/ic_wen.png" /> </div>
            <div class="back side"> <img  id="backimg5" src="" />
                <p id="backtext5">0{:C('SITECONFIG.EMONEY_NAME')}</p>
          </div>
        </div>
      </div>
    </li>

  </ul>
</div>
<div style="clear:both;"></div>
<div class="tuijian clearfix"  style="background-color: #f4f4f4;">
    <h5>精品好书在这里</h5>
    <ul>
        <Hongshu:bangdan name="android_{$sex_flag}_xinshu" items="6">
            <li onClick="hg_gotoUrl('{:url('Book/view', array('bid'=>$row[bid]))}')">
            <div><span class="tag"></span><img src="{$row.face}" width="100" height="125">
            <p>{$row.catename}</p>
          </div>
        </li>
        </Hongshu:bangdan>
    </ul>
<div class="warning" style="margin-top:20px">
<fieldset style="margin-top: 340px;">
  <legend>温馨提示</legend>
  <p>每天签到可获得一次抽奖机会</p>
  <p>奖品为6~51个{:C('SITECONFIG.EMONEY_NAME')}</p>
</fieldset>
</div>
</block>
