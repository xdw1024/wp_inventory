<?php
/**
 * 所有控制器的基类
 * cx
 * 20170616
 */

namespace app\admin\controller;

use think\Controller;

class Adminbase extends Controller
{
    public function _initialize()
    {
        if (!lw_is_login())
        {
            $this->redirect(zw_build_url('login/index'));
        }
//        /** 判断是否有权限进行操作*/
//        if (!zw_authentication_current_user($this->request->controller(), $this->request->action()))
//        {
////            $this->error('对不起，您没有此操作权限');
//        }
    }
}