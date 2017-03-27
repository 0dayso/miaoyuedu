<?php
 
/**
 * 功能：循环检测并创建文件夹
 * 参数：$path 文件夹路径
 * 返回：
 */
function createDir($path) {
    if (!is_dir($path)) {
        if (!mkdir(dirname($path))) {
            return false;
        }
        if (!mkdir($path, 0777)) {
            return false;
        }
    }
    return true;
}

/**
 * 秒转换成时间
 */
function secondToTime($sec) {
    return date('Y-m-d H:i:s', $sec);
}

/**
 * 算日期间隔
 */
function timeDiff($date) {
    $Date = new \Org\Util\Date();
    return $Date->timeDiff($date);
}

/**
 * 显示文件大小
 */
function ReadableFilesize($size) {
    $mod   = 1024;
    $units = explode(' ', 'B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }
    return round($size, 2) . ' ' . $units[$i];
}

/**
 * 发送POST请求
 */
function PostData($data, $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if (stripos($url, 'https://') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);

    curl_close($ch);
    return $response;
}

function GetData($url) {
    $ch     = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/**
 * 获取字串首字母
 */
function getFirstLetter($s0) {
    $firstchar_ord = ord(strtoupper($s0{0}));
    if ($firstchar_ord >= 65 and $firstchar_ord <= 91)
        return strtoupper($s0{0});
    if ($firstchar_ord >= 48 and $firstchar_ord <= 57)
        return '#';
    $s             = iconv("UTF-8", "gb2312", $s0);
    $asc           = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 and $asc <= -20284)
        return "A";
    if ($asc >= -20283 and $asc <= -19776)
        return "B";
    if ($asc >= -19775 and $asc <= -19219)
        return "C";
    if ($asc >= -19218 and $asc <= -18711)
        return "D";
    if ($asc >= -18710 and $asc <= -18527)
        return "E";
    if ($asc >= -18526 and $asc <= -18240)
        return "F";
    if ($asc >= -18239 and $asc <= -17923)
        return "G";
    if ($asc >= -17922 and $asc <= -17418)
        return "H";
    if ($asc >= -17417 and $asc <= -16475)
        return "J";
    if ($asc >= -16474 and $asc <= -16213)
        return "K";
    if ($asc >= -16212 and $asc <= -15641)
        return "L";
    if ($asc >= -15640 and $asc <= -15166)
        return "M";
    if ($asc >= -15165 and $asc <= -14923)
        return "N";
    if ($asc >= -14922 and $asc <= -14915)
        return "O";
    if ($asc >= -14914 and $asc <= -14631)
        return "P";
    if ($asc >= -14630 and $asc <= -14150)
        return "Q";
    if ($asc >= -14149 and $asc <= -14091)
        return "R";
    if ($asc >= -14090 and $asc <= -13319)
        return "S";
    if ($asc >= -13318 and $asc <= -12839)
        return "T";
    if ($asc >= -12838 and $asc <= -12557)
        return "W";
    if ($asc >= -12556 and $asc <= -11848)
        return "X";
    if ($asc >= -11847 and $asc <= -11056)
        return "Y";
    if ($asc >= -11055 and $asc <= -10247)
        return "Z";
    return '#';
}

/**
 * 判断email地址是否合法
 *
 * @param string  $email 邮件地址
 * @return boolean 邮件地址是否合法
 */
function isValidEmail($email) {
    return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
}

/**
 * 判断手机号是否合法
 * @param type $phone
 * @return boolean 是否合法
 */
function isValidPhone($phone) {
    if (verifyPhone($phone, 'mobile')) {

    } elseif (verifyPhone($phone, 'liantong')) {

    } elseif (verifyPhone($phone, 'dianxin')) {

    } else {
        return false;
    }
    return true;
}

/**
 * 远程文件是否存在
 */
function remote_file_exists($url) {
    $ch      = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $contents = curl_exec($ch);
    if (preg_match("/404/", $contents) || $contents == false) {
        return false;
    } else {
        return true;
    }
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

/**
 * 获取封面路径算法
 */
function getBookfacePath($bid, $size = "middle") {
    $redis = new \HS\MemcacheRedis();
    $book  = $redis->getRedis('book_normal#' . $bid);
    // var_dump($book);
    unset($redis);
    if ($book["imgstatus"] != 3) {
        $result = C('TMPL_PARSE_STRING.__STATICURL__') . "/img/001.jpg";
    } else {
        $result = C('BOOKFACE_URL') . "/" . floor($bid / 10000) . '/' . floor(($bid % 10000) / 100) . "/" . $bid . "_" . $size . ".jpg";
    }
    if (substr($result, 0, 4) !== 'http') {
        $result = ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . ':' . $result;
    }
    return $result;
}

/**
 * 获得用户头像的算法
 *
 * @param int  $uid 用户id
 * @param string  $size 头像尺寸
 * @return string 用户头像路径
 */
function getUserFacePath($uid, $size = 'middle') {
    $uid    = abs(intval($uid)); //UID取整数绝对值
    $uid    = sprintf("%09d", $uid); //前边加0补齐9位，例如UID为31的用户变成 000000031
    $dir1   = substr($uid, 0, 3); //取左边3位，即 000
    $dir2   = substr($uid, 3, 2); //取4-5位，即00
    $dir3   = substr($uid, 5, 2); //取6-7位，即00
    $result = C('AVATAR_ROOT') . "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/" . substr($uid, -2) . "_avatar_{$size}.jpg";
    return $result;
}

/**
 * 获得用户头像的算法
 *
 * @param int  $uid 用户id
 * @param string  $size 头像尺寸
 * @return string 用户头像路径
 */
function getUserFaceUrl($uid, $size = 'middle') {
    $uid    = abs(intval($uid)); //UID取整数绝对值
    $uid    = sprintf("%09d", $uid); //前边加0补齐9位，例如UID为31的用户变成 000000031
    $dir1   = substr($uid, 0, 3); //取左边3位，即 000
    $dir2   = substr($uid, 3, 2); //取4-5位，即00
    $dir3   = substr($uid, 5, 2); //取6-7位，即00
    $result = "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/" . substr($uid, -2) . "_avatar_{$size}.jpg";
    $fn = C('AVATAR_ROOT') . $result;
    if(!file_exists($fn)){
//         if(defined('CLIENT_NAME') && CLIENT_NAME === 'android') {
            $result = C('AVATAR_URL').'/noavatar_big.gif';
//         } else {
//             $result = '';
//         }
    } else {
        $result = C('AVATAR_URL').$result;
    }
    if($result) {
        if (substr($result, 0, 4) !== 'http') {
            $result = ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . ':' . $result;
        }
    }
    return $result;
}

/**
 * 获取缓存的key，与cachemap进行映射
 */
function getCacheKey($name) {
    if (C('cache_prefix') && stristr($name, C('cache_prefix'))) {
        return $name;
    }
    $key = C('mcconfig.prefix') . $name;
    $arr = array();
    if (strpos($name, '#') > 0) {
        $arr = C('cachemap.' . substr($name, 0, strpos($name, '#') + 1));
    } else {
        $arr = C('cachemap.' . $name);
    }
    if (is_array($arr)) {
        $openmap = $arr[1];
        if ($openmap == 1) {
            if (strpos($name, '#') > 0) {
                $key = $arr[0] . substr($name, strpos($name, '#') + 1);
            } else {
                $key = $arr[0];
            }
        } else {
            //原始key
            $key = C('cache_prefix') . $name;
        }
    } else {
        //原始key
        $key = C('cache_prefix') . $name;
    }
    return $key;
}

/**
 * 检测输入的验证码是否正确
 */
function checkVerify($code, $id = '') {;
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 *
 */
function read($filename, $method = "rb", $start = null, $lines = null) {
    if (!is_file($filename)) {

        return false;
    }

    if ($start == null && $lines == null && function_exists("file_get_contents")) {
        $contents = file_get_contents($filename);
    } else {
        if (!($fd = @fopen($filename, $method)))
            return false;
        flock($fd, LOCK_SH);
        if ($start == null && $lines == null) {
            $contents = fread($fd, filesize($filename));
        } else {
            if ($start > 1) {
                for ($loop = 1; $loop < $start; $loop++) {
                    fgets($fd, 65536);
                }
            }
            if ($lines == null) {
                while (!feof($fd))
                    $contents .= fgets($fd, 65536);
            } else {
                for ($loop = 0; $loop < $lines; $loop++) {
                    $contents .= fgets($fd, 65536);
                    if (feof($fd))
                        break;
                }
            }
        }
        fclose($fd);
    }
    return $contents;
}

/**
 * 检测用户是否登录
 *
 */
function checkLogin($callback = '') {
    $user = session("logininfo");
    if (!$user || !is_array($user)) {
        $msg = C('MESSAGES');
        echoJson($msg['nologin'], $callback);
        exit(0);
    }
}

function getRelativeTime($btime, $stype = 'lastmonth', &$starttime, &$endtime) {
    if (!$btime) {
        $btime = time();
    }
    switch ($stype) {
        case 'lastmonth':
            $starttime = mktime(0, 0, 0, date('m', $btime) - 1, 1, date("Y", $btime));
            $endtime   = mktime(23, 59, 59, date('m', $btime), 00, date("Y", $btime));
            break;
        case 'thismonth':
            $starttime = mktime(0, 0, 0, date("m", $btime), 1, date("Y", $btime));
            $endtime   = mktime(23, 59, 59, date('m', $btime) + 1, 00, date("Y", $btime));
            break;
        case 'lastday':
            $starttime = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - 1, date('Y', $btime));
            $endtime   = mktime(24, 0, 0, date('m', $btime), date('d', $btime) - 1, date('Y', $btime));
            break;
        case 'thisday':
            $starttime = mktime(0, 0, 0, date('m', $btime), date('d', $btime), date('Y', $btime));
            $endtime   = mktime(24, 0, 0, date('m', $btime), date('d', $btime), date('Y', $btime));
            break;
        case 'thisweek':
            $starttime = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - date("w", $btime) + 1, date("Y", $btime));
            $endtime   = mktime(23, 59, 59, date('m', $btime), date('d', $btime) - date("w", $btime) + 7, date("Y", $btime));
            break;
        case 'lastweek':
            $starttime = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - date("w", $btime) + 1 - 7, date("Y", $btime));
            $endtime   = mktime(23, 59, 59, date('m', $btime), date('d', $btime) - date("w", $btime) + 7 - 7, date("Y", $btime));
            break;
    }
}

