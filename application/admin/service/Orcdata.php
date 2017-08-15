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
class Orcdata extends Model
{

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// 门店表
///
///
    /**
     * 获取已有门店表的数据
     *
     * @return bool | array 存在返回节点，不存在则返回False
     * @author  zzwu cx
     * @date  20170717
     *
     */
    public function getLocalOrgCount()
    {
        $allShop = Db::name('torgmanage')->where('1=1')->column('count(*)');
        $enableShop = Db::name('torgmanage')->where(['isenable'=>'1'])->column('count(*)');
        $result = ['allShop'=>current($allShop),'enableShop'=>current($enableShop)];
        return $result;
    }

  /**
     * 获取ORACLE门店表的数据
     *
     * @return bool | array 存在返回节点，不存在则返回False
     * @author  zzwu cx
     * @date  20170717
     *
     */
    public function getOrcOrgCount()
    {
        $conn = dw_getOracleConnection();
        //获取数据
        $select = "select count(*) from hscmp.tOrgManage where isEnable=1";
        $select = iconv("utf-8", "gb2312", $select);
        $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
        $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
        if (!$row_count) { //没有行
            $e = oci_error($result_rows);
            echo htmlentities($e['message']);
        }
        set_time_limit(0);
        //数据格式转换，转存
        while ($row = oci_fetch_assoc($result_rows)) {
            foreach ($row as $key => $value) {
                $enableShop = mb_convert_encoding ( $value,'utf-8','gb2312');
            }
        }
        $select = "select count(*) from hscmp.tOrgManage";
        $select = iconv("utf-8", "gb2312", $select);
        $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
        $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
        if (!$row_count) { //没有行
            $e = oci_error($result_rows);
            echo htmlentities($e['message']);
        }
        set_time_limit(0);
        //数据格式转换，转存
        while ($row = oci_fetch_assoc($result_rows)) {
            foreach ($row as $key => $value) {
                $allShop = mb_convert_encoding ( $value,'utf-8','gb2312');
            }
        }
        $result = ['allShop'=>$allShop,'enableShop'=>$enableShop];
        return $result;
    }

    public function clearLocalOrgTable(){
        $result =  Db::name('torgmanage')->where('1=1')->delete();
        return $result;
    }

