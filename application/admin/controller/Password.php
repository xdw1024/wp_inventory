<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/7/16
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;

class Password extends Adminbase{
    /**
     * 密码管理
     *
     * @author  xdw
     * @date  20170725
     */
    public function index()
    {
        return $this->fetch('index');
    }

    /**
     * 验证原密码
     *
     * @author  xdw
     * @date  20170725
     */
    public function checkPassword()
    {
        if(!input('password_old')){
            return zw_sprint_result('原密码错误！','', FAIL_CODE);
        }
        $password_old = Request::instance()->post('password_old');
        $logic = model('Password','logic');
        $result = $logic->checkPassword(lw_get_current_admin_id(),$password_old);
        return ($result) ? zw_sprint_result('原密码正确', '') : zw_sprint_result('原密码错误！','', FAIL_CODE);
    }

    /**
     * 密码保存(新增、编辑)
     *
     * @author  xdw
     * @date  20170725
     */
    public function save(){
        $post = [
            'password_old'       => zw_set_password(Request::instance()->post('password_old')),
            'password_new1'       => zw_set_password(Request::instance()->post('password_new1')),
            'password_new2'       => zw_set_password(Request::instance()->post('password_new2')),
        ];

        //编辑
        $user_id = lw_get_current_admin_id();
        $validate = $this->validate($post,'Password.edit');
        if($validate !== true)
        {
            $this->error($validate);
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $model = model('Password','logic');
        $result = $model->editPassword($user_id,$post);
        $result? $this->success('修改成功', 'password/index') : $this->error('修改失败');
    }

}