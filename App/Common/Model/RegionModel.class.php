<?php 
namespace Common\Model;
use Think\Model;
class RegionModel extends Model {

    /**
     * 通过父级id查地区名字
     */
    public function getRegionNameByPID($pid = 1){
        $result = $this->field('region_name,region_id')->where(['parent_id' => $pid])->select();
        return $result;
    }

    /**
     * 通过id查地区名字
     */
    public function getRegionNameByGID($gid = 0){
        $result = $this->field('region_name,region_id')->where(['region_id' => $gid])->select();
        return $result;
    }

    /**
     * 通过拼音查地区名字
     */
    public function getRegionNameByPY($py = 2){
        $result = $this->field('region_name,region_id')->where(['region_name_en' => $py,'_logic' => 'or','region_shortname_en'=>$py])->select();
        return $result;
    }

    // 通过父级名字找下属地区名字
    public function getRegionNameByName($name){
        $region_id = $this->where(['region_name'=>$name])->getField('region_id');
        // echo $name;
        $result = $this->field('region_name,region_id')->where(['parent_id' => $region_id])->select();
        // echo $region_id;
        // echo $this->getLastSql();
        return $result;
    }
}