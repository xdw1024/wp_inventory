<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 2017/7/12
 * Time: 7:31
 */
namespace app\admin\controller;

use think\Controller;

class Orcconnection extends Adminbase{
    public $conn;//oracle连接

    function _initialize()
    {
        if (!lw_is_login())
        {
            $this->redirect(zw_build_url('login/index'));
        }
        $this->conn = dw_getOracleConnection();
    }
}