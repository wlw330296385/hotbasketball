<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class LoginController extends Controller
{
	public function _initialize(){
		C(setting());
	}
    public function index()
    {

    	// 登陆
    	if(IS_POST){
			$password = mySh1(I('post.password'));
			$en_name = I('post.en_name');
			$member = M('member');
			$where = [
						'en_name'	=>['eq',$en_name],
						'telephone'	=>['eq',$en_name],
						'_logic'	=>'or'
					];
			$map['_complex'] = $where;
			$map['password'] = ['eq',$password];
			$result = $member->where($map)
					->find();
			if($result){
				$url = U('index/index');
				header("Location: $url");
				$cookie = password($en_name);
				cookie('member',$cookie);
				session('en_name',$en_name);
				session('mid',$result['id']);
			}else{
				echo "<script>alert('账号密码错误')</script>";
			}
    	}
    	$this->display();   	
    }

    public function wx_login(){
    	$wxAuth = new \Org\Util\Wx\WxAuth;
    	$wxAuth -> step_1();
    }

    // 注册
    public function sign_up(){

    	cookie('auth',null);
		if(IS_POST){
			$rules = array(
				array('verify','require','验证码必须！'), //默认情况下用正则进行验证
				array('en_name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
				array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
				array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
				array('telephone','','电话号码已被使用',0,'unique',1)
			);
			$member = new \Common\Model\MemberModel; // 实例化User对象
			if (!$member->validate($rules)->create()){
			     // 如果创建失败 表示验证没有通过 输出错误提示信息
			    $this->ajaxReturn($member->getError());
			 }else{
			 	$data = $member->create();
			 	$data['telephone'] = $data['en_name'];
			 	$result = $member->sign_up($data);
			 	if($result){
			 		$cookie = password($data['en_name']);
					cookie('member',$cookie);
			 		session('mid',$result);
			 		session('en_name',$data['en_name']);
			 		// 发送短信通知
			 		$this->ajaxReturn(['code'=>1,'msg'=>'注册成功']);
			 		// 跳转到主页
			 		$this->redirect('Index/index');
			 	}else{
			 		$this->ajaxReturn(['code'=>0,'msg'=>'网络繁忙，请稍后再试']);
			 	}
			 }

    	}
    	$this->display('index');
    	
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

    public function verify()
    {
        $config = array(
            'fontSize' => 14, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW' => 100,
            'imageH' => 30,
        );
        $verify = new \Think\Verify($config);
        $verify->entry('login');
    }
   
}