<?php
namespace Common\Model;
use Think\Model;
class MediaModel extends Model{
    protected $trueTableName='media';
    protected static $_type = ['系统','上课','活动','比赛','录像','个人','评论'];
    protected static $mimes = ['image/jpeg','image/jpg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png'];
    protected static $exts  = ['jpeg','jpg','png','pjpeg','gif','bmp','x-png'];	
    protected static $suffix = ['图片','视频'];
    protected $savepath ;
    protected $rootpath = './public/';

    /**
     * 上传一张图片
     * $files 为上传的内容
     */
    public function save_files($files){
    	$this->savepath = CONTROLLER_NAME.'/'.date('Y')."/".date('m')."/";
    	 $option = [
            'mimes' => self::$mimes,
            'exts' => self::$exts,
            'rootPath' => $this->rootpath,
            'savePath' => $this->savepath,
            'subName'  =>  array('date', 'd'),
        ];
        $upload = new Upload($option);
        $info = $upload->upload($files);
        if(!$info) {// 上传错误提示错误信息
           return $upload->getError();
        }else{// 上传成功
            foreach ($info as $item) {
                $filePath[] = __ROOT__.$rootpath.$item['savepath'].$item['savename'];
            }
            $ImgStr = implode("|", $filePath);
            return $ImgStr;
        }
    }

    /**
     * 保存一张图片
     */
    public function save_media($data){
    	if(!$this->create($data)){
    		return $this->getError();
    	}else{
    		$result = $this->add($data);
    		return $result;
    	}
    }

    /**
     * 根据id获取图片
     */
    public function get_url_by_id($id = 1){
    	$result = $this->where(['id'=>$id])->find();
    	return $result;
    }

    /**
     * 根据类型获取图片
     * $type 类型
     * $parent_id 类型id
     */
    public function get_url_by_parent_id($parent_id,$_type,$limit = 0){
    	if($limit = 0){
    		$result = $this->where(['parent_id'=>$parent_id])
    			->where(['type'=>$type])
    			->select();
    		}else{
    			$result = $this->where(['parent_id'=>$parent_id])
    			->where(['type'=>$type])
    			->limit($limit)
    			->select();
    		}
    	return $result;
    }
}
    