    public function synShops(){
        $conn = dw_getOracleConnection();
         //获取数据
        $select = "SELECT * FROM hscmp.tOrgManage";
        $select = iconv("utf-8", "gb2312", $select);
        $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
        $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
        if (!$row_count) { //没有行
            $e = oci_error($result_rows);
            echo htmlentities($e['message']);
        }
        set_time_limit(0);
        //获取所有数据
        $nrows = oci_fetch_all($result_rows,$result,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
        foreach ($result as $key => $value){
            $result[$key]['ORGNAME'] =  mb_convert_encoding ( $result[$key]['ORGNAME'],'utf-8','gb2312');
            $result[$key]['REMARK'] =  mb_convert_encoding ( $result[$key]['REMARK'],'utf-8','gb2312');
            $result[$key]['POSCOUNTVERIFY'] = NULL;
            $result[$key] = array_change_key_case($result[$key]);
        }
        $result = Db::name('torgmanage')->insertAll($result);
    }

    public function getAlltOrgMangeTable(){
//        $result = Db::name('torgmanage')->column('orgcode','orgname','isenable');
        $result = Db::name('torgmanage')->select();
        return $result;
    }

    public function updateOrgTypeTable($orgcode,$orgname,$isenable){
        if(Db::name('org_type')->where(['orgcode'=>$orgcode])->find()){
            //update
            $result = Db::name('org_type')->where(['orgcode' => $orgcode])->update(['orgname'=>$orgname,'isenable'=>$isenable]);
        }
        else{
            //insert
            $result = Db::name('org_type')->insertGetId(['orgcode'=>$orgcode,'orgname'=>$orgname,'isenable'=>$isenable],false);
            if(!$result){
                $result = Db::name('org_type')->where(['orgcode'=>$orgcode,'orgname'=>$orgname,'isenable'=>$isenable])->find();
                $result = $result['id'];
            }
        }
        return $result;
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// 月度门店销售明细表
///
///
    /**
     * 获取 本地 月度门店销售明细表 的数据量
     * @author  xdw
     * @date  20170717
     */
    public function getLocalShopMonthDataCount($pg_table)
    {
        $result = Db::name($pg_table)->column('count(*)');
        return $result;
    }

    /**
     * 获取 oracle 月度门店销售明细表 的数据量
     * @author  xdw
     * @date  20170717
     */
    public function getOrcShopMonthDataCount($pg_table)
    {
        $conn = dw_getOracleConnection();
        //获取数据
        $select = "select count(*) from hscmp.".$pg_table;
        $select = iconv("utf-8", "gb2312", $select);
        $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
        $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
        if (!$row_count) { //没有行
            $e = oci_error($result_rows);
            echo htmlentities($e['message']);
        }
        set_time_limit(0);
        //数据格式转换，转存
        while ($row = oci_fetch_assoc($result_rows)) {
            foreach ($row as $key => $value) {
                $value = mb_convert_encoding ( $value,'utf-8','gb2312');
            }
        }
        return $value;
    }

    /**
     * 清除 本地 月度门店销售明细表 的数据
     * @author  xdw
     * @date  20170717
     */
    public function clearLocalShopMonthDataTable($pg_table){
        $result =  Db::name($pg_table)->where('1=1')->delete();
        return $result;
    }

    /**
     * 同步 月度门店销售明细表 的数据量
     * @author  xdw
     * @date  20170717
     */
    public function synShopMonthData($pg_table,$field_array){
        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
        $result = Db::name($pg_table)->column('count(*)');
        $result = current($result);

        $conn = dw_getOracleConnection();
        $start = $result+1;
        $end = $result+1000;
        while (true){
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT * FROM hscmp.".$pg_table." order by SerialNo) A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows,$result,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
            if($nrows==0){
                return;
            }
            foreach ($result as $key => $value){
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2){
                    if(array_key_exists(strtolower($key2),$field_array)){//对特殊字段值做类型转换
                        switch($field_array[strtolower($key2)]){
                            case 'float':
                                $result[$key][$key2] =   floatval($result[$key][$key2]);
                                break;
                            case 'int':
                                //xxxxxxxxxxxxxxxxxxxxxx
                                break;
                        }
                    }else{
                        $result[$key][$key2] =  mb_convert_encoding ( $result[$key][$key2],'utf-8','gb2312');
                    }
                }
                $result[$key] = array_change_key_case($result[$key]);
            }
            $result = Db::name($pg_table)->insertAll($result);
            $start=$end+1;
            $end +=1000;
        }
    }
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// 门店商品E积分销售表
///
///
///
    // 同步门店商品E积分销售表数据
    public function syntProCertiInfo($current_date){
        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
        $result = Db::name('tprocertiinfo')
            ->where('usesaleno','like',$current_date.'%')
            ->column('count(*)');
        $result = current($result);

        $conn = dw_getOracleConnection();
        $start = $result+1;
        $end = $result+5000;
        while (true){
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT Certino,CxBillNo,CertiStatus,UseSaleNo,RecOrgCode FROM hscmp.tProCertiInfo where UseSaleNo like '$current_date%' order by Certino) A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows,$result,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
            if($nrows==0){
                return;
            }
            foreach ($result as $key => $value){
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2){
                    $result[$key][$key2] =  mb_convert_encoding ( $result[$key][$key2],'utf-8','gb2312');
                }
                $result[$key] = array_change_key_case($result[$key]);
            }
            $result = Db::name('tprocertiinfo')->insertAll($result);
//            $result = $this->syntProCertiInfoRecord($result);
            $start=$end+1;
            $end +=5000;
        }
    }

