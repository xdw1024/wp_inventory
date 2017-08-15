<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 10:29
 */

namespace app\admin\validate;

use think\Validate;


class Menu extends Validate
{

    protected $rule = [
        ['menu_name','require|min:3','角色名最少3位'],
    ];
    protected $scene = [
        'add' => ['role_name'],
        'edit' => ['role_name']
    ];

}