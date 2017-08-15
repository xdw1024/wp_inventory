<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/19
 * Time: 11:29
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Role extends Model
{
    /**
     * 角色信息查询
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function getRole(){
        $role = Db::view('role', '*')
            ->order('sort', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $role;
    }

    public function role($id){
        $role = Db::name('menu_role', '*')
            ->where('role_id',$id)
            ->select();
        return $role;
    }

    /**
     * 角色信息查询（编辑）
     *
     * @author  sy
     * @date  20170617
     *
     */
    public function getRoleInfo($role_id)
    {
        $role = Db::view('role', '*')->where(['id' => $role_id])->find();
        return $role;
    }

    /**
     * 新增角色
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function add($data)
    {
        $result = Db::name('role')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('role')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 编辑角色信息
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function edit($role_id,$data)
    {
        $result = Db::name('role')->where(['id' => $role_id])->update($data);
        return $result;
    }

    /**
     * 刪除角色
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function deleteRole($role_id)
    {
        $result = Db::name('role')->where(['id' => $role_id])->delete();
        return $result;
    }

    /**
     * 搜索查看
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function searchRole($map)
    {
        $result = Db::view('role', '*')
            ->where($map)
            ->order('sort', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

    /**
     * 刪除角色
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function getMenu()
    {
        $result = Db::name('menu')->order('level', 'asc')->select();
        return $result;
    }

    /**
     * 添加角色菜单关联
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function getMenuRole($role_id,$menu_id)
    {
//        return $menu_id;
        $result = Db::name('menu_role')->insertGetId([ 'role_id' => $role_id,'menu_id' => $menu_id]);
        if(!$result){
            $result = Db::name('menu_role')->where([ 'role_id' => $role_id,'menu_id' => $menu_id])->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 刪除角色关联
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function roleDelete($role_id)
    {
        $result = Db::name('menu_role')->where(['id' => $role_id])->delete();
        return $result;
    }
}