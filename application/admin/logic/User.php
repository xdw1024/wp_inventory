<?php
/**
 * 用户
 * 业务逻辑层
 * cx
 * 20170617
 */

namespace app\admin\logic;

use think\Model;

class User extends Model
{

    /**
     * 人员信息查询显示
     *
     * @author  cx
     * @date  20170617
     */
    public function getUsers(){
        $org = model('user', 'service');
        $result = $org->getUsers();
        foreach ($result as $key => $value){
            //获取组织部门
            $orgs = $org->getOrgUser($value['id']);
            if (!empty($orgs)){
                $org_data = $org->getOrg($orgs['org_id']);
                $value['user_org'] = $org_data['org_name'];
            }
            else{
                $value['user_org'] = '';
            }
            //获取角色
            $user_role = $org->getRoleUser($value['id']);
            if (!empty($orgs)){
                $role = $org->getRole($user_role['role_id']);
                $value['user_role'] = $role['role_name'];;
            }
            else{
                $value['user_role'] = '';
            }
            $result[$key] = $value;
        }
//        print_r($result);
//        die();
        return $result;
    }

    /**
     * 人员信息搜索
     *
     * @author  sy
     * @date  20170622
     */
    public function searchUser($search_info){
        $map['user_name|account'] = ['like','%'.$search_info.'%'];
        $org = model('user','service');
        $result = $org->searchUser($map);
        foreach ($result as $key => $value){
            //获取组织部门
            $orgs = $org->getOrgUser($value['id']);
            if (!empty($orgs)){
                $org_data = $org->getOrg($orgs['org_id']);
                $value['user_org'] = $org_data['org_name'];
            }
            else{
                $value['user_org'] = '';
            }
            //获取角色
            $user_role = $org->getRoleUser($value['id']);
            if (!empty($orgs)){
                $role = $org->getRole($user_role['role_id']);
                $value['user_role'] = $role['role_name'];;
            }
            else{
                $value['user_role'] = '';
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * 新增人员
     *
     * @author  sy
     * @date  20170623
     */
    public function addUser($post,$role_id,$org_id){
        $org = model('user','service');
        $user_id = $org->addUser($post);
        //添加人员角色
        if((!empty($role_id)) && ($user_id != false)){
            $data_role =[
                'user_id' => $user_id,
                'role_id' => $role_id,
            ];
            $role_user = $org->addUserRole($data_role);
        }
        //添加人员组织
        if((!empty($org_id)) && ($user_id != false)){
            $data_org = [
                'user_id' => $user_id,
                'org_id' => $org_id,
            ];
            $org_user = $org->addUserOrg($data_org);
        }
        return $user_id;
    }

    /**
     * 编辑人员
     *
     * @author  sy
     * @date  20170623
     */
    public function editUser($user_id,$post,$role_id,$org_id){
        $org = model('user','service');
        //编辑人员角色
        if(!empty($role_id)){
            $data_role =[
                'user_id' => $user_id,
                'role_id' => $role_id,
            ];
                $role_user = $org->editUserRole($data_role);
        }
        //编辑人员组织
        if(!empty($org_id)){
            $data_org = [
                'user_id' => $user_id,
                'org_id' => $org_id,
            ];
            $org_user = $org->editUserOrg($data_org);
            if($org_user == '0'){
                $data_org = [
                    'user_id' => $user_id,
                    'org_id' => $org_id,
                ];
                $org->addUserOrg($data_org);
            }
        }
        $result = $org->editUser($user_id,$post);
        return $result;
    }

    /**
     * 编辑获取人员信息
     *
     * @author  sy
     * @date  20170623
     */
    public function getUserInfo($user_id){
        $org = model('user','service');
        $user = $org->getUserInfo($user_id);
        $user_role = $org->getRoleUser($user_id);
        $user_org = $org->getOrgUser($user_id);
        //获取人员角色
        if($user_role){
            $user['users_role'] = $user_role['role_id'];
        }
        else{
            $user['users_role'] = '';
        }
        //获取人组织
        if($user_org){
            $user['users_org'] = $user_org['org_id'];
            $role_name = $org->getOrg($user_org['org_id']);
            $user['role_name'] = $role_name['org_name'];
        }
        else{
            $user['users_org'] = '';
            $user['role_name'] = '';
        }
        return $user;
    }

    /**
     * 获取组织树
     *
     * @author  sy
     * @date  20170623
     */
    public function getUserOrg(){
        $org = model('user','service');
        $org_list = $org->getUserOrg();
        $org_result = [];
        if (is_array($org_list) && count($org_list) !== 0)
        {
            foreach($org_list as $key=>$row)
            {
                // 默认展开两级 Mao 20170427
                $is_open = ($row['level'] > 2) ? false : true;
                // 判断是否有子节点
                $is_parent = ($row['rgt'] == $row['lft'] + 1) ? false : true;
                $org_result[] = [
                    'id'        => $row['id'],
                    'pId'       => $row['parent_org_id'],
                    'name'      => $row['org_name'],
                    'open'      => $is_open,
                    'isParent'  => $is_parent,
                ];
            }
        }
        return $org_result;
    }
}