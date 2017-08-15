<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 9:27
 */

namespace app\admin\controller;

use think\Request;
class Menu extends Adminbase
{
    /**
     * 菜单管理
     *
     * @author  sy
     * @date  20170620
     */
    public function index(){
        //获取菜单信息
        $org = model('Menu','logic');
        $menu = $org->getMenu();
        $this->assign('menu', $menu);
        if (Request::instance()->isAjax())
        {
            $page = $menu->render();
            $data = $menu->all();
            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
        }
        return $this->fetch('index');
    }

    /**
     * 新增菜单
     *
     * @author  sy
     * @date  20170620
     */
    public function addMenu()
    {
        //获取菜单等级为1的信息
        $org = model('Menu','service');
        $menu_data = $org->getMenuData();
        $this->assign('menu_data', $menu_data);
        return $this->fetch('addMenu');
    }

    /**
     * 编辑菜单
     *
     * @author  sy
     * @date  20170620
     */
    public function editMenu()
    {
        if (!input('id')){
            $this->error('参数错误');
        }
        //获取菜单等级为1的信息
        $org = model('Menu','service');
        $menu_level = $org->getMenuData();
        $this->assign('menu_level', $menu_level);
        //获取选中的菜单信息
        $menu_id = input('id');
        $menu_data = $org->getMenuInfo($menu_id);
        $this->assign('menu_data',$menu_data);
        return $this->fetch('editMenu');
    }

    /**
     * 刪除菜单
     *
     * @author  sy
     * @date  20170620
     */
    public function deleteMenu()
    {
        if (!input('id'))
        {
            return zw_sprint_result('请选择删除用户', '', FAIL_CODE);
        }
        $menu_id = Request::instance()->post('id');
        $org = model('Menu','service');
        $result = $org->deleteMenu($menu_id);
        return $result ? zw_sprint_result('删除成功', $result) : zw_sprint_result('删除失败', '', FAIL_CODE);
    }

    /**
     * 菜单保存(新增、编辑)
     *
     * @author  sy
     * @date  20170620
     */
    public function save(){
        $post = [
            'parent_id'       => Request::instance()->post('parent_id'),
            'menu_name'       => Request::instance()->post('menu_name'),
            'controller'             => Request::instance()->post('controller'),
            'method'             => Request::instance()->post('method'),
            'icon'             => Request::instance()->post('icon'),
            'status'             => Request::instance()->post('status'),
            'sort'             => Request::instance()->post('sort'),
            'view'             => Request::instance()->post('view'),
        ];
        //新增
        if(!input('post.id')){
            //验证器判断
            $validate = $this->validate($post,'menu.add');
            if($validate !== true)
            {
                return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
            }
            $org = model('Menu','logic');
            $result = $org->addMenu($post);
            $result? $this->success('新增成功', 'Menu/index') : $this->error('新增失败');
        }
        //编辑
        $menu_id = input('post.id');
        $validate = $this->validate($post,'menu.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $org = model('Menu','logic');
        $result = $org->editMenu($menu_id,$post);
        $result? $this->success('编辑成功', 'Menu/index') : $this->error('编辑失败');
    }

    /**
     * 搜索查看
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function searchMenu(){
        $search_info = Request::instance()->get('search_info');
        $org = model('Menu','logic');
        $result = $org->searchMenus($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
    }
}