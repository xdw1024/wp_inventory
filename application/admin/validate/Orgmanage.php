<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 10:29
 */

namespace app\admin\validate;

use think\Validate;


class Orgmanage extends Validate
{

    protected $rule = [
        ['org_type','require','请设置单位类型'],
        ['parent_org_id','require','请设置上级组织id'],
    ];
    protected $scene = [
        'edit' => ['org_type','parent_org_id']
    ];

}