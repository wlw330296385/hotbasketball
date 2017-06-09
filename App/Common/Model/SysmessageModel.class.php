<?php
namespace Common\Model;
use Think\Model;
class SysmessageModel extends Model{
    protected $trueTableName='sysmessage';

    protected $_auto = [
        ['status',3,1],
        ['create_time','time',1,'function']
    ];
    /**
     * 获取个人全部消息
     * $mid 个人id
     */
    public function get_message_list($mid){
        $result = this->where(['receive_id'=>$mid])->select();
        return $result;
    }    

    /**
     * 获取个人全部未读消息
     * $mid 个人id
     */
     public function get_unread_message($mid){
        $result = this->where(['receive_id'=>$mid,'status'=>0])->select();
        return $result;
    } 

    /**
     * 阅读一条消息
     * $mid 消息id
     */
    public function read_a_message($mid){
        $result = this->where(['id'=>$mid])->save(['status'=>1]);
        return $result;
    }

    /**
     * 发送一条消息
     * $rid 接受者id
     */
    public function send_a_message($data){
        if($this->create($data)){
            $result = this->add();
            return $result;
        }else{
            return this->getError();
        }

    }
}