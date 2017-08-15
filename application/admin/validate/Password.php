<?php
/**
 * 用户
 * 数据服务接口层
 * cx
 * 20170617
 */

namespace app\admin\validate;

use think\Validate;


class Password extends Validate
{

    protected $rule = [
        ['password','require|min:6','密码最少6位'],
        ['password_new1','require|min:6','密码最少6位'],
    ];
    protected $scene = [
        'edit' => ['password_new1'],
    ];

}