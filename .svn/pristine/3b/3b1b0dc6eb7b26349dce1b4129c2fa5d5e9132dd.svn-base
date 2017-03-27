<extend name="Common/base" />
<block name="header">
<include file="Common/head2" />
</block>
<block name="body">
    <style>
        .nolist{padding:30px 0; width:100%;  text-align: center;font-size:14px;color:#666;}
        .list{margin-top:10px;}
        .list li{ background-color: #fff; margin-bottom: 10px;width:100%;}
        .tit{height:40px;width:100%;color:#666; background-color: #dedede;}
        .nr{width:100%; text-align: left;padding:0 10px;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
        .nr .hsnum{width:50%;display:inline-block;padding:10px 0;}
        .nr .date{width:30%;display:inline-block;padding:10px 0;}
        .nr .state{width:20%;display:inline-block;padding:10px 0;}
    </style>
<if condition="$cardlist">
<!-- <php>var_dump($cardlist);</php> -->
<div class="list ">
    <ul>
       <foreach name="cardlist" item="row">
       <li>
           <div class="tit">卡号：{$row.cardno}</div>
           <div class="nr"><div class="hsnum">{$row.cardnum}红薯银币</div><div class="date">{$row.time}</div><div class="state">已兑换</div></div>
       </li>
       </foreach>
    </ul>
</div>
<else/>
  <div class="list nolist">暂无记录</div>
</if>
</block>