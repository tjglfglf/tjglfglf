<?php

// error_reporting(E_ALL);
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
function getAccessToken_new($appId,$appSecret,$gengxin=false) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
      $res = json_decode(httpGet($url),true);
      $access_token = $res['access_token'];
    return $access_token;
}
function ding_yue($uniacid,$openid,$template,$data,$page){
        global $_W,$_GPC;
        // $openid = 'ocMvv0NlUyyohdJy-qVYoMnZ2hEc';
        $info = pdo_fetch('SELECT * FROM ' . tablename('lh_xiche_parment') . " where uniacid=:uniacid",array(":uniacid"=>$uniacid));
        $ding_yue = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_tongzhi")." where uniacid=:uniacid",array(":uniacid"=>$uniacid));


        $appid = $info['wx_appid'];
        $appsecret = $info['wx_appsecret'];

         $template_id = $template; 
        // $template_id = 'JpwrSYmYyHdvS43lyS-DVEJt7YQUtl9RH-qaZTiqQkg'; 

         if($info['access_token_time_wx']<=(time()+3000)){
            $accessToken =getAccessToken_new($appid ,$appsecret);
            pdo_update('lh_xiche_parment',['access_token_wx'=>$accessToken,'access_token_time_wx'=>time()+3000],["uniacid"=>$uniacid]);
         }else{
            $accessToken = $info['access_token_wx'];
         }

        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $accessToken;
        $data_time = date("Y-m-d H:i:s");
        $dd = array();

        $dd['data']  = $data;
        $dd['touser'] = $openid;
        $dd['template_id'] = $template_id;
        $dd['page'] = $page; 
        
        $result1 = https_curl_json($url, $dd,'json');
        return json_encode($result1);
    }

function https_curl_json($url,$data,$type){
        if($type=='json'){
            $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $data=json_encode($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl);
        return $output;
    }
