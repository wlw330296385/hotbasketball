<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2016-01-20
 * 版    本：1.0.0
 * 功能说明：用户控制器。
 *
 **/

namespace Qwadmin\Controller;

class TeamController extends ComController
{
    public function index()
    {
        
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';

        $prefix = C('DB_PREFIX');
        if ($order == 'asc') {
            $order = "{$prefix}team.id asc";
        } elseif (($order == 'desc')) {
            $order = "{$prefix}team.id desc";
        } else {
            $order = "{$prefix}team.create_time asc";
        }
        if ($keyword <> '') {
            if ($field == 'user') {
                $where = "{$prefix}team.user LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where = "{$prefix}team.phone LIKE '%$keyword%'";
            }
            if ($field == 'qq') {
                $where = "{$prefix}team.qq LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "{$prefix}team.email LIKE '%$keyword%'";
            }
        }


        $user = M('team');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $user
            ->where($where)
            ->count();

        $list = $user
            ->order($order)
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();
            // dump($list);die;
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $group = M('auth_group')->field('id,title')->select();
        $this->assign('group', $group);
        $this->display();
    }

    public function team_info()
    {
        $tid = I('.id');
        $teamModel = new \Common\Model\TeamModel;
        $result = $teamModel -> get_team_info($tid);
        $this->assign('list',$result);
        $this->display();
    }

    public function create_team(){
        $team = M($team)->where(['id'=>$tid])->find();
        $this->assign('team',$team);
        $this->display('form');
    }



    public function update_team($tid = 0){
        if($tid!=0){
            $team = M($team)->where(['id'=>$tid])->find();
            $this->assign('team',$team);
        }
        if(IS_POST){
            $team = new \Common\Model\TeamModel;
            $result = $team->update_team($tid);
            if($result){
                $mid = session('mid');
                addlog('ID：'.$mid.'修改了一支球队：'.json_encode($result));
                $this->success('操作成功');
            }
        }
        $this->display('form');
    }
}
