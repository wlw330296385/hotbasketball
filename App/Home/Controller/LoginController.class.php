<?php
namespace Home\Controller;

class LoginController extends ComController
{


	public function _initialize(){
		parent::_initialize();
	}


    public function index()
    {
    	// 登陆
    	if(IS_POST){
			$rules = array(
				array('verify','require','验证码必须！'), //默认情况下用正则进行验证
				array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
				array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
				array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
				array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
			);


			$url = U('index/index');
			header("Location: $url");
    	}
    	$this->display();
    	
    }

    public function wx_login(){
    	$wxAuth = new \Org\Util\Wx\WxAuth;
    	$wxAuth -> step_1();
    }

    // 注册
    public function sign_up(){
		if(IS_POST){
			$rules = array(
				array('en_name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
				array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
				array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
				array('telephone','','电话号码已被使用',0,'unique',1)
			);
			$member = new \Common\Model\Member;; // 实例化User对象
			if (!$member->validate($rules)->create()){
			     // 如果创建失败 表示验证没有通过 输出错误提示信息
			    $this->ajaxReturn($member->getError());
			 }else{
			 	$data = $member->create();
			 	$result = $member->sign_up($data);
			 	if($result){
			 		// 发送短信通知
			 		$this->ajaxReturn(['code'=>1,'msg'=>'注册成功']);
			 	}else{
			 		$this->ajaxReturn(['code'=>0,'msg'=>'网络繁忙，请稍后再试']);
			 	}
			 }

    	}
    	$this->display();
    	
    }


    public function get_wxuserinfo(){
    	$code = I('get.code');
    	if($code){
    		$wxAuth = new \Org\Util\Wx\WxAuth;
    		$result = $wxAuth->step_2($code);
    		$result = json_decode($result);
    		$access_token = $result['access_token'];
    		$openid = $result['openid'];
    		$userinfo = $wxAuth->step_3($access_token,$openid);
    		$userinfo = json_decode($userinfo);
    		dump($userinfo);
    	}else{
    		echo '用户未授权';
    	}
    }
   
}