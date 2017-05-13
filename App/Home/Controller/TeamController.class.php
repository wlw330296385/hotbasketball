<?php 
namespace Home\Controller;
class TeamController extends ComController
{
	// 球队首页
	public function index($id){
		$mid = session('mid');
		$team = new \Common\Model\TeamModel;
		// 球队信息
		if($id){
			$the_team = $team->get_team_info($tid);
			$this->assign('the_team',$the_team);
		}else{
			
			$the_team = $team->get_team_list_by_mid($mid);
			if($result){
				$this-assign('the_team',$the_team);
				// 获取球队照片
				$tid = I('post.tid');
				$media = $team->get_team_images($tid);
			}else{		
				$this->redirect('team_list');
			}
		}
			
		$this->display();
	}

	// 获取所有球队列表
	public function team_list(){
		$teams = M('team')->select();
		$this->assign('teams',$teams);
		// dump($teams);die;
		$this->display();
	}

	public function team_list_of_user(){

	} 
}