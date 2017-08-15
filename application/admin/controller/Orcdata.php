<?php
/**
 * 同步oracle 数据库表数据
 * Created by PhpStorm.
 * user: asus1
 * Date: 2017/7/12
 * Time: 7:31
 */
namespace app\admin\controller;

use think\Db;

class Orcdata extends Orcconnection {

    function index(){
        return $this->fetch();
    }

    //同步 月度门店销售明细表
    function synShopMonthData(){
//        if (!input('date')){
//            $this->error('请选择同步的月份');
//        }
//        $now_year_month = input('date');
        if($this->conn) {
            $now_year_month = date('Ym');
            $table_prefix = 'fyjc_';
            $orc_table = 'hscmp.tsalpludetail'.$now_year_month;//月度销售表
            $pg_table = 'tsalpludetail'.$now_year_month;//月度销售表
            //dw_synTableData($orc_table,$pg_table);
            //创建数据表，指定要转换数据类型的字段
            $field_array = [
                'hxtotal'=>'float',
                'hmtotal'=>'float',
                'xscount'=>'float',
            ];
            if(!dw_isTableExist($table_prefix.$pg_table)){
                //dw_createTable($pg_table,$field_array);
                $sql = 'CREATE TABLE '.$table_prefix.$pg_table.' AS (SELECT * FROM fyjc_tsalpludetailyyyymm)';//用模板表克隆
                $result = Db::execute($sql);
            }
            //同步数据
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synShopMonthData($pg_table,$field_array);
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 商品表（暂时不需要）
    function synGoods(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synGoods();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 门店表(已有数据可能变动，因此同步时全部删除再全部插入)
    function synShops(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synShops();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 门店商品E积分销售表(已有数据不会变动，因此同步时发现新的数据就插进入)
    function synShopsGoodsSaleE(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synShopsGoodsSaleE();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 E积分主表(已有数据不会变动，因此同步时发现新的数据就插进入)
    function synE(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synE();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 团购主表(已有数据不会变动，因此同步时发现新的数据就插进入)
    function synGroupBuy(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synGroupBuy();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

    //同步 门店商品团购销售表(已有数据不会变动，因此同步时发现新的数据就插进入)
    function synShopGoodsGroupBuy(){
        if($this->conn) {
            $logic = model('Orcdata','logic');
            try{
                $res_msg = $logic->synShopGoodsGroupBuy();
            }
            catch(Exception $e){
                $res_msg = false;
            }
            $res_msg? $this->success($res_msg, 'Orcdata/index') : $this->error('同步失败');
        }
    }

}