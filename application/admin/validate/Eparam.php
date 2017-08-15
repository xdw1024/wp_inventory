<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 10:29
 */

namespace app\admin\validate;

use think\Validate;


class Eparam extends Validate
{

    protected $rule = [
        ['pluname','require','商品券名称不能为空'],
        ['price','require','面值不能为空'],
    ];
    protected $scene = [
        'add' => ['pluname','price'],
        'edit' => ['pluname','price']
    ];

}