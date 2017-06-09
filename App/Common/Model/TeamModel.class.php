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
            'parent_key'        =>'parent_id'
    	]
    ];

    protected $_auto = [
        ['create_time','time',1]
    ];

    // 自动验证
    protected $_validate = array(
         array('team','require','队名必须'), //默认情况下用正则进行验证
         array('team','','队名存在',0,'unique',1), // 在新增的时候是否唯一
         array('captain_id','','一个人只能发起一个球队',0,'unique',1), // 在新增的时候验证name字段是否唯一
         array('telephone','require','联系电话必须'), // 当值不为空的时候判断是否在一个范围内
         array('contract','require','联系人必须'), // 验证确认密码是否和密码一致
       );
    /**
     * 获取球队下的所有队员
     */
    public function get_team_member($tid){
        $result = $this->where(['id'=>$tid])->relation('team_member')->find();
        return $result;
    }
    /**
     * 创建一个球队
     * $tid球duiid
     */
    public function update_team($tid){
        $data = $this->create();
        if(!$data){
            return $this->getError();
        }else{
            if($tid == 0){
                $data['captain_id'] = session('mid');
                $data['captain'] = M('member')->where(['id'=>$mid])->getField('en_name');
                $result = $this->add($data);
            }else{
                $data['id'] = $tid;
                $result = $this->save($data);
            }          
            return $result;
        }
        
    }
    /**
     * 获取一个人的所属球队
     */
    public function get_team_list_by_mid($mid){
        $result = false;
        $team_ids = M('team_member')->where(['member_id'=>$mid])
                    ->where(['status'=>1])
                    ->getField('team_id');
        // dump($team_ids);
        if($team_ids){
            $result = $this->where(['id'=>['in',$team_ids]])->select();
        }   
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
    	$result = $this->where(['id'=>$tid])->relation('team_member')->find();
    	if($result){
            foreach ($result['team_member'] as $key => $value) {
                $result['team_member'][$key]['member_type'] = self::$_type[$value['type']]; 
            }   		
    	}
        return $result;
    }

    /**
     * 获取球队所有照片
     * $tid 球队id
     * $type 照片类型
     */
    public function get_team_images($tid,$type = 3){
    	$result = M('media')->where(['parent_id'=>$tid])->where(['type'=>$type])->where(['suffix'=>0])->getField('url,remark');
    	return $result;
    }

}
    