/**
 *     XSS
 */
function removeXSS($val) {
    $val    = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val);
        $val = preg_replace('/(�{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val);
    }
    $ra1   = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title',
        'base');
    $ra2   = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint',
        'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete',
        'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin',
        'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover',
        'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit',
        'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra    = array_merge($ra1, $ra2);
    $found = true;
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(�{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2);
            $val         = preg_replace($pattern, $replacement, $val);
            if ($val_before == $val) {
                $found = false;
            }
        }
    }
    return $val;
}

/**
 *     防注入
 */
function abacaAddslashes($var) {
    if (!get_magic_quotes_gpc()) {
        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $var [$key] = abacaAddslashes($val);
            }
        } else {
            $var = addslashes($var);
        }
    }
    return $var;
}

/**
 * 加密函数
 *
 * @param string  $txt 需加密的字符串
 * @param string  $key 加密密钥，默认读取SECURE_CODE配置
 * @return string 加密后的字符串
 */
function jiami($txt, $key = null) {
    empty($key) && $key = C('SECURE_CODE');
    //有mcrypt扩展时
    if (function_exists('mcrypt_module_open')) {
        return desencrypt($txt, $key);
    }
    //无mcrypt扩展时
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $nh    = rand(0, 64);
    $ch    = $chars[$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = base64_encode($txt);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey[$k++])) % 64;
        $tmp .= $chars[$j];
    }
    return $ch . $tmp;
}

