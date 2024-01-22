<?php

require dirname(__FILE__).'/../../framework/bootstrap.inc.php';
$input = file_get_contents('php://input');
global $_W;
    //  error_reporting(E_ALL & ~E_NOTICE);
    //  error_reporting(1);
    require_once IA_ROOT."/addons/lh_xiche/inc/lib/WxPay.Config.php";
    $config = new WxPayConfig();
    //处理微信回调
    header('Content-type:text/html; Charset=utf-8');
    $mchid = $config->GetMerchantId();
    $appid = $config->GetAppId();  
    $apiKey = $config->GetKey();  
function log_data($data) {
    //数据类型检测
    if (is_array($data)) {
        $data = json_encode($data);
    }
    $filename = IA_ROOT."/addons/lh_xiche/log/".date("Y-m-d").".log";
    $str = date("Y-m-d H:i:s")."   $data"."\n";
    file_put_contents($filename, $str, FILE_APPEND|LOCK_EX);
    return null;
}
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $apiKey;
    public function __construct($mchid, $appid, $key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->apiKey = $key;
    }
    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $postStr = file_get_contents('php://input');
        log_data($postStr);
/*$postStr = "<xml><appid><![CDATA[wxf3c71f18cb24d3f5]]></appid>
<attach><![CDATA[wx_yue]]></attach>
<bank_type><![CDATA[ABC_CREDIT]]></bank_type>
<cash_fee><![CDATA[2000]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1605283718]]></mch_id>
<nonce_str><![CDATA[l8f3w771nj6g3sxfpgrcp60a2nnfw13q]]></nonce_str>
<openid><![CDATA[o40yT4opfBHT648LNt3-AX7NcA-8]]></openid>
<out_trade_no><![CDATA[2021022512233311]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[62D533BAFE53F7AAFF73511AE2CFB38F]]></sign>
<time_end><![CDATA[20210126140638]]></time_end>
<total_fee>2000</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[4200000785202101263331520206]]></transaction_id>
</xml>
"; */

        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);        
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // var_dump($postObj);
        if ($postObj === false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die($postObj->return_msg);
        }
        if ($postObj->result_code != 'SUCCESS') {
            die($postObj->err_code);
        }
        $arr = (array)$postObj;
        unset($arr['sign']);    
        // echo self::getSign($arr, $config['key']);
        
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $arr;
        if (self::getSign($arr, $config['key']) == $postObj->sign) {}else{
            echo "sign err";
        }
    }
    /**
     * 获取签名
     
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
*/
    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::ToUrlParams($params);
        //$signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        $signStr =  strtoupper(hash_hmac("sha256",$unSignParaString . "&key=" . $key ,$key));
        return $signStr;
    }



    public static function ToUrlParams($params)
    {
        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

}


