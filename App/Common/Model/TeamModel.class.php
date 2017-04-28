<?php
namespace Common\Model;
use Think\Model\RelationModel;
class TeamModel extends RelationModel{
    protected $trueTableName='team';

    protected static $_type = ['队员','队长','教练','领队','队委','副领队','副队长','副教练'];

    protected $_link = [
    	'team_member' => [
    		'mapping_type'		=> self::HAS_MANY,
    		'class_name'		=> 'team_member',
    		'mapping_name'		=> 'team_member',
    		'foreign_key'		=> 'id',
    		'parent_key'        => 'team_id',
    	],
    	'team_images' => [
    		'mapping_type'		=> self::HAS_MANY,
    		'class_name'		=>'media',
    		'mapping_name'		=>'images',
    		'foreign_key'		=>'id',
            'parent_key'        =>'team_id'
    	]
    ];

    protected $_auto = [
        ['create_time','time',1]
    ];


    /**
     * 创建一个球队
     * $init_id创建者
     */
    public function create_team(){
        $result = $this->create()->add();
        return $result;
    }


    /**
     * 加入一个球队
     * 球队id     $tid
     */
    public function join_team($tid,$mid,$member_name,$type){
        $result = M('team_member')->find($tid);
    }

    /**
     * 获取球队信息
     * $tid 球队id
     */
    public function get_team_info($tid){
    	$result = $this->relation('team_member')->select();
    	if($result){
            foreach ($result['team_member'] as $key => $value) {
                $result['team_member'][$key]['member_type'] = $_type[$value['type']]; 
            }
    		return $result;
    	}else{
    		return [];
    	}
    }

    /**
     * 获取球队所有照片
     * $tid 球队id
     * $type 照片类型
     */
    public function get_team_images($tid,$type){
    	$result = $this->where(['id'=>$tid])->relation('team_images')->select();
    	if($result){
    		return $result;
    	}else{
    		return [];
    	}
    }

}
    