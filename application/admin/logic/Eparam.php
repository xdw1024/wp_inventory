<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/07/15
 * Time: 9:45
 */

namespace app\admin\logic;

use think\Model;

class Eparam extends Model
{
    /**
     * 获取E积分信息
     *
     * @author  xdw
     * @date  20170716
     */
    public function getEParam(){
        $org = model('Eparam', 'service');
        $result = $org->getEParam();
        return $result;
    }

    /**
     * 新增E积分信息
     *
     * @author  xdw
     * @date  20170716
     */
    public function addEParam($post){
        $model = model('Eparam', 'service');
        $result = $model->addEParam($post);
        return $result;
    }

    /**
     * 编辑E积分信息
     *
     * @author  xdw
     * @date  20170716
     */
    public function editEParam($eparam_id,$post){
        $org = model('Eparam', 'service');
        $result = $org->editEParam($eparam_id,$post);
        return $result;
    }

    /**
     * E积分信息搜索
     *
     * @author  xdw
     * @date  20170716
     */
    public function searchEParam($search_info){
        $map['pluname'] = ['like','%'.$search_info.'%'];
        $modle = model('Eparam','service');
        $result = $modle->searchEParam($map);
        return $result;
    }

}