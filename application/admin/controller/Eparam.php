<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/7/16
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;

class Eparam extends Adminbase{
    /**
     * E积分管理
     *
     * @author  xdw
     * @date  20170716
     */
    public function index()
    {
        $model = model('Eparam','logic');
        $eparam = $model->getEParam();
        $this->assign('eparam', $eparam);
        if (Request::instance()->isAjax())
        {
            $page = $eparam->render();
            $data = $eparam->all();
            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
        }
        return $this->fetch('index');
    }

    /**
     * 新增 E积分
     *
     * @author  xdw
     * @date  20170716
     */
    public function addEparam()
    {
        return $this->fetch('addEParam');
    }

    /**
     * 编辑 E积分
     *
     * @author  xwd
     * @date  20170716
     */
    public function editEParam()
    {
        if (!input('id')){
            $this->error('参数错误');
        }
        //获取门店组织
        $model = model('eparam','service');
        $eparam_id = input('id');
        $eparam_data = $model->getEParamInfo($eparam_id);
        $this->assign('eparam_data',$eparam_data);
        return $this->fetch('editEParam');
    }

    /**
     * E积分保存(新增、编辑)
     *
     * @author  xdw
     * @date  20170716
     */
    public function save(){
        $post = [
            'pluname'       => Request::instance()->post('pluname'),
            'price'       => Request::instance()->post('price'),
        ];
        //新增
        if(!input('post.id')){
            //验证器判断
            $validate = $this->validate($post,'eparam.add');
            if($validate !== true)
            {
                return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
            }
            $model = model('Eparam','logic');
            $result = $model->addEparam($post);
            $result? $this->success('新增成功', 'Eparam/index') : $this->error('新增失败');
        }
        //编辑
        $Eparam_id = input('post.id');
        $validate = $this->validate($post,'Eparam.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $model = model('Eparam','logic');
        $result = $model->editEparam($Eparam_id,$post);
        $result? $this->success('编辑成功', 'Eparam/index') : $this->error('编辑失败');
    }

    /**
     * 刪除E积分
     *
     * @author  xdw
     * @date  20170716
     */
    public function deleteEParam()
    {
        if (!input('id'))
        {
            return zw_sprint_result('请选择删除E积分券', '', FAIL_CODE);
        }
        $eparam_id = Request::instance()->post('id');
        $model = model('Eparam','service');
        $result = $model->deleteEParam($eparam_id);
        return $result ? zw_sprint_result('删除成功', $result) : zw_sprint_result('删除失败', '', FAIL_CODE);
    }

    /**
     * 搜索查看
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function searchEParam(){
        $search_info = Request::instance()->get('search_info');
        $model = model('Eparam','logic');
        $result = $model->searchEParam($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
    }

}