<?php
namespace Common\Model;
class Media extends Model{
    protected $trueTableName='media';

    protected function getUrlByID($id = 1){
    	$result = M('media')->find($id);
    	if($result){
    		return $result['url'];
    	}else{
    		return 2;
    	}
    }
}
    