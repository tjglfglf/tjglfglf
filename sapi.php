   <?php

define('IN_SYS', true);

define('WWW_ROOT', dirname(dirname(dirname(__FILE__))));

require WWW_ROOT.'/framework/bootstrap.inc.php';
global $_GPC, $_W;
/* 
   var_dump($_REQUEST);*/
function log_data($data) {
    //数据类型检测
    if (is_array($data)) {
        $data = json_encode($data);
    }
    $filename = IA_ROOT."/addons/lh_xiche/log/lunxun".date("Y-m-d").".log";
    $str = date("Y-m-d H:i:s")."   $data"."\n";
    file_put_contents($filename, $str, FILE_APPEND|LOCK_EX);
    return null;
}
$postStr = file_get_contents('php://input');
log_data($postStr);
log_data($_POST);
log_data($_GET);/**/
// kai_men();
 if($is_kongxian==1){
    $is_kongxian_str= 'qlUFZAAnAAwBAAEA1f3U2s+0s7VCdK8=';
 }else{
     $is_kongxian_str= 'qlUFZAAnAAwBAAEAuaTOu7/Vz9BSb68=';
 }
    
function kai_men($men){
    $data = [
        'Response_AlarmInfoPlate'=>[
             'info'=>"{$men}",
/*                'serialData'=>[//正在洗车
                [
                "serialChannel"=> 0,
                "data"=> 'qlUFZAAnAAwBAAEA1f3U2s+0s7VCdK8=',
                "dataLen"=>46
                ],*/
                'serialData'=>[//工位空闲
                [
                "serialChannel"=> 0,
                "data"=> 'qlUFZAAnAAwBAAEAuaTOu7/Vz9BSb68=',
                "dataLen"=>46
                ],
/*
                [
                "serialChannel"=> 0,
                "data"=> 'qlUFZAAnABUCPAEA1MFCMTIzNDUs0+C27jUw1KrYQq8=',
                "dataLen"=>64
                ],*/

                 
             
             ],
        ]
    ];
    echo_data($data);
}
function echo_data($data){
    header('Content-type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin:*");
    echo json_encode($data);die;
}

$gongwei = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_gongwei")." where id={$_GPC['id']}");
// var_dump($gongwei);
if($gongwei['kaimen']==1){
/*    if($_GPC['rk_type']==1&&){
        
    }*/
    pdo_update("lh_xiche_gongwei",['kaimen'=>0],array("id"=>$_GPC['id']));
    kai_men('ok');
}else{
    if($gongwei['is_show']==1){
        pdo_update("lh_xiche_gongwei",['is_show'=>0],array("id"=>$_GPC['id']));
        kai_men('no');
    }
     
}