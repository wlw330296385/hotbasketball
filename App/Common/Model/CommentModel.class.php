<?php 
namespace Common\Model;
use Think\Model\RelationModel;
class CommentModel extends RelationModel {
    protected $trueTableName='comment';

    protected static $_type = ['比赛评论','活动评论','训练评论','其它'];
	 /**
     * 自动验证规则
     */
    protected $_validate = array(
       ['member_id','require','评论人必须', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT],
       ['content','require','内容必须', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT],
    );

    /**
     * 自动完成规则
     */
    protected $_auto = [
        ['create_time','time','1','function']
    ];


    /**
     * 关联模型
     */
    protected $_link = [
        'comment_reply'=>[
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'comment_reply',
            'mapping_name'  => 'comment_reply',
            'foreign_key'   =>'id',
            'parent_key'    =>'parent_id'
        ]
    ];

    /**
     * 根据活动获取评论
     */
   public function get_comments($parent_id,$type){
        $result = $this->where(['type'=>$type])
                ->where(['$parent_id'=>$parent_id])
                ->relation('comment_reply')
                ->select();
        return $result;
   }

   /**
    * 添加一条评论
    */

   public function create_comment($data){
        if(!$this->create($data)){
            return $this->getError();
        }else{
            if($data['reply_id']){
                $result = M('comment_reply')->add($data);
            }else{
                $result = $this->add($data);
            }
            return $result;
        }      
   }
}