function httpGet($url,$headers='') {
    $curl = curl_init();
    if($headers != ''){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    if($res === false){
        var_dump(curl_errno($curl));
    }
    curl_close($curl);
    return $res;
}
// curl请求  post方式
function httpPost($url,$data,$headers='') {
    $curl = curl_init();
    if($headers != ''){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    if($res === false){
        var_dump(curl_errno($curl));
    }
    curl_close($curl);
    return $res;
} 

function user_jifen_edit($u_id,$nums,$content,$type,$uniacid){
        global $_GPC,$_W;
        // 6  发布消耗
        // 5 抽奖消费
          $data_jf = array();
          $data_jf['uniacid'] = $uniacid;
          $data_jf['u_id'] = $u_id;
          $data_jf['nums'] = $nums;
          $data_jf['content'] = $content;
          $data_jf['type'] = $type;
          $data_jf['addtime'] = date("Y-m-d H:i:s",time());
          pdo_insert("lh_xiche_jifen_log",$data_jf);
          

          //更新微擎积分
          $user = pdo_get('lh_xiche_user', array('id' =>$u_id));
          $user_info = pdo_get('mc_mapping_fans', array('openid' =>$user['w_openid']));
          if($user_info['uid']>0){
                $data = array();
                $data['uid'] = $user_info['uid'];
                $data['uniacid'] = $uniacid;
                $data['credittype'] = 'credit1';
                $data['num'] = $nums;
                $data['operator'] = 0;
                $data['module'] = 'lh_xiche';
                $data['clerk_type'] = 1;//操作人类型 1: 线上操作 2: 系统后台(公众号管理员和操作员) 3: 店员. 当clerk_type =2时,clerk_id存的是管理员或操作员的uid
                $data['createtime'] =  time();
                $data['remark'] = $content;
                $data['real_uniacid'] = $uniacid;
                pdo_insert("mc_credits_record",$data);
                pdo_update('mc_members', array('credit1 +='=>$nums), array('uid' =>$user_info['uid']));
                $w_user = pdo_get('mc_members', array('uid' =>$user_info['uid']));
                pdo_update('lh_xiche_user', array('jifen ='=>$w_user['credit1']), array('id' =>$u_id));
          }else{
                pdo_update('lh_xiche_user', array('jifen +='=>$nums), array('id' =>$u_id));
          }
          
    }

// 佣金修改
function user_yongjin_edit($uniacid ,$u_id,$nums,$content,$type){


        $data_yj = array();
        $data_yj['uniacid'] = $uniacid;
        $data_yj['u_id'] = $u_id;
        $data_yj['nums'] = $nums;
        $data_yj['content'] = $content;
        $data_yj['type'] = $type;
        $data_yj['addtime'] = date("Y-m-d H:i:s",time());
        pdo_insert("lh_xiche_yongjin_log",$data_yj);

        $user_data['money +='] = $nums;
        
        pdo_update('lh_xiche_user', $user_data, array('id' =>$u_id));
}


        $wxPay = new WxpayService($mchid,$appid,$apiKey);
        $result = $wxPay->notify();
        $result['out_trade_no'] = substr($result['out_trade_no'], 0, -2);
        if($result['attach']=="app_jifen"){
                $info = $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_jforder")." where ordersn=:ordersn and type=1  ",array(":ordersn"=>$result['out_trade_no']));
                $u_id = $order_info['u_id'];
                $uniacid = $order_info['uniacid'];
            if(!empty($info)){
                $res = pdo_update("lh_xiche_jforder",array('transaction_id'=>$result['transaction_id'],'type'=>'待发货'),array("ordersn"=>$result['out_trade_no']));
 
                user_jifen_edit($order_info['u_id'],-$order_info['jifen'],"积分兑换",6,$uniacid);
            }
            die;
        }

        if($result['attach']=="app_jifen"){
                $info = $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_jforder")." where ordersn=:ordersn and type=1  ",array(":ordersn"=>$result['out_trade_no']));
                $u_id = $order_info['u_id'];
                $uniacid = $order_info['uniacid'];
            if(!empty($info)){
                $res = pdo_update("lh_xiche_jforder",array('transaction_id'=>$result['transaction_id'],'type'=>'待发货'),array("ordersn"=>$result['out_trade_no']));
 
                user_jifen_edit($order_info['u_id'],-$order_info['jifen'],"积分兑换",6,$uniacid);
            }
            die;
        }
        if($result['attach']=="cars_order"){
                $info = $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cars_order")." where ordersn=:ordersn and status=0  ",array(":ordersn"=>$result['out_trade_no']));
                $u_id = $order_info['u_id'];
                $uniacid = $order_info['uniacid'];
            if(!empty($info)){
                $res = pdo_update("lh_xiche_cars_order",array('transaction_id'=>$result['transaction_id'],'status'=>'1'),array("ordersn"=>$result['out_trade_no']));
                $shangjia = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shangjia")." where uniacid=$uniacid and id='{$order_info['s_id']}'");
                pdo_update("lh_xiche_shangjia",['gongwei_shiyong -='=>1],array("id"=>$info['s_id']));
                pdo_update("lh_xiche_gongwei",['type'=>0,'kaimen'=>1,'is_show'=>1],array("id"=>$info['g_id']));
                user_yongjin_edit($uniacid ,$shangjia['u_id'],$money,'商家收益：'.$order_info['car_number'].'洗车'.$order_info['fenzhong'],200);
            }

            die;
        }
        if($result['attach']=="wx_yue"){
            // error_reporting(E_ALL);
                $info = $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_chongzhi")." where ordersn=:ordersn and status=0  ",array(":ordersn"=>$result['out_trade_no']));
                $u_id = $order_info['u_id'];
                $uniacid = $order_info['uniacid'];
                // var_dump($info['money']);
            if(!empty($info)){
                $res = pdo_update("lh_xiche_chongzhi",array('transaction_id'=>$result['transaction_id'],'status'=>'1'),array("ordersn"=>$result['out_trade_no']));
                $cz_set_info =  pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cz_set")." where money=:money ",array(":money"=>$info['money']));
					if($cz_set_info['zs_jf']>0){
						user_jifen_edit($order_info['u_id'],$cz_set_info['zs_jf'],"充值赠送积分",6,$uniacid);
					}

                    $data_jf = array();
                    $data_jf['uniacid'] = $uniacid;
                    $data_jf['u_id'] = $order_info['u_id'];
                    $data_jf['nums'] = $order_info['money'];
                    $data_jf['content'] = "小程序充值";
                    $data_jf['type'] = "4";
                    $data_jf['addtime'] = date("Y-m-d H:i:s",time());
                    pdo_insert("lh_xiche_yongjin_log",$data_jf);
                    pdo_update('lh_xiche_user', array('money +='=>$order_info['money']), array('id' =>$order_info['u_id']));
					if($cz_set_info['zs_je']>0){
						$data_jf = array();
						$data_jf['uniacid'] = $uniacid;
						$data_jf['u_id'] = $order_info['u_id'];
						$data_jf['nums'] = $cz_set_info['zs_je'];
						$data_jf['content'] = "充值赠送";
						$data_jf['type'] = "4";
						$data_jf['addtime'] = date("Y-m-d H:i:s",time());
						pdo_insert("lh_xiche_yongjin_log",$data_jf);
						pdo_update('lh_xiche_user', array('money +='=>$cz_set_info['zs_je']), array('id' =>$order_info['u_id']));
					}
					
            }
            die;
        }




    if($result['attach']=='wx_goods'){//商品支付
    
            $info = $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where ordersn=:ordersn and type=10  ",array(":ordersn"=>$result['out_trade_no']));
            if(!empty($info)){
            $res = pdo_update("lh_xiche_order",array('transaction_id'=>$result['transaction_id'],'type'=>'20','pay_type'=>'微信支付'),array("ordersn"=>$result['out_trade_no']));
             pdo_update('lh_xiche_user', array('sum_money +='=>$order_info['count_money']), array('id' =>$order_info['u_id']));
            $u_id = $order_info['u_id'];
            $uniacid = $order_info['uniacid'];
            $user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id));
            // if($user_info['type']==2||$info['count_money']>=298){}
            //消费送用户积分
            $user_type = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_user_type")." where uniacid=$uniacid and  money<='{$user_info['sum_money']}' order by  money desc ");
            pdo_update('lh_xiche_user', array('type'=>$user_type[0]['sort']),array('uniacid' => $uniacid,'id' => $u_id));


            $jfguizhe = pdo_get('lh_xiche_jfguizhe', array('uniacid' =>$uniacid));
            user_jifen_edit($order_info['u_id'],$order_info['count_money']*$jfguizhe['xiaofei'],"消费奖励积分",4,$uniacid);



            $goods_list = pdo_getall('lh_xiche_ordergoods',array('o_id'=>$order_info['o_id']));
            foreach ($goods_list as $key => $value) {
                pdo_update('lh_xiche_goods', array('stock -='=>1,'xiaoliang +='=>1), array('id' =>$value['g_id']));
            }
            
        
            
 //返佣
        $fenxiao_set = pdo_get('lh_xiche_fenxiao', array('uniacid' => $uniacid), array('*'));
        
        $user_info_f = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $user_info['f_id']), array('*'));
        if(!empty($user_info_f)){
            //上级 销售额
            pdo_update('lh_xiche_user', array('xiaoshou +='=>$order_info['count_money']), array('id' =>$user_info['f_id']));
            $data_jf = array();
            $money = $order_info['count_money']*$fenxiao_set['y_moneyyi'];
            if($money>0){
                $data_jf['uniacid'] = $uniacid;
                $data_jf['u_id'] = $user_info['f_id'];
                $data_jf['nums'] = $money;
                $data_jf['content'] = "一级奖励";
                $data_jf['type'] = "4";
                $data_jf['addtime'] = date("Y-m-d H:i:s",time());
                pdo_insert("lh_xiche_yongjin_log",$data_jf);
                pdo_update('lh_xiche_user', array('money +='=>$money), array('id' =>$user_info['f_id']));
            }
            //查询当前用户销售等级
            $xiaoshou = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_xiaoshou")." where uniacid=$uniacid and  money<='{$user_info_f['xiaoshou']}' order by  money desc ");
            $money = $order_info['count_money']*$xiaoshou[0]['bili'];
            log_data('销售奖励：'.$money);
            if($money>0){
                $data_jf = array();
                $data_jf['uniacid'] = $uniacid;
                $data_jf['u_id'] = $user_info['f_id'];
                $data_jf['nums'] = $money;
                $data_jf['content'] = "销售奖励";
                $data_jf['type'] = "4";
                $data_jf['addtime'] = date("Y-m-d H:i:s",time());
                pdo_insert("lh_xiche_yongjin_log",$data_jf);
                pdo_update('lh_xiche_user', array('money +='=>$money), array('id' =>$user_info['f_id']));
            }

            $user_info_ff = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $user_info['ff_id']), array('*'));
            if(!empty($user_info_ff)){
                //上上级
                $money = $order_info['count_money']*$fenxiao_set['y_moneyer'];
                if($money>0){
                    $data_jf = array();
                    $data_jf['uniacid'] = $uniacid;
                    $data_jf['u_id'] = $user_info['ff_id'];
                    $data_jf['nums'] = $money ;
                    $data_jf['content'] = "二级奖励";
                    $data_jf['type'] = "4";
                    $data_jf['addtime'] = date("Y-m-d H:i:s",time());
                    pdo_insert("lh_xiche_yongjin_log",$data_jf);
                    pdo_update('lh_xiche_user', array('money +='=>$money), array('id' =>$user_info['ff_id']));
                }
            }
        }
             
            if($res==1){echo "SUCCESS";}
            }

        }else{
             echo 'pay error';
        }


    exit;