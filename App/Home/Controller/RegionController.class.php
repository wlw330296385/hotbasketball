<?php 
namespace Home\Controller;

class RegionController extends ComController{

    // 通过父级id找下属地区
	public function get_province($pid = 1){     

        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByPID($pid);
        if(IS_AJAX){
        	return $this->ajaxReturn($result);
        }else{
        	return $result;
        }
    }

    // 通过地区id找地名
    public function getRegionNameByGID($pid){
        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByGID($pid);
        if(IS_AJAX){
        	return $this->ajaxReturn($result);
        }else{
        	return $result;
        }
    }

    // 通过拼音找地名
    public function getRegionNameByPY($py = 2){
    	$region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByPY($py);
        if(IS_AJAX){
        	return $this->ajaxReturn($result);
        }else{
        	return $result;
        }
    }


    // 通过父级地名找下属地名
    public function getRegionNameByName($name){
        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByName($name);
        if(IS_AJAX){
            return $this->ajaxReturn($result);
        }else{
            return $result;
        }
    }
}