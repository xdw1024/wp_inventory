<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/19
 * Time: 14:58
 */

namespace app\admin\validate;

use think\Validate;


class Role extends Validate
{

    protected $rule = [
        ['role_name','require|min:3','角色名最少3位'],
        ['describe','require|min:3','角色描述最少3位'],
    ];
    protected $scene = [
        'add' => ['role_name','describe'],
        'edit' => ['role_name','describe']
    ];

}