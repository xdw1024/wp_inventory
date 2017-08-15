<?php
/**
 * 用户
 * 数据服务接口层
 * cx
 * 20170617
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class User extends Model
{

    /**
 * 人员列表查询
 *
 * @author  cx
 * @date  20170617
 *
 */
    public function getUsers()
    {
        $users = Db::name('user')
            ->where('status',1)
            ->order('sort', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
//        $users = Db::view('user', '*')
//            ->where('status',1)
//            ->order('sort', 'desc')
//            ->paginate(15);
        return $users;
    }

    /**
     * 人员信息查询
     *
     * @author  cx
     * @date  20170617
     *
     */
    public function getUserInfo($user_id)
    {
        $user = Db::name('user', '*')->where(['id' => $user_id])->find();
        return $user;
    }

    public function userView($user_id)
    {
        $user = Db::name('role_user', '*')->where(['user_id' => $user_id])->select();
        return $user;
    }

    /**
     * 刪除人员
     *
     * @author  cx
     * @date  20170617
     *
     */
    public function deleteUser($user_id)
    {
        $result = Db::name('user')->where(['id' => $user_id])->delete();
        return $result;
    }

    /**
     * 新增人员
     *
     * @author  cx
     * @date  20170617
     *
     */
    public function addUser($user)
    {
        $result = Db::name('user')->insertGetId($user,false);
        if(!$result){
            $post = [
                'account'       => $user['account'],
            ];
            $result = Db::name('user')->where($post)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 修改人员信息
     *
     * @author  cx
     * @date  20170617
     *
     */
    public function editUser($user_id,$user)
    {
        $result = Db::name('user')->where(['id' => $user_id])->update($user);
        return $result;
    }

    /**
     * 搜索查看
     *
     * @author  sy
     * @date  20170619
     *
     */
    public function searchUser($map)
    {
        $result = Db::name('user', '*')
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
     * 获取角色信息
     *
     * @author  sy
     * @date  20170621
     *
     */
    public function getUserRole()
    {
        $result = Db::view('role', '*')->select();
        return $result;
    }

    /**
     * 获取角色信息
     *
     * @author  sy
     * @date  20170621
     *
     */
    public function getRole($role_id)
    {
        $result = Db::view('role', '*')->where('id',$role_id)->find();
        return $result;
    }

    /**
     * 获取人员组织
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function getUserOrg(){
        $result = Db::name('org')->select();
        return $result;
    }

    /**
     * 新增人员角色
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function addUserRole($data){
        $result = Db::name('role_user')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('role_user')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 新增人员组织
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function addUserOrg($data){
        $result = Db::name('org_user')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('org_user')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 编辑人员角色
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function editUserRole($data){
        $result = Db::name('role_user')->where(['user_id' => $data['user_id']])->update(['role_id' => $data['role_id']]);
        return $result;
    }

    /**
     * 编辑人员组织
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function editUserOrg($data){
        $result = Db::name('org_user')->where(['user_id' => $data['user_id']])->update(['org_id' => $data['org_id']]);
        return $result;
    }

    /**
     * 查看人员角色
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function getRoleUser($user_id){
        $role_id = Db::name('role_user')->where('user_id',$user_id)->find();
        return $role_id;
    }

    /**
     * 查看人员组织关联信息
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function getOrgUser($user_id){
        $result = Db::name('org_user')->where('user_id',$user_id)->find();
        return $result;
    }

    /**
     * 查看人员组织
     *
     * @author  sy
     * @date  20170623
     *
     */
    public function getOrg($org_id){
        $org = Db::name('org')->where('id',$org_id)->find();
        return $org;
    }

    public function checkUserExist($account){
        $result = Db::name('user')->where('account',$account)->find();
        return $result;
    }
}