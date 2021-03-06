<?php 
namespace Common\Model;
use Think\Model\RelationModel;
class MemberModel extends RelationModel {

	/**
     * 自动验证规则
     */
    protected $_validate = array(

    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        ['create_time', 'time',1, 'function'],
        ['status',0,1]
    );
    protected static $_type = ['爱好者','热血教头','裁判','学生'];
    protected static $judge_levels = [
        '无等级','三级裁判员','二级裁判员','一级裁判员','国家级裁判员','国家A级裁判员'
    ];

    protected static $coach_levels = [
        '无等级','三级教练','二级教练','一级教练','国家级教练','国家A级教练'
    ];
    /**
     * 关联模型
     */
     protected $_link = [
        'member_info'   =>[
           'mapping_type'   => self::HAS_ONE, 
           'class_name'     =>'member_info',
           'foreign_key'    =>'member_id',
        ],
        'media'         => [
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'media',
            'foreign_key'   => 'id',
            'condition'     => 'type = 1',
            'mapping_limit' => 3,
            'mapping_order' => 'id desc'
        ]
    ];

    /**
     * 注册
     */
    public function sign_up($data){
        $data['password'] = mySh1($data['password']);
        $result = $this->add($data);
        return $result;
    }

    /**
     * 关联查询用户其他信息
     */
    public function get_member_info($mid = 1){
        $result = $this->where(['id'=>$mid])
                ->relation('member_info')
                ->find();
        return $result;
    }

    /**
     * 获取用户图片
     * type == 1 用户个人头像
     *         2 。。。。
     */
    public function get_member_images($mid,$type = 1){
        $result = $this->relation('media')->select();

        if($result){
                return $result;
            }else{
                return 0;
            }
    }

    /**
     * 获取当前用户基本信息
     * $mid 用户id
     */
    public function get_init_information($mid = 0)
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
    public function adding_more_information(){
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

    /**
     * 添加一条信息到member_coach
     */
     public function add_member_coach($data){
        $result = M('member_coach')->add($data);
        return $result;
    }

    /**
     * 修改一条信息到member_coach
     */
    public function update_member_coach($map,$result){
        $result = M('member_coach')->where($map)->save($data);
        return $result;
    }

    /**
     * 添加一条信息到member_judge
     */
    public function add_member_judge($data){
        $result = M('member_coach')->add($data);
        return $result;
    }


    /**
     * 修改一条信息到member_judge
     */
    public function update_member_judge($map,$data){
        $result = M('member_judge')->where($map)->save($data);
        return $result;
    }

}