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
    public function index()
    {
        
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
        $count = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.ch_nameitle")
            ->order($order)
            ->join("{$prefix}auth_group_access ON {$prefix}member.id = {$prefix}auth_group_access.id")
            ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
            ->where($where)
            ->count();

        $list = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.ch_nameitle")
            ->order($order)
            ->join("{$prefix}auth_group_access ON {$prefix}member.id = {$prefix}auth_group_access.id")
            ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $group = M('auth_group')->field('id,title')->select();
        $this->assign('group', $group);
        $this->display();
    }

    public function del()
    {

        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        //id为1的禁止删除
        if ($ids == 1 or !$ids) {
            $this->error('参数错误！');
        }
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                if ($v == 1) {//id为1的禁止删除
                    unset($ids[$k]);
                }
                $ids[$k] = intval($v);
            }
            if (!$ids) {
                $this->error('参数错误！');
                $ids = implode(',', $ids);
            }
        }

        $map['id'] = array('in', $ids);
        if (M('staff')->where($map)->delete()) {
            M('auth_group_access')->where($map)->delete();
            addlog('删除会员id：' . $ids);
            $this->success('恭喜，用户删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit()
    {

        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
        if ($id) {
            //$member = M('staff')->where("id='$id'")->find();
            $prefix = C('DB_PREFIX');
            $user = M('staff');
            $member = $user->field("{$prefix}member.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}member.id = {$prefix}auth_group_access.id")->where("{$prefix}member.id=$id")->find();

        } else {
            $this->error('参数错误！');
        }

        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);

        $this->assign('member', $member);
        $this->display('form');
    }

    public function update($ajax = '')
    {
        if ($ajax == 'yes') {
            $id = I('get.id', 0, 'intval');
            $gid = I('get.gid', 0, 'intval');
            M('auth_group_access')->data(array('group_id' => $gid))->where("id='$id'")->save();
            die('1');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : false;
        $user = isset($_POST['user']) ? htmlspecialchars($_POST['user'], ENT_QUOTES) : '';
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        if (!$group_id) {
            $this->error('请选择用户组！');
        }
        $password = isset($_POST['password']) ? trim($_POST['password']) : false;
        if ($password) {
            $data['password'] = password($password);
        }
        $head = I('post.head', '', 'strip_tags');
        $data['sex'] = isset($_POST['sex']) ? intval($_POST['sex']) : 0;
        $data['head'] = $head ? $head : '';
        $data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : 0;
        $data['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $data['qq'] = isset($_POST['qq']) ? trim($_POST['qq']) : '';
        $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';


        $data['t'] = time();
        if (!$id) {
            if ($user == '') {
                $this->error('用户名称不能为空！');
            }
            if (!$password) {
                $this->error('用户密码不能为空！');
            }
            if (M('staff')->where("user='$user'")->count()) {
                $this->error('用户名已被占用！');
            }
            $data['user'] = $user;
            $id = M('staff')->data($data)->add();
            M('auth_group_access')->data(array('group_id' => $group_id, 'id' => $id))->add();
            addlog('新增会员，会员id：' . $id);
        } else {
            M('auth_group_access')->data(array('group_id' => $group_id))->where("id=$id")->save();
            addlog('编辑会员信息，会员id：' . $id);
            M('staff')->data($data)->where("id=$id")->save();

        }
        $this->success('操作成功！');
    }


    public function add()
    {

        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);
        $this->display('form');
    }
}
