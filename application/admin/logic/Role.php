<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/19
 * Time: 11:27
 */

namespace app\admin\logic;

use think\Model;

class Role extends Model
{
    /**
     * 获取角色信息
     *
     * @author  sy
     * @date  20170619
     */
    public function getRole(){
        $org = model('Role', 'service');
        $result = $org->getRole();
        return $result;
    }

    /**
     * 角色信息搜索
     *
     * @author  sy
     * @date  20170622
     */
    public function searchRole($search_info){
        $map['role_name|describe']  = ['like','%'.$search_info.'%'];
        $org = model('Role','service');
        $result = $org->searchRole($map);
        return $result;
    }

    /**
     *添加角色菜单关联
     *
     * @author  sy
     * @date  20170623
     */
    public function getMenuRole($role_id,$menu_id){
        $org = model('Role','service');
        $data = $org->role($role_id);
        if(!empty($data)){
            foreach($data as $k =>$v){
                $org->roleDelete($v['id']);
            }
        }
        if(empty($menu_id)){
            return false;
        }
        $result = $org->role($role_id);
        foreach ($menu_id as $v) {
            $result = $org->getMenuRole($role_id, $v);
        }
        return $result;
    }
//    /**
//     * 获取角色信息
//     *
//     * @author  sy
//     * @date  20170619
//     */
//    public function getRoleInfo($role_id){
//        $org = model('Role', 'service');
//        $result = $org->role($role_id);
//        foreach($result as $key => $value){
//            if($value[])
//        }
//        return $result;
//    }
}