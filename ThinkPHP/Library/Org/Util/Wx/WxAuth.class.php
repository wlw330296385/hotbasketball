<?php
namespace Org\Util\Wx;
class WxAuth
{

	private $appid;
	private $appsecret;
	private $redirect_uri;
	private $response_type = 'code';
	private $scope = 'snsapi_userinfo';
	private $state = 'hbm';
	private $wechat_redirect = '';
	private $authorization_code = 'authorization_code';
	public function step_1(){
		$request_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='$this->redirect_uri.'&response_type='.$this->response_type.'&scope='.$this->scope.'&state=STATE#'.$this->wechat_redirect;
		header($request_url);
	}


	public function step_2($code){
		$request_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type='.$this->authorization_code;
		$result = file_get_contents($request_url);
		return json_encode($result);
	}

	public function  step_3($access_token,$openid){
		$request_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$result = file_get_contents($request_url);
		return json_encode($result);
	}
}