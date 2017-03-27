<?php
/**
 * 注意，这里是通用的用户分组配置文件，原则上不允许自行修改！
 * 通用配置是适合所有客户端或者绝大多数客户端使用。如果需要为某个或者某几个客户端做独立的配置，尤其是会影响到其它客户端的配置，请单独填写并单独加载！
 */
return array(
    'USERGROUP' =>  array(
        1 => array(
            'id'        => 1,
            'title'     => '普通会员',
            'higher'    => 1,
            'lower'     => 100,
            'flowernum' => 5,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 1,
            'zhuannum'  => 0
        ),
        2 => array(
            'id'        => 2,
            'title'     => '黑铁会员',
            'higher'    => 101,
            'lower'     => 1000,
            'flowernum' => 6,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 1,
            'zhuannum'  => 1
        ),
        3 => array(
            'id'        => 3,
            'title'     => '青铜会员',
            'higher'    => 1001,
            'lower'     => 3000,
            'flowernum' => 7,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 2
        ),
        4 => array(
            'id'        => 4,
            'title'     => '白银会员',
            'higher'    => 3001,
            'lower'     => 6000,
            'flowernum' => 8,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 3
        ),
        5 => array(
            'id'        => 5,
            'title'     => '黄金会员',
            'higher'    => 6001,
            'lower'     => 10000,
            'flowernum' => 9,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 4
        ),
        6 => array(
            'id'        => 6,
            'title'     => '白金会员',
            'higher'    => 10001,
            'lower'     => 16000,
            'flowernum' => 10,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 5
        ),
        7 => array(
            'id'        => 7,
            'title'     => '钻石会员',
            'higher'    => 16001,
            'lower'     => 25000,
            'flowernum' => 11,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 8
        ),
        8 => array(
            'id'        => 8,
            'title'     => '金钻会员',
            'higher'    => 25001,
            'lower'     => 50000,
            'flowernum' => 12,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 9
        ),
        9 => array(
            'id'        => 9,
            'title'     => '皇冠会员',
            'higher'    => 50001,
            'lower'     => 100000,
            'flowernum' => 13,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 10
        ),
        10 => array(
            'id'        => 10,
            'title'     => '至尊会员',
            'higher'    => 100001,
            'lower'     => 9999999,
            'flowernum' => 15,
            'favnum'    => 5,
            'cancomment'=> 1,
            'pmnum'     => 0,
            'zhuannum'  => 10
        ),
    ),
    'BOOKUSERGROUP' =>  array(
        '0'         =>  array('name'=>'见习','start'=>0,'end'=>500,'level'=>0),
        '1'         =>  array('name'=>'学徒','start'=>500,'end'=>2000,'level'=>1),
        '2'         =>  array('name'=>'弟子','start'=>2000,'end'=>5000,'level'=>2),
        '3'         =>  array('name'=>'执事','start'=>5000,'end'=>10000,'level'=>3),
        '4'         =>  array('name'=>'舵主','start'=>10000,'end'=>20000,'level'=>4),
        '5'         =>  array('name'=>'堂主','start'=>20000,'end'=>30000,'level'=>5),
        '6'         =>  array('name'=>'护法','start'=>30000,'end'=>40000,'level'=>6),
        '7'         =>  array('name'=>'长老','start'=>40000,'end'=>50000,'level'=>7),
        '8'         =>  array('name'=>'掌门','start'=>50000,'end'=>70000,'level'=>8),
        '9'         =>  array('name'=>'宗师','start'=>70000,'end'=>100000,'level'=>9),
        '10'        =>  array('name'=>'盟主','start'=>100000,'end'=>200000,'level'=>10),
        '11'        =>  array('name'=>'地盟','start'=>200000,'end'=>600000,'level'=>11),
        '12'        =>  array('name'=>'天盟','start'=>600000,'end'=>1000000,'level'=>12),
        '13'        =>  array('name'=>'总盟','start'=>1000000,'end'=>100000000,'level'=>13),
    )
);