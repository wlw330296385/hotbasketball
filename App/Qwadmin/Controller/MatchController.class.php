<?php


namespace Qwadmin\Controller;
use Common\Model\MatchModel;
class MatchController extends ComController
{
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        if($order == 'asc'){
            $order = 'create_time ASC';
        }else{
            $order = 'create_time DESC';
        }
        if($keyword<>''){
           $where = [$field=>['LIKE','%'.$keyword.'%']];
        }
        $match = M('match');
        $count = $match->where($where)->limit($offset,$pagesize*$p)->count();
        // echo $count;die;
        $result = $match
                ->where($where)
                ->limit($offset ,$pagesize*$p)
                ->order($order)
                ->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('page',$page);
        $this->assign('list', $result);
        $this->assign('nav', array('user', 'grouplist', 'grouplist'));//导航
        $this->display();
    }

    public function match_info($match_id){
        $match = new \Common\Model\MatchModel;
        $result = $match->get_match_info($match_id);
        $this->assign('result',$result);
        //获取比赛评论
        $comment = new \Common\Model\CommentModel;
        $comment_list = $comment->get_comments($match_id,0);
        $this->assign('comment',$comment_list);
        //获取比赛轮播图
        $media = new \Common\Model\MediaModel;
        $medias = $media->get_url_by_parent_id($match_id,3);
        $this->assign('medias',$medias);


        $this->display('form');
    }

    public function add_match_record(){

    }

    public function create_match(){
        if(IS_POST){
            $match = new \Common\Model\MatchModel;
            $result = $match->create_match();
            if($result){
                $this->success('添加成功');
            }
        }else{
            $region = new \Home\Controller\RegionController();
        // $province = $region->get_province();
        $province = $region->get_province();
        $teams = M('team')->field('team,id')->select();
        $this->assign('province',$province);
        $this->assign('teams',$teams);
        $this->display('form');
        }
    }


}