//    public function syntProCertiInfoRecord($result){
//        $certino = '';
//        foreach ($result as $key => $value){
//            $certino .= $value['certino'] . ',';
//        }
//        $certino = substr($certino,0,strlen($certino)-1);
//        $res = Db::name('tprocertiinfo')
//            ->where('certino','in',$certino)
//            ->column('count(*)');
//        if(current($res)==5000){
//            return;
//        }
//        else{
//            //删除，重新插入
//            $res = Db::name('tprocertiinfo')
//                ->where('certino','in',$certino)
//                ->delete();
//            $res = Db::name('tprocertiinfo')->insertAll($result);
//        }
//    }
    // 清空门店商品E积分销售表数据
//    public function cleartProCertiInfo($current_date){
//        $result =  Db::name('tprocertiinfo')
//            ->where('usesaleno','like',$current_date.'%')
//            ->delete();
//        return $result;
//    }


//    // 同步门店商品E积分销售表数据
//    public function syntProCertiInfo($current_date){
//        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
//        $result = Db::name('tprocertiinfo')
//            ->where('usesaleno','like',$current_date.'%')
//            ->column('count(*)');
//        $result = current($result);
//
//        $conn = dw_getOracleConnection();
//        $start = $result+1;
//        $end = $result+1000;
//        while (true){
//            //获取数据
//            $select = "SELECT * FROM (
//                            SELECT A.*, ROWNUM RN
//                            FROM (SELECT * FROM hscmp.tProCertiInfo where UseSaleNo like $current_date'%' order by UseSaleNo) A
//                            WHERE ROWNUM <= $end
//                            )WHERE RN >= $start";
//            $select = iconv("utf-8", "gb2312", $select);
//            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
//            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
//            if (!$row_count) { //没有行
//                $e = oci_error($result_rows);
//                echo htmlentities($e['message']);
//            }
//            set_time_limit(0);
//            //获取所有数据
//            $nrows = oci_fetch_all($result_rows,$result,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
//            if($nrows==0){
//                return;
//            }
//            foreach ($result as $key => $value){
//                unset($result[$key]['RN']);
//                unset($value['RN']);
//                foreach ($value as $key2 => $value2){
//                    $result[$key][$key2] =  mb_convert_encoding ( $result[$key][$key2],'utf-8','gb2312');
//                }
//                $result[$key] = array_change_key_case($result[$key]);
//            }
//            $result = Db::name('tprocertiinfo')->insertAll($result);
//            $start=$end+1;
//            $end +=1000;
//        }
//    }

    /////////////////////////////////////////////////////////////////////////////
    /// 商品表
    ///

    public function clearvSkuPluCat(){
        $result =  Db::name('vskuplucat')
            ->where('1=1')
            ->delete();
        return $result;
    }

    public function synvSkuPluCat()
    {
        $conn = dw_getOracleConnection();
        $start = 1;
        $end = 1000;
        while (true) {
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT * FROM hscmp.vSkuPluCat) A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
            if ($nrows == 0) {
                return;
            }
            foreach ($result as $key => $value) {
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2) {
                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
                }
                $result[$key] = array_change_key_case($result[$key]);
            }
            $result = Db::name('vskuplucat')->insertAll($result);
            $start=$end+1;
            $end +=1000;
        }

    }

    /////////////////////////////////////////////////////////////////////////////
    /// 商品E积分主表
    ///
    public function syntProBillHead()
    {
        $conn = dw_getOracleConnection();
        $start = 1;
        $end = 1000;
        while (true) {
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT * FROM hscmp.tProBillHead) A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
            if ($nrows == 0) {
                return;
            }
            foreach ($result as $key => $value) {
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2) {
                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
                }
                $result[$key] = array_change_key_case($result[$key]);
            }
            //与本地数据对比，发现有新的数据就insert，旧数据就update
            $result = $this->syntProBillHeadRecord($result);
            $start = $end + 1;
            $end += 1000;
        }
    }

    public function syntProBillHeadRecord($result){
        foreach ($result as $key => $value) {
            $res = Db::name('tprobillhead')->where(['billno' => $value['billno']])->find();
            if ($res) {
                //update
                //Db::name('twslxshead')->where(['billno' => $value['billno']])->update($value);
                unset($result[$key]);
            } else {
                //insert
            }
        }
        $result = Db::name('tprobillhead')->insertAll($result);
        return $result;
    }
