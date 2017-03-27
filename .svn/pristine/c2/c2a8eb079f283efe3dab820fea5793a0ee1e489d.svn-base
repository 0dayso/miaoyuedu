<?php

namespace HS;
/**
 * 模型扩展类
 */
class Model extends \Think\Model{
    static public function _getSubTable($bid) {
        return str_pad(floor($bid / C('CHPTABLESIZE')), 2, "0", STR_PAD_LEFT);
    }

    /**
     * 指定当前的数据表
     * @access public
     * @param mixed $table
     * @return Model
     */
    public function table($table) {
        $prefix =   $this->tablePrefix;
        if(is_array($table)) {
            $this->options['table'] =   $table;
        }elseif(!empty($table)) {
            //将__TABLE_NAME__替换成带前缀的表名
            $table  = preg_replace_callback("/__([A-Z0-9_-]+)__/sU", function($match) use($prefix){ return $prefix.strtolower($match[1]);}, $table);
            $this->options['table'] =   $table;
            $this->trueTableName = $table;
            $this->flush();
        }
        return $this;
    }

    /**
     * 得到完整的数据表名
     * @access public
     * @return string
     */
    public function getTableName() {
        if(empty($this->trueTableName)) {
            $tableName  = !empty($this->tablePrefix) ? $this->tablePrefix : '';
            if(!empty($this->tableName)) {
                $tableName .= $this->tableName;
            }else{
                $tableName .= parse_name($this->name);
            }
            $this->trueTableName    =   strtolower($tableName);
        }
        return (!empty($this->dbName)?$this->dbName.'.':'').$this->trueTableName;
    }


    //缓存指向memcache
    protected function initMemcache() {
        S(C('mcconfig'));
    }

    //缓存指向redis
    protected function initRedis() {
        S(C('rdconfig'));
    }

    /**
     * 定义函数
     *
     * 数组排序
     *
     * @param   array $arr      要排序的数组
     * @param   int   $begin    排序开始位置
     * @param   int   $end      排序结束位置
     * @param   int   $shownum  总显示个数
     * @param   int   $type     1：正序 2：倒叙 3：随机
     * @return  array
     */
    protected function listHandle($arr, $shownum, $begin = 0, $end = 9, $type = 1) {
        // //验证参数
        // if(!isset($arr) || ($end < $begin) || ($shownum < 1)){
        //     return;
        // }
        if (!is_numeric($shownum)) {
            $shownum = 10;
        }
        if ($end + 1 > $shownum) {
            $end = $shownum - 1;
        }
        if ($begin > $end) {
            $begin = $end;
        }
        if ($arr['isrollshow'] > 0 && $arr['rollshowtime'] > 0) {
            $timestamp = strtotime(date('Y-m-d'));
            $now = strtotime(date('Y-m-d H:i:s'));
            $count = (floor(($now - $timestamp) / $arr['rollshowtime'] * 1000) / 1000) % $shownum; //计算出移动几次
            //取出需要移动的数组并且拼接
            if ($end + 1 <= $shownum) {
                $arrtemp1 = array_slice($arr['booklists'], $begin, ($end - $begin + 1));
                $arrtemp2 = array_slice($arr['booklists'], $shownum, (count($arr['booklists']) - $shownum));
                //var_dump($arrtemp);
                $arrtemp = array_merge($arrtemp1, $arrtemp2);
                if ($type == 1) {

                }
                if ($type == 2) {
                    $arrtemp = array_reverse($arrtemp);
                } elseif ($type == 3) {
                    if (S('indexrandarr' . $count) && is_array(S('indexrandarr' . $count))) {
                        $arrtemp = S('indexrandarr' . $count);
                    } else {
                        shuffle($arrtemp);
                        S('indexrandarr' . $count, $arrtemp, $arr['rollshowtime'] + 1);
                    }
                }
                if ($type != 3) {
                    $x = 0;
                    while ($x <= ($count - 1)) {
                        //数组向前移动算法
                        for ($i = 0; $i < count($arrtemp); $i++) {
                            if ($i == 0) {
                                $temp = $arrtemp[0];
                                $arrtemp[$i] = $arrtemp[$i + 1];
                            } elseif ($i == (count($arrtemp) - 1)) {
                                $arrtemp[count($arrtemp) - 1] = $temp;
                            } else {
                                $arrtemp[$i] = $arrtemp[$i + 1];
                            }
                        }
                        $x++;
                    }
                }
                //合并最终的数组
                if ($begin != 0)
                    $beginarr = array_slice($arr['booklists'], 0, $begin);
                else
                    $beginarr = array();
                $middlearr = array_slice($arrtemp, 0, $end - $begin + 1);

                if ($end != ($shownum - 1))
                    $endarr = array_slice($arr['booklists'], $end + 1, $shownum - ($end + 1));
                else
                    $endarr = array();
                $resultarr = array_merge($beginarr, $middlearr, $endarr);
                return $resultarr;
            }
        }
        else {
            return array_slice($arr['booklists'], 0, $shownum);
        }
    }

}
