<?php

namespace Home\Controller;
class MemberController extends ComController()
{

	public function index(){
		$id = I('.id');
		$member = new \Common\Model\Member;
		// $result_1 = $member->get_member_info($id,1);
		// $result_2 = $member->get_member_info($id,2);
		// $result_3 = $member->get_member_info($id,3);
		// $result_4 = $member->get_member_info($id,4);
		$result = M('member_fans')->where(['id'=>$id])
				->join("member_coach on member_id = $id")
				->join("member_judge on member_id = $id")
				->select();		
		if(IS_POST){
			$this->ajaxReturn($result);

		}else{
			$this->assign('list',$result);
			$this->display();
		}
	}
	
}