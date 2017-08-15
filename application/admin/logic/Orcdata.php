<?php
/**
 * 组织架构
 * 业务逻辑层
 * cx
 * 20170616
 */

namespace app\admin\logic;

use think\Model;

class Orcdata extends Model
{

    /**
     * 同步 门店表
     *
     * @author cx
     * @date  20170717
     */
    public function synShops()
    {
        $service = model('Orcdata', 'service');
        //获取现有的门店数量和可用的门店数量
        $count_now = $service->getLocalOrgCount();
        //获取oracle的门店数量和可用的门店数量
        $count_orc = $service->getOrcOrgCount();
        //对比数据是否一致，比对总数量和总的可用门店数量
        if($count_now['allShop'] == $count_orc['allShop']&&$count_now['enableShop'] == $count_orc['enableShop']){
            $res_msg =  '同步成功，未有新门店数据变动';
        }
        else{
            //把本地表清空，把新的数据插入
            $service->clearLocalOrgTable();
            $result = $service->synShops();
            //同步到org_type表
            $result = $this->setOrgTypeTable();
            //后面做日志，记录同步记录
            $res_msg = '同步成功';
        }
        return $res_msg;
    }

    public function setOrgTypeTable(){
        $service = model('Orcdata', 'service');
        //获取所有的torgmanage表数据
        $result = $service->getAlltOrgMangeTable();
        foreach ($result as $key => $value){
            //更新或插入org_type表
            $result = $service->updateOrgTypeTable($value['orgcode'],$value['orgname'],$value['isenable']);
        }
    }

    /**
     * 同步 商品表
     *
     * @author cx
     * @date  20170717
     */
    public function synGoods()
    {
        $service = model('Orcdata', 'service');
        //清空，重新插入
        $service->clearvSkuPluCat();
        $result = $service->synvSkuPluCat();
        //把本月lrdate录入的商品插入fyjc_new_plu_temp表
        return '同步成功';
    }

    /**
     * 同步 门店商品E积分销售表(大表50多万行数据)
     *
     * @author cx
     * @date  20170717
     */
    public function synShopsGoodsSaleE()
    {
        $service = model('Orcdata', 'service');
        $current_date = date('Ym');
//        $service->cleartProCertiInfo($current_date);
        //先跟本地的数据对比，有新的数据就插入
        $result = $service->syntProCertiInfo($current_date);
        return '同步成功';
    }


    /**
     * 同步 '月度'门店销售明细表
     *
     * @author xdw
     * @date  20170717
     */
    public function synShopMonthData($pg_table,$field_array)
    {
        $service = model('Orcdata', 'service');
        //获取现有的可用的月度门店销售明细数量
        $count_now = $service->getLocalShopMonthDataCount($pg_table);
        $count_now = current($count_now);
        //获取oracle中可用的月度门店销售明细数量
        $count_orc = $service->getOrcShopMonthDataCount($pg_table);
        //对比数据是否一致，（应该比对总数量和总的可用门店数量）
        if ($count_now == $count_orc) {
            $res_msg = '同步成功，未有新数据变动';
        } else {
            //把本地表清空，把新的数据插入
            $service->clearLocalShopMonthDataTable($pg_table);
            $result = $service->synShopMonthData($pg_table,$field_array);
            //后面做日志，记录同步记录
            $res_msg = '同步成功';
        }
        return $res_msg;
    }

    /**
     * 同步 门店商品E积分主表
     *
     * @author cx
     * @date  20170717
     */
    public function synE()
    {
        $service = model('Orcdata', 'service');
        //先跟本地的数据对比，有新的数据就插入
        $result = $service->syntProBillHead();
        return '同步成功';
    }

    /**
     * 同步 团购主表
     *
     * @author cx
     * @date  20170717
     */
    public function synGroupBuy()
    {
        $service = model('Orcdata', 'service');
        //先跟本地的数据对比，有新的数据就插入
        $current_date = date('Y-m');
        $result = $service->syntWslXsHead($current_date);
        //自动识别备注
        $this->autoCheckGroupBuyRemark($current_date);
        return '同步成功';
    }

     /**
     * 同步 门店商品团购销售表
     *
     * @author cx
     * @date  20170717
     */
    public function synShopGoodsGroupBuy()
    {
        $service = model('Orcdata', 'service');
        //先跟本地的数据对比，有新的数据就插入
        $current_date = '1PFXS'.date('Y');
        $result = $service->syntWslXsBody($current_date);
        return '同步成功';
    }

    /**
     * 自动识别团购备注
     *
     * @author cx
     * @date  20170717
     */
    public function autoCheckGroupBuyRemark($current_date)
    {
        $service = model('Orcdata', 'service');
        $result = $service->getMonthGroupBuy($current_date);
        foreach($result as $key => $value){
            if(!$value['fix_type']) {
                $fix_type = false;
                //如果未修正，就进行修正
                if ($value['billtype'] == '5') {
                    $fix_type = 8;
                } elseif (($value['remark'] == '团购')) {
                    $fix_type = 1;
                } elseif (($value['remark'] == '批发')) {
                    $fix_type = 2;
                } elseif (($value['remark'] == '特殊')) {
                    $fix_type = 3;
                } elseif (($value['remark'] == '自用')) {
                    $fix_type = 4;
                } elseif (($value['remark'] == '油非互动')) {
                    $fix_type = 5;
                } elseif (($value['remark'] == '小站买断')) {
                    $fix_type = 6;
                } elseif (($value['remark'] == '合资公司')) {
                    $fix_type = 7;
                }
                if ($fix_type) {
                    //更新数据
                    $service->updateGroupBuyFixType($value, $fix_type);
                }
            }
        }
        return '同步成功';
    }



}