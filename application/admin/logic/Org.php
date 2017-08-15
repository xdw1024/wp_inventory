<?php
/**
 * 组织架构
 * 业务逻辑层
 * cx
 * 20170616
 */

namespace app\admin\logic;

use think\Model;

class Org extends Model
{

    /**
     * 获取树结构
     *
     * @author  zzwu cx
     * @date  20170616
     */
    public function getTree($level,$isGetAll=true)
    {
        $org = model('Org', 'service');
        //判断根结点存在与否
        if ($org->getRootNodeId() === False) {
            $org->addRootNode("root");
        }
        $tree_result = [];
        //// 获取root节点的所有子孙节点
        //$trees = $org->getTree($level);
        //获取当前管理员可管辖的节点
        $trees = $org->getCurrentAdminManageOrgNotes($level);

        if (is_array($trees) && count($trees) !== 0) {
            foreach ($trees as $key => $row) {
                // 默认展开两级 Mao 20170427
                $is_open = ($row['level'] > 2) ? false : true;
                // 判断是否有子节点
                if($isGetAll){
                    $is_parent = ($row['rgt'] == $row['lft'] + 1) ? false : true;
                }
                else{
                    $is_parent =false;
                }
                $tree_result[] = [
                    'id' => $row['id'],
                    'pId' => $row['parent_org_id'],
                    'name' => $row['org_name'],
                    'open' => $is_open,
                    'isParent' => $is_parent
                ];
            }
        }
        return $tree_result;
    }

    /**
     * 插入一个新的节点
     *
     * @author  zzwu cx
     * @date  20170617
     */
    public function addNode($node_id,$node_name){
        $org = model('Org', 'service');
        $pre_node = $org->getNodeById($node_id);
        if(!$pre_node)
        {
            return false;
        }
        //添加子节点
        $result = $org->addNode($pre_node,$node_name);
        return $result;

    }

    /**
     * 插入一个新的节点
     *
     * @author  Mao cx
     * @date  20170617
     */
    public function getChildNodes($node_id,$level){
        $org = model('Org', 'service');
        //当前节点
        $current_node = $org->getNodeById($node_id);
        // 获取当前节点的下一层子节点
        if($level == 0){
            $next_level = $current_node['level'] + 1;
        }else{
            $next_level = $level;
        }

        $node_list = $org->getNextLevelNode($current_node,$next_level);
        $childNodes = array();
        foreach ($node_list as $key => $node) {
            $childNodes[] = array(
                'id' => $node['id'],
                'pId' => $node['parent_org_id'],
                'name' => $node['org_name'],
                'open' => false,
                'isParent' => $node['lft'] + 1 != $node['rgt'],
                'level' => $node['level']
            );
        }
        return $childNodes;
    }

    /**
     * 删除节点及所有子节点
     *
     * @author  zzwu cx
     * @date  20170617
     */
    public function deleteNode($node_id){
        $org = model('Org', 'service');
        //当前节点
        $current_node = $org->getNodeById($node_id);
        if(empty($current_node))
        {
            return false;
        }
        //删除删除节点和所有子节点
        $result = $org->deleteNode($current_node);
        return $result;
    }
}