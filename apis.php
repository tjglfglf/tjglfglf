<?php

define('IN_SYS', true);

define('WWW_ROOT', dirname(dirname(dirname(__FILE__))));

require WWW_ROOT.'/framework/bootstrap.inc.php';
global $_GPC, $_W;

// die;
/* 
   var_dump($_REQUEST);*/
function log_data($data) {
    //数据类型检测
    if (is_array($data)) {
        $data = json_encode($data);
    }
    $filename = IA_ROOT."/addons/lh_xiche/log/cron".date("Y-m-d").".log";
    $str = date("Y-m-d H:i:s")."   $data"."\n";
    file_put_contents($filename, $str, FILE_APPEND|LOCK_EX);
    return null;
}
/**/log_data($_GET);
log_data($postStr);
log_data($_POST);
function kai_men(){
    $data = [
        'Response_AlarmInfoPlate'=>[
             'info'=>"ok",
            'serialData'=>[
                [
                "serialChannel"=> 0,
               "data"=> 'AGT//zAUAbu206254sHZLMfryOuzoc2js7WpNg==',
                "dataLen"=>28
                /* "data"=> 'AGT//zASAdTBQjEyMzQ1LLu206254sHZapE=',
                "dataLen"=>36*/
                ]
             ],
        ]
    ];
    echo_data($data);
    
    
    
}

   class CrcTool{
      
    static  function crc166($string, $length = 0) {
    
            $auchCRCHi = array(0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81,
            0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0,
            0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01,
            0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41,
            0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81,
            0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0,
            0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01,
            0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40,
            0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81,
            0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0,
            0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01,
            0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41,
            0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81,
            0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0,
            0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01,
            0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81, 0x40, 0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41,
            0x00, 0xC1, 0x81, 0x40, 0x01, 0xC0, 0x80, 0x41, 0x01, 0xC0, 0x80, 0x41, 0x00, 0xC1, 0x81,
            0x40);
            $auchCRCLo = array(0x00, 0xC0, 0xC1, 0x01, 0xC3, 0x03, 0x02, 0xC2, 0xC6, 0x06, 0x07, 0xC7, 0x05, 0xC5, 0xC4,
            0x04, 0xCC, 0x0C, 0x0D, 0xCD, 0x0F, 0xCF, 0xCE, 0x0E, 0x0A, 0xCA, 0xCB, 0x0B, 0xC9, 0x09,
            0x08, 0xC8, 0xD8, 0x18, 0x19, 0xD9, 0x1B, 0xDB, 0xDA, 0x1A, 0x1E, 0xDE, 0xDF, 0x1F, 0xDD,
            0x1D, 0x1C, 0xDC, 0x14, 0xD4, 0xD5, 0x15, 0xD7, 0x17, 0x16, 0xD6, 0xD2, 0x12, 0x13, 0xD3,
            0x11, 0xD1, 0xD0, 0x10, 0xF0, 0x30, 0x31, 0xF1, 0x33, 0xF3, 0xF2, 0x32, 0x36, 0xF6, 0xF7,
            0x37, 0xF5, 0x35, 0x34, 0xF4, 0x3C, 0xFC, 0xFD, 0x3D, 0xFF, 0x3F, 0x3E, 0xFE, 0xFA, 0x3A,
            0x3B, 0xFB, 0x39, 0xF9, 0xF8, 0x38, 0x28, 0xE8, 0xE9, 0x29, 0xEB, 0x2B, 0x2A, 0xEA, 0xEE,
            0x2E, 0x2F, 0xEF, 0x2D, 0xED, 0xEC, 0x2C, 0xE4, 0x24, 0x25, 0xE5, 0x27, 0xE7, 0xE6, 0x26,
            0x22, 0xE2, 0xE3, 0x23, 0xE1, 0x21, 0x20, 0xE0, 0xA0, 0x60, 0x61, 0xA1, 0x63, 0xA3, 0xA2,
            0x62, 0x66, 0xA6, 0xA7, 0x67, 0xA5, 0x65, 0x64, 0xA4, 0x6C, 0xAC, 0xAD, 0x6D, 0xAF, 0x6F,
            0x6E, 0xAE, 0xAA, 0x6A, 0x6B, 0xAB, 0x69, 0xA9, 0xA8, 0x68, 0x78, 0xB8, 0xB9, 0x79, 0xBB,
            0x7B, 0x7A, 0xBA, 0xBE, 0x7E, 0x7F, 0xBF, 0x7D, 0xBD, 0xBC, 0x7C, 0xB4, 0x74, 0x75, 0xB5,
            0x77, 0xB7, 0xB6, 0x76, 0x72, 0xB2, 0xB3, 0x73, 0xB1, 0x71, 0x70, 0xB0, 0x50, 0x90, 0x91,
            0x51, 0x93, 0x53, 0x52, 0x92, 0x96, 0x56, 0x57, 0x97, 0x55, 0x95, 0x94, 0x54, 0x9C, 0x5C,
            0x5D, 0x9D, 0x5F, 0x9F, 0x9E, 0x5E, 0x5A, 0x9A, 0x9B, 0x5B, 0x99, 0x59, 0x58, 0x98, 0x88,
            0x48, 0x49, 0x89, 0x4B, 0x8B, 0x8A, 0x4A, 0x4E, 0x8E, 0x8F, 0x4F, 0x8D, 0x4D, 0x4C, 0x8C,
            0x44, 0x84, 0x85, 0x45, 0x87, 0x47, 0x46, 0x86, 0x82, 0x42, 0x43, 0x83, 0x41, 0x81, 0x80,
            0x40);
            $length = ($length <= 0 ? strlen($string) : $length);
            $uchCRCHi = 0xFF;
            $uchCRCLo = 0xFF;
            $uIndex = 0;
            for ($i = 0; $i < $length; $i++) {
            $uIndex = $uchCRCLo ^ ord(substr($string, $i, 1));
            $uchCRCLo = $uchCRCHi ^ $auchCRCHi[$uIndex];
            $uchCRCHi = $auchCRCLo[$uIndex];
            }
            return(chr($uchCRCLo) . chr($uchCRCHi));
    }
    
    }

