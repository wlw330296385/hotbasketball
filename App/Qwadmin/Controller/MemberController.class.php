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

class MemberController extends ComController
{

    protected static $_type = ['爱好者','热血教头','裁判','学生'];
    public function index()
    {     
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';
        if ($order == 'asc') {
            $order = "create_time ASC";
        } else {
            $order = "create_time DESC";
        }
        if ($keyword <> '') {
            $where = [$field=>['LIKE','%'.$keyword.'%']];
        }


        $user = M('member');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $user
            ->order($order)
            ->where($where)
            ->count();

        $list = $user
            ->order($order)
            ->where($where)
            ->limit($offset,$pagesize*$p)
            ->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        foreach ($list as $key => &$value) {
            $a = explode(',',$value['type']);
            // dump($a);die;
            foreach ($a as $v) {
                $value['types'].= self::$_type[$v].'|';
            }
        }

        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }


    public function fans(){
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';

        $prefix = C('DB_PREFIX');
        if ($order == 'asc') {
            $order = "{$prefix}member.ch_name asc";
        } elseif (($order == 'desc')) {
            $order = "{$prefix}member.ch_name desc";
        } else {
            $order = "{$prefix}member.id asc";
        }
        if ($keyword <> '') {
            if ($field == 'user') {
                $where = "{$prefix}member.user LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where = "{$prefix}member.phone LIKE '%$keyword%'";
            }
            if ($field == 'qq') {
                $where = "{$prefix}member.qq LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "{$prefix}member.email LIKE '%$keyword%'";
            }
        }

        $user = M('member');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $user
            ->order($order)
            ->join('member_fans on member.id = member_fans.member_id')
            ->where(['type'=>1])
            ->where($where)
            ->count();

        $list = $user
            ->order($order)
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }



    /**
     * 关联查询用户所有信息
     */

    public function member_info()
    {
        $result = [];
        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
        if ($id) {
           $member = new \Common\Model\MemberModel;
           $result = $member->get_member_info($id);
        } else {
            $this->error('参数错误！');
        }
        $this->assign('result', $result);
        $this->display('form');
    }

    


}
