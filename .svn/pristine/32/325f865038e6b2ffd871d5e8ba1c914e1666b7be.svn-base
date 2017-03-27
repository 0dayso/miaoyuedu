<?php
namespace HS;

class Tree {

    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    var $arr = array();

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    var $icon = array('│', '├', '└', '┬', '─');

    /**
     * @access private
     */
    var $ret = array();
    var $pid = 'parentid';
    var $myid = 'id';

    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
     *      )
     */
    function __construct($arr = array(), $id = '', $pid = '') {
        if ($id) {
            $this->myid = $id;
        }
        if ($pid){
            $this->pid = $pid;
        }
        if ($arr) {
            foreach ($arr as $v) {
                $id = $v[$this->myid];
                $this->arr[$id] = $v;
            }
        }
        $this->ret = '';
    }

    function buildTree($root = 0) {
        $tree = array();
        if ($ls = $this->get_child($root)) {
            foreach ($ls as $key => $v) {
                $sub = array();
                if ($this->get_child($v[$this->myid])) {
                    $sub = $this->buildTree($v[$this->myid]);
                }
                if ($sub) {
                    $v['sub'] = $sub;
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 得到父级数组
     * @param int
     * @return array
     */
    function get_parent($myid) {
        $newarr = array();
        $parentid = $this->pid;
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $pid = $this->arr[$myid][$parentid];
        $pid = $this->arr[$pid][$parentid];
        if (is_array($this->arr)) {
            foreach ($this->arr as $a) {
                $id = $a[$this->myid];
                if ($a[$parentid] == $pid) {
                    $newarr[$id] = $a;
                }
            }
        }
        return $newarr;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    function get_child($myid = 0) {
        $upid = $this->pid;
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $a) {
                $id = $a[$this->myid];
                if ($a[$upid] == $myid) {
                    $newarr[$id] = $a;
                }
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 得到当前位置数组
     * @param int
     * @return array
     */
    function get_pos($myid, &$newarr) {
        $parentid = $this->pid;
        $a = array();
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $newarr[] = $this->arr[$myid];
        $pid = $this->arr[$myid][$parentid];
        if (isset($this->arr[$pid])) {
            $this->get_pos($pid, $newarr);
        }
        if (is_array($newarr)) {
            krsort($newarr);
            foreach ($newarr as $v) {
                $a[$v[$this->myid]] = $v;
            }
        }
        return $a;
    }

    /**
     *  得到树型结构
     * @param $myid 表示获得这个ID下的所有子级
     * @param $str 生成树形结构基本代码, 例如: "<option value=$id $select>$spacer$name</option>"
     * @param $sid 被选中的ID, 比如在做树形下拉框的时候需要用到
     * @param $adds
     * @param $str_group
     * @return unknown_type
     */
    function getTree($myid = 0, $str = '', $sid = 0, $adds = '', $str_group = '', $level = 1) {
        if ($str) {
            $this->ret = '';
        }
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $a) {
                $id = $a[$this->myid];
                $j = $k = '';
                if ($number == $total) {
                    if ($number == 1) {
                        if ($level != 1)
                            $j .= $this->icon[2];
                    } else {
                        $j .= $this->icon[2];
                    }
                } else {
                    $j .= $this->icon[1];
                    $k = $this->icon[0];
                }
                if ($this->get_child($a[$this->myid])) {
                    $j .= $this->icon[3];
                    $xx = $adds . $k . '　';
                } else {
                    $j .= $this->icon[4];
                    $xx = $adds . $k . '　';
                }
                if ($number == $total && $level == 1 && $number == 1) {
                    $xx = substr($xx, 0, strlen($xx) - 12);
                }
                $spacer = $adds ? $adds . $j : $j;
                $spacer = str_replace($this->icon[1] . $this->icon[3], $this->icon[1] . $this->icon[4] . $this->icon[3], $spacer);
                //$spacer = str_replace($this->icon[0].'&nbsp;&nbsp;'.$this->icon[1], $this->icon[0].$this->icon[1], $spacer);
                //$spacer = str_replace($this->icon[0].'&nbsp;&nbsp;'.$this->icon[2], $this->icon[0].$this->icon[2], $spacer);
                if ($str) {
                    $selected = $id == $sid ? 'selected' : '';
                    @extract($a);
                    //$parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                    //$this->ret .= $nstr;
                    if($parentid == 0 && $str_group){
                        $this->ret .= $str_group;
                    } else {
                        $this->ret .= $str;
                    }
                } else {
                    $a['pre'] = $spacer;
                    $this->ret[$id] = $a;
                }
                $level1 = $level + 1;
                $this->getTree($id, $str, $sid, $xx, $str_group, $level1);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 同上一方法类似,但允许多选
     */
    function getFullTree() {
        $arr = array();
        foreach ($this->arr as $v) {
            $id = $v[$this->myid];
            $arr[] = $v;
            if (!$this->get_parent($id)) {
                $r = $this->getTree($id);
                if ($r) {
                    $arr = array_merge($arr, $this->getTree($id));
                }
            }
        }
        return $arr;
    }

    function have($list, $item) {
        return(strpos(',,' . $list . ',', ',' . $item . ','));
    }

}