function to8($str){
    if(strlen($str)<8){
        for($i=8-strlen($str);$i>0;$i--){
                $str="0".$str;
        }
    }
    return $str;
}
function everyto8(&$arr){
        for($i=0;$i<count($arr);$i++){
            $arr[$i]=to8(base_convert($arr[$i], 16, 2));
        }
}
function everytodec(&$arr){
        for($i=0;$i<count($arr);$i++){
            $arr[$i]=base_convert($arr[$i], 2, 10);
        }
}
function checktotal(&$arr){
    $endm=3-count($arr)%3;
    if(count($arr)%3 !=0){
        for($i=0;$i<$endm;$i++){
            $arr[]="00000000";
        }
        return $endm;
    }else{
        return 0;
    }
}
function divide6($arr){
    foreach($arr as $val){
        $str.=$val;
    }
    if(strlen($str)%6 !=0)die("error");
    for($i=0;$i<strlen($str)/6;$i++){
        $result[]=substr($str,$i*6,6);
    }
    return $result;
}
function addzero(&$arr){
    for($i=0;$i<count($arr);$i++){
            $arr[$i]=to8($arr[$i]);
    }
}
function encode64($arr,$table,$endmod2){
    for($i=0;$i<count($arr)-$endmod2;$i++){
            $result.=$table[$arr[$i]];
    }
    for($i=0;$i<$endmod2;$i++){
            $result.="=";
    }
    return $result;
}
 

function get_base64($str){
    $str = str_split($str,2);
everyto8($str);
$endmod=checktotal($str);
$str=divide6($str);
addzero($str);
everytodec($str);
$base64code=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','+','/','=');
 return $b_data = encode64($str,$base64code,$endmod);
}


