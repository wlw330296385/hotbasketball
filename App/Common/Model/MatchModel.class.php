<?php 
namespace Common\Model;
use Think\Model\RelationModel;
class MatchModel extends RelationModel {
	    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('match_time', 'time', '比赛时间格式错误', self::EXISTS_VALIDATE, 'function', self::MODEL_BOTH),
        array('match_venue_province', 'require', '必须填写省份', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('match_venue_city', 'require', '必须填写城市', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('match_venue_area', 'require', '必须填写区域', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('match_venue_address', 'require', '必须填写详细地址', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('team_first_need_player', 'require', '需要人数必须！', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('member_id', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
        array('member','系统发起',self::MODEL_INSERT)
    );


    /**
     * 关联模型
     */
    protected $_link = [
        'region' => [
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'region'
        ],
        'participate_member'=>[
            'mapping_type'  =>self::HAS_MANY,
            'class_name'    =>'participation_match',
            'mapping_name'  =>'participate_member',
            'foreign_keys'  =>'id',
            'parent_key'    =>'match_id'
        ],
        'logo' => [
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    =>'team',
            'foreign_key '  =>'team_first_id',
            
        ]
    ];

    public function get_match_info($match_id){
        $result = $this
                // ->where(['id'=>$match_id])
                ->relation('participate_member')
                ->find($match_id);
        return $result;
    } 
    /**
     * 插入数据
     */
    public function create_match(){
        $this->startTrans();
        $data = $this->create();
        $result = $this->add();
        // $participate_member = json_decode($data['participate_member']);
        // $result1 = M('participation_match')->addAll($participate_member);
        // if($result1 && $result){
        if($result){
            $this->commit();
            return $result;
        }else{
            $this->rollback();

        }
    }

    /**
     * $tids 球队数组
     */
    public function get_match($tids){
        $result = $this->where(['team_first_id'=>['in',$tids],'_logic'=>'or','team_second_id'=>['in',$tids]])->relation('logo')->select();
        return $result;
    }
}