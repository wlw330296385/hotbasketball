<?php 
namespace Home\Controller;
class ActivityController extends ComController()
{
	public function index(){
		$this->display();
	}

	/**
	 * 发起一个活动
	 */
	public function create_activity(){
		$member_id = session('mid');
		$teams = M('team_member') -> where(['member_id'=>$member_id])
				// ->where(['type'=>['in',[1,3,4,5,6]]])
				->getField('team,team_id');
				

	}


	public function get_team_member(){
		$team_id = ('.team_id');
		$team = new \Common\Model\Team;
		$team_member = $team->get_team_member($team_id);
		$this->ajaxReturn($team_member);
	}

	public function get_sign_member(){
		$team_id = I('.team_id');
		$participate_member = M('participate_activity')
				->where(['team_id'=>$team_id])
				->select();
	}

	/**
	 * 记录一个活动
	 */
	public function record_activity(){
		$activity_id = I('get.activity_id');
		//记录一个已发起的活动
		if($activity_id){
			
		}else{
			//记录一个未发起的活动

		}
	}


}