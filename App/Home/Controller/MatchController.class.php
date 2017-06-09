<?php 
namespace Home\Controller;
class MatchController extends ComController{


	public function index(){
		$match = new \Common\Model\MatchModel;
		$mid = session('mid');
		$tids = M('team_member')->where(['member_id'=>$mid,'status'=>1])->getField('team_id');
		$matchs = $match->get_match($tids);

		$this->assign('matchs',$matchs);
		$this->display();
	}

	public function create_match(){
		$tid = I('tid');
		$team = new \Common\Model\TeamModel;
		$enemy = $team->field('id,team')->where(['id'=>['NEQ',$tid]])->select();
		$this->assign('enemy',$enemy);
		$region = new \Home\Controller\RegionController();
        $province = $region->get_province();
		$this->assign('province',$province);



		// dump($enemy);die;
		$this->display('form');
	}


	public function team_member(){
		$tid = I('tid');
		$team = new \Common\Model\TeamModel;
		$team_info = $team->get_team_member($tid);
		$this->assign('team_member',$team_info['team_member']);
		$this->display();
	}
}