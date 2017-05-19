<?php 
namespace Home\Widget;
use Think\Controller;
class ExampleWidget extends Controller
{
	public function home_footer_menu(){
		$home_footer_menu = M('home_footer_menu');
		return $result = $home_footer_menu
				->where(['status'=>1])
				->order('ord DESC')
				->limit(6)
				->select();

		$this->assign('menu',$result);	
	}

}