//    public function syntProBillHead()
//    {
//        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
//        $result = Db::name('tprobillhead')->where('1=1')->column('count(*)');
//        $result = current($result);
//
//        $conn = dw_getOracleConnection();
//        $start = $result + 1;
//        $end = $result + 1000;
//        while (true) {
//            //获取数据
//            $select = "SELECT * FROM (
//                            SELECT A.*, ROWNUM RN
//                            FROM (SELECT * FROM hscmp.tProBillHead) A
//                            WHERE ROWNUM <= $end
//                            )WHERE RN >= $start";
//            $select = iconv("utf-8", "gb2312", $select);
//            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
//            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
//            if (!$row_count) { //没有行
//                $e = oci_error($result_rows);
//                echo htmlentities($e['message']);
//            }
//            set_time_limit(0);
//            //获取所有数据
//            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
//            if ($nrows == 0) {
//                return;
//            }
//            foreach ($result as $key => $value) {
//                unset($result[$key]['RN']);
//                unset($value['RN']);
//                foreach ($value as $key2 => $value2) {
//                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
//                }
//                $result[$key] = array_change_key_case($result[$key]);
//            }
//            $result = Db::name('tprobillhead')->insertAll($result);
//            $start = $end + 1;
//            $end += 1000;
//        }
//    }


    /////////////////////////////////////////////////////////////////////////////
    /// 门店商品团购销售表
    ///

    public function clearWslXsBody($current_date){
        $result =  Db::name('twslxsbody')
            ->where('billno','like',$current_date.'%')
            ->delete();
        return $result;
    }

//    public function syntWslXsBody($current_date)
//    {
//        $conn = dw_getOracleConnection();
//        $start = 1;
//        $end = 1000;
//        while (true) {
//            //获取数据
//            $select = "SELECT * FROM (
//                            SELECT A.*, ROWNUM RN
//                            FROM (SELECT * FROM hscmp.tWslXsBody where Billno like '%$current_date%' order by Billno ) A
//                            WHERE ROWNUM <= $end
//                            )WHERE RN >= $start";
//            $select = iconv("utf-8", "gb2312", $select);
//            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
//            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
//            if (!$row_count) { //没有行
//                $e = oci_error($result_rows);
//                echo htmlentities($e['message']);
//            }
//            set_time_limit(0);
//            //获取所有数据
//            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
//            if ($nrows == 0) {
//                return;
//            }
//            foreach ($result as $key => $value) {
//                unset($result[$key]['RN']);
//                unset($value['RN']);
//                foreach ($value as $key2 => $value2) {
//                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
//                }
//                $result[$key] = array_change_key_case($result[$key]);
//                //转换float
//                $result[$key]['hmltotal'] = floatval($result[$key]['hmltotal']);
//                $result[$key]['sstotal'] = floatval($result[$key]['sstotal']);
//                $result[$key]['pfcount'] = floatval($result[$key]['pfcount']);
//            }
//            $result = Db::name('twslxsbody')->insertAll($result);
//            $start = $end + 1;
//            $end += 1000;
//        }
//    }
    public function syntWslXsBody($current_date)
    {
        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
        $result = Db::name('twslxsbody')->where('billno','like',"%$current_date%")->column('count(*)');
        $result = current($result);

        $conn = dw_getOracleConnection();
        $start = $result + 1;
        $end = $result + 1000;
        while (true) {
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT * FROM hscmp.tWslXsBody where Billno like '%$current_date%' order by Billno,Serialno ) A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
            if ($nrows == 0) {
                return;
            }
            foreach ($result as $key => $value) {
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2) {
                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
                }
                $result[$key] = array_change_key_case($result[$key]);
                //转换float
                $result[$key]['hmltotal'] = floatval($result[$key]['hmltotal']);
                $result[$key]['sstotal'] = floatval($result[$key]['sstotal']);
                $result[$key]['pfcount'] = floatval($result[$key]['pfcount']);
            }
            $result = Db::name('twslxsbody')->insertAll($result);
            $start = $end + 1;
            $end += 1000;
        }
    }


    /////////////////////////////////////////////////////////////////////////////
    /// 团购主表
    ///
    public function syntWslXsHead($current_date)
    {

        $conn = dw_getOracleConnection();
        $start = 1;
        $end = 1000;
        while (true) {
            //获取数据
            $select = "SELECT * FROM (
                            SELECT A.*, ROWNUM RN
                            FROM (SELECT * FROM hscmp.tWslXsHead where Rptdate like '$current_date%') A
                            WHERE ROWNUM <= $end
                            )WHERE RN >= $start";
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if (!$row_count) { //没有行
                $e = oci_error($result_rows);
                echo htmlentities($e['message']);
            }
            set_time_limit(0);
            //获取所有数据
            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
            if ($nrows == 0) {
                return;
            }
            foreach ($result as $key => $value) {
                unset($result[$key]['RN']);
                unset($value['RN']);
                foreach ($value as $key2 => $value2) {
                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
                }
                $result[$key] = array_change_key_case($result[$key]);
            }
            //与本地数据对比，发现有新的数据就insert，旧数据就update
            $result = $this->syntWslXsHeadRecord($result);
            $start = $end + 1;
            $end += 1000;
        }
    }

    public function syntWslXsHeadRecord($result){
        foreach ($result as $key => $value) {
            $res = Db::name('twslxshead')->where(['billno' => $value['billno']])->find();
            if ($res) {
                //update
                //Db::name('twslxshead')->where(['billno' => $value['billno']])->update($value);
                unset($result[$key]);
            } else {
                //insert
            }
        }
        $result = Db::name('twslxshead')->insertAll($result);
        return $result;
    }
