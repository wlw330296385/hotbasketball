<?php 
namespace Common\Model;

class ActivityModel extends Model(){

	//记录一个活动
	public function create_activity(){
		$this->startTrans();
		$data = $this->create();
		$result = $this->add();
		$participate_member = json_decode($data['participate_member']);
		$result1 = M('participate_activity')->addAll($participate_member);
		if($result1 && $result){
			$this->commit();
			return $result;
		}else{
			$this->callback();
			return false;
		}
	}


	//发布一个活动
	public function record_activity(){
		$data = $this->create();
		$result = $this->add();
		return $result;
	}
}