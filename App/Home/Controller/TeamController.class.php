<?php 
namespace Home\Controller;
class TeamController extends ComController
{
	// 球队首页
	public function index(){
		$mid = session('mid');
		$team = new \Common\Model\TeamModel;
		// 球队信息
		$tid = I('tid');
		if($tid){
			$the_team = $team->get_team_info($tid);
			$this->assign('the_team',$the_team);
			$is_join = M('team_member')
						->where(['team_id'=>$tid])
						->where(['member_id'=>$mid])
						->find();
			$this->assign('is_join',$is_join);
			// dump($is_join);die;
			// dump($the_team);die;
			$this->assign('the_team',$the_team);
		}else{							
			$this->redirect('team_list');
		}		
		$this->display();
	}

	// 获取所有球队列表
	public function team_list(){
		$mid = session('mid');
		$team = new \Common\Model\TeamModel;
		$teams = $team->get_team_list_by_mid($mid);	
		if($teams){
			$tips = '我的球队';
		}else{
			$tips = '你还没加入球队，请加入一个球队吧';
			$teams = M('team')->select();			
		}
		$this->assign('tips',$tips);
		$this->assign('teams',$teams);
		$this->display();
	}

	public function team_list_of_user(){

	} 

	// 申请加入球队
	public function apply(){
		$team_member = D('team_member');
		$data = $team_member->create();
		$data['member_id'] = session('mid');
		$data['member'] = session('en_name');
		$data['create_time'] = time();
		$is_apply = $team_member
					->where(['member_id'=>$data['member_id']])
					->where(['team_id'=>$data['team_id']])
					->where(['status'=>['NEQ',2]])
					->find();
		if($is_apply){
			$this->ajaxReturn(['code'=>0, 'msg'=>'请勿重复申请']);
		}
		//开启邀请码
		$is_invite = M('team')->where(['id'=>$data['team_id']])->getField('is_invite,invitation_code');
		if($is_invite['is_invite'] == 1){
			$invitation_code = I('.invitation_code');
			if($invitation_code != $is_invite['invitation_code']){
				$this->ajaxReturn(['code'=>0, 'msg'=>'邀请码不正确']);
			}
		}
		$result = $team_member->add($data);
		if($result){
			$content = [
				'title'			=>'入队申请',
				'status'		=>0,
				'send_id'		=>$data['member_id'],
				'send_name'		=>$data['member'],
				'receive_id'	=>$data['team_id'],
				'content'		=>$data['member'].'申请加入球队'
			];
			$message = new \Common\Model\MessageModel;
			$result = $message->send_message($content);
			if($result){
				$this->ajaxReturn(['code'=>1, 'msg'=>'您已申请入队，请等待批准入队']);
			}else{
				$this->ajaxReturn(['code'=>0,'msg'=>'申请失败,请重试']);
			}
			
		}else{

			$this->ajaxReturn($team_member->getError());
		}
		
	}

	// 验证邀请码
	public function verify_code(){

		if(IS_AJAX){

			$invitation_code = I('post.invitation_code','','/^\w+$/');
			$team_id = I('post.team_id');
			$is_invite = M('team')->where(['id'=>$team_id])->getField('is_invite,invitation_code');
			// $this->ajaxReturn(['code'=>0, 'msg'=>$is_invite]);
			if($is_invite[0]['is_invite'] == 0){
				if($invitation_code != $is_invite[0]['invitation_code']){
					$this->ajaxReturn(['code'=>0, 'msg'=>'邀请码不正确']);
				}else{
					$this->ajaxReturn(['code'=>1, 'msg'=>'邀请码正确']);
				}
			}else{
				$this->ajaxReturn(['code'=>1, 'msg'=>'不需要验证码']);
			}
		}else{
			$this->ajaxReturn(['code'=>0, 'msg'=>'喵喵喵？']);
		}
	}


	// 队员操作[同意入队，踢出队伍,拒绝入队]
	public function team_player_operate(){
		$mid = session('mid');
		if(IS_AJAX){
			$team_id = I('team_id');
			$team = I('team');
			$applyer_id = I('applyer_id');
			$portrait = M('member')->where(['id'=>$applyer_id])->getField('wx_avatar');
			$action = I('action');
			$team_member = M('team_member');
			$power = $team_member->where(['member_id'=>$mid,'team_id'=>$team_id])->getField('type');
			if($power!=4){
				$this->ajaxReturn(['code'=>0,'msg'=>'没有权限']);
			}
			$title = '';
			$content = '';
			switch ($action) {
				case 'reject':
					$title = '你的入队申请被拒绝';
					$content = '你被'.$team.'拒绝';
					$result = $team_member->where(['member_id'=>$applyer_id,'team_id'=>$team_id])->save(['status'=>2,'portrait'=>$portrait]);
					break;
				case 'kick':
					$title = '你被踢出球队';
					$content = '你被踢出了球队：'.$team;
					$result = $team_member->where(['member_id'=>$applyer_id,'team_id'=>$team_id])->save(['status'=>4,'portrait'=>$portrait]);
					break;
				default:
					$title = '你的入队申请已得到同意';
					$content = $team.'已同意你进球队';
					$result = $team_member->where(['member_id'=>$applyer_id,'team_id'=>$team_id])->save(['status'=>1,'portrait'=>$portrait]);
					break;
			}
			if($result){
				// 发送一则系统消息
				$sysmessage = new \Common\Model\SysmessageModel;
				$messageContent = [
					'title'		=>$title,
					'content'	=>$content,
					'receive_id'=>$applyer_id,
				]
				$sysmessageResult = $sysmessage->send_a_message($messageContent);
				$this->ajaxReturn(['code'=>1,'msg'=>'操作成功']);
			}else{
				$this->ajaxReturn(['code'=>0,'msg'=>'该请求失效']);
			}
		}else{
			$this->error('非法操作');
		}
	}


	// 退出球队
	public function qiut(){
		$team_id = I('post.team_id');
		$member_id = session('mid');
		$team_member = D('team_member');
		$result = $team_member
				->where(['team_id'=>$team_id])
				->where(['member_id'=>$member_id])
				->find();
		if($result){
			$team_member->where(['team_id'=>$team_id])
				->where(['member_id'=>$member_id])
				->save(['status'=>5]);
			$this->ajaxReturn(['msg'=>'退出成功']);
		}else{
			$this->ajaxReturn(['msg'=>'请求错误']);
		}

	}



}