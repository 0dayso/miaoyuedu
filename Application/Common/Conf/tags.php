<?php
/**
 * 注意，这里是通用的配置文件，不允许自行修改！
 */
return array(
    'app_begin'  => array(
        'Client\Common\CheckEnvBehavior',
        'Usercenter\Common\CheckEnvBehavior'
    ),
    'action_begin' => array(
        'Common\Behavior\actionBeginBehavior',
    ),
    'action_end' => array(
         'Com\AppEndBehavior',
    ),
);
