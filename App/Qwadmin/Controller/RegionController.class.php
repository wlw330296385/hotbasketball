<?php 
namespace Qwadmin\Controller;

class RegionController extends ComController{

	public function get_province($pid = 1){     

        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByPID($pid);
        if(IS_AJAX){
        	return $this->ajaxReturn($result);
        }else{
        	return $result;
        }
    }

    public function getRegionNameByPID($pid){
        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByPID($pid);
        if(IS_AJAX){
            return $this->ajaxReturn($result);
        }else{
            return $result;
        }
    }

    public function getRegionNameByGID($gid){
        $region = new \Common\Model\RegionModel;
        $result = $region->getRegionNameByGID($gid);
        if(IS_AJAX){
        	return $this->ajaxReturn($result);
        }else{
        	return $result;
        }
    }

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