/**
 * 解密函数
 *
 * @param string  $txt 待解密的字符串
 * @param string  $key 解密密钥，默认读取SECURE_CODE配置
 * @return string 解密后的字符串
 */
function jiemi($txt, $key = null) {
    empty($key) && $key = C('SECURE_CODE');
    //有mcrypt扩展时
    if (function_exists('mcrypt_module_open')) {
        return desdecrypt($txt, $key);
    }
    //无mcrypt扩展时
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $ch    = $txt[0];
    $nh    = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = substr($txt, 1);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0) {
            $j += 64;
        }
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

/**
 * DES加密函数
 *
 * @param string  $input
 * @param string  $key
 */
function desencrypt($input, $key) {

    //使用新版的加密方式
    require_once THINK_PATH . '/Library/Org/Util/DES_MOBILE.php';
    $desc = new DES_MOBILE();
    return $desc->setKey($key)->encrypt($input);
}

/**
 * DES解密函数
 *
 * @param string  $input
 * @param string  $key
 */
function desdecrypt($encrypted, $key) {
    //使用新版的加密方式
    require_once THINK_PATH . '/Library/Org/Util/DES_MOBILE.php';
    $desc = new DES_MOBILE();
    return $desc->setKey($key)->decrypt($encrypted);
}

/**
 * 输出json
 *
 * @param array 或 string  $param 需要输出的主题
 * @param string  $callback
 */
function echoJson($param, $callback) {
    header('Content-type: application/json');
    if ($param && is_array($param)) {
        if ($callback) {
            echo $callback . '(' . json_encode($param) . ')';
        } else {
            echo json_encode($param);
        }
    } else if ($param && is_string($param)) {
        if ($callback) {
            echo $callback . '(' . $param . ')';
        } else {
            echo $param;
        }
    }
}

/**
 * 字符串截取，支持中文和其他编码
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length = 0, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice        = join("", array_slice($match[0], $start, $length));
    }
    if ($suffix && strLength($str) > $length) {
        return $slice . '......';
    }
    return $slice;
}

/**
 * PHP获取字符串中英文混合长度
 * @param $str string 字符串
 * @param $charset string 编码
 * @return 返回长度，1中文=1位，2英文=1位
 */
function strLength($str, $charset = 'utf-8') {
    if (!$str) {
        return 0;
    }
    if (function_exists('mb_strlen')) {
        return mb_strlen($str, $charset);
    }
    $num = strlen($str);
    if ($charset == 'utf-8') {
        $str = iconv('utf-8', 'gb2312', $str);
        if (!$str) {  //转换失败
            return $num;
        }
    }
    $cnNum = 0;
    for ($i = 0; $i < $num; $i++) {
        if (ord(substr($str, $i + 1, 1)) > 127) {
            $cnNum++;
            $i++;
        }
    }
    $enNum  = $num - ($cnNum * 2);
    $number = ($enNum / 2) + $cnNum;
    return ceil($number);
}

/**
 * php的escape函数
 * @param string $str 需要转换的字符串
 * @return string
 */
