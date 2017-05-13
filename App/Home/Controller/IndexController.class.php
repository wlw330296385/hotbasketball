<?php

namespace Home\Controller;

class IndexController extends ComController
{
    public function index()
    {
    	// 按钮
    	$menus = M('home_menu')->limit(3)->select();
    	$this->assign('menus',$menus);
    	// 公告
    	
        $this->display();
    }

}