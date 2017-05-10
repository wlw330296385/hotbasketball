<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;

class ComController extends Controller
{
	public $MEMBER;
    public function _initialize()
    {
        C(setting());
        /*
        $links = M('links')->limit(10)->order('o ASC')->select();
        $this->assign('links',$links);
        */
        $url = U('login/index');
        $is_wap = $this->is_mobile();
        if($is_wap){
            // 检测是微信端还是普通网页
            $is_wx = $this->is_weixin();
            if($is_wx) {
                $url = U("login/wx_login");
            } 
        }
        //检测是否登录
        $flag =  $this->check_login();        
        if (!$flag) {
            header("Location: {$url}");
            exit(0);
        }
    }





    protected function check_login()
    {
    	session_start();
        $flag = false;
        $salt = C("COOKIE_SALT");
        $ip = get_client_ip();
        $auth = cookie('member');
        $en_name = session('en_name');
        if ($en_name) {
            $member = M('member')->where(array('en_name' => $en_name))->find();
            if ($member) {
                if ($auth ==  password($member['en_name'])) {
                    $flag = true;
                    $this->MEMBER = $member;
                }
            }
        }
        return $flag;
    }


    // 判断是否微信
    protected function is_weixin() { 
	    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { 
	        return true; 
	    	} 
    	return false; 
	}

	protected function is_mobile() { 
		  // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		  if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		    return true;
		  } 
		  // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		  if (isset($_SERVER['HTTP_VIA'])) { 
		    // 找不到为flase,否则为true
		    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		  } 
		  // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
		  if (isset($_SERVER['HTTP_USER_AGENT'])) {
		    $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
		    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
		    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
		      return true;
		    } 
		  } 
		  // 协议法，因为有可能不准确，放到最后判断
		  if (isset ($_SERVER['HTTP_ACCEPT'])) { 
		    // 如果只支持wml并且不支持html那一定是移动设备
		    // 如果支持wml和html但是wml在html之前则是移动设备
		    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
		      return true;
		    } 
		  } 
		  return false;
	}
}