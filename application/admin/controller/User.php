<?php
/**
 * 用户
 * 控制器
 * cx
 * 20170617
 */

namespace app\admin\controller;

use think\Request;
use think\Controller;

class User extends Adminbase
{

    /**
     * 人员信息查询显示
     *
     * @author  cx
     * @date  20170617
     */
    public function index()
    {
        $User = model('user','logic');
        $users = $User->getUsers();
        if (Request::instance()->isAjax())
        {
            $page = $users->render();
            $data = $users->all();
            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
        }
        $this->assign('users', $users);
        return $this->fetch('index');
    }

    /**
     * 新增人员页面显示
     *
     * @author  cx
     * @date  20170617
     */
    public function addUser()
    {
        //获取人员基本属性
        $org = model('user','service');
        $users_role = $org->getUserRole();
        $tree = model('user','logic');
        $users_org = $tree->getUserOrg();
//        $this->assign('users_duty', $users_duty);
//        $this->assign('users_adlevel', $users_adlevel);
//        $this->assign('users_nation', $users_nation);
//        $this->assign('users_political', $users_political);
//        $this->assign('users_education', $users_education);
        $this->assign('users_role', $users_role);
        $this->assign('users_org', json_encode($users_org));
        return $this->fetch('addUser');
    }

    /**
     * 编辑人员页面显示
     *
     * @author  cx
     * @date  20170617
     */
    public function editUser()
    {
        //判断是否传递id
        if (!input('id')){
            $this->error('参数错误');
        }
        //获取人员基本属性
        $user_id = input('id');
        $org = model('user','logic');
        $user = $org->getUserInfo($user_id);
        $search = model('user','service');
        $users_role = $search->getUserRole();
        $users_org = $org->getUserOrg();
        $this->assign('users_org', json_encode($users_org));
        $this->assign('users_role',$users_role);
        $this->assign('user',$user);
        return $this->fetch('editUser');
    }

    /**
     * 刪除人员
     *
     * @author  cx
     * @date  20170617
     */
    public function deleteUser()
    {
        if (!input('id'))
        {
            return zw_sprint_result('请选择删除用户', '', FAIL_CODE);
        }
        $user_id = Request::instance()->post('id');
        $org = model('user','service');
        $result = $org->deleteUser($user_id);
        return $result ? zw_sprint_result('删除成功', $result) : zw_sprint_result('删除失败', '', FAIL_CODE);
    }

    /**
     * 人员保存(新增、编辑)
     *
     * @author  cx
     * @date  20170617
     */
    public function saveUser()
    {
        $post = [
            'user_name'             => Request::instance()->post('user_name'),
            'account'       => Request::instance()->post('account'),
            'password'       => zw_set_password(Request::instance()->post('password')),
            'mobile'             => Request::instance()->post('mobile'),
            'sex'             => Request::instance()->post('sex'),
            'status'             => Request::instance()->post('status'),
            'sort'             => Request::instance()->post('sort'),
        ];
        $role_id = Request::instance()->post('users_role');
        $org_id = Request::instance()->post('users_org');
        //新增
        if(!input('post.id')){
            //验证器判断
            $validate = $this->validate($post,'user.add');
            if($validate !== true)
            {
                return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
            }
            $org = model('user','logic');
            $service = model('user','service');
            //验证用户是否已存在
            $result = $service->checkUserExist($post['account']);
            if($result){
                $this->error('帐号已存在');
            }
            $result = $org->addUser($post,$role_id,$org_id);
            $result? $this->success('新增成功', 'user/index') : $this->error('新增失败');
        }
        //编辑
        $user_id = input('post.id');
        $validate = $this->validate($post,'user.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $org = model('user','logic');
        $result = $org->editUser($user_id,$post,$role_id,$org_id);
        $result? $this->success('修改成功', 'user/index') : $this->error('修改失败');
    }

    /**
     * 人员搜索
     *
     * @author  sy
     * @date  20170620
     */
    public function searchUser(){
        $search_info = Request::instance()->get('search_info');
        $org = model('user','logic');
        $result = $org->searchUser($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
    }
}