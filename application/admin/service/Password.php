<?php
/**
 * Created by PhpStorm.
 * user: xdw
 * Date: 2017/07/15
 * Time: 9:48
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Password extends Model
{
    /**
     * 编辑 密码信息
     * @author  xdw
     * @date  20170725
     */
    public function editPassword($user_id,$data)
    {
        $result = Db::name('user')->where('id',$user_id)->update($data);
        return $result;
    }

    /**
     * 获取 密码信息
     * @author  xdw
     * @date  20170725
     */
    public function getPassword($user_id)
    {
        $result = Db::name('user')->where('id',$user_id)->find();
        return $result['password']? $result['password']:'';
    }

}