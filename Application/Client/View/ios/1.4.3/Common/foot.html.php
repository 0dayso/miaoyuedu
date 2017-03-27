<php>
if (function_exists('canTest') && cantest()){
    echo '<div id="debug_msg" style="display: block; min-height: 200px; width: 100%; overflow: hidden;word-break:break-all;">';
    if(strpos($_SERVER['HTTP_HOST'], 'test.com')) {
        echo '内测<br/>';
    } else {
        echo '线上测试<br/>';
    }
    echo 'CLIENT_NAME:'.CLIENT_NAME.'<br />';
    echo 'CLIENT_VERSION:'.CLIENT_VERSION.'<br />';
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
    echo '_get_uuid_device:';
    print_r($deviceInfo);
    echo '<hr>';
    echo '</div>';
}
</php>