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
class Org extends Model
{

    /**
     * 获取根节点
     *
     * @return bool | array 存在返回节点，不存在则返回False
     * @author  zzwu cx
     * @date  20170616
     *
     */
    public function getRootNodeId()
    {
        $result = Db::view('org', 'id')->where(['parent_org_id' => '-1'])->select();
        if (is_array($result) && count($result) != 0)
        {
            return reset($result)['id'];
        }
        return false;
    }


    /**
     * 添加根节点
     *
     * @return int | bool 参数错误返回false(注意0和false的区别)
     * @author  zzwu cx
     * @date  20170616
     *
     */
    public function addRootNode($name)
    {
        $result = Db::name('org')->insertGetId([
            'parent_org_id' => '-1',
            'org_name' => $name,
            'level' => '1',
            'lft' => '1',
            'rgt' => '2',
        ]);
        if(!$result){
            $result = Db::name('org')->where([
                'parent_org_id' => '-1',
                'org_name' => $name,
                'level' => '1',
                'lft' => '1',
                'rgt' => '2',
            ])->find();
            $result = $result['id'];
        }
        return $result ? $result : false;
    }

    /**
     * 获取根节点的3级子节点
     *
     * @return bool | array 存在返回节点，不存在则返回False
     * @author  zzwu cx
     * @date  20170617
     *
     */
    public function getTree($level)
    {

        $result = Db::view('org', '*')
            ->where('level', '<=', $level)
            ->order('id')
            ->select();
        return $result ? $result : false;
    }

    /**
     * 获取节点的信息
     *
     * @author  zzwu cx
     * @date  20170617
     *
     */
    public function getNodeById($node_id)
    {
        $result = Db::view('org', '*')->where(['id' => $node_id])->select();
//        $result = Db::query('SELECT * FROM qzzy_org WHERE "id"='.$node_id);
        if (is_array($result) && count($result) != 0)
        {
            return reset($result);
        }
        return false;
    }

