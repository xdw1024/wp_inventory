<?php
/**
 * 组织架构
 * 数据服务接口层
 * cx
 * 20170616
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Orgmanage extends Model
{

    /**
     * 获取所有门店
     *
     * @author cx
     * @date  20170715
     *
     */
    public function getAllOrg()
    {
        $orgs = Db::name('org_type')
            ->order(['org_id'=>'desc','type'=>'desc','orgcode'])
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $orgs;
    }


    /**
     * 获取所有门店
     *
     * @author cx
     * @date  20170715
     *
     */
    public function getOrgcodeByParentId($org_id)
    {
        $orgs = Db::name('org_type')
            ->where(['parent_org_id'=>$org_id])
            ->order(['orgcode'])
            ->select();
        return $orgs;
    }

    /**
     * 根据门店id获取门店信息
     *
     * @author cx
     * @date  20170715
     *
     */
    public function getOrgInfoByOrgCode($orgcode)
    {
        $orgs = Db::name('org_type')
            ->where(['orgcode'=>$orgcode])
            ->find();
        return $orgs;
    }

    /**
     * 获取所有门店类型
     *
     * @author cx
     * @date  20170715
     *
     */
    public function getAllOrgType()
    {
        $result = Db::view('type_org', '*')->select();
        return $result;
    }

    /**
     * 保存门店参数修改数据
     *
     * @author cx
     * @date  20170715
     *
     */
    public function savefixParam($data)
    {
        $result = Db::name('org_type')->where(['orgcode' => $data['orgcode']])->update($data);
        return $result;
    }

    public function searchOrg($map){
        $org_model = model('Org', 'service');
        $org_ids = $org_model->getCurrentAdminManageOrgNoteIds();
        if($org_ids){
            $map['org_id'] = ['in',implode(',',$org_ids)];
        }
        $result = Db::name('org_type', '*')
            ->where($map)
            ->order('orgcode')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

    public function getLevelOrg($level){
        $result = Db::name('org')
            ->where('level', '<=', 3)
            ->select();
        return $result;
    }

    /**
     * 获取所有门店
     *
     * @author xdw
     * @date  20170722
     *
     */
    public function getCurrentAdminManageOrgs()
    {
        $map = [];
        $org_model = model('Org', 'service');
        $org_ids = $org_model->getCurrentAdminManageOrgNoteIds();
        if($org_ids){
            $map['org_id'] = ['in',implode(',',$org_ids)];
        }

        $orgs = Db::name('org_type')
            ->where($map)
            ->order(['org_id'=>'desc','type'=>'desc','orgcode'])
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $orgs;
    }

    /**
     * 获取当前管理员可管理的组织（不到门店级）
     * @author xdw
     * @date  20170722
     */
    public function getLevelOrgByCurrentAdmin($level){
        $map = [];
        $org_model = model('Org', 'service');
        $org_ids = $org_model->getCurrentAdminManageOrgNoteIds();
        if($org_ids){
            $map['id'] = ['in',implode(',',$org_ids)];
        }

        $result = Db::name('org')
            ->where($map)
            ->where('level', '<=', $level)
            ->order('sort','desc')
            ->select();
        return $result;
    }

}