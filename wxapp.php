<?php
/**
 * lh_xiche模块小程序接口定义
 *
 * @author Mob228308473815
 * @url
 */
defined('IN_IA') or exit('Access Denied');
// error_reporting(E_ALL);
require IA_ROOT.'/addons/lh_xiche/site.php';
class lh_xicheModuleWxapp extends WeModuleWxapp {
    function __construct(){ 
        global $_GPC, $_W;
            $_GPC['do'];
            $dl = ['index','wx_login','wx_pay','my','Goods_list','My_question','Goods_page','hbCode','wx_dingyue','article','slide','jz_job','weizhi'
            ,'menu','upload_img','ditu','jingdian','base','index_tuijian',
            'gonglue',
            'shangjia',
            'guanggao',
            'renwu',
            'shipin',
            'mentop',
            'jfthumb',
            'qiye'];

            $token = $_SERVER['HTTP_TOKEN'];
            if(!in_array($_GPC['do'], $dl)){
                if(empty($token)){
                    output_error('请登陆');
                }
            }
            if(!empty($token)){
                $token_info = pdo_get('lh_xiche_usertoken',['token'=>$token]);



                if(empty($token_info)){
                    output_error('token无效');
                }else{
                    $user_info = pdo_fetch("SELECT id,lahei FROM ".tablename("lh_xiche_user")." where id=:u_id ",array(":u_id"=>$token_info['u_id']));
                        if($user_info['lahei']==1){
                            die;
                        }
                    $_SESSION['u_id'] = $token_info['u_id'];

                }
            }

            $fu = ['wx_pay','wx_login','hbCode','wx_dingyue','bang_tel'];
            if(in_array($_GPC['do'], $fu)){
                $a = $_GPC['do'];
                $this->$a();
            }else{
                $lh_xiche = new lh_xicheModuleSite(false);
                $lh_xiche->modulename ="lh_xiche";
                $f = 'doMobile'.$_GPC['do'];
                $lh_xiche->$f();
            }

    }
    public function doPageTest(){
        global $_GPC, $_W;
        $errno = 0;
        $message = '返回消息';
        $data = array();
        return $this->result($errno, $message, $data);
    }

