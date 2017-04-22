<?php 
namespace Common\Model;
use Think\Model;
class RegionModel extends Model {

    /**
     * 通过父级id查地区名字
     */
    protected function getRegionNameByPID($pid = 0){
        $result = M('region')->field('region_name')->where(['parent_id' => $pid])->select();
        return $result['region_name'];
    }

    /**
     * 通过id查地区名字
     */
    protected function getRegionNameByGID($gid = 0){
        $result = M('region')->field('region_name')->where(['region_id' => $gid])->select();
        return $result['region_name'];
    }

    /**
     * 通过拼音查地区名字
     */
    protected function getRegionNameByPY($py = 2){
        $result = M('region')->field('region_name')->where(['region_name_en' => $py,'_logic' => 'or','region_shortname_en'=>$py])->select();
        return $result['region_name'];
    }

}