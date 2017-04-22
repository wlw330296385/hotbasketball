<?php 
namespace Common\Model;
use Think\Model;
class MatchModel extends Model {


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
        array('match_time', 'time', self::MODEL_INSERT, 'function'),
        array('initiator_id', 'get_initiator_id', self::MODEL_INSERT,'callback'),
        array('status', '1', self::MODEL_INSERT),
        array('initiator_name','get_initiator_name',sefl::MODEL_INSERT,'callback')
    );


    /**
     * 获取当前用户信息
     * $mid 用户id
     */
    protected function get_initiator_id($mid = 0)
    {
    	$mid = session('mid');
        if($mid){
        	return $mid;
        } else {
        	return false;
        }
    }

    protected function get_initiator_name($mid = 0){
    	$mid = session('mid');
        $member_info = D('member')
        	->field('ch_name')
        	->find($mid);
        if ($member_info) {
            return $member_info['ch_name'];
        }else{
        	return false;
        }
        
    }
    /**
     * 插入数据
     */

}