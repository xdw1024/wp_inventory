<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/07/15
 * Time: 9:48
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Eparam extends Model
{
    /**
     * E积分查询
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function getEParam(){
        $role = Db::view('plu_e_param', '*')
            ->order('id', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $role;
    }

    /**
     * 获取  E积分信息
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function getEParamInfo($eparam_id){
        $role = Db::view('plu_e_param', '*')->where('id',$eparam_id)->find();
        return $role;
    }

    /**
     * 新增 E积分
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function addEParam($data)
    {
        $result = Db::name('plu_e_param')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('plu_e_param')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 编辑 E积分信息
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function editEParam($eparam_id,$data)
    {
        $result = Db::name('plu_e_param')->where(['id' => $eparam_id])->update($data);
        return $result;
    }

    /**
     * 刪除 E积分信息
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function deleteEParam($eparam_id)
    {
        $result = Db::name('plu_e_param')->where(['id' => $eparam_id])->delete();
        return $result;
    }

    /**
     * 搜索查看
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function searchEParam($map)
    {
        $result = Db::view('plu_e_param', '*')
            ->where($map)
            ->order('id', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

}