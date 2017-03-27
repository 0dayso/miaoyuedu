<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: HelpController.class.php 1073 2016-09-08 09:35:25Z dingzi $
 */

namespace Client\Controller;

use Client\Common\Controller;

class StatichelpController extends Controller{

    /**
	 * 帮助列表（帮助一级页面）
	 * @return string 帮助问题列表
	 * */
	public function _index() {
	    $this->pageTitle = "帮助中心";
        
	    $this->display();
	}
    /**
     * 
     */
	public function aboutAction(){
	    $version = I('P27','','trim');
	    
	    $this->assign('version',$version);
	    $this->display();
	}

	/**
	 * 帮助二级页面
	 *
	 * @param int $helpid 帮助id
	 *
	 * @return string 帮助问题及解决方案
	 * */
	public function ArticleAction(){
	    
	    $this->display();
	}
	/**
	 * 关于我们(index)、联系我们(lxwm)、如何投稿(tgsm)、版权声明(bqsm)
	 */
	public function aboutHelpAction(){
	   
	   $this->display();
	}







}