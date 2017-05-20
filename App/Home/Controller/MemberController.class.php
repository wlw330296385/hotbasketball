<?php

namespace Home\Controller;
class MemberController extends ComController
{

	public function index(){
		if(I('get.id')){
			$id = I('get.id');
			$this->assign('id',$id);
		}else{
			$id = session('mid');
		}
		
		try{
			$member = new \Common\Model\MemberModel;
			$result = $member->get_member_info($id);
			unset($result['password']);
			}catch(Exception $e){

				print_r ($e->getMessage());
			}
		if(IS_POST){
			$this->ajaxReturn($result);

		}else{
			// dump($result);
			$this->assign('result',$result);
			$this->display();
		}
	
	}


	// 注销
	public function qiut(){
		$id = I('id');
		cookie('member',null);
		session('en_name',null);
		session('mid',null);
		echo "<script>alert('注销成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
	}
	
}