function ping_xian($a,$men,$is_kongxian){
    log_data($a);
    
$string = $a = iconv('UTF-8','GBK' , $a);
$length = strlen($string);
$result = array();
//十进制
for($i=0;$i<$length;$i++){
// if(ord($string[$i])>127){}
$result[] = ord($string[$i]);
}

//  var_dump($result);
//十六进制

$strings = '';
$stringss = [];
foreach($result as $v){
$dec = explode(" ",$v);
$stringss[] = dechex($dec[0])." ".dechex($dec[1]);
$strings.=dechex($dec[0]);
}

$yuyin_str = $strings= strtoupper($strings);
// var_dump($stringss);
$a = strtoupper($strings);
 //$a ='01000100'.$a;//第一行，正在洗车 
$a ='023C0100'.$a;//第二行

$str_lent = strlen($a)/2;
$str_lent = dechex($str_lent);

$str_lent =str_pad($str_lent,2,0,STR_PAD_LEFT);
$a = "05640027"."00".$str_lent.$a.'0000';
$s = pack('H*',$a);
$t = CrcTool::crc166($s);
$t = unpack("H*", $s.$t);

$str = $t[1];
$str1 = substr($str,-4);
$str1a =  substr($str1,-2).substr($str1,0,2);
$str2 = substr($str,0,-8);

$str = $str2.$str1a;

$str =strtoupper("AA55".$str.'AF');;
$str_lent =  strlen($str);
$b_data = get_base64($str);
log_data($str);
$yuyin_str = "01015B73335D".$yuyin_str;
$yuyin_str_lent = strlen($yuyin_str)/2;
$yuyin_str_lent = dechex($yuyin_str_lent);

$yuyin_str_lent =str_pad($yuyin_str_lent,2,0,STR_PAD_LEFT);

$yuyin_str = "FD00".$yuyin_str_lent.$yuyin_str;
 log_data($yuyin_str);
 
 if($is_kongxian==1){
    $is_kongxian_str= 'qlUFZAAnAAwBAAEA1f3U2s+0s7VCdK8=';
 }else{
     $is_kongxian_str= 'qlUFZAAnAAwBAAEAuaTOu7/Vz9BSb68=';
 }
    
    $data = [
        'Response_AlarmInfoPlate'=>[
             'info'=>"{$men}",
             "serialData"=>[
                    [
                    "serialChannel"=> 0,
                    "data"=> $is_kongxian_str,
                    "dataLen"=>46
                    ],
                    [
                    "serialChannel"=> 0,
                    "data"=> $b_data,
                    "dataLen"=>$str_lent
                    ],
                    [
                    "serialChannel"=> 0,
                    "data"=> get_base64($yuyin_str),
                    "dataLen"=>strlen($yuyin_str)
                    ]
                ]
        ]
    ];
     log_data($data);
    echo_data($data);
}



// ping_xian($a);


