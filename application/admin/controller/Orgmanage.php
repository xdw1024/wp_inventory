<?php
/**
 * Created by PhpStorm.
 * User: cif
 * Date: 2017-07-15
 * Time: 11:56
 */

namespace app\admin\controller;

use think\Request;
use think\Session;

class Orgmanage extends Adminbase
{
    /**
     * 门店设置主页，能够设置门店的类型，门店放到组织架构树里面
     *
     * @author cx xdw
     * @date  20170722
     */
    public function index()
    {
        //获取所有门店
        Session::set('page', (input('page')? input('page') : 1));
        Session::set('search_info', (input('search_info') ? input('search_info') : ''));

        $org = model('Orgmanage','logic');
        $service = model('Org','service');
        $user_id = lw_get_current_admin_id();
        $orginfo = $service->getNodeByUserId($user_id);
        if($orginfo['org_id']==1){
            $orgs = $org->getAllOrg();
        }
        else{
            //获取当前管理员可管辖的组织、门店
            $orgs = $org->getCurrentAdminManageOrgs();
        }
        if (Request::instance()->isAjax())
        {
            $page = $orgs->render();
            $data = $orgs->all();
            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page,'page_num'=>session('page'),'search_info'=>session('search_info')]);
        }
        //渲染界面
        $this->assign('orgs', $orgs);
        return $this->fetch('index');
    }


    /**
     * 修正门店参数
     *
     * @author cx
     * @date  20170715
     */
    public function fixparam()
    {
        //判断是否传递id
        if (!input('orgcode')){
            $this->error('参数错误');
        }
        //获取人员基本属性
        $orgcode = input('orgcode');
        $org = model('Orgmanage','logic');
        $org = $org->getOrgInfoByOrgCode($orgcode);
        $search = model('Orgmanage','logic');
        //获取前3级的组织结构（不要门店）
        //$orgTree = $search->getLevelOrg(3);
        $orgTree = $search->getLevelOrgByCurrentAdmin(3);//筛选出当前管理员可管理的组织
        $this->assign('orgTree', json_encode($orgTree));
        $this->assign('org',$org);
        return $this->fetch('fixOrgParam');
    }

    /**
     * 保存门店修正信息
     *
     * @author  cx
     * @date  20170715
     */
    public function savefixParam()
    {
        $post = [
            'orgcode'             => Request::instance()->post('orgcode'),
            'org_type'             => Request::instance()->post('org_type'),
            'org_id'             => Request::instance()->post('org_id'),
            'parent_org_id'       => Request::instance()->post('parent_org'),
            'parent_org_name'       => Request::instance()->post('parent_org_name'),
            'orgname'       => Request::instance()->post('orgname'),
            'old_parent_org_id'       => Request::instance()->post('old_parent_org_id'),
        ];
        //编辑
        $orgcode = input('post.orgcode');
        $validate = $this->validate($post,'Orgmanage.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $org = model('Orgmanage','logic');
        $result = $org->savefixParam($orgcode,$post);
        $result? $this->success('提交成功', 'Orgmanage/index') : $this->error('提交失败');
    }

    /**
     * 门店搜索
     *
     * @author  sy
     * @date  20170620
     */
    public function searchOrg(){
        Session::set('page', (input('page')? input('page') : 1));
        Session::set('search_info', (input('search_info') ? input('search_info') : ''));

        $search_info = Request::instance()->get('search_info');
        $logic = model('Orgmanage','logic');
        $result = $logic->searchOrg($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page,'page_num'=>session('page'),'search_info'=>session('search_info')]);
    }
}