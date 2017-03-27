<php>
if (function_exists('canTest') && cantest()){
    echo '<div id="debug_msg" style="display: block; min-height: 200px; width: 100%; overflow: hidden;word-break:break-all;">';
    if(strpos($_SERVER['HTTP_HOST'], 'test.com')) {
        echo '内测<br/>';
    } else {
        echo '线上测试<br/>';
    }
    //print_r($_SERVER);
    if($userinfo){
        echo 'UserInfo:';
        pre($userinfo);
    }
    echo 'path:'.$_SERVER['PHP_SELF'].'<br/>';
    echo '_GET:' . print_r($_GET, 1) . '<br/>';
    echo '_POST:' . print_r($_POST, 1) . '<br/>';
    echo 'querystring:'.$_SERVER['QUERY_STRING'].'<br/>';
    echo 'referer:' . $_SERVER['HTTP_REFERER'] . '<br />';
    echo 'M_forward:' . $M_forward . '<br />';
	echo 'android_get_uuid_device:';
	print_r(android_get_uuid_device());
    echo '<br />CLIENT_PARAMS:';
    echo '<script>if(typeof(doClient)==\'function\') document.write(doClient(\'getParams\'));</script>';
    echo '</div>';
    $begin = $end =0;
    mk_time_xiangdui(NOW_TIME, 'thismonth', $begin, $end);
    echo date('d', $end);
}
</php>