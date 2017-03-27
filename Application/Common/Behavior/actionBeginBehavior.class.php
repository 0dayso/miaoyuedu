<?php

namespace Common\Behavior;

use Think\Behavior;
use HS\Qqfangdao;
defined('THINK_PATH') or exit();

// 系统运行结束要执行的代码统一写在这里
class actionBeginBehavior extends Behavior {

    public function run(&$param) {
        register_shutdown_function('__shutDwonApp');
    }

}
