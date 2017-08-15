<?php
/**
 * 用户
 * 数据服务接口层
 * cx
 * 20170617
 */

namespace app\admin\validate;

use think\Validate;


class User extends Validate
{

    protected $rule = [
        ['account','require|min:3','用户名最少3位'],
        ['password','require|min:6','密码最少6位'],
        ['user_name', 'require', '姓名不能为空']
    ];
    protected $scene = [
        'add' => ['account','password','user_name','duty_id'],
        'edit' => ['account','user_name','duty_id']
    ];

}