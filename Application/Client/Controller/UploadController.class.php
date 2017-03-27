<?php
/**
 * yqm个人中心用户头像上传
 *
 * @dingzi 2016-07-21 14:48
 */

namespace Client\Controller;

use Client\Common\Controller;

class UploadController extends Controller {
    public function _initialize() {
        parent::_initialize();
        noCachePage();
    }

    /**
     * 裁剪之前图片上传
     *
     * @param int $uid 用户id
     * @param int $timestamp 时间戳
     * @param string $token 表单令牌
     */
    public function uploadAction() {
        $_uid = I('post.uid', '0', 'intval');
        if (!$_uid) {
            exit(0);
        }
        $timestamp   = I('post.timestamp', '', 'intval');
        $token       = I('post.token', '');
        $uid         = $_uid;
        $verifyToken = md5($uid . 'unique_salt' . $timestamp);
        if ($token != $verifyToken) {
            echoJson(C("MESSAGES")['tokenerror']);
            exit(0);
        }
        $uid  = abs(intval($uid)); //UID取整数绝对值
        $uid  = sprintf("%09d", $uid); //前边加0补齐9位，例如UID为31的用户变成 000000031
        $dir1 = substr($uid, 0, 3); //取左边3位，即 000
        $dir2 = substr($uid, 3, 2); //取4-5位，即00
        $dir3 = substr($uid, 5, 2); //取6-7位，即00

        $randnum = randomstr(8);
        $config  = array(
            'maxSize'  => 2048 * 1024,
            'rootPath' => C('AVATAR_ROOT'),
            'savePath' => "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/",
            'saveName' => substr($uid, -2) . "_avatar_default" . $randnum,
            'exts'     => C('IMGEXT'),
            'autoSub'  => false,
            'saveExt'  => 'jpg',
            'subName'  => array('date', 'Ym'),
        );
        // var_dump($config);
        if (!is_dir($config['rootPath'] . $config['savePath'])) {

            $res = mkdir($config['rootPath'] . $config['savePath'], 0777, true);
            if ($res) {
                // echo "目录 $path 创建成功";
            } else {
                // echo "目录 $path 创建失败";
            }
        }
        $hd        = opendir($config['rootPath'] . $config['savePath']);
        $file_preg = "/" . substr($uid, -2) . "_avatar_default(.+)\.jpg$/";
        while ($filename  = readdir($hd)) {
            if (preg_match($file_preg, $filename)) {
                @unlink($config['rootPath'] . $config['savePath'] . $filename);
            }
        }
        $upload = new \Think\Upload($config); // 实例化上传类
        $info   = $upload->uploadOne($_FILES['Filedata']);
        if (!$info) {
            echo $upload->error;
            exit;
        }
        unset($upload);
        $info['uid'] = $_uid;
        $info['url'] = C('AVATAR_URL') . $config['savePath'] . $config['saveName'] . ".jpg";
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        echoJson($info);
    }

    /**
     * 编辑之后上传图片
     *
     * @param int $timestamp 时间戳
     */
    public function imgcropAction() {
        $this->check_user_login();
        $picname = I("get.picname", "", "trim");
        $uid = isLogin();
        if (!$uid) {
            $this->ajaxReturn(array("status" => 0, "message" => "请先登录"));
        }

        //判断文件名是否合法
        $file_preg = "/" . substr($uid, -2) . "_avatar_default(.+)\.jpg$/";
        if (!preg_match($file_preg, $picname)) {
            $this->ajaxReturn(array("status" => 0, "message" => "参数错误"));
        }
        $longuid = sprintf("%09d", $uid); //前边加0补齐9位，例如UID为31的用户变成 000000031
        $dir1    = substr($longuid, 0, 3); //取左边3位，即 000
        $dir2    = substr($longuid, 3, 2); //取4-5位，即00
        $dir3    = substr($longuid, 5, 2); //取6-7位，即00

        $imgroot      = C('AVATAR_ROOT') . "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/" . substr($longuid, -2);
        $imgpath      = C('AVATAR_ROOT') . "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/" . $picname;
        $imgtemppath  = $imgroot . "_avatar_temp.jpg";
        $targetlarge  = $imgroot . "_avatar_large.jpg";
        $targetbig    = $imgroot . "_avatar_big.jpg";
        $targetmiddle = $imgroot . "_avatar_middle.jpg";
        $targetsmall  = $imgroot . "_avatar_small.jpg";

        $imgY1 = I("post.imgY1", '', 'intval');
        $imgX1 = I("post.imgX1", '', 'intval');

        $imgW = I("post.imgW", '', 'floatval');
        $imgH = I("post.imgH", '', 'floatval');

        try {
            $image = new \Think\Image();

            $image->open($imgpath);
            $image->thumb($imgW, $imgH)->save($imgtemppath);

            $image->open($imgtemppath);

            if (file_exists($targetbig)) {
                unlink($targetbig);
            }
            $image->crop(200, 200, $imgX1, $imgY1)->save($targetbig);
            $image->open($targetbig);


            if (file_exists($targetlarge)) {
                unlink($targetlarge);
            }

            $image->thumb(120, 120)->save($targetlarge);


            if (file_exists($targetmiddle)) {
                unlink($targetmiddle);
            }
            $image->thumb(48, 48)->save($targetmiddle);
            $image->open($targetmiddle);

            if (file_exists($targetsmall)) {
                unlink($targetsmall);
            }
            $image->thumb(32, 32)->save($targetsmall);

            unset($image);
            unlink($imgtemppath);
            unlink($imgpath);
            $response = Array(
                "status"  => 1,
                "message" => 'success',
                "url"     => getUserFaceUrl($uid, 'big'),
                "uid"     => $uid,
            );
        }
        catch (Exception $e) {
            $response = Array(
                "status"  => 0,
                "message" => $e->getMessage()
            );
        }
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        echoJson($response);
    }
    
