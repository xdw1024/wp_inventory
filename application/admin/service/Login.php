<?php
/**
 * Created by PhpStorm.
 * user:sy
 * Date: 2017/7/4
 * Time: 11:23
 */

namespace app\admin\service;

use think\Model;
use think\Db;

class Login extends Model
{
    /**
     * 获取指派任务
     *
     * @author  sy
     * @date  20170704
     *
     */
    public function getUser($post_data){
        $has_user = Db::name('user')
            ->field('*')
            ->where(['account' => $post_data['account'], 'status' => TRUE_STATUS])
            ->find();
        return $has_user;
    }
}