    /**
     * 插入一个新的节点
     *
     * @author  zzwu cx
     * @date  20170617
     *
     */
    public function addNode($pre_node,$node_name){
        Db::name('org')->where('lft', '>', $pre_node['lft'])->update(['lft' => ['exp', 'lft+2']]);
        Db::name('org')->where('rgt', '>', $pre_node['lft'])->update(['rgt' => ['exp', 'rgt+2']]);
        $result = Db::name('org')->insertGetId([
            'parent_org_id' => $pre_node['id'],
            'org_name' => $node_name,
            'level' => $pre_node['level'] + 1,
            'lft' => $pre_node['lft']+1,
            'rgt' => $pre_node['lft']+2,
            'sort' => 1,
        ]);
        if(!$result){
            $result = Db::name('org')->where([
                'parent_org_id' => $pre_node['id'],
                'org_name' => $node_name,
                'level' => $pre_node['level'] + 1,
                'lft' => $pre_node['lft']+1,
                'rgt' => $pre_node['lft']+2,
                'sort' => 1,
            ])->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 插入一个新的节点
     *
     * @author  Mao cx
     * @date  20170617
     *
     */
    public function getNextLevelNode($current_node,$next_level){
        $result = Db::name('org')
            ->where('level', 'between',array($current_node['level'], $next_level))
            ->where('lft', '>', $current_node['lft'])
            ->where('rgt', '<', $current_node['rgt'])
            ->select();
        return $result;
    }

    /**
     * 插入一个新的节点
     *
     * @author  Mao cx
     * @date  20170617
     *
     */
    public function editNodeName($node_id,$name){
        $result = Db::name('org')->where(['id' => $node_id])->update(['org_name' => $name]);
        return $result;
    }

    /**
     * 插入一个新的节点
     *
     * @author  Mao cx
     * @date  20170617
     *
     */
    public function deleteNode($current_node){
        //删除所有子节点
        Db::name('org')->where('lft', '>=', $current_node['lft'])->where('rgt', '<=', $current_node['rgt'])->delete();
        //中间缺少的数
        $number = $current_node['rgt'] - $current_node['lft'] + 1;
        //后续节点lft减中间缺少的数
        Db::name('org')->where('lft', '>', $current_node['rgt'])->update(['lft' => ['exp', "lft-$number"]]);
        //后续节点rgt减中间缺少的数
        $result = Db::name('org')->where('rgt', '>', $current_node['rgt'])->update(['rgt' => ['exp', "rgt-$number"]]);
        return $result;
    }

    /**
     * 获取所有的叶子节点
     *
     * @author  Mao cx
     * @date  20170617
     *
     */
    public function getAllLeafNode($current_node){
        $result = Db::name('org')
            ->where('id', $current_node)
            ->order('id')
            ->select();
        if(current($result)['level']==4){
            return $result;
        }
        $result = Db::name('org')
            ->where(['level'=>'4'])
            ->where('lft', '>', current($result)['lft'])
            ->where('rgt', '<', current($result)['rgt'])
            ->order('id')
            ->select();
        return $result;
    }

    /**
     * 获取节点的信息
     *
     * @author  zzwu cx
     * @date  20170617
     *
     */
    public function getNodeByUserId($userid)
    {
        $result = Db::view('org_user', 'org_id')->where(['user_id' => $userid])->find();
        return $result;
    }

    /**
     * 获取节点的信息
     *
     * @author  zzwu cx
     * @date  20170617
     *
     */
    public function getOrgcodeByOrgId($org_ids)
    {
        $result = Db::view('org_type', 'orgcode')->where('org_id','in',$org_ids)->select();
        return $result;
    }

    public function getNodeByLevelId($level){
        $result = Db::view('org', 'id,org_name')
            ->where(['level'=>$level])
            ->order('sort','desc')
            ->select();
        return $result;
    }

    /**
     * 获取当前用户可管辖的指定层级组织
     * @author xdw
     * @date 20170722
     */
    public function getNodeByCurrentAdminAndLevel($level){
        $result = '';
        $admin_link_note = $this->getNodeByUserId(zw_get_admin_user_id());
        if(!empty($admin_link_note) && count($admin_link_note)!=0) {
            $top_note = Db::name('org')
                ->where('id', $admin_link_note['org_id'])
                ->order('id')
                ->select();
            if(!empty($top_note) && count($top_note)!=0) {
                $result = Db::name('org')
                    ->field('id,org_name')
                    ->where('level', '=', $level)
                    ->where('lft', '>=', current($top_note)['lft'])
                    ->where('rgt', '<=', current($top_note)['rgt'])
                    ->order('sort','DESC')
                    ->select();
            }
        }

        return $result;
    }

    /**
     * 获取当前管理员可管理的组织、门店
     * @author xdw
     * @date 20170722
     */
    public function getCurrentAdminManageOrgNotes($level){
        $trees = '';
        $admin_link_note = $this->getNodeByUserId(zw_get_admin_user_id());
        if(!empty($admin_link_note) && count($admin_link_note)!=0) {
            $top_note = Db::name('org')
                ->where('id', $admin_link_note['org_id'])
                ->order('id')
                ->select();
            if(!empty($top_note) && count($top_note)!=0) {
                $trees = Db::name('org')
                    ->where('level', '<=', $level)
                    ->where('lft', '>=', current($top_note)['lft'])
                    ->where('rgt', '<=', current($top_note)['rgt'])
                    ->order('sort','DESC')
                    ->select();
            }
        }

       return $trees;
    }

    /**
     * 获取当前管理员可管理的组织、门店 ids
     * @author xdw
     * @date 20170722
     */
    public function getCurrentAdminManageOrgNoteIds(){
        $note_ids =array();
        $admin_link_note = $this->getNodeByUserId(zw_get_admin_user_id());
        if(!empty($admin_link_note) && count($admin_link_note)!=0) {
            $top_note = Db::name('org')
                ->where('id', $admin_link_note['org_id'])
                ->order('id')
                ->select();
            if(!empty($top_note) && count($top_note)!=0) {
                $notes = Db::name('org')
                    ->field('id')
                    ->where('lft', '>=', current($top_note)['lft'])
                    ->where('rgt', '<=', current($top_note)['rgt'])
                    ->order('id')
                    ->select();
                foreach($notes as $item){
                    $note_ids[] = $item['id'];
                }
            }
        }

        return $note_ids;
    }

    public function getNodeByParentIdAndLevelId($org_id){
        $node = $this->getNodeById($org_id);
        //分公司id
        if($node['level']== 2){
            $result = Db::view('org', 'id,org_name')
                ->where('level',3)
                ->where(['parent_org_id'=>$org_id])
                ->order('sort','desc')
                ->select();
        }
        //分区id
        elseif($node['level']== 3){
            $result = Db::view('org', 'id,org_name')
                ->where('level',4)
                ->where(['parent_org_id'=>$org_id])
                ->order('sort','desc')
                ->select();
        }

        return $result;
    }


}