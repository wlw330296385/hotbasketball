<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MessageModel extends RelationModel{
    protected $trueTableName='message';
    /**
     * 自动完成
     */
    protected $_auto = [
    	['status',3,1],
    	['create_time','time',1,'function']
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
     * 创建一条消息
     */
    public function send_message($content){
    	$this->create($content);
        $result = $this->add();
        return $result;
    }


    /**
     * 获取球队消息list
     */
    public function get_team_message_list($tid){
        $result = $this->field('message.*,team_member.status as member_status')
                        ->where(['receive_id'=>$tid])
                        ->join('team_member on (message.send_id = team_member.member_id AND team_member.team_id ='.$tid.')','left')
                        ->select();
        return $result;
    }

    /**
     * 获取球队未读消息
     */
    public function get_team_message_unread(){
    	$uid = $this->get_person_id();
    	// 两条查询
    	$team_list = M('team_member')->where(['member_id'=>$uid])->getField('team_id');
    	$result = $this->where(['id'=>['in',$team_list]])->where(['status'=>0]) ->count();
    	return $result;
    }


    /**
     * 阅读一条消息
     * $mid 信息id
     */
    public function read_message($mid){
    	$result = $this->where(['id'=>$mid])->find();    	
    	if($result){
    		$this->where(['id'=>$mid])->save(['status'=>1]);
        }  
         return $result;
    }
    
}