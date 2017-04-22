<?php 
namespace Common\Model;
use Think\Model\RelationModel;
class MemberModel extends RelationModel {

	    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('match_time', 'time', '比赛时间格式错误', self::EXISTS_VALIDATE, 'function', self::MODEL_BOTH),
        array('match_venue', 'require', '必须填写地址', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('team_first_need_player', 'require', '需要人数必须！', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('sign_login', 'time', self::MODEL_INSERT, 'function'),
        array('initiator_id', 'get_initiator', self::MODEL_INSERT,'callback'),
        array('status', '1', self::MODEL_INSERT),
    );


    /**
     * 关联模型
     */
     protected $_link = [
        'member_student'=>[
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'member_student'
        ],
        
        'member_coach'=>[
            'mapping_type' => self::HAS_ONE,
            'class_name'   => 'member_coach'
        ],

        'member_fans' =>[
            'mapping_type'  =>self::HAS_ONE,
            'class_name'    =>'member_fans'
        ],

        
    ];

    /**
     * 关联查询用户全部
     */
    protected function get_memberinfo($mid, $type){
        switch ($type) {
             case '1':
                 $table = 'member_student';
                 break;
            case '2':
                 $table = 'member_coach';
                 break;
            case '3':
                 $table = 'member_fans';
                 break;
            default:
                 $table = 'member_fans';
                 break;

        $member = D('member');
        $result = $member->relation($table)->find(1);
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 获取当前用户基本信息
     * $mid 用户id
     */
    protected function get_initiator($mid = 0)
    {
    	$mid = session('mid');
        $user_info = D('member')
        	->find($mid);
        if ($user_info) {
            return $user_info;
        }
        return false;
    }

    /**
     * 补充会员信息
     */
    protected function adding_More_Information(){
        $mid = session('mid');
        switch (I('post.type') ) {
            case '1':
                 $table = 'member_student';
                 break;
            case '2':
                 $table = 'member_coach';
                 break;
            case '3':
                 $table = 'member_fans';
                 break;
            default:
                 $table = 'member_fans';
                 break;
         } 
        $member = M($table);
        $list = $member->create();
        $list['member_id'] = $mid;
        $member->add($list);

    }

    

    }
}