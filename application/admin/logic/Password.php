<?php
/**
 * Created by PhpStorm.
 * user: xdw
 * Date: 2017/07/15
 * Time: 9:45
 */

namespace app\admin\logic;

use think\Model;

class Password extends Model
{

    /**
     * 编辑密码信息
     * @author  xdw
     * @date  20170725
     */
    public function editPassword($user_id,$post){

        $service = model('Password', 'service');
        $result = $service->editPassword($user_id,['password'=>$post['password_new1']]);
        return $result;
    }

    /**
     * 对比原密码
     * @author  xdw
     * @date  20170725
     */
    public function checkPassword($user_id,$password_old){

        $service = model('Password', 'service');
        $db_password = $service->getPassword($user_id);
        if(zw_compare_password($password_old,$db_password)){
            return true;
        }
        return false;
    }

}