    // https://app.icekun.com/app/index.php?op=&do=wx_login&i=3&c=entry&m=lh_xiche&v=1&from=wxapp&a=wxapp
  function httpGet($url) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT, 500);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_URL, $url);
      $res = curl_exec($curl);
      curl_close($curl);
      return $res;
  }

    public function wx_login() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $result = pdo_fetch('SELECT * FROM ' . tablename('lh_xiche_parment') . " where uniacid=:uniacid", array(":uniacid" => $uniacid));

        $APPID = $result['wx_appid'];
        $SECRET = $result['wx_appsecret'];
        $code = trim($_REQUEST['code']);
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$APPID}&secret={$SECRET}&js_code={$code}&grant_type=authorization_code";
        $aa = $this->httpGet($url);
       // var_dump($aa);
        $data['userinfo'] = json_decode($aa);

        $openid = $data['userinfo']->openid;
        $sessionKey = $data['userinfo']->session_key;
        $unionid = $data['userinfo']->unionid;
        
        if($unionid=='NULL' || $unionid==NULL || empty($unionid)){
            $unionid = "";
        }

        $item = array();
        $item['uniacid'] = $uniacid;
        $item['openid'] = $openid;
        $item['unionid'] = $unionid;
        $item['sessionKey'] = $sessionKey;
        $item['name'] = $_GPC['nickName'];
        $item['thumb'] = $_GPC['avatarUrl'];
        $item['ip'] = get_ip();
        $item['end_login_time'] = date("Y-m-d H:i:s",time());


        if(!empty($openid)){
            //查询用户信息
            $user = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and openid=:openid ",array(":openid"=>$openid,":uniacid"=>$uniacid));
            $u_id = $user['id'];
            $is_insert = false;
            if (empty($user)) {
                if($unionid != ""){
                    $users = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and  unionid=:unionid ",array(":unionid"=>$unionid,":uniacid"=>$uniacid));
                    $u_id = $users['id'];
                    if (empty($users)) {
                        $item['addtime'] = date("Y-m-d H:i:s",time()); 
                        $res = pdo_insert('lh_xiche_user', $item);
                        $u_id = pdo_insertid();
                        $is_insert = true;
                    }else{
                        $res = pdo_update('lh_xiche_user', $item, array('id' => $users['id']));
                  }
                }else{
                    $item['addtime'] = date("Y-m-d H:i:s",time()); 
                    $res = pdo_insert('lh_xiche_user', $item);
                    $u_id = pdo_insertid();
                    $is_insert = true;
                }
            }else{
                if($user['lahei']==1){
                    output_error('用户授权信息获取失败');
                    die;
                }
                $res = pdo_update('lh_xiche_user', $item, array('id' => $u_id));
            }

            if($is_insert){
                //新用户奖励积分
                $user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where uniacid=:uniacid and sort=:type ",array(":uniacid"=>$uniacid,":type"=>1));
                if($user_type['new_user']>0){
                    $this->user_jifen_edit($u_id,$user_type['new_user'],"新用户奖励",2);
                }
            }
            $datas = array();
            if($_GPC['f_id']>0&&$is_insert){
                $user_info_f = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and id=:id ",array(":uniacid"=>$uniacid,":id"=>$_GPC['f_id']));
                if(!empty($user_info_f)){
                    $datas['ff_id'] = $user_info_f['f_id'];

                      $user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where uniacid=:uniacid and sort=:type ",array(":uniacid"=>$uniacid,":type"=>$user_info_f['type']));
                      if($user_info_f['yaoq_day']<100){//分险控制
                            $this->user_jifen_edit($user_info_f['id'],$user_type['yaoqing'] ,"推荐新用户奖励",2);
                      }

                      //一级用户加1
                      pdo_update('lh_xiche_user', array('yi_sum +=' =>1,'yaoq_day +='=>1), array('id' =>$user_info_f['id']));
                      if(!empty($user_info_f['f_id'])){
                        //二级+1
                        pdo_update('lh_xiche_user', array('er_sum +=' =>1), array('id' =>$user_info_f['f_id']));
                      }
                }
                $datas['f_id'] = $_GPC['f_id'];
                $res = pdo_update('lh_xiche_user', $datas, array('id' => $u_id));
            }

                $data['openid'] = $openid;
                $data['sessionKey'] = $sessionKey;
                $data['unionid'] = $unionid;

                $token_str = md5('lhshop'.time().$u_id);
                $token = [
                    'u_id'=>$u_id,
                    'uniacid'=>$uniacid,
                    'token'=>$token_str,
                    'client_type'=>'wx',
                    'login_time'=>time(),
                ];
                pdo_insert('lh_xiche_usertoken', $token);

                output_data(['status'=>1,'token'=>$token_str]);
        }else{
            output_error('用户授权信息获取失败');

        }

    }

        //支付
    public function wx_pay()
    {
        //o_id  和 id   type 和pay_type的坑
         // error_reporting(E_ALL);
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        include 'inc/class/wxpay.php';
        $id = $_GPC['id'];
        $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id=:id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));

        $res = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_parment")." WHERE uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $wxts = 'wx_goods';
        $count_money = $order_info['count_money'];
        if($_GPC['type']=='baodan'){
            $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_baodan")." where o_id=:id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
            $count_money = $order_info['jiage'];
            $wxts = 'baodan';
        }
        if(!empty($_SESSION['u_id'])){
            $user_info = pdo_get('lh_xiche_user',['id'=>$_SESSION['u_id']]);
        }

        if(empty($user_info)){
            die;
        }
        if($_GPC['type']=='jf_goods'){
            $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_jforder")." where id=:id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));

            if($order_info['yunfei']>0){
                $count_money = $order_info['yunfei'];
            }else{//运费等于0 直接提示兑换成功

                if($user_info['jifen']<$order_info['jifen']){
                    output_error('积分不足');
                }
                $res = pdo_update("lh_xiche_jforder",array('transaction_id'=>0,'type'=>'待发货'),array("id"=>$id ));
            
                $this->user_jifen_edit($order_info['u_id'],-$order_info['jifen'],"积分兑换",6);

                output_data(['zhifu'=>1]); 
                die;
            }
            $wxts="app_jifen";
        }

       
        if($_GPC['pay_type']=='wx_vip'){//vip购买
            $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order_vip")." where id=:id and status=10 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
            // var_dump($order_info);
            $attach="wx_vip";
            $count_money =$order_info['count_money'] = $order_info['money'];
            $order_info['ordersn'] = $order_info['ordersn'];
        }

        if($_GPC['pay_type']=='cars_order'){//vip购买
            $order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cars_order")." where id=:id  and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));

            $yongshi = (time()-strtotime($order_info['addtime']))/60;
            $shangjia = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shangjia")." where uniacid=$uniacid and id='{$order_info['s_id']}'");
            $money = $shangjia['qibu_jiage'];
            if($yongshi>$shangjia['qibu_time']){
                $money +=($yongshi-$shangjia['qibu_time'])*$shangjia['chaoshi'];
            }

            $oreder_data = [
                'money'=>$money,
                'fenzhong'=>$yongshi,
                'end_time'=>date('Y-m-d H:i:s'),
            ];
            pdo_update("lh_xiche_cars_order",$oreder_data,array("id"=>$order_info['id']));


            $attach="cars_order";
            $count_money =$order_info['count_money'] = $money;
            $order_info['ordersn'] = $order_info['ordersn'];
        }

        $appid = $res['wx_appid'];
        $openid = $user_info['openid'];//需要获取这个openid
        $mch_id = $res['mchid'];
        $key = $res['wxkey'];
        $out_trade_no = $order_info['ordersn'].mt_rand(10,99);
        
        if (empty($count_money)) {
            $body = '订单付款';
            $total_fee = floatval(99 * 100);
        } else {
            $body = '订单付款';
            $total_fee = floatval($count_money * 100);
        }
        $notify_url = $_W['siteroot'].'/addons/lh_xiche/wxapp_notify.php';
        $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee,$notify_url,$attach);
        $return = $weixinpay->pay();
        output_data($return); 
    }

    public function wx_dingyue(){
                global $_W, $_GPC;
        //'e3KWLgNS8YWA7x8zTob6iR0LSUOSri23M9qdDl9cmP0',1111
        $arr = ['PNVWOMjWSpKa8lHkLGGaofzlasaNSmss9VwDwsJswLo','iriUCZXUNU3DMwarVXkduhLubhL4x-t6lkSQqqgKipk','LsbDyuL1xeG9sINF2mcfgiDrvfa1I2GRjgIhL8YzQVo'];

        if($_GPC['type']=='jz'){
            $arr = ['uLKG3qdJrPKDGEynNfumOqmNLzx2YFsp4FTIh2kVzDU','AALG6WxtSznYvRCy6DVeFgXqrWhph0KzyJeyCJ2Qy-g','pddH7HJia-2sYcBRR-KzrqeGRdOfpGGsJTSLzLeAQYw'];
        }

        if($_GPC['type']=='jz_bm'){//后台审核
            $arr = ['shbDyonSQ5npNZZTxD-jM6LgAEvp10smYXdU30Wsk04'];
        }


        output_data($arr);
    }

    //海报二维码
     public function hbCode(){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $result = pdo_fetch('SELECT * FROM ' . tablename('lh_xiche_parment') . " where uniacid=:uniacid", array(":uniacid" => $uniacid));
  
        
        $APPID = $result['wx_appid'];
        $SECRET = $result['wx_appsecret'];
        $tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$APPID}&secret={$SECRET}";
        $getArr=array();
        // $tokenArr=json_decode($this->send_post($tokenUrl,$getArr,"GET"),true);
        $tokenArr=json_decode($this->httpGet($tokenUrl));
        $access_token=$tokenArr->access_token;
            
            if(empty($_GPC['path'])){
                $_GPC['path'] = "pages/index/index";
            }
            if(empty($_GPC['id'])){
                $_GPC['id'] =$_SESSION['u_id'];
            }
            $post_data=array(
                // "scene"=>$id.",".$user_id,
                "scene"=>"{$_GPC['id']}",
                "page"=>"{$_GPC['path']}",
                "width"=>'72'
                );

                $post_data = json_encode($post_data);
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
                // $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$access_token."";
                // $post_data='{"path":"hyb_o2o/tablist/index/index","width":100}';
            $result=$this->api_notice_increment($url,$post_data); 
             // var_dump($result);die;
            $image_name = $_SESSION['u_id']."_wxcode.png";
            $filepath = "../attachment/{$image_name}";   
            $file_put = file_put_contents($filepath, $result);
            @require_once (IA_ROOT . '/framework/function/file.func.php');
            @file_remote_upload($image_name);
           $img_url =  $_W['attachurl'].$image_name;

       /*     header('Content-Type:png');
            echo $img;*/
       /*     $img=base64_encode($img);
            return $img;*/
         if(strpos($img_url,"https")===true&&!empty($img_url)) {

         }else{
             // $img_url= str_replace('http','https',$img_url);
         }
           
        // http://app.icekun.com/attachment/images/3/2020/07/lT33lxLD2tDz52J2L15Idi3D2yPd4t.jpg
        output_data($img_url);
     }
    //获取手机号
    public function doPagebang_tel(){
        global $_GPC, $_W;
        require_once dirname(__FILE__) . '/inc/class/bang_tel.php';
        $u_id = $_SESSION['u_id'];
        $uniacid = $_W['uniacid'];
        $result = pdo_fetch('SELECT * FROM ' . tablename('lh_xiche_parment') . " where uniacid=:uniacid",array(":uniacid"=>$uniacid));
        $appid = $result['wx_appid'];
        $user = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where id=:id ",array(":id"=>$u_id));
       $sessionKey = $user['sessionKey'];

        $encryptedData = $_GPC['encryptedData'];
        $iv = $_GPC['iv'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $datas = array('gstage' => 1, 'rdata' => $data);
        } else {
            $datas = array('gstage' => 0, 'rdata' => $errCode);
        }
         output_data($datas);
    }
    private function api_notice_increment($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }else{
            return $tmpInfo;
        }
    }

    public function user_jifen_edit($u_id,$nums,$content,$type){
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
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
          // $user_info = pdo_get('mc_mapping_fans', array('openid' =>'odNNX56C4qxV011KYm-39t2YQZrg'));
          // var_dump($user_info);
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
                pdo_update('lh_xiche_user', array('jifen +='=>$nums), array('id' =>$u_id));
          }else{
                pdo_update('lh_xiche_user', array('jifen +='=>$nums), array('id' =>$u_id));
          }
          
    }
}