//    public function syntWslXsHead($current_date)
//    {
//        //获取本地已有的数据总数，比对oralce，如有新的数据就插入
//        $result = Db::name('twslxshead')->where('1=1')->column('count(*)');
//        $result = current($result);
//
//        $conn = dw_getOracleConnection();
//        $start = $result + 1;
//        $end = $result + 1000;
//        while (true) {
//            //获取数据
//            $select = "SELECT * FROM (
//                            SELECT A.*, ROWNUM RN
//                            FROM (SELECT * FROM hscmp.tWslXsHead where Rptdate like '$current_date%' order by Rptdate ) A
//                            WHERE ROWNUM <= $end
//                            )WHERE RN >= $start";
//            $select = iconv("utf-8", "gb2312", $select);
//            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
//            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
//            if (!$row_count) { //没有行
//                $e = oci_error($result_rows);
//                echo htmlentities($e['message']);
//            }
//            set_time_limit(0);
//            //获取所有数据
//            $nrows = oci_fetch_all($result_rows, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
//            if ($nrows == 0) {
//                return;
//            }
//            foreach ($result as $key => $value) {
//                unset($result[$key]['RN']);
//                unset($value['RN']);
//                foreach ($value as $key2 => $value2) {
//                    $result[$key][$key2] = mb_convert_encoding($result[$key][$key2], 'utf-8', 'gb2312');
//                }
//                $result[$key] = array_change_key_case($result[$key]);
//            }
//            $result = Db::name('twslxshead')->insertAll($result);
//            $start = $end + 1;
//            $end += 1000;
//        }
//    }

    public function getMonthGroupBuy($current_date){
        $orgs = Db::name('twslxshead')
            ->field('billno,billtype,remark,fix_type')
            ->where('rptdate','like','%'.$current_date.'%')
            ->order(['rptdate'=>'desc'])
            ->select();
        return $orgs;
    }

    public function updateGroupBuyFixType($value,$fix_type){
        $map = [
            'billno' => $value['billno']
        ];

        $data = [
            'fix_type' => $fix_type
        ];
        //update
        Db::name('twslxshead')->where($map)->update($data);
    }
}