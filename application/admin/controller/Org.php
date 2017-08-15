<?php
/**
 * 组织架构
 * 控制器
 * cx
 * 20170616
 */

namespace app\admin\controller;

use think\Request;

class Org extends Adminbase
{

    /**
     * 组织架构树查询显示
     *
     * @author  zzwu cx
     * @date  20170616
     */
    public function index()
    {
        //获取树结构数据
        $org = model('Org','logic');
        $tree = $org->getTree(3);
        //渲染界面
        $this->assign('org', json_encode($tree));
        return $this->fetch('index');
    }


    /**
     * 插入一个新的节点
     *
     * @author  zzwu cx
     * @date  20170617
     */
    public function addNode()
    {
        if (!input('post.treeNodeId'))
        {
            return zw_sprint_result('无法获取节点信息，请刷新后重试', '', FAIL_CODE);
        }
        $node_id = Request::instance()->post('treeNodeId');
        $node_name = Request::instance()->post('treeNodeName');

        //插入节点
        $org = model('Org','logic');
        $result = $org->addNode($node_id,$node_name);

        return $result ? zw_sprint_result('插入成功', $result) : zw_sprint_result('插入失败', '', FAIL_CODE);
    }

    /**
     * 获取某个节点的下一层子节点
     *
     * @author  Mao cx
     * @date  20170617
     */
    public function getChildNodes()
    {
        $level = input('post.level/d');
        $node_id = input('post.node_id/d');
        //获取下一层子节点
        $org = model('Org','logic');
        $result = $org->getChildNodes($node_id,$level);
        return zw_sprint_result('ok', $result);
    }

    /**
     * 修改名字
     *
     * @author  Mao cx
     * @date  20170617
     */
    public function editNodeName()
    {
        if (!input('post.treeNodeId'))
        {
            return zw_sprint_result('无法获取节点信息，请刷新后重试', '', FAIL_CODE);
        }
        $node_id = Request::instance()->post('treeNodeId');
        $name = Request::instance()->post('treeNodeName');
        //修改单位名字
        $org = model('Org', 'service');
        $result = $org->editNodeName($node_id,$name);
        return $result ? zw_sprint_result('重命名成功') : zw_sprint_result('重命名失败', '', FAIL_CODE);
    }

    /**
     * 删除节点及所有子节点
     *
     * @author  Mao cx
     * @date  20170617
     */
    public function deleteNode()
    {
        if (!input('post.treeNodeId'))
        {
            return zw_sprint_result('无法获取节点信息，请刷新后重试', '', FAIL_CODE);
        }
        $node_id = Request::instance()->post('treeNodeId');

        //删除节点及所有子节点
        $org = model('Org','logic');
        $result = $org->deleteNode($node_id);
        return $result ? zw_sprint_result('删除成功') : zw_sprint_result('删除失败', '', FAIL_CODE);
    }
}