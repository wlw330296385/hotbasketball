<?php 
namespace Home\Controller;
class MessageController extends ComController{

	public function index(){
		$member_id = session('mid');
		// 获取个人所有信息
		
		$message = M('message');
		$team_list = M('team_member')
					->where(['member_id'=>$member_id])
					// ->where(['type'=>['neq',0]])
					->getField('team_id',true);
		$str_team_list = implode(',', $team_list);

		$where['_string'] = "(receive_id IN (".$str_team_list.") AND receive_type = 1) OR (receive_id = ".$member_id." AND receive_type = 0)";

    	$result = $message
				->where($where)
				->select();
		$this->assign('message',$result);		
		$this->display();
	}
}