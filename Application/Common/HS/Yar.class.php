<?php

namespace HS;

class yar {

    private $_handler;
    private $_api = '';
    private $_error = array(
        'msg'  => '',
        'code' => 0,
        'type' => ''
    );
    private $_onerror = false;
    private $_last_method = '';

    public function __construct($fn = '', $onerror = false) {
        $this->_onerror = $onerror;
        $this->_api = $fn;
        if (substr($fn, -4) != '.php') {
            $fn .= '.php';
        }
        if (substr($fn, 0, 1) != '/') {
            $fn = '/' . $fn;
        }
        try {
            $this->_handler = new \Yar_Client(C('RPCURL') . $fn);
        } catch (\Exception $ex) {
            $this->processEx($ex);
        }
    }

    public function __call($name, $arguments) {
        $this->_last_method = $name;
        if (!$this->_handler) {
            //初始化的时候就已经出错了？
            $this->_error = array(
                'msg'  => '系统初始化错误',
                'code' => '500',
                'type' => 'System'
            );
            $this->saveLog();
            return false;
        }
        try {
            $content = ob_get_contents();
            ob_end_clean();
            //以下是为了防止接口直接输出了错误信息！
            ob_start();
            //$result  = call_user_method_array($name, $this->_handler, $arguments);
            $result = call_user_func_array(array($this->_handler, $name), $arguments);
            $cc = ob_get_contents();
            ob_end_clean();
            if ($result === false) {
                //$this->_error = array(        接口确实会返回false
                //    'code' => 500,
                //    'msg'  => '网络错误，请稍候再试',
                //    'type' => 'unknow'
                //);
                if ($cc) {
                    $this->_error['server_msg'] = $cc;
                    if (strpos($cc, 'MySQL Query Error')) {
                        $this->_error['code'] = '1000';
                    }
                    $this->saveLog();
                    return false;
                }
            }
            if ($content) {
                echo $content;
            }
        } catch (\Exception $ex) {
            $this->processEx($ex);
            return false;
        }
        //$this->_last_method = '';
        return $result;
    }

    private function processEx($ex) {
        $this->_error = array(
            'msg'         => $ex->getMessage(),
            'code'        => $ex->getCode(),
            'server_msg'  => $ex->getMessage(),
            'server_code' => $ex->getCode(),
        );
        if (method_exists($ex, 'gettype')) {
            $this->_error['type'] = $ex->getType();
        } else {
            $this->_error['type'] = 'unknow';
        }
        if ($this->_error['type'] == 'Yar_Exception_Client') {
            switch ($this->_error['server_code']) {
                case 16:
                    $regex = "@server responsed non\-\d+ code '(\d+)'@i";
                    $result = preg_match($regex, $this->_error['msg'], $match);
                    if ($result && $match[1]) {
                        $this->_error['code'] = $match[1];
                        $this->_error['msg'] = '网络错误，请稍候再试';
                    }
                    break;
                case 2:
                    $this->_error['code'] = 404;
                    $this->_error['msg'] = '网络请求出错，请稍后再试';
                    break;
            }
        } else if ($this->_error['type'] == 'Yar_Exception_Server') {
            switch ($this->_error['server_code']) {
                case 4:
                    $this->_error['code'] = 404;
                    $this->_error['msg'] = '网络请求出错，请稍候再试';
                    break;
            }
        }
        $this->saveLog();
    }

    public function getError() {
        if ($this->_error['msg']) {
            return $this->_error['msg'] . '(' . $this->_error['code'] . ')'; // . $this->_error['type'];
        } else if ($this->_error['code'] > 0) {
            return '未知错误，错误代码：' . $this->_error['code']; // . ' at ' . $this->_error['type'];
        }
        return false;
    }

    private function _msg($code = 0) {
        $result = array(
            '4'  => '指定的方法未定义',
            '16' => '接口地址不能解析', //curl
        );
    }

    public function saveLog() {
        //记录一下日志
        $info = array(
            'interface' => $this->_api,
            'method'    => $this->_last_method,
            'error'     => $this->_error,
            //'session'   => session(),
            //'rpc'       => C('RPCURL')
        );
        //\Think\Log::write(print_r($info, 1), 'ERROR', '', LOG_PATH . 'INTERFACE_QUERY');
        if ($this->_onerror) {
            if ($msg = $this->getError()) {
                _exit($msg);
            }
        }
    }

}