    /**
     * IOS上传头像
     * 
     */
    public function uploadUserFaceAction(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $byte = $_POST['pic'];
        //处理一下数据流
        $byte = str_replace(' ', '', $byte);
        $byte = str_ireplace("<", "", $byte);
        $byte = str_ireplace(">", "", $byte);
        if(!$byte){
            $output['message'] = '请选择要上传的图片';
            $this->ajaxReturn($output);
        }
        $this->check_user_login();
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录!';
            $this->ajaxReturn($output);
        }
        //将数据流转换成二进制
        $byte = pack("H*",$byte);
        //计算保存路径
        $uid  = abs(intval($uid)); //UID取整数绝对值
        $uid  = sprintf("%09d", $uid); //前边加0补齐9位，例如UID为31的用户变成 000000031
        $dir1 = substr($uid, 0, 3); //取左边3位，即 000
        $dir2 = substr($uid, 3, 2); //取4-5位，即00
        $dir3 = substr($uid, 5, 2); //取6-7位，即00
        //上传配置
        $config  = array(
            'rootPath' => C('AVATAR_ROOT'),
            'savePath' => "/" . $dir1 . "/" . $dir2 . "/" . $dir3 . "/",
            'saveName' => substr($uid, -2) . "_avatar_temp.jpg",
        );
        //创建文件夹
        if (!is_dir($config['rootPath'] . $config['savePath'])) {
            $res = mkdir($config['rootPath'] . $config['savePath'], 0777, true);
        }
        if (file_exists($config['rootPath'].$config['savePath'].$config['saveName'])) {
            @unlink($config['rootPath'] . $config['savePath'] . $config['saveName']);
        }
        //写入文件
        $num = file_put_contents($config['rootPath'].$config['savePath'].$config['saveName'], $byte);
        if($num === false){
            $output['message'] = '对不起，上传失败，请稍后再试';
            $this->ajaxReturn($output);
        }
        //制作各尺寸头像
        $imgroot      = $config['rootPath'] . $config['savePath'] . substr($uid, -2);
        $imgpath      = $config['rootPath'] . $config['savePath'] .$config['saveName'];
        $targetlarge  = $imgroot . "_avatar_large.jpg"; //120*120
        $targetbig    = $imgroot . "_avatar_big.jpg";   //200*200
        $targetmiddle = $imgroot . "_avatar_middle.jpg";// 48*48
        $targetsmall  = $imgroot . "_avatar_small.jpg"; //32*32
        try{
            $image = new \Think\Image();
            $image->open($imgpath);
            
            //big
            if (file_exists($targetbig)) {
                @unlink($targetbig);
            }
//             $image->crop(200, 200, 0, 0)->save($targetbig);
            $image->thumb(200, 200)->save($targetbig);
            $image->open($targetbig);
            
            //large
            if (file_exists($targetlarge)) {
                @unlink($targetlarge);
            }
            $image->thumb(120, 120)->save($targetlarge);
            
            //middle
            if (file_exists($targetmiddle)) {
                @unlink($targetmiddle);
            }
            $image->thumb(48, 48)->save($targetmiddle);
            $image->open($targetmiddle);
            
            //small
            if (file_exists($targetsmall)) {
                @unlink($targetsmall);
            }
            $image->thumb(32, 32)->save($targetsmall);
            
            unset($image);
            //删除临时图片
            @unlink($imgpath);
            $output = Array(
                "status"  => 1,
                "message" => 'success',
                "url"     => getUserFaceUrl($uid, 'big').'?t='.NOW_TIME,
                );
        }catch(Exception $e){
            $output['message'] = '对不起，上传头像失败，请稍后再试';
        }
        $this->ajaxReturn($output);
    }
}
