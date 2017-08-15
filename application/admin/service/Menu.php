<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 9:48
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Menu extends Model
{
    /**
     * 全部菜单信息查询
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function getMenu(){
        $role = Db::view('menu', '*')
            ->order('sort', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $role;
    }

    /**
     * 菜单等级信息
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function getMenuData(){
        $data = Db::view('menu', '*')
            ->where('level','1')
            ->order('sort', 'desc')
            ->select();
        return $data;
    }

    /**
     * 父级菜单信息
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function getParentMenu($data){
        $data = Db::view('menu', '*')
            ->where('id',$data)
            ->find();
        return $data;
    }

    /**
     * 父级菜单信息
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function getMenuInfo($menu_id){
        $data = Db::view('menu', '*')
            ->where('id',$menu_id)
            ->find();
        return $data;
    }

    /**
     * 新增菜单
     *
     * @author  sy
     * @date  20170620
     *
     */
    public function addMenu($data)
    {
        $result = Db::name('menu')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('menu')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 编辑菜单信息
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function editMenu($menu_id,$data)
    {
        $result = Db::name('menu')->where(['id' => $menu_id])->update($data);
        return $result;
    }

    /**
     * 刪除角色
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function deleteMenu($menu_id)
    {
        $result = Db::name('menu')->where(['id' => $menu_id])->delete();
        return $result;
    }

    /**
     * 搜索查看
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function searchMenu($map)
    {
        $result = Db::view('menu', '*')
            ->where($map)
            ->order('sort', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }
}