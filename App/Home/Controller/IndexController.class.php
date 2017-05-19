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
    	$announcement = M('announce')->limit(5)->select();
    	$this->assign('announce',$announcement);
    	// 轮播
    	$slideshow = M('slideshow')->limit(5)->select(3);
    	$this->assign('slideshow',$slideshow);
    	// 三条活动
    	$team = M('team')->find();
    	$this->assign('team',$team);
    	$match = M('match')->find();
    	$this->assign('match',$match);
    	// dump($team);dump($match);die;
    	$article = M('article')->find();
    	$this->assign('article',$article);
    	
        $this->display();
    }

}