function echo_data($data){
    header('Content-type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin:*");
    // var_dump($data);
    echo json_encode($data);die;
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

function  gongwei_edit(){
    pdo_update("lh_xiche_shangjia",['gongwei_shiyong +='=>1],array("id"=>$_GPC['s_id']));
    // pdo_update("lh_xiche_shangjia",['gongwei_shiyong +='=>1],array("id"=>$_GPC['s_id']));
}



$postStr = file_get_contents('php://input');
 log_data($postStr);
$uniacid = 2;
$post_data = json_decode($postStr,true);
$chepai = $post_data['AlarmInfoPlate']['result']['PlateResult']['license'];
// kai_men();
$rk_type = $_GPC['rk_type'];
  
$chepai_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cars")." where uniacid=$uniacid and car_number='{$chepai}'");
$gongwei = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_gongwei")." where id={$_GPC['id']}");
    $user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where  id='{$chepai_info['u_id']}'");
if(empty($chepai_info)){
    
    ping_xian('请先小程序绑定车辆','no',$gongwei['type']);
    // echo_data('请绑定车牌');
}


if($rk_type==1){
    
    if($gongwei['type']>0){
        // echo_data('正在使用');
        die;
    }
   $sql ="SELECT * FROM ".tablename("lh_xiche_cars_order")." where uniacid=$uniacid and c_id='{$chepai_info['id']}'  order by id desc";
   //and status=0   这个有问题，先不判断支付状态，默认最后一个订单都是未支付。
   $order_info = pdo_fetch($sql);
   if(strtotime($order_info['end_time'])>(time()-50)){
        //小于20秒
         die;
   }
       $shangjia = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shangjia")." where uniacid=$uniacid and id='{$gongwei['s_id']}'");
    if($user_info['money']<$shangjia['qibu_jiage']){
         ping_xian($chepai_info['car_number'].'余额不足，请充值入场','no',$gongwei['type'],'余额不足,请充值');
    }
    $oreder_data = [
        'g_id'=>$_GPC['id'],
        'uniacid'=>$uniacid,
        'ordersn'=>date("YmdHis").mt_rand(10,99),
        'c_id'=>$chepai_info['id'],
        'car_number'=>$chepai_info['car_number'],
        'u_id'=>$chepai_info['u_id'],
        's_id'=>$_GPC['s_id'],
        'status'=>0,
        'addtime'=>date('Y-m-d H:i:s'),
    ];
    pdo_insert("lh_xiche_cars_order",$oreder_data); 
    pdo_update("lh_xiche_shangjia",['gongwei_shiyong +='=>1],array("id"=>$_GPC['s_id']));
    pdo_update("lh_xiche_gongwei",['type'=>1],array("id"=>$_GPC['id']));
    // kai_men();//进行开门 
        $user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where  id='{$chepai_info['u_id']}'");
    ping_xian($chepai_info['car_number'].'余额'.$user_info['money'].'元','ok',1);
}

if($rk_type==2){
 
   $money  = 0;
   $sql ="SELECT * FROM ".tablename("lh_xiche_cars_order")." where uniacid=$uniacid and c_id='{$chepai_info['id']}'  order by id desc";
   //and status=0   这个有问题，先不判断支付状态，默认最后一个订单都是未支付。
   $order_info = pdo_fetch($sql);
 // var_dump($order_info);
   if($order_info['status']==1){
    // error_reporting();
        pdo_update("lh_xiche_shangjia",['gongwei_shiyong -='=>1],array("id"=>$_GPC['s_id']));
        pdo_update("lh_xiche_gongwei",['type'=>0,'is_show'=>1],array("id"=>$_GPC['id']));
        // kai_men();//进行开门 
        ping_xian($chepai_info['car_number'].'消费'.$order_info['money'].'余额'.$user_info['money'].'元','ok',$gongwei['type']);
   }
   $yongshi = (time()-strtotime($order_info['addtime']))/60;
   $shangjia = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shangjia")." where uniacid=$uniacid and id='{$order_info['s_id']}'");
   $money = $shangjia['qibu_jiage'];
   if($yongshi>$shangjia['qibu_time']){
                $ys2 = ceil($yongshi-$shangjia['qibu_time']);
        $money += $ys2*$shangjia['chaoshi'];
   }
/*   var_dump($shangjia);
var_dump($yongshi);die;
*/
    $oreder_data = [
        'money'=>$money,
        'fenzhong'=>$yongshi,
        'end_time'=>date('Y-m-d H:i:s'),
    ];
    pdo_update("lh_xiche_cars_order",$oreder_data,array("id"=>$order_info['id']));
    $user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where  id='{$chepai_info['u_id']}'");
    if($user_info['money']>=$money){
        pdo_update("lh_xiche_gongwei",['type'=>0,'kaimen'=>1,'is_show'=>1],array("id"=>$_GPC['id']));
        pdo_update("lh_xiche_shangjia",['gongwei_shiyong -='=>1],array("id"=>$_GPC['s_id']));
        
        user_yongjin_edit($uniacid ,$chepai_info['u_id'],-$money,'洗车自动扣款',100);
        user_yongjin_edit($uniacid ,$shangjia['u_id'],$money,'商家收益：'.$chepai_info['car_number'].'洗车'.$yongshi,200);
        pdo_update("lh_xiche_cars_order",['status'=>1],array("id"=>$order_info['id']));
        
        ping_xian($chepai_info['car_number'].'消费'.$money.'余额'.($user_info['money']-$money).'元','no',$gongwei['type']);
       
    }else{
        ping_xian($chepai_info['car_number'].'余额不足，请充值','no',$gongwei['type']);
    }
    
    //支付成功后才开门，并且更新工位状态
}