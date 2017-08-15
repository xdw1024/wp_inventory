<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/7/4
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;

class Login extends Controller{
    /**
     * 登录页面
     *
     * @author  sy
     * @date  20170704
     */
    public function index()
    {
        if(lw_is_login())
        {
            //$this->redirect(zw_build_url('orgpaydetail/index'));
            $this->redirect(zw_build_url('index/index'));
        }
        $this->view->engine->layout('login');
        return $this->fetch();
    }

    /**
     * 登录验证
     *
     * @author  sy
     * @date  20170704
     */
    public function login()
    {
        if (!input('post.account') || !input('post.password'))
        {
            return zw_sprint_result('用户名或者密码不允许为空', '', FAIL_CODE);
        }
        $postData = Request::instance()->post();
        $post_data = [
            'account' => $postData['account'],
            'password'     => $postData['password'],
        ];
        //调用验证器验证
        $validate = $this->validate($postData,'Login');
        if (true != $validate) {
            return zw_sprint_result('输入有误，'.$validate,'',FAIL_CODE);
        }
        $org = model('Login','service');
        $has_user = $org->getUser($post_data);
        if (is_array($has_user) && count($has_user) != 0) {
            //校验密码
            if (zw_compare_password($post_data['password'], $has_user['password'])) // zw_compare_password
            {
                //更新写入此次登录信息（访问端ip、登录时间等）
                zw_set_session_in_login($has_user);
                //登录成功后重定向
                return zw_sprint_result('登陆成功', zw_build_url('orgpaydetail/index'));
            }
        }
        return zw_sprint_result('此帐号不存在或密码错误或者已经被限制使用，请联系超级管理员','',FAIL_CODE);
    }

    /**
     *
     * 退出登录
     *
     * @author  nlw
     * @date  20170412
     */
    public function logout()
    {
        if (zw_clear_session_in_logout())
        {
            $this->redirect(zw_build_url('login/index'));
        }

    }
}