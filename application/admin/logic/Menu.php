<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 9:45
 */

namespace app\admin\logic;

use think\Model;

class Menu extends Model
{
    /**
     * 获取菜单信息
     *
     * @author  sy
     * @date  20170620
     */
    public function getMenu(){
        $org = model('Menu', 'service');
        $result = $org->getMenu();
        foreach ($result as $key => $value){
           if($value['parent_id'] == '0'){
               $value['parent_name'] = '一级菜单';
           }
           else{
               $parent_data = $org->getParentMenu($value['parent_id']);
               $value['parent_name'] = $parent_data['menu_name'];
           }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * 新增菜单信息
     *
     * @author  sy
     * @date  20170620
     */
    public function addMenu($post){
        if(empty($post['parent_id'])){
            $post['level'] = '1';
        }
        else{
            $post['level'] = '2';
        }
        $org = model('Menu', 'service');
        $data = $org->getMenu();
//        foreach ($data as $key => $value){
//            if(($value['controller'] == $post['controller']) && ($value['method'] == $post['method'])){
//                return false;
//            }
//        }
        $result = $org->addMenu($post);
        return $result;
    }

    /**
     * 编辑菜单信息
     *
     * @author  sy
     * @date  20170620
     */
    public function editMenu($menu_id,$post){
        if($post['parent_id'] == '0'){
            $post['level'] = '1';
        }
        else{
            $post['level'] = '2';
        }
        $org = model('Menu', 'service');
        $result = $org->editMenu($menu_id,$post);
        return $result;
    }

    /**
     * 搜索菜单信息
     *
     * @author  sy
     * @date  20170620
     */
    public function searchMenu($map){
        $org = model('Menu', 'service');
        $result = $org->searchMenu($map);
        foreach ($result as $key => $value){
            if($value['parent_id'] == '0'){
                $value['parent_name'] = '一级菜单';
            }
            else{
                $parent_data = $org->getParentMenu($value['parent_id']);
                $value['parent_name'] = $parent_data['menu_name'];
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * 菜单信息搜索
     *
     * @author  sy
     * @date  20170622
     */
    public function searchMenus($search_info){
        $map['menu_name|controller|method'] = ['like','%'.$search_info.'%'];
        $org = model('Menu','service');
        $result = $org->searchMenu($map);
        return $result;
    }
}