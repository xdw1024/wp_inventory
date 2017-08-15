<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/19
 * Time: 11:23
 */

namespace app\admin\controller;

use think\Request;
class Role extends Adminbase
{

    /**
     * 角色管理
     *
     * @author  sy
     * @date  20170619
     */
    public function index(){
        //获取角色信息
        $org = model('Role','logic');
        $role = $org->getRole();
        if (Request::instance()->isAjax())
        {
            $page = $role->render();
            $data = $role->all();
            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
        }
        $this->assign('role', $role);
        return $this->fetch('index');
    }

    /**
     * 新增角色
     *
     * @author  sy
     * @date  20170619
     */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
     * 编辑角色
     *
     * @author  sy
     * @date  20170619
     */
    public function edit()
    {
        if (!input('id')){
            $this->error('参数错误');
        }
        $role_id = input('id');
        $org = model('Role','service');
        $role_data = $org->getRoleInfo($role_id);
        $this->assign('role_data',$role_data);
        return $this->fetch('edit');
    }

    /**
     * 刪除角色
     *
     * @author  sy
     * @date  20170619
     */
    public function deleteRole()
    {
        if (!input('id'))
        {
            return zw_sprint_result('请选择删除用户', '', FAIL_CODE);
        }
        $role_id = Request::instance()->post('id');
        $org = model('Role','service');
        $result = $org->deleteRole($role_id);
        return $result ? zw_sprint_result('删除成功', $result) : zw_sprint_result('删除失败', '', FAIL_CODE);
    }

    /**
     * 角色保存(新增、编辑)
     *
     * @author  sy
     * @date  20170619
     */
    public function save(){
        $post = [
            'role_name'       => Request::instance()->post('role_name'),
            'describe'       => Request::instance()->post('describe'),
            'sort'             => Request::instance()->post('sort'),
        ];
        //新增
        if(!input('post.id')){
            //验证器判断
            $validate = $this->validate($post,'role.add');
            if($validate !== true)
            {
                return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
            }
            $org = model('Role','service');
            $result = $org->add($post);
            $result? $this->success('新增成功', 'Role/index') : $this->error('新增失败');
        }
        //编辑
        $role_id = input('post.id');
        $validate = $this->validate($post,'role.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $org = model('Role','service');
        $result = $org->edit($role_id,$post);
        $result? $this->success('编辑成功', 'Role/index') : $this->error('编辑失败');
    }

    /**
     * 角色搜索
     *
     * @author  sy
     * @date  20170619
     */
    public function search(){
        $search_info = Request::instance()->get('search_info');
        $org = model('Role','logic');
        $result = $org->searchRole($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
    }

    /**
     * 角色菜单关联
     *
     * @author  sy
     * @date  20170622
     */
    public function roleMenuRelation(){
        if (!input('id')){
            $this->error('参数错误');
        }
        $role_id = input('id');
        //获取菜单信息
        $org = model('Role','service');
        $menus = $org->getMenu();
        $role = $org->getRoleInfo($role_id);
        $this->assign('menus', $menus);
        $this->assign('role', $role);
        return $this->fetch('roleMenuRelation');
    }

    /**
     * 角色菜单关联保存
     *
     * @author  sy
     * @date  20170622
     */
    public function saveRelation(){
        if (!input('role_id')){
            $this->error('参数错误');
        }
        $role_id = Request::instance()->post('role_id');
        $menu_id = Request::instance()->post('menu_id/a');
        //添加角色菜单关联
        $org = model('Role','logic');
        $result = $org->getMenuRole($role_id,$menu_id);
        return $result ? zw_sprint_result('添加成功', json_encode($result)) : zw_sprint_result('添加失败', '', FAIL_CODE);
    }
}