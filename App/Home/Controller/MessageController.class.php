<?php 
namespace Home\Controller;
class MessageController extends ComController{

	/**
	 * 获取球队消息
	 */
	public function index(){
		$tid = I('tid');
		// 获取个人所有信息		
		$message = new \Common\Model\MessageModel;
		$result = $message->get_team_message_list($tid);
		dump($tid);
	}


	// 获取系统消息
	public function team_message(){
		$mid = session('mid');
		$sysMessage = new \Common\Model\SysmessageModel;
		$result =  $sysMessage->get_message_list($mid);
		dump($result);
	}
}