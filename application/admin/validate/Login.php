<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/7/4
 * Time: 11:17
 */

namespace app\admin\validate;

use think\Validate;


class Login extends Validate
{
    protected $rule = [
        'account'  =>  'require|max:25',
        'password'  =>  'require',
    ];
}