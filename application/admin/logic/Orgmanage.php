<?php
/**
 * 门店管理
 * 业务逻辑层
 * cx
 * 20170616
 */

namespace app\admin\logic;

use think\Model;

class Orgmanage extends Model
{

    /**
     * 获取所有门店
     *
     * @author cx
     * @date  20170715
     */
    public function getAllOrg(){
        $org = model('Orgmanage', 'service');
        $result = $org->getAllOrg();
        $result = $this->getParamInfo($result);
        return $result;
    }

    /**
     * 根据门店id获取门店信息
     *
     * @author cx
     * @date  20170715
     */
    public function getOrgInfoByOrgCode($orgcode){
        $org = model('Orgmanage', 'service');
        $result = $org->getOrgInfoByOrgCode($orgcode);
        return $result;
    }

    /**
     * 保存门店参数修改数据
     *
     * @author cx
     * @date  20170715
     */
    public function savefixParam($orgcode,$post){
        //新节点，直接插入org表
        $logic = model('Org', 'logic');
        if(!$post['org_id']){
            $result = $logic->addNode($post['parent_org_id'],$post['orgname']);
            $data =[
                'orgcode' => $post['orgcode'],
                'type' => $post['org_type'],
                'org_id' => $result,
                'parent_org_id' => $post['parent_org_id'],
                'parent_org_name' => $post['parent_org_name'],
            ];
        }
        //修改旧节点
        elseif($post['parent_org_id'] == $post['old_parent_org_id']){
            //未修改所属组织
            $data =[
                'orgcode' => $post['orgcode'],
                'type' => $post['org_type'],
            ];
        }
        else{
            //先删除原节点，再重新插入
            $logic->deleteNode($post['org_id']);
            $result = $logic->addNode($post['parent_org_id'],$post['orgname']);
            $data =[
                'orgcode' => $post['orgcode'],
                'type' => $post['org_type'],
                'org_id' => $result,
                'parent_org_id' => $post['parent_org_id'],
                'parent_org_name' => $post['parent_org_name'],
            ];
        }
        $service = model('Orgmanage', 'service');
        $result = $service->savefixParam($data);
        return $result;
    }


    /**
     * 过滤字段参数
     *
     * @author cx
     * @date  20170715
     */
    public function getParamInfo($orgs)
    {
        $data = $orgs->all();
        foreach ($data as $key => $value){
            switch ($value['type']){
                case 1:
                    $value['type'] = '自营店';
                    $orgs[$key] = $value;
                    break;
                case 2:
                    $value['type'] = '承包店';
                    $orgs[$key] = $value;
                    break;
                case 3:
                    $value['type'] = '高速店';
                    $orgs[$key] = $value;
                    break;
                case 4:
                    $value['type'] = '虚拟店';
                    $orgs[$key] = $value;
                    break;
                case 5:
                    $value['type'] = '内部店';
                    $orgs[$key] = $value;
                    break;
                case 6:
                    $value['type'] = '商客门店';
                    $orgs[$key] = $value;
                    break;
            }
        }
        return $orgs;
    }

    public function searchOrg($search_info){
        $map['orgname|orgcode'] = ['like','%'.$search_info.'%'];
        $service = model('Orgmanage','service');
        $result = $service->searchOrg($map);
        $result = $this->getParamInfo($result);
        return $result;
    }

    public function getLevelOrg($level){
        $service = model('Orgmanage','service');
        $org_list = $service->getLevelOrg($level);
        $org_result = [];
        if (is_array($org_list) && count($org_list) !== 0)
        {
            foreach($org_list as $key=>$row)
            {
                // 默认展开两级 Mao 20170427
                $is_open = ($row['level'] > 2) ? false : true;
                // 判断是否有子节点
                $is_parent = ($row['rgt'] == $row['lft'] + 1) ? false : true;
                if($row['level'] >= $level){
                    $is_parent = false;
                }
                $org_result[] = [
                    'id'        => $row['id'],
                    'pId'       => $row['parent_org_id'],
                    'name'      => $row['org_name'],
                    'open'      => $is_open,
                    'isParent'  => $is_parent,
                ];
            }
        }
        return $org_result;
    }

    /**
     * 获取当前管理员可管理的门店
     * @author xdw
     * @date  20170722
     */
    public function getCurrentAdminManageOrgs(){
        $org = model('Orgmanage', 'service');
        $result = $org->getCurrentAdminManageOrgs();
        $result = $this->getParamInfo($result);
        return $result;
    }

    /**
     * 获取当前管理员可管理的组织
     * @author xdw
     * @date  20170722
     */
    public function getLevelOrgByCurrentAdmin($level){
        $service = model('Orgmanage','service');
        $org_list = $service->getLevelOrgByCurrentAdmin($level);
        $org_result = [];
        if (is_array($org_list) && count($org_list) !== 0)
        {
            foreach($org_list as $key=>$row)
            {
                // 默认展开两级 Mao 20170427
                $is_open = ($row['level'] > 2) ? false : true;
                // 判断是否有子节点
                $is_parent = ($row['rgt'] == $row['lft'] + 1) ? false : true;
                if($row['level'] >= $level){
                    $is_parent = false;
                }
                $org_result[] = [
                    'id'        => $row['id'],
                    'pId'       => $row['parent_org_id'],
                    'name'      => $row['org_name'],
                    'open'      => $is_open,
                    'isParent'  => $is_parent,
                ];
            }
        }
        return $org_result;
    }

}