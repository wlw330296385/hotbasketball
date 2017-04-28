<?php
namespace Common\Model;
use Think\Model;
class Message extends Model{
    protected $trueTableName='message';
    protected static $_type = ['系统消息','球队消息'];
    /**
     * 自动完成
     */
    protected $_auto = [
    	['status',0,1],
    	['send_time','time',1,'function']
    ];


    /**
     * 获取个人id
     */
    protected function get_person_id(){
    	$id = session('uid');
    	return $id;
    }


    /**
     * 创建一条消息
     */
    public function send_message(){
    	$this->create();
    	$this->add();
    }

    /**
     * 获取系统消息list
     * $p 页码
     */
    public function get_sys_message($p){
    	$uid = $this->get_person_id();
    	$result = $this->where(['reseive_id'=>$uid])->where(['type'=>0])->limit(($p-1)*10,$p*10)->select();
    	return $result?$result:[];
    }

 	/**
     * 获取系统未读消息数量
     */
 	public function get_sys_number(){
 		$uid = $this->get_person_id();
    	$result = $this->where(['reseive_id'=>$uid])->where(['type'=>0])->where(['status']=>0)->count();
    	return $result?$result:0;
 	}

    /**
     * 获取球队消息list
     */
    public function get_team_message(){
    	$uid = $this->get_person_id();
    	// 两条查询
    	$team_list = M('team_member')->where(['member_id'=>$uid])->getField('team_id');
    	$result = $this->where(['id'=>['in',$team_list]]) ->select();
    	return $result?$result:[];
    }

    /**
     * 获取球队未读消息
     */
    public function get_team_message(){
    	$uid = $this->get_person_id();
    	// 两条查询
    	$team_list = M('team_member')->where(['member_id'=>$uid])->getField('team_id');
    	$result = $this->where(['id'=>['in',$team_list]])->where(['type'=>1])->where(['status'=>0]) ->count();
    	return $result?$result:0;
    }


    /**
     * 阅读一条消息
     * $mid 信息id
     */
    public function read_message($mid){
    	$result = $this->where(['id'=>$mid])->find();
    	
    	if($result){
    		$result['message_type'] = $_type[$result['type']];
    		$this->where(['id'=>$mid])->save(['status'=>1]);
    		return $result;
    	}else{
    		return [];
    	}
    }
}
    