function escape($str) {
    preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r);
    //匹配utf-8字符，
    $str = $r [0];
    $l   = count($str);
    for ($i = 0; $i < $l; $i ++) {
        $value = ord($str [$i] [0]);
        if ($value < 223) {
            $str [$i] = rawurlencode(utf8_decode($str [$i]));
            //先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符.
            //utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。
        } else {
            $str [$i] = "%u" . strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $str [$i])));
        }
    }
    return join("", $str);
}

/**
 * php的unescape函数
 * @param string $str 需要转换的字符串
 * @return string
 */
function unescape($str) {
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i ++) {
        if ($str [$i] == '%' && $str [$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f)
                $ret .= chr($val);
            else if ($val < 0x800)
                $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
            else
                $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else if ($str [$i] == '%') {
            $ret .= urldecode(substr($str, $i, 3));
            $i += 2;
        } else
            $ret .= $str [$i];
    }
    return $ret;
}

/**
 * 发送短信的函数
 * @param string $phonenum 手机号码
 * @param string $msg 短信内容
 * @return string
 */
function sendMobileMsg($phonenum, $msg) {
    $cty_username = 'shlyxx-1';
    $cty_pass     = 'f61be7';
    $cty_msgurl   = 'http://si.800617.com:4400/SendSms.aspx?un=' . $cty_username . '&pwd=' . $cty_pass . '&mobile=' . $phonenum . '&msg=' . urlencode($msg);
    $result       = file_get_contents($cty_msgurl);

    $result = str_replace(array('result=', '&'), '', strtolower($result));
    switch ($result) {
        case '1':
            return 'ok';
            break;
        case '-1':
            return '用户名和密码参数为空或者参数含有非法字符';
            break;
        case '-2':
            return '手机号参数不正确';
            break;
        case '-3':
            return 'msg参数为空或长度小于0个字符';
            break;
        case '-4':
            return 'msg参数长度超过64个字符';
            break;
        case '-6':
            return '发送号码为黑名单用户';
            break;
        case '-8':
            return '下发内容中含有屏蔽词';
            break;
        case '-9':
            return '下发账户不存在';
            break;
        case '-10':
            return '下发账户已经停用';
            break;
        case '-11':
            return '下发账户无余额';
            break;
        case '-15':
            return 'MD5校验错误';
            break;
        case '-16':
            return 'IP服务器鉴权错误';
            break;
        case '-17':
            return '接口类型错误';
            break;
        case '-18':
            return '服务类型错误';
            break;
        case '-22':
            return '手机号达到当天发送限制';
            break;
        case '-23':
            return '同一手机号，相同内容达到当天发送限制';
            break;
        case '-99':
            return '系统异常';
            break;
        default:
            return '网络错误' . $result;
            break;
            break;
    }
}

/**
 * 检测手机
 * @param type $mobileId
 * @param type $type
 * @return boolean
 */
function verifyPhone($mobileId, $type) {
    $mobileId = floatval(trim($mobileId));
    if (!$mobileId) {
        return false;
    }
    $type        = trim($type);
    $cm          = array(134, 135, 136, 137, 138, 139, 150, 151, 152, 158, 159, 157, 182, 183, 184, 187, 188, 147, 1705, 178); //移动的号码
    $liantong_cm = array(130, 131, 132, 155, 156, 185, 186, 1709); //联通的号码
    $dianxin_cm  = array(133, 153, 177, 189, 180, 181, 1700); //电信号码
    $mobile      = substr($mobileId, 0, 3);
    $mobile_170  = substr($mobileId, 0, 4); //170号段，取前四位数
    if ($type == 'mobile') {
        if ((in_array($mobile, $cm) || in_array($mobile_170, $cm)) && preg_match("/^(?:13|15|18|17|14)[0-9]{9}$/", $mobileId)) {
            return true;
        } else {
            return false;
        }
    } elseif ($type == 'liantong') {
        if ((in_array($mobile, $liantong_cm) || in_array($mobile_170, $liantong_cm)) && preg_match("/^(?:13|15|18|17|14)[0-9]{9}$/", $mobileId)) {
            return true;
        } else {
            return false;
        }
    } elseif ($type == 'dianxin') {
        if ((in_array($mobile, $dianxin_cm) || in_array($mobile_170, $dianxin_cm)) && preg_match("/^(?:13|15|17|18|17|14)[0-9]{9}$/", $mobileId)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    $ckey_length = 4; //note 随机密钥长度 取值 0-32;
    //note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    //note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    //note 当此值为 0 时，则不产生随机密钥

    $key  = md5($key); //  ? $key : UC_KEY
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey   = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string        = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box    = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 * 生成随机字符串
 * 添加$addStr，可以自己指定字符串以适合文章内容添加随机水印
 */
function randomstr($length, $addStr = '') {
    $hash  = '';
    $chars = '123456789abcdefghijklmnopqrstuvwxyz' . $addStr;
    $max   = strlen($chars) - 1;
    //mt_srand((double)microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 格式化输出信息
 *
 * @param 字符串/数组 $array 要输出的信息
 * @param 逻辑值 $exit 是否需要退出
 */
function pre($array, $exit = false) {
    if ($array) {
        if (is_string($array)) {
            echo '<br>';
            echo htmlspecialchars($array);
            echo '<br>';
        } else {
            echo "<div style='font-size:12px;line-height:14px;text-align:left;color:#000;background-color:#fff;'><pre>";
            print_r($array);
            echo "</pre></div>";
        }
    }
    if ($exit) {
        E('程序调试断点！', 222);
    }
}

/**
 * URL组装 支持不同URL模式
 * 本函数是相对于ThinkPHP的U函数的一个扩展，主要实现的功能是根据路由表反推出实际要生成的网址
 * @staticvar array $rules 系统路由表
 * @param string $str URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $para 传入的参数，支持数组和字符串
 * @param string|boolean $suffixed 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @param int $suffix_idx 后缀名称编号（如果有多个后缀名称设置的话）
 * @return string 组装后的URL
 */
function url($str, $para = array(), $suffixed = true, $domain = false, $suffix_idx = 0) {
    static $rules = array();
    //CLIENT_NAME已经走域名了，这里就不必要再加入
    if (defined('CLIENT_NAME')) {
        $para['client'] = constant('CLIENT_NAME');
    }
    //如果链接中指定了version且与配置文件中的当前version不相同，则在链接中追加一下
    $client       = C('CLIENT');
    if (defined('CLIENT_NAME') && defined('CLIENT_VERSION') && constant('CLIENT_VERSION') != $client[constant('CLIENT_NAME')]['version']) {
        $para['version'] = constant('CLIENT_VERSION');
        if (CLIENT_NAME === 'ios') {
              for($i = 26; $i < 40; $i++){
                if (isset($_GET['P' . $i])) {
                    $para['P' . $i] = $_GET['P' . $i];
                }
            }
        }
    }
    if (isset($para['fu'])) {
        if (strpos($para['fu'], 'new2old_login') !== false) {
            $para['fu'] = '/';
        }
        if ($para['fu']) {
            if ($para['fu'] != 'webview' && substr($para['fu'], 0, 4) !== 'http' && defined('ROOT_URL')) {
                $para['fu'] = ROOT_URL . $para['fu'];
            }
            if (defined('CLIENT_NAME') && constant('CLIENT_NAME') !== 'html5') {

            } else {
                cookie('fu', $para['fu']);
            }
            //$para['fu'] = base64_encode($para['fu']);
        } else {
            unset($para['fu']);
        }
    }
    //如果是带有ajax的请求，那么就定义一下扩展名
    if (strpos(strtolower($str), 'ajax') !== false) {
        $suffixed = 'do';
    }
    //修正一下有可能会没有设置的sex_flag
    if (defined('CLIENT_NAME') && constant('CLIENT_NAME') == 'html5' && (!isset($para['sex_flag']) || !trim($para['sex_flag']))){
        $sexflag = cookie('sex_flag');
        if($sexflag && in_array($sexflag, array('nan', 'nv')) && $suffixed!=='do') {
            //$para['sex_flag'] = $sexflag;
        }
    }
    if (C('URL_MODEL') == 0) {
        return str_replace('.html.html', '.html', U($str, $para, $suffixed, $domain));
    }
    $args = func_get_args();
    $argc = count($args);
    if ($argc > 1) {
        if (!is_array($para)) {
            $suffix_idx = $para;
            $para       = array();
        }
        if (is_numeric($suffixed)) {
            $suffixed   = true;
            $suffix_idx = $suffixed;
        }
    }
    //if(isInWechat()){
    //    $para['____t'] = NOW_TIME;
    //}
    /**
     * 对路由规则进行缓存
     */
    if (!$rules) {
        //if (C('URL_ROUTER_ON')) {
        $rules = C('URL_ROUTE_RULES');
        //}
        if (!$rules) {
            $rules = array();
        }
        foreach ($rules as $k => $v) {
            $rule = array();
            if(is_array($v)) {
                $vv = explode('/', $v[0]);
            } else {
                $vv   = explode('/', $v);
            }
            $vend = array_pop($vv);
            $vv[] = strtolower($vend);

            $rule['rule'] = implode('/', $vv);
            $rule['url']  = $k;
            $match        = array();
            preg_match_all('@:([a-zA-Z]+)@i', $k, $match);
            if ($match) {
                foreach ($match[1] as $v) {
                    $rule['keys'][$v] = $v;
                }
            }
            $rules[$k] = $rule;
        }
    }
    //if (C('URL_ROUTER_ON')) {             //MOB里会屏蔽系统路由的开启，这里忽略一下
    $extra = '';

    $info = parse_url($str);
    $url  = !empty($info['path']) ? $info['path'] : ACTION_NAME;
    if (isset($info['fragment'])) { // 解析锚点
        $anchor = $info['fragment'];
        if (false !== strpos($anchor, '?')) { // 解析参数
            list($anchor, $info['query']) = explode('?', $anchor, 2);
        }
        if (false !== strpos($anchor, '@')) { // 解析域名
            list($anchor, $host) = explode('@', $anchor, 2);
        }
    } elseif (false !== strpos($url, '@')) { // 解析域名
        list($url, $host) = explode('@', $info['path'], 2);
    }
    // 解析子域名
    if (isset($host)) {     //已经在请求中加入了域名
        if (strtoupper($host) === $host) {      //输入的是全大写字母则从配置文件中去查找
            $domain = C($host);
        } else {
            $domain = $host . (strpos($host, '.') ? '' : strstr($_SERVER['HTTP_HOST'], '.'));
        }
    } elseif ($domain === true) {
        $domain = $_SERVER['HTTP_HOST'];
        if (C('APP_SUB_DOMAIN_DEPLOY')) { // 开启子域名部署
            $domain = $domain == 'localhost' ? 'localhost' : 'www' . strstr($_SERVER['HTTP_HOST'], '.');
            // '子域名'=>array('模块[/控制器]');
            foreach (C('APP_SUB_DOMAIN_RULES') as $key => $rule) {
                $rule = is_array($rule) ? $rule[0] : $rule;
                if (false === strpos($key, '*') && 0 === strpos($url, $rule)) {
                    $domain = $key . strstr($domain, '.'); // 生成对应子域名
                    $url    = substr_replace($url, '', 0, strlen($rule));
                    break;
                }
            }
        }
    }
    /**
     * 判断扩展名
     * 如果$suffixed为true，则取系统设置的扩展名，否则将$suffixed作为扩展名来处理。
     */
    if ($suffixed) {
        $suffix = $suffixed === true ? C('URL_HTML_SUFFIX') : $suffixed;
        if ($pos    = strpos($suffix, '|')) {
            if ($suffix_idx) {
                //取第几个
                $suffix = explode('|', $suffix);
                if (isset($suffix[$suffix_idx])) {
                    $suffix = $suffix[$suffix_idx];
                } else {
                    $suffix = current($suffix);
                }
            } else {
                $suffix = substr($suffix, 0, $pos);
            }
        }
        if ($suffix && '/' != substr($url, -1)) {
            $suffix = '.' . ltrim($suffix, '.');
        }
    } else {
        $suffix = '';
    }

    // 解析参数
    if (is_string($para)) { // aaa=1&bbb=2 转换成数组
        parse_str($para, $para);
    } elseif (!is_array($para)) {
        $para = array();
    }
    if (isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'], $params);
        $para = array_merge($params, $para);
    }

    $sstr  = $url;
    //将传递过来的str转换成ThinkPHP的  模块/控制器/动作
    $param = explode('/', $sstr);
    if (count($param) == 1) {
        $urls = array(
            MODULE_NAME, CONTROLLER_NAME, strtolower($param[0])
        );
    } else if (count($param) == 2) {
        $urls = array(
            MODULE_NAME, ucfirst($param[0]), strtolower($param[1])
        );
    } else {
        $param[0] = ucfirst($param[0]);
        $param[1] = ucfirst($param[1]);
        $param[2] = strtolower($param[2]);
        $urls     = $param;
    }
    if ($urls[1] == 'User' || $urls[1] == 'Userajax' || $urls[1] == 'Third') {
        //要确保这三个控制器不会在Client中使用！
        $suffixed = $suffix   = '.do';
        $urls[0] = 'Usercenter';
    } else{
        $urls[0] = 'Client';
    }

    //取子域名设置
    $root_url = '';
    if (C('APP_SUB_DOMAIN_DEPLOY') && C('APP_SUB_DOMAIN_RULES')) {  //这个级别更高？
        $subs   = C('APP_SUB_DOMAIN_RULES');
        if ($finded = array_search($urls[0] . '/' . $urls[1], $subs)) {
            $root_url = $finded;
        } else if ($finded = array_search($urls[0], $subs)) {
            $root_url = $finded;
        }
    }
    if (!$root_url && $domain) {
        $root_url = $domain;
    } else if (!$root_url) {
        if($urls[0] == 'Usercenter') {
            $root_url = C('USERDOMAIN');
        } else if ($urls[0] == 'Client') {
            $root_url = C('CLIENT.' . CLIENT_NAME . '.domain');
        } else {
            $root_url = __ROOT__;
        }
    }

    if ($root_url) {
        $domains = C('SITE_DOMAINS');
        if (isset($domains[$root_url])) {
            $_set     = $domains[$root_url];
            $root_url = $_set['type'] . '://' . $root_url;
            if ($_set['port']) {
                $root_url.=':' . $_set['port'];
            }
        } else {
            if (substr($root_url, 0, 4) !== 'http') {
                $root_url = 'http://' . $root_url;
            }
        }
    }

    if (!$root_url) {
        if($urls[0] == 'Usercenter') {
            $root_url = C('USERDOMAIN');
        } else if ($urls[0] == 'Client') {
            $root_url = C('CLIENT.' . CLIENT_NAME . '.domain');
        } else {
            $root_url = __ROOT__;
        }
    }

    //$root_url .= __APP__;
    $extra = $anchor ? '#' . $anchor : '';

    $sstr    = implode('/', $urls);
    $nomatch = false;
    //echo "\r\nFIND:" . $sstr . "\r\n";
    foreach ($rules as $ss => $v) {
        //echo '匹配:' . $v['rule'] . "\r\n";
        if ($sstr && $v['rule'] === $sstr) {
            //echo '匹配成功！' . "\r\n";
            if ($v['keys'] && $para) {
                $url  = $ss . '/';
                $add  = '';
                $tmp1 = $para;
                $tmp2 = $v['keys'];
                foreach ($v['keys'] as $k => $v) {
                    if (isset($tmp1[$k])) {
                        $url = str_replace(':' . $k . '/', $tmp1[$k] . '/', $url);
                        unset($tmp1[$k]);
                    } else {
                        $nomatch = true;
                        break;
                    }
                }
                if (!$nomatch) {
                    if (substr($url, -1) === '/') {
                        $url = substr($url, 0, -1);
                    }
                    $url .= $suffix;
                    if ($tmp1) {
                        $url .= '?';
                        foreach ($tmp1 as $k => $v) {
                            $url .= $k . '=' . urlencode($v) . '&';
                        }
                        $url = substr($url, 0, - 1);
                    }
                    return $root_url . '/' . $url . $extra;
                }
            } else if (!$v['keys']) {
                $url = $ss;
                if ($para) {
                    $url .= $suffix . '?';
                    foreach ($para as $k => $v) {
                        $url .= $k . '=' . urlencode($v) . '&';
                    }
                    $url = substr($url, 0, - 1);
                }
                if (substr($url, -1) === '/') {
                    $url = substr($url, 0, -1);
                }
                if (!$para) {
                    $url .= $suffix;
                }
                return $root_url . '/' . $url . $extra;
            }
        }
    }
    //echo 'not matched';
    if($root_url && $root_url!=ROOT_URL) {
        if(count(explode('/', $str))==2) {
            $str = $urls[0].'/'.$str;
        }
        $str .= '@'.str_replace(array('http://', 'https://'), '', $root_url);
    }
    return str_replace('.html.html', '.html', U($str, $para, $suffixed, $domain));
}

/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */
if (!function_exists('array_column')) {

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null) {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc   = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput     = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey  = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key      = $value    = null;
            $keySet   = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key    = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value    = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value    = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }

}
/**
 * 获取支付设置（名称，兑换比例）
 * @param type $payname
 * @param type $type
 */
function getPayConfig($payname, $type = 'scale') {
    //容错名称，
    $alias   = array(
        'WECHATPAY'    => 'WEIXINPAY', 
        'ALIAPY_WAP'   => 'ALIPAY_WAP', //应该是测试的时候写错
        'ALIPAY_SDK'   => 'ALIPAY_WAP',
        'JIUYOU-NET'   => 'JIUYOU', // 2012-05-03 10:49:23最后一条支付记录，以后(2012-05-21 17:50:17)全是JIUYOU
        'NETEASE-NET'  => 'NETEASE', // 2012-05-03 22:22:48最后一条支付记录，以后(2012-05-04 12:07:48)全是NETEASE
        'SNDACARD-NET' => 'SNDACARD', // 2012-05-03 16:40:43最后一条支付记录，以后(2012-05-06 23:09:03)全是SNDCARD
        'SOHU-NET'     => 'SOHU', // 2012-05-03 10:53:06最后一条支付记录，以后(2012-05-08 13:10:35)全是SOHU
        'WANMEI-NET'   => 'WANMEI', // 2012-04-17 22:30:06最后一条支付记录，以后(2012-05-08 13:02:31)全是WANMEI
        'ZHENGTU-NET'  => 'ZHENGTU'// 2012-05-03 10:50:30最后一条支付记录，以后(2012-05-28 16:56:22)全是ZHENGTU
    );
    $key     = 'PAY_LISTS';
    $payname = strtoupper($payname);
    if (isset($alias[$payname])) {
        $payname = $alias[$payname];
    }
    $type  = strtoupper($type);
    $alias = '';
    if (substr($payname, -4) === '-NET') {
        $alias = substr($payname, 0, -4);
    }
    $type = strtoupper($type);
    if (C($key . '.' . $payname)) {
        $key .= '.' . $payname;
    } else if ($alias && C($key . '.' . $alias)) {
        $key .= '.' . $alias;
    } else {
        //支付方式错误
        E('你选择的支付方式' . $payname . '暂时不可用，请换一个支付方式或者稍候再试。');
    }
    if ($type === 'SCALE' || $type === 'NAME') {
        $key .= '.' . $type;
    }
    return C($key);
}

/**
 * 检测当前是否在微信中打开
 * @return boolean
 */
function isInWechat() {
    $ua = I('server.HTTP_USER_AGENT');
    return strpos($ua, 'MicroMessenger') !== false;
}

/**
 * 将字符串格式化为HTML段落格式
 *
 * @param string $string 要格式化的字符串
 * @param int $line_breaks 默认为1，为1时，为nl2br的增强版，为2时，单行换行将被转换为HTML换行标签。
 * @param boolean $xml 默认为true，为true时，将使用自闭合的换行标签（<br />）。
 * @return string 格式化后的字符串
 */
function nl2p($string, $line_breaks = 1, $xml = true) {
    // 清除字符串中已经存在的换行、分段标签
    $string = trim(str_replace(array('<p>', '</p>', '<br>', '<br />', "\r"), '', $string));

    if ($line_breaks == 1) {
        return preg_replace("/([\n])/i", '<br' . ($xml == true ? ' /' : '') . '>', $string);
    } else if ($line_breaks == 2) {
        return '<p>' . preg_replace(array("/([\n]{2,})/i", "/\n/i"), array("</p><p>", '<br' . ($xml == true ? ' /' : '') . '>'), $string) . '</p>';
    } else {
        return '<p>' . preg_replace("/([\n]{1,})/i", "</p><p>", $string) . '</p>';
    }
}

/**
 * 友好的时间显示
 *
 * @param int $sTime
 *            待显示的时间
 * @param string $type
 *            类型. normal | mohu | full | ymd | other
 * @return string
 */
function friendly_date($sTime, $type = 'normal') {
    if (!$sTime) {
        return '';
    }
    // sTime=源时间，cTime=当前时间，dTime=时间差
    if (strlen(intval($sTime)) != strlen($sTime)) {
        $sTime = strtotime($sTime);
    }
    $cTime  = NOW_TIME;
    $dTime  = $cTime - $sTime;       //时间差（秒）
    $dYear  = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
    $dDay   = $dMonth = -1;
    if ($dYear === 0) {
        $dDay   = intval(date("z", $cTime)) - intval(date("z", $sTime));  //时间差，天
        $dMonth = intval(date('m', $cTime)) - intval(date('m', $sTime));
    }
    // normal：n秒前，n分钟前，n小时前，日期
    if ($type == 'full') {
        return date("Y-m-d , H:i:s", $sTime);
    } elseif ($type == 'ymd') {
        return date("Y-m-d", $sTime);
    } elseif ($type == 'md') {
        return date("m-d", $sTime);
    } else {
        if ($dYear === 0) {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '刚刚';
                } else {
                    return intval(floor($dTime / 10) * 10) . "秒前";
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dYear == 0 && $dDay == 0) {
                return '今天' . date('H:i', $sTime);
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . "天前";
            } elseif ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } else {
                return $dMonth . '个月前';
            }
        } else {
            return $dYear . '年前';
        }
    }
}

/**
 * QQ防盗
 */
function __shutDwonApp() {
    static $isRunned = false;
    if($isRunned) {
        return ;
    }
    $isRunned = true;
    $url = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);
    $config = C('QQ_FANGDAO');
    if (!$config) {
        //有可能缓存中没有
        C(load_config(COMMON_PATH . '/Conf/qqfangdao.php'));
        $config = C('QQ_FANGDAO');
    }
    $config = array_change_key_case($config, CASE_LOWER);
    if (isset($config[$url])) {
        $set = $config[$url];
        if(isset($set['__multi__'])) {
            foreach($set['__multi__'] as $subset) {
                if(__checkRule($subset)) {
                    //这里进入你的防盗代码
                    $fangdaoModel = new \HS\Qqfangdao();
                    $res = $fangdaoModel->postDyData($_SERVER['__uid__']);
//                     $data['uid'] = $_SERVER['__uid__'];
//                     $data['fangdaoReturn'] = $res;
//                     \Think\Log::write(print_r($data, 1), 'INFO', '', LOG_PATH . 'QQ_FANGDAO');
                }
            }
        } else {
            if(__checkRule($set)) {
                //这里进入你的防盗代码
                $fangdaoModel = new \HS\Qqfangdao();
                $res = $fangdaoModel->postDyData($_SERVER['__uid__']);
//                 $data['uid'] = $_SERVER['__uid__']; 
//                 $data['fangdaoReturn'] = $res;
//                 \Think\Log::write(print_r($data, 1), 'INFO', '', LOG_PATH . 'QQ_FANGDAO');
            }
        }
    }
    
}
/**
 * QQ防盗
 */
function __checkRule($set = array()) {
    if (isset($set['method']) && $method = strtoupper($set['method'])) {
        if (in_array($method, array('GET', 'POST', 'AJAX'))) {
            if (!defined('IS_' . $method) || !constant('IS_' . $method)) {
                return false;
            }
        }
    }

    if (isset($set['param']) && $param = $set['param']) {
        if (is_array($param)) {
            //参数列表
            foreach ($param as $key => $var) {
                if (is_array($var)) {
                    if (!in_array(I($key), $var)) {
                        return false;
                    }
                } else if (I($key) != $var) {
                    return false;
                }
            }
        } else {
            //只需要检测一个参数，存在即可
            if (is_null(I($param, null))) {
                return false;
            }
        }
    }
    if(isset($set['session']) && $set['session'] === 'start') {
        session('[start]');
        $uid = intval(session('uid'));
    } else {
        $uid = cookie('uid');
    }
    $_SERVER['__uid__'] = intval($uid);
    return true;
}
