<?php 
namespace Home\Controller;

class FileUploadController extends ComController()
{
	public function img_upload($savepath){
		$upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
	    $upload->savePath  = $savepath;
	    $upload->saveName  = array('date','Y-m-d H:i:s').'-'.mt_rand();
	    $upload->autoSub = true;
		$upload->subName = array('date','Ymd');
	    // $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();

	    if(!$info) {// 上传错误提示错误信息
	        $this->ajaxReturn($this->error($upload->getError()));
	    }else{// 上传成功
	        $this->ajaxReturn($info);
	    }
	}
	
}