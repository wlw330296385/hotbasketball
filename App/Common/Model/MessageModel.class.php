<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MessageModel extends RelationModel{
    protected $trueTableName='message';
    protected static $_type = ['系统消息','球队消息'];
    /**
     * 自动完成
     */
    protected $_auto = [
    	['status',3,1],
    	['send_time','time',1,'function']
    ];

    // 关联查询
     protected $_link = [
        'team_message'=>[
            'mapping_type'  =>self::HAS_ONE,
            'class_name'    =>'team_member',
            // 'mapping_name'  =>'send_type',
            'foreign_key'   =>'member_id',
            'condition'     =>'message.send_id = team_member.member_id',
            'mapping_fields '=>'status',
        ]
     ];
    /**
     * 获取个人id
     */
    protected function get_person_id(){
    	$id = session('mid');
    	return $id;
    }


    /**
     * 创建一条消息
     */
    public function send_message($content){
    	$this->create($content);
        $this->add();
    }

    /**
     * 获取系统消息list
     * $p 页码
     */
    public function get_sys_message($p){
    	$uid = $this->get_person_id();
    	$result = $this->where(['reseive_id'=>$uid])
                        ->where(['type'=>0])
                        ->limit(($p-1)*10,$p*10)
                        ->select();
    	return $result;
    }

 	/**
     * 获取系统未读消息数量
     */
 	public function get_sys_unread_number(){
 		$uid = $this->get_person_id();
    	$result = $this->where(['reseive_id'=>$uid])->where(['type'=>0])->where(['status'=>0])->count();
    	return $result;
 	}

    /**
     * 获取球队消息list
     */
    public function get_team_message_list($tid){
        // $result = $this->where(['receive_type'=>1,'receive_id'=>$tid])
        //                 ->relation('team_message')
        //                 ->select();
        $result = $this->field('message.*,team_member.status as member_status')
                        ->where(['receive_type'=>1,'receive_id'=>$tid])
                        ->join('team_member on (message.send_id = team_member.member_id AND team_member.team_id ='.$tid.')','left')
                        ->select();
        // echo $this->getLastSql();
        return $result;
    }

    /**
     * 获取球队未读消息
     */
    public function get_team_message_unread(){
    	$uid = $this->get_person_id();
    	// 两条查询
    	$team_list = M('team_member')->where(['member_id'=>$uid])->getField('team_id');
    	$result = $this->where(['id'=>['in',$team_list]])->where(['type'=>1])->where(['status'=>0]) ->count();
    	return $result;
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
        }  
         return $result;
    }
    
}