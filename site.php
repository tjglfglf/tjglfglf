<?php
/**
 * lh_xiche模块微站定义
 * @url
 */
defined('IN_IA') or exit('Access Denied');
 // error_reporting(E_ALL);
require IA_ROOT.'/addons/lh_xiche/inc/func/core.php';
require IA_ROOT.'/addons/lh_xiche/inc/func/functions.php';
define('STYLE_PATH', '/addons/lh_xiche/template/mobile/style/');
$order_type = array();
$order_type['0']='已取消';
$order_type['10']='待支付';
$order_type['20']='待发货';
$order_type['30']='待收货';
$order_type['40']='确认完成';
$order_type['50']='退款中';
$order_type['60']='已退款';
//if(date('Ymd',time())>20190829){echo "err";die;}
$goods_jifen_yunfei = 5;

class lh_xicheModuleSite extends Core {
public $menu = '';
	function __construct($wxapp = true){ 
		global $_GPC,$_W; 
    	$uniacid = $_W['uniacid'];
    				
    					// $_SESSION['u_id']=1314;

    	 // $_SESSION['u_id']=null;die; 
		          	if($_GPC['v']==1&&!isset($_SESSION['lailu'])){
		          		 $_SESSION['lailu'] =  $_SERVER['HTTP_REFERER'];
		          		 if(!empty($_GPC['lailu']&&$_GPC['do']!='login')){
		          		 	$_SESSION['lailu'] =  $_GPC['lailu'];
		          		 }
		          	}
		          	//wxref=mp.weixin.qq.com#wechat_redirect

		          	if(!empty($_GPC['wxref'])){
		          		if($_GPC['do']=='duobao'&&$_GPC['op']=='goods_info'){
		          			header("location:".$_W['setting']['site']['url']."/addons/lh_xiche/html/#/pages/treainfor/treainfor?id={$_GPC['id']}");	die;
		          		}
		          		if($_GPC['do']=='duobao'&&$_GPC['op']=='my'){
		          			header("location:".$_W['setting']['site']['url']."/addons/lh_xiche/html/#/pages/myrecord/myrecord");	die;
		          		}
		          	}
/*					          	echo $_SERVER['HTTP_REFERER'];
					          	echo $_SERVER['lailu'];
				echo $_GPC['lailu'];die;*/
    	 // var_dump($_W['fans']);die;

	      // if($_GPC['do']!='login'&&$_GPC['c']=='entry'){//微信授权登陆逻辑

		// if($_do==''&&!in_array($_GPC['do'], $this->$_do)){//小程序登陆判断
		
	          //分销商逻辑
	          if(!empty($_GPC['f_id'])){
	          	$this->log_data($_GPC['f_id']);
	              $_SESSION['f_id']=$_GPC['f_id'];
	          }
		 if($wxapp){//小程序判断

	      if($_GPC['do']!='upload_img'&&$_GPC['do']!='jfthumb'&&$_GPC['do']!='index_data'&&$_GPC['do']!='user_login'&&$_GPC['do']!='user_reg'&&$_GPC['do']!='user_reset'&&$_GPC['do']!='Img_yzg'&&$_GPC['do']!='Sendsms'&&$_GPC['c']=='entry'){

	          //判断是否app 还是微信
	          if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
	            //微信登录
	            $login_url="weixin";
	            $_W['is_app']=false;
	          }else{
	          	//$_SESSION['u_id']=1;
	            //app登录
	            $login_url="app";
	            $_W['is_app']=true;
	          }
	          //分销商逻辑
	          if(!empty($_GPC['f_id'])){
	              $_SESSION['f_id']=$_GPC['f_id'];
	          }
	          // echo $_SESSION['lailu'];die;
	          if($_GPC['op']!='weixin'){
	          	// unset($_SESSION['lailu']);

	          	
		    		if(!isset($_SESSION['u_id'])){
		    			//完了要加清除cookie
		    			// echo "location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=login&op={$login_url}&m=".$_GPC['m'];die;
		    			 header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=login&v={$_GPC['v']}&op={$login_url}&m=".$_GPC['m']); //微信授权之前的
		    			// header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=user_login&op={$login_url}&m=".$_GPC['m']);
		              die;
		    		}
		            $user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and id=:u_id ",array(":uniacid"=>$uniacid,":u_id"=>$_SESSION['u_id']));
		            if(empty($user_info)){
		            	$_SESSION['u_id']=null;

		                header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=login&v={$_GPC['v']}&op={$login_url}&m=".$_GPC['m']);
		                die;
		            }
		            //未绑手机号
		            if(empty($user_info['u_phone'])){
		                // header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=login&op=&m=".$_GPC['m']);
		                // die;
		            }
		            if($user_info['lahei']==1){
		                header("location:/aaa/");
		                die;
		            }
				}

/*
				$users = pdo_get('mc_mapping_fans', array('openid' =>$user_info['w_openid']));
				$wq_user = pdo_get('mc_members',  array('uid' =>$users['uid']));
				if(!empty($wq_user)){
					pdo_update("lh_xiche_user",array('jifen'=>$wq_user['credit1']),array('id'=>$_SESSION['u_id']));
				}*/
				

			
	        }else{
	        	if(!isset($_SESSION['u_id'])){}

	        	
	        }
	        }
	        $this->menu  = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_menu")." where uniacid=$uniacid order by sort asc");

	}                                


	public function doMobileWxSign(){
		$wxinfo= $this->getSignPackage();
		$base = pdo_get('lh_xiche_base', array('uniacid'=>$uniacid));
		output_data($wxinfo);
	}

	

	public function doMobileBase(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$base =pdo_fetch("SELECT * FROM ".tablename("lh_xiche_base")." where uniacid=:uniacid ",array(":uniacid"=>$uniacid));

		if(strpos($base['logo'],"http")===false&&!empty($base['logo'])){
			$base['logo'] = $_W['attachurl'].$base['logo'];
		}

		if(strpos($base['erweima'],"http")===false&&!empty($base['erweima'])){
			$base['erweima'] = $_W['attachurl'].$base['erweima'];
		}
		if(strpos($base['hbbg'],"http")===false&&!empty($base['hbbg'])){
			$base['hbbg'] = $_W['attachurl'].$base['hbbg'];
		}
		$base['f_id']=$_SESSION['u_id'];
		$base['thumb'] = unserialize($base['thumb']);
		
		foreach ($base['thumb'] as $key => &$value) {
			if(strpos($value,"http")===false&&!empty($value)){
				$value = $_W['attachurl'].$value;
			}
		}
		


		$base['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/addons/lh_xiche/html/#/?';


		output_data($base);
	}


	
	public function doMobileIndex(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$uniacid = $_W['uniacid'];
		$city = $_COOKIE['city'];
		$u_id = $_SESSION['u_id'];
		$yuming = 'https://'.$_SERVER['HTTP_HOST'];
		if(!empty($_GPC['wxref'])){
			header("location:".$yuming.'/addons/lh_xiche/html/#/');	
		}
		
		if($_GPC['v']!=1){
			 header("location:".$yuming.'/addons/lh_xiche/html/#/');	
		}



	    $base = pdo_get('lh_xiche_base', array('uniacid'=>$uniacid));
	    // $goods_list = pdo_getall('lh_xiche_goods', array('uniacid'=>$uniacid));$where ="  ";
	    $gonggao_list = pdo_getall('lh_xiche_gonggao', array('uniacid'=>$uniacid),array(),'',array('id desc'),array(0,5));
	    // var_dump($gonggao_list);die;
	    $goods_style_list = pdo_getall('lh_xiche_goods_style', array('uniacid'=>$uniacid),array(),'',array('sort'),array(0,4));
	    // $index_list = pdo_get('lh_xiche_index', array('uniacid'=>$uniacid,'type in'=>'(0,1)'));
		$index_list = pdo_getall('lh_xiche_index', "uniacid=$uniacid and type in(0,1)",array(),'',array('sort desc'));

	    foreach ($index_list as &$value) {
				if(strpos($value['image'],"http")===false){
					$value['image'] = $_W['attachurl'].$value['image'];
				}
	    	$value['goods_list'] = pdo_fetchall("SELECT id,ids,name,jiage,thumb FROM ".tablename("lh_xiche_goods")." where uniacid=:uniacid and status=1 and id in({$value['info']})",array(":uniacid"=>$uniacid));
	    	foreach ($value['goods_list'] as $k => &$v) {
				if(strpos($v['thumb'],"http")===false){
					$v['thumb'] = $_W['attachurl'].$v['thumb'];
				}
	    	}
	    }


		$jf_list = pdo_getall('lh_xiche_jftype', "uniacid=$uniacid ");

		foreach ($jf_list as $key => &$value) {
			$value['list'] = pdo_getall('lh_xiche_jfgoods', "uniacid=$uniacid and tuijian=1 and type={$value['id']}");
		}
		 $jfgoods_list = pdo_getall('lh_xiche_jfgoods', "uniacid=$uniacid and tuijian=1");//旧的
		foreach ($jfgoods_list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}

		//轮播
		



		$menutop = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_menutop")." where uniacid=$uniacid order by sort asc");
		foreach ($menutop as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}
		
		
	


		$article_list = pdo_getall('lh_xiche_article', array('uniacid'=>$uniacid,'is_tuijian'=>1),array(),'',array(),array(0,10));

			foreach ($article_list as $key => &$value) {
				if(strpos($value['pic'],"http")===false){
					$value['pic'] = $_W['attachurl'].$value['pic'];
				}
			}

		
		$menu= $this->menu;
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
					$value['thumb2'] = $_W['attachurl'].$value['thumb2'];
				}
			}
			$url = 'https://'.$_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('index').'&f_id='.$u_id;

	$list_article = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_article")." WHERE uniacid=$uniacid $where order by sort asc");

	foreach ($list_article as $key => &$value) {
		if(strpos($value['pic'],"http")===false){
			$value['pic'] = $_W['attachurl'].$value['pic'];
		}
	}

		if($_GPC['v']==1){
			$base['thumb'] = unserialize($base['thumb']);
			$lunbo = array();
			foreach ($base['thumb'] as $key => &$value) {

				if(strpos($value,"http")===false){
					$value = $_W['attachurl'].$value;
				}
				$lunbo[$key]['img'] = $value;
				$lunbo[$key]['link'] = '/pages/prizewinner/prizewinner';
			}

			$base_set['yaoqing_ico'] =$_W['attachurl'].$base['yaoqing_ico'];
			$base_set['kefu_ico'] =$_W['attachurl'].$base['kefu_ico'];
			$base_set['kefu_wx'] =$_W['attachurl'].$base['kefu_wx'];
			$base_set['name'] =$base['name'];

$shangjia_sum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('lh_xiche_jz_merchant')." where uniacid=$uniacid  ");
$fabu_sum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('lh_xiche_fabu')." where uniacid=$uniacid  ");
$fabu_pv = pdo_fetchcolumn("SELECT sum(pv) FROM ".tablename('lh_xiche_fabu')." where uniacid=$uniacid  ");
			output_data(array('base_set'=>$base_set ,
				'menu'=>$menu,
				'lunbo'=>$lunbo,
				'gonggao'=>$gonggao_list,
				'menutop'=>$menutop,
				'duobao_tj'=>$duobao_tj,
				'duobao_tj'=>[],
				'duobao'=>$duobao,
				'duobao'=>[],
				'goods_list'=>$goods_list,
				// 'kaijiang'=>$kaijiang,
				'kaijiang'=>[],
				'user_list'=>$user_list,
				'index_list'=>$index_list,
				'list_article'=>$list_article,
				'jfgoods_list'=>$jfgoods_list,
				'jfgoods_list_cz'=>$jfgoods_list,
				'shangjia_sum'=>$shangjia_sum,
				'fabu_sum'=>$fabu_sum,
				'fabu_pv'=>$fabu_pv,

				'url'=>$url
				)
			);
		}

	}

	public function doMobileMenu(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$uniacid = $_W['uniacid'];
		$menu= pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_menu")." where uniacid=$uniacid order by sort asc");
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
				if(strpos($value['thumb2'],"http")===false){
					$value['thumb2'] = $_W['attachurl'].$value['thumb2'];
				}
			}
		output_data($menu);
	}

	public function doMobileSlide(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:"0";
		$uniacid = $_W['uniacid'];
		$menu = pdo_getall('lh_xiche_slide', ['uniacid'=>$uniacid,'weizhi'=>$op]);
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		output_data($menu);
	}

	//商品分类
	public function doMobileGoods_class(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		// $list = pdo_getall('lh_xiche_goods_style', array('uniacid'=>$uniacid));
		$index_list = pdo_getall('lh_xiche_index', "uniacid=$uniacid ");

	    foreach ($index_list as &$value) {
	    	$value['goods_list'] = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_goods")." where uniacid=:uniacid and id in({$value['info']})",array(":uniacid"=>$uniacid));
	    }

		include $this->template('goods_class');
	}
	public function doMobileGoods_style(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_goods_style")." where uniacid=$uniacid and fid=0");
	    foreach ($list as &$value) {
	    	$value['subCategoryList'] = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_goods_style")." where uniacid=$uniacid and fid={$value['id']}");
	    	foreach ($value['subCategoryList'] as $k => &$v) {
				if(strpos($v['img'],"http")===false){
					$v['img'] = $_W['attachurl'].$v['img'];
				}
	    	}
	    }

		output_data($list);
	}
	//商品列表
	public function doMobileGoods_list(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$where =" and status=1 ";
		$pindex = max(0, intval($_GPC['page']));
		$psize = 10;
		if(!empty($id)){
			$where .= " and (type_fid=$id or type=$id)";
		}
		if(!empty($_GPC['paixu'])){
			$paixu = $_GPC['paixu'];
		}else{
			$paixu = 'desc';
		}

		$sort = ' order by ids desc';
		if($_GPC['sort']=='1'){
			$sort = ' order by xiaoliang '.$paixu;
		}
		if($_GPC['sort']=='2'){
			$sort = ' order by jiage '.$paixu;
		}
		if(!empty($_GPC['lowInput'])){
			$where .= " and jiage>={$_GPC['lowInput']}";
		}
		if(!empty($_GPC['tallInput'])){
			$where .= " and jiage<={$_GPC['tallInput']}";
		}

		$limit = ($pindex * $psize).',' . $psize;
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_goods")."  where uniacid=$uniacid {$where} $sort  limit $limit");
		$class = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_goods_style")."  where uniacid=$uniacid  ");
		array_unshift($class,array("id"=>"0","title"=>"全部"));

		foreach ($list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
			if($value['auto_xiajia']==1){
				$time =strtotime($value['auto_xiajia_time'])-time() ;
				$value['miaosha'] = "剩余".intval($time / (60 * 60 * 24))."天".intval($time % (60 * 60 * 24) % 3600 / 60)."小时";
			}

		}
		if($_GPC['v']==1){
			$menu = $this->menu;
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
			output_data(['list'=>$list,'class'=>$class,'menu'=>$menu]);
		}
		include $this->template('goods_list');
	}

	//商品列表
	public function doMobileOrder_kuaisu(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$where =" and status=1 ";
		$where .= " and kuaisu=1";
		$filed = "id,name,thumb,jiage";
		$list = pdo_fetchall("SELECT $filed FROM ".tablename("lh_xiche_goods")."  where uniacid=:uniacid {$where} order by ids desc ",array(":uniacid"=>$uniacid));

		foreach ($list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}
		$output_data['list'] = $list;
		$output_data['menu'] = $this->menu;
			foreach ($output_data['menu'] as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		$output_data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('index').'&f_id='.$u_id;
		if($_GPC['v']==1){ 
			output_data($output_data);
		}
		include $this->template('order_kuaisu');
	}

	//商品列表
	public function doMobileHezuofang(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$where =" and status=1 ";
		$where .= " and kuaisu=1";
		$filed = "id,name,thumb,jiage";
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_hezuofang")."  where uniacid=:uniacid  order by sort desc ",array(":uniacid"=>$uniacid));

		$output_data['list'] = $list;
		$output_data['menu'] = $this->menu;
			foreach ($output_data['menu'] as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		$output_data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('index').'&f_id='.$u_id;
		if($_GPC['v']==1){ 
			output_data($output_data);
		}
	}
	//商品详情
	public function doMobileGoods_page(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];

		$info = pdo_get('lh_xiche_goods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		$info['content'] = html_entity_decode($info['content']);
		$info['thumbs'] = unserialize($info['thumbs']);
		foreach ($info['thumbs'] as $key => &$value) {
			if(strpos($value,"http")===false){
				$value = $_W['attachurl'].$value;
			}
		}
		if(strpos($info['thumb'],"http")===false){
			$info['thumb'] = $_W['attachurl'].$info['thumb'];
		}
		// $wxinfo= $this->getSignPackage();1

		$goods_set = pdo_get('lh_xiche_goods_set', array('uniacid' => $uniacid), array('*'));
		$youhuiquan = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_youhuiquan")." where uniacid=:uniacid and y_status=0 ",array(":uniacid"=>$uniacid));
		$info['tishi'] = $goods_set['tishi'];

		foreach ($youhuiquan as $key => &$value) {
			$value['lingqu'] = false;
		}
		$info['youhuiquan'] = $youhuiquan;
			if($info['auto_xiajia']==1){
				$time =strtotime($info['auto_xiajia_time'])-time() ;
				$info['miaosha'] = "剩余".intval($time / (60 * 60 * 24))."天".intval($time % (60 * 60 * 24) % 3600 / 60)."小时结束";
			}
		if($_GPC['v']==1){
			output_data($info);
		}
		include $this->template('goods_page');
	}

	public function doMobileYouhuiquan(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$u_id = $_SESSION['u_id'];
		if($op=='lingqu'){
			$info = pdo_get('lh_xiche_useryouhuiquan',['y_id'=>$_GPC['id'],'u_id'=>$u_id]);
			if(!empty($info)){
				output_error('您已经领取过了！');
			}
			$data = [];
			$data['type'] = '未使用';
			$data['u_id'] = $u_id;
			$data['y_id'] = $_GPC['id'];
			$data['uniacid'] = $uniacid;
			$data['addtime'] = date('Y-m-d H:i:s');
			pdo_insert("lh_xiche_useryouhuiquan",$data);
			output_data('领取成功');
		}

		if($op=='my'){
			$sql=" select a.*,b.* from".tablename('lh_xiche_useryouhuiquan')." as a left join  ".tablename('lh_xiche_youhuiquan')." as b on a.y_id=b.y_id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id}";
		  	$list = pdo_fetchall($sql);

			output_data($list);
		}

	}

	//公告详情
	public function doMobileGonggao(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		if($id>0){
			$info = pdo_get('lh_xiche_gonggao', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		}else{
			$info = pdo_getall('lh_xiche_gonggao', array('uniacid' => $uniacid), array('*'),'','id desc');

			foreach ($info as $key => &$v) {
				if(strpos($v['pic'],"http")===false){
					$v['pic'] = $_W['attachurl'].$v['pic'];
				}
			}
		}

		if($_GPC['v']==1){
			output_data($info);
		}
		// $wxinfo= $this->getSignPackage();
		include $this->template('gonggao');
	}


	//商品下单
	public function doMobileGoods_order(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$id = $_GPC['id'];
		$list = array();
		if($_GPC['num']<1){
			$_GPC['num']=1;
		}
		$_GPC['num'] = ceil($_GPC['num']);

		if($op=='add'&&!empty($id)){

			$goods_info = pdo_get('lh_xiche_goods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
			if(strpos($goods_info['thumb'],"http")===false){
				$goods_info['thumb'] = $_W['attachurl'].$goods_info['thumb'];
			}
			if($goods_info['xiangou']>0){//查询是否限购

			}
			$list['list'][]= $goods_info;
			$list['sum_money'] = $goods_info['jiage']*$_GPC['num'];
			$list['yunfei'] =  $goods_info['kuaidi'];
			$list['sum_num'] = $list['g_num'] =  $_GPC['num'];
		}

		if($op=='addcart'){
			$cart_id = substr($_GPC['cart_id'],0,strlen($_GPC['cart_id'])-1);
		   	$sql=" select a.*,b.name,b.jiage,b.jiage,b.thumb from".tablename('lh_xiche_cart')." as a left join  ".tablename('lh_xiche_goods')." as b on a.g_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id} and a.id in({$cart_id})";
		  	$list['list'] = pdo_fetchall($sql);

		  	$list['sum_money']=0;
		  	$list['sum_num']=0;
		  	foreach ($list['list'] as $key => &$value) {
		  		$list['sum_money']+=$value['g_num']*$value['jiage'];
		  		$list['sum_num']+=$value['g_num'];
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
		  	}
		}

		
		$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));

			$sql=" select a.*,b.* from".tablename('lh_xiche_useryouhuiquan')." as a left join  ".tablename('lh_xiche_youhuiquan')." as b on a.y_id=b.y_id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id}";
		  	$youhuiquan = pdo_fetchall($sql);

		if($_GPC['v']==1){
			output_data(['address'=>$address,'list'=>$list,'youhuiquan'=>$youhuiquan]);
		}
		include $this->template('goods_order');
	}

	public function doMobileOrders_add(){
		// error_reporting(E_ALL);
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		//
		if(!empty($_GPC['addr_id'])){
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'id'=>$_GPC['addr_id'],'u_id'=>$u_id));

		}else{
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'checked'=>1,'u_id'=>$u_id));
		}
			if(empty($address)){
				$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));
			}
		$data = array();
		$data['uniacid'] = $uniacid;
		$data['u_id'] = $u_id;
		$data['ordersn'] = date("YmdHis").mt_rand(10,99);
		$data['name'] = $address['uname'];
		$data['telphone'] = $address['phone'];
		$data['address'] = $address['address'];
		$data['xxaddress'] = $address['xxaddress'];
		$data['beizhu'] = $_GPC['message'];
		$data['hexiao'] = mt_rand(1000000,9999999);
		
		// $data['yhj_monney'] = $yhj_info['money'];
		$data['type'] = 10;
        $data['kuaidi'] = $goodsyunfei;
        // $data['hy_money'] = $huiyuanyouhui;
		$data['yhj_id'] = $yhj_info['y_id'];
		$data['xdtime'] = date("Y-m-d H:i:s",time());
		if($_GPC['num']<1){
			$_GPC['num']=1;
		}
		$_GPC['num'] = ceil($_GPC['num']);
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		if($_GPC['spec_id']>0){//直接购买
			$goods_info = pdo_get('lh_xiche_goods', array('uniacid' => $uniacid,'status' => 1,'id' => $_GPC['spec_id']), array('*'));
			if(empty($goods_info)){
				output_error('暂无商品信息');
			}
			if($user_info['type']<$goods_info['vip']&&$goods_info['vip']!=0){//判断当前会员等级是否支持
				output_error('您的会员等级不足，不支持购买');
			}
			$list['list'][]= $goods_info;
			$list['sum_money'] = $sum_money = $goods_info['jiage']*$_GPC['num'];
			//查询限购
			if($goods_info['xiangou']>0){
				$order = pdo_fetchcolumn("select count(*) as num from ".tablename("lh_xiche_ordergoods")." where uniacid={$uniacid} and u_id=$u_id and g_id={$_GPC['spec_id']}");

				if($order['num']>=$goods_info['xiangou']){
					output_error('该商品限购'.$goods_info['xiangou'].'个，你已经购买过了');
				}
				if($_GPC['num']>$goods_info['xiangou']){
					output_error('该商品限购'.$goods_info['xiangou'].'个，你已超出购买数量');
				}
			}
	  		if($goods_info['baoyou']!=1){
	  			$data['yunfei'] = $goods_info['kuaidi'];
	  		}
			$data['count_money'] = $list['sum_money'];
			$data['s_id'] = $goods_info['s_id'];

			pdo_insert("lh_xiche_order",$data);
			$o_id = pdo_insertid();
			$arr=array();
			$arr['uniacid'] = $uniacid;
			$arr['o_id'] = $o_id;
			$arr['g_id'] = $goods_info['id'];
			$arr['num'] = $_GPC['num'];
			$arr['g_name'] = $goods_info['name'];
			$arr['g_jiage'] = $goods_info['jiage'];
			$arr['thumb'] = $goods_info['thumb'];

			$arr['u_id'] = $u_id;
			pdo_insert("lh_xiche_ordergoods",$arr); 
			output_data($o_id);
		}

		if(!empty($_GPC['cart_id'])){//购物车提交
			$cart_id = substr($_GPC['cart_id'],0,strlen($_GPC['cart_id'])-1);
		   	$sql=" select a.*,b.name,b.s_id,b.jiage,b.jiage,b.thumb,b.xiangou,b.vip from".tablename('lh_xiche_cart')." as a left join  ".tablename('lh_xiche_goods')." as b on a.g_id=b.id where b.status=1 and a.uniacid={$_W['uniacid']} and a.u_id={$u_id} and a.id in({$cart_id})";
		  	$list['list'] = pdo_fetchall($sql);
			if(empty($list['list'])){
				output_error('暂无商品信息');
			}
		  	$list['sum_money']=0;
		  	$list['sum_num']=0;
		  	$list['yunfei'] = 0;
		  	foreach ($list['list'] as $key => $value) {
		  		$list['sum_money']+=$value['g_num']*$value['jiage'];
		  		$list['sum_num']+=$value['g_num'];
		  		if($value['baoyou']!=1){
		  			$list['yunfei']+=$value['kuaidi'];
		  		}
				//查询限购
				if($value['xiangou']>0){
					$order = pdo_fetchcolumn("select count(*) as num from ".tablename("lh_xiche_ordergoods")." where uniacid={$uniacid} and u_id=$u_id and g_id={$value['g_id']}");

					if($order['num']>=$value['xiangou']){
						output_error($value['name'].'限购'.$value['xiangou'].'次，你已经购买过了');
					}
					if($value['g_num']>$value['xiangou']){
						output_error('该商品限购'.$goods_info['xiangou'].'个，你已超出购买数量');
					}
				}
				if($user_info['type']<$value['vip']&&$value['vip']!=0){//判断当前会员等级是否支持
					output_error('您的会员等级不足，不支持购买');
				}

		  	}
		  	pdo_delete("lh_xiche_cart","id in({$cart_id})");
		}

		$data['count_money'] = $list['sum_money'];

		pdo_insert("lh_xiche_order",$data);
		$o_id = pdo_insertid();

		foreach ($list['list'] as $key => $value) {
			$arr=array();
			$arr['uniacid'] = $uniacid;
			$arr['o_id'] = $o_id;
			$arr['g_id'] = $value['id'];
			$arr['num'] = $value['g_num'];
			$arr['g_name'] = $value['name'];
			$arr['g_jiage'] = $value['jiage'];
			$arr['thumb'] = $value['thumb'];
			$arr['u_id'] = $u_id;
			$arr['s_id'] = $value['s_id'];
			pdo_insert("lh_xiche_ordergoods",$arr); 
		}
		output_data($o_id);

	}
	public function doMobilePay(){
		global $_GPC,$_W;
		 // error_reporting(E_ALL);
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));

		require_once IA_ROOT."/addons/lh_xiche/inc/lib/WxPay.JsApiPay.php";
		$id = $_GPC['id'];
		$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id=:id and type=10 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
		$order_info['count_money'] += $order_info['yunfei'];
		$attach="wx_goods";
// var_dump($order_info);die;
		if(empty($order_info)&&$_GPC['pay_type']!='jifen'){
			// return false;
		}
		if($_GPC['pay_type']=='jifen'){
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_jforder")." where id=:id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));

			if($order_info['yunfei']>0){
				$order_info['count_money']= $order_info['yunfei'];
			}else{//运费等于0 直接提示兑换成功
            	$res = pdo_update("lh_xiche_jforder",array('transaction_id'=>0,'type'=>'待发货'),array("id"=>$id ));
            
				$this->user_jifen_edit($order_info['u_id'],-$order_info['jifen'],"积分兑换",6);

				echo "<script>alert('兑换成功')</script><script>window.location.href='/addons/lh_xiche/html/#/pages/my/my'</script>";die;
			}
			$attach="app_jifen";

		}

		if($_GPC['pay_type']=='fabu'){//发布模块
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_fabu")." where id=:id and pay_status=10 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
			$attach="wx_fabu";
			$order_info['count_money'] = $order_info['pt_chou']+$order_info['yj_edu'];
			$order_info['ordersn'] = $order_info['id'];
		}
		if($_GPC['pay_type']=='wx_shangjia'){//商家模块
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shangjia")." where id=:id and pay_status=10 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
			$attach="wx_shangjia";
			$order_info['count_money'] = $order_info['pt_chou']+$order_info['yj_edu'];
			$order_info['ordersn'] = $order_info['id'];
		}
		if($_GPC['pay_type']=='cz_huafei'){//话费充值模块
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cz_order")." where id=:id and status=10 and type=1 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
			$attach="cz_huafei";
		}
		if($_GPC['pay_type']=='cz_huiyuan'){//会员充值模块
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cz_order")." where id=:id and status=10 and type=2 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
			$attach="cz_huiyuan";
		}
		if($_GPC['pay_type']=='cz_liuliang'){//流量充值模块
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cz_order")." where id=:id and status=10 and type=3 and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
			$attach="cz_liuliang";
		}
		if($_W['is_app']){
		    //①、获取用户openid
		    $tools = new JsApiPay();
		    $config = new WxPayConfig();
		    $ordersn = date("YmdHis").mt_rand(0,99);

		        $userip = $_SERVER["REMOTE_ADDR"]; //获得用户设备IP
		       	$appid = $config->GetAppId();//微信给的appid
		        $mch_id = $config->GetMerchantId();//微信官方的
		        $key = $config->GetKey();//自己设置的微信商家key
		        $out_trade_no = $order_info['ordersn'].mt_rand(10,99); //订单号
		        

		        $nonce_str=MD5($out_trade_no);;//随机字符串
		        $total_fee = $order_info['count_money']*100; //金额*100
		        $spbill_create_ip = $userip; //IP
		        $notify_url = $_W['siteroot'].'/addons/lh_xiche/wxapp_notify.php'; //回调地址 jishu.whwlhd.com/index.php/Home/Pay/wx/id/
		        $trade_type = 'MWEB';//交易类型 具体看API 里面有详细介绍
		        $body="商品支付";
		        if($_GPC['pay_type']=='jifen'){
		        	$attach="app_jifen";
				}else{
					$attach="app_goods";
				}		        
		    
		         $scene_info ='{"h5_info":{"type":"Wap","wap_url":"'.$_W['siteroot'].'","wap_name":"支付"}}';//场景信息 必要参数
		         $signA ="appid=$appid&attach=$attach&body=$body&mch_id=$mch_id&nonce_str=$nonce_str&notify_url=$notify_url&out_trade_no=$out_trade_no&scene_info=$scene_info&spbill_create_ip=$spbill_create_ip&total_fee=$total_fee&trade_type=$trade_type";

		         $strSignTmp = $signA."&key=$key"; //拼接字符串  注意顺序微信有个测试网址 顺序按照他的来 直接点下面的校正测试 包括下面XML  是否正确
		         $sign = strtoupper(MD5($strSignTmp)); // MD5 后转换成大写

		         $post_data="<xml><appid>$appid</appid><attach>$attach</attach><body>$body</body><mch_id>$mch_id</mch_id><nonce_str>$nonce_str</nonce_str><notify_url>$notify_url</notify_url><out_trade_no>$out_trade_no</out_trade_no><scene_info>$scene_info</scene_info><spbill_create_ip>$spbill_create_ip</spbill_create_ip><total_fee>$total_fee</total_fee><trade_type>$trade_type</trade_type><sign>$sign</sign>
		        </xml>";//拼接成XML 格式*/


		        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//微信传参地址
				//请求有缓冲，注意刷新
		        $ch = curl_init();  
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
		        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
		        curl_setopt($ch, CURLOPT_URL, $url);  
		        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
		        curl_setopt($ch, CURLOPT_POST, true);  
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		        curl_setopt($ch, CURLOPT_TIMEOUT, 30);  
		        $response = curl_exec($ch);  
		        curl_close($ch);
		        $dataxml= $response;
		        $objectxml = (array)simplexml_load_string($dataxml,'SimpleXMLElement',LIBXML_NOCDATA); //将微信返回的XML 转换成数组
		        // var_dump($objectxml);die;
		        if($objectxml['return_code'] == 'SUCCESS'){
		            $redirect_url = urlencode($_W['siteroot'].'app/'.$this->createMobileUrl('orders',array('o_id'=>$id)));
		            $url = $objectxml['mweb_url'].'&redirect_url='.$redirect_url;
		            echo "<script> window.location.href='$url'</script>";
		            exit;
		        }  
		        exit;
		}
/*		if(false&&$attach=='wx_goods'){
			header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=test_login&o_id=$id&op={$login_url}&m=".$_GPC['m']);
			die;
		}*/
		//①、获取用户openid
		$tools = new JsApiPay();
		$openId = $user_info['w_openid'];
		if(empty($openId)){
			$openId = $tools->GetOpenid();
		}

		if($_GPC['pay_type']=='tuiguang'){
			$order_info = pdo_get('lh_xiche_jz_merchant', array('uniacid'=>$uniacid,'id'=>$id));
			$order_info['count_money'] = $order_info['tg_money']*$order_info['tg_num'];
			$order_info['ordersn'] = $id;
		}
	

		$input = new WxPayUnifiedOrder();
		$input->SetNotify_url($_W['siteroot'].'/addons/lh_xiche/wxapp_notify.php');

		$input->SetOpenid($openId);

		if(empty($order_info)){
			// output_error("暂无订单信息");
		}

		$input->SetBody('订单支付');
		$input->SetAttach($attach);
		$input->SetOut_trade_no($order_info['ordersn'].mt_rand(10,99));
		$input->SetTotal_fee($order_info['count_money']*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 300));
		$input->SetGoods_tag('订单支付');
		$input->SetTrade_type("JSAPI");




		$config = new WxPayConfig();
		$order = WxPayApi::unifiedOrder($config, $input);

		$jsApiParameters = $tools->GetJsApiParameters($order);
			//var_dump($jsApiParameters);die;
		//获取共享收货地址js函数参数
		$editAddress = $tools->GetEditAddressParameters();
		if($_GPC['pay_type']=='cz_huafei'||$_GPC['pay_type']=='cz_huiyuan'||$_GPC['pay_type']=='cz_liuliang'){
			$sucess_url = $_W['setting']['site']['url'].'/addons/lh_xiche/html/#/pages/chongzhi/result?id='.$id;
		}else{
			$sucess_url = $_W['siteroot'].'app/'.$this->createMobileUrl('pay_sucess',array('o_id'=>$id,'attach'=>$attach));

		}
		
echo    <<<EOB
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<title>微信安全支付</title>
</head>
<body>
<script type="text/javascript">
function jsApiCall() {
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        {$jsApiParameters},
        function(res) {
            var h;
            if (res && res.err_msg == "get_brand_wcpay_request:ok") {
                // success;
                h = '{$sucess_url}';
                location.href = h;
            } else {
                // fail;
                h = '';
                location.href =window.history.go(-1);
            }

        }
    );
}
window.onload = function() {
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    } else {
        jsApiCall();
    }
}
</script>
</body>
</html>
EOB;
exit;
	}
		public function doMobilePay_sucess_app(){
			global $_GPC,$_W;
			$uniacid = $_W['uniacid'];
			$u_id = $_SESSION['u_id'];
			$o_id = $_GPC['o_id'];
			$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
			$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id=:o_id and uniacid=:uniacid and type=10",array(":uniacid"=>$uniacid,":o_id"=>$o_id));
			//查单逻辑，看是否支付成功。
			require_once IA_ROOT."/addons/lh_xiche/inc/lib/WxPay.JsApiPay.php";
			
		$out_trade_no = $_REQUEST["out_trade_no"];
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$config = new WxPayConfig();
		printf_info(WxPayApi::orderQuery($config, $input));


		}
		
		public function doMobilePay_sucess(){
				
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$o_id = $_GPC['o_id'];
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));

		$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id=:o_id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":o_id"=>$o_id));
   		$orderlist = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_ordergoods")." WHERE uniacid=:uniacid and o_id=:o_id",array(":uniacid"=>$uniacid,":o_id"=>$o_id));
   		if(!empty($order_info)){

	    foreach ($orderlist as &$values) {
	        pdo_update("lh_xiche_goods",array("xiaoliang +="=>$values['num']),array("id"=>$values['g_id']));

	        $ordergoodsname[]=$values['g_name']."(".$values['num']."个)";
	        $ordergoodsnum +=$values['num'];
	    }
			
		$ordergoodsname = implode(",",$ordergoodsname);
	    $data_moban = array(
	        "first"=>array(
	            "value"=> '您好，您的订单已支付成功',
	            "color"=>"#4a4a4a"
	        ),

	        "keyword1"=>array(
	            "value"=>$order_info['ordersn'],
	            "color"=>"#9b9b9b"
	        ),
	        "keyword2"=>array(
	            "value"=>$ordergoodsname,
	            "color"=>"#9b9b9b"
	        ),
	        "keyword3"=>array(
	            "value"=>$order_info['count_money'],
	            "color"=>"#9b9b9b"
	        ),
	        "keyword4"=>array(
	            "value"=>'微信支付',
	            "color"=>"#9b9b9b"
	        ),
	        "keyword5"=>array(
	            "value"=>date('Y-m-d H:i:s'),
	            "color"=>"#9b9b9b"
	        ),
	        "Remark"=>array(
	            "value"=>'点击查看详细信息',
	            "color"=>"#9b9b9b"
	        ),
	    );

/*{{first.DATA}}
订单编号：{{keyword1.DATA}}
商品信息：{{keyword2.DATA}}
已付金额：{{keyword3.DATA}}
支付方式：{{keyword4.DATA}}
支付时间：{{keyword5.DATA}}
{{remark.DATA}}*/

   $r = $this->sendTemplate($user_info['w_openid'], 'spzf_wx', $data_moban, $url = $_W['siteroot'].'app/'.$this->createMobileUrl('orders'));
    }else{
    	$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_jforder")." where id=:id   ",array(":id"=>$o_id));
		$a = $order_info['j_ids'];
    	$order_goods = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jfgoods")." where id in($a)");

		foreach ($order_goods as &$values) {

	        $ordergoodsname[]=$values['name']."(1个)";
	    }
			$ordergoodsname = implode(",",$ordergoodsname);
			//$ordergoodsname
	    $data_moban = array(
	        "first"=>array(
	            "value"=> '您好，您已成功兑换奖品',
	            "color"=>"#4a4a4a"
	        ),
	        "points"=>array(
	            "value"=>$order_info['jifen'],
	            "color"=>"#9b9b9b"
	        ),
	        "date"=>array(
	            "value"=>date('Y-m-d H:i:s',time()),
	            "color"=>"#9b9b9b"
	        ),
	        "productType"=>array(
	            "value"=>'奖品内容',
	            "color"=>"#9b9b9b"
	        ),
	        "name"=>array(
	            "value"=>$ordergoodsname,
	            "color"=>"#9b9b9b"
	        ),
				
			
	        "Remark"=>array(
	            "value"=>'点击查看详细信息',
	            "color"=>"#9b9b9b"
	        ),
	    );
   		$r = $this->sendTemplate($user_info['w_openid'], 'jfdh_wx', $data_moban, $url = $_W['siteroot'].'app/'.$this->createMobileUrl('orders'));

    }
    if($_SESSION['db_id']>0){
    	header("location:".$_W['setting']['site']['url'].'/addons/lh_xiche/html/#/pages/treainfor/treainfor?id='.$_SESSION['db_id']);die;
    }



		//header("location:".$_W['siteroot'].'app/'.$this->createMobileUrl('orders'));die;
		header("location:".$_W['setting']['site']['url'].'/addons/lh_xiche/html/#/pages/my-order/my-order?status=0');die;

		}
	
	//商品订单
	public function doMobileOrders(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";

		$info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		if($op=='shangjia'){
			$sj_info = pdo_get('lh_xiche_shangjia',array('u_id'=>$u_id));
			$where = "uniacid=$uniacid and s_id={$sj_info['id']}";
		}else{
			$where = "uniacid=$uniacid and u_id=$u_id";
		}
		
		if($op=='obligation'||$_GPC['type']==1){
			$where .=" and type=10";
		}
		if($op=='unfilled'||$_GPC['type']==2){
			$where .=" and type=20";
		}
		if($op=='delivered'||$_GPC['type']==3){
			$where .=" and type=30";
		}
		if($op=='refund'){
			$where .=" and type=50";
		}
	 	$where .=" order by xdtime desc";
		// error_reporting(E_ALL);
		// $list =pdo_getall('lh_xiche_order', $where, array('*'),'',array('o_id'));
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_order")." where $where ");
		// error_reporting(E_ALL);
		foreach($list as $key=>&$value){
			$value['goods_list'] = pdo_getall('lh_xiche_ordergoods', array('uniacid' => $uniacid,'o_id' => $value['o_id']), array('*'));
			foreach ($value['goods_list'] as $k => &$v) {
				if(strpos($v['thumb'],"http")===false){
					$v['thumb'] = $_W['attachurl'].$v['thumb'];
				}
			}
		}
		
		if($_GPC['v']==1){
			output_data($list);
		}

		include $this->template('orders');
	}

	public function doMobileOrders_info(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		
		$list = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id={$_GPC['o_id']} ");
		// error_reporting(E_ALL);
		$list['goods_list'] = pdo_getall('lh_xiche_ordergoods', array('uniacid' => $uniacid,'o_id' => $list['o_id']), array('*'));
		foreach ($list['goods_list'] as $k => &$v) {
			if(strpos($v['thumb'],"http")===false){
				$v['thumb'] = $_W['attachurl'].$v['thumb'];
			}
		}
		output_data($list);
	}
	
	//订单物流
	public function doMobileOrders_wuliu(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";

		$o_id = $_REQUEST['o_id'];


		if($_GPC['type']=='jifen'){
				$info = pdo_fetch("SELECT ordersn,kd_dh,kd_gs FROM ".tablename("lh_xiche_jforder")." where id=:o_id order ",array("o_id"=>$_GPC['o_id']));
				$num =$info['kd_gs'];
					    $com =$info['kd_dh'];
	    	
		}else{
			if(!empty($o_id)){
		        $info = pdo_fetch("SELECT ordersn,kuaidi_name,kuaidi_sn FROM ".tablename("lh_xiche_order")." where o_id=:o_id order by o_id desc ",array("o_id"=>$_GPC['o_id']));
			}
		    $com =$info['kuaidi_name'];
		    $num =$info['kuaidi_sn'];
		}
	    $kuaidi100 =  pdo_fetch("SELECT kuaidi100,kuaidi100_key FROM".tablename('lh_xiche_base')."where uniacid='{$uniacid}'");
	    $post_data = array();
	    $post_data["customer"] = $kuaidi100['kuaidi100_key'];
	    $key=  $kuaidi100['kuaidi100'];


	    $datas['com']=$com;  //查询的快递公司的编码， 一律用小写字母
	    $datas['num']=$num;  //查询的快递单号， 单号的最大长度是32个字符 358263398950
	    $post_data["param"] =json_encode($datas);
	    $url='http://poll.kuaidi100.com/poll/query.do';
	    $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
	    $post_data["sign"] = strtoupper($post_data["sign"]);
	    $o="";
	    foreach ($post_data as $k=>$v)
	    {
	        $o.= "$k=".urlencode($v)."&";   //默认UTF-8编码格式
	    }
	    $post_data=substr($o,0,-1);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	    $result = curl_exec($ch);
	  	$data = str_replace("\"",'"',$result );
	    $wuliu = json_decode($data,true);
	    $kuaidi = array();
	    $kuaidi['shentong'] = '申通';
	    $kuaidi['shunfeng'] = '顺丰';
	    $kuaidi['yunda'] = '韵达快运';
	    $kuaidi['tiantian'] = '天天快递';
	    $kuaidi['yuantong'] = '圆通速递';
	    $kuaidi['zhongtong'] = '中通速递';
	    $kuaidi['ems'] = 'ems快递';
	    $kuaidi['huitongkuaidi'] = '百世快递';
	    $kuaidi['quanfengkuaidi'] = '全峰快递';
	    $kuaidi['zhaijisong'] = '宅急送';
	    $kuaidi['youzhengguonei'] = '邮政快递包裹';
	    $wuliu['com'] = $kuaidi[$com];

	                  
	    $wuliu['ordersn'] = $info['ordersn'];

        output_data($wuliu);
	}
	//积分订单
	public function doMobileOrders_jf(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		// $op = !empty($_GPC['op'])?$_GPC['op']:"display";

		$info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));


		$where = "uniacid=$uniacid and u_id=$u_id";
		if($op=='display' ||$_GPC['type']==0 ){
			$where .=" and type in('待发货','待收货','已完成')";
		}
		if($op=='unfilled' ||$_GPC['type']==1){
			$where .=" and type='待发货'";
		}
		if($op=='delivered' ||$_GPC['type']==2){
			$where .=" and type='待收货'";
		}

		if($_GPC['type']==3){
				$where .=" and type in('已完成')";
		}

		$where .=" order by id desc";
		// error_reporting(E_ALL);
		$list =pdo_getall('lh_xiche_jforder', $where, array('*'),'',array('id'));
		// error_reporting(E_ALL);


		foreach($list as $key=>&$value){
			$value['goods_list'] = pdo_getall('lh_xiche_jfgoods', " id in({$value['j_ids']})" , array('*'));
			foreach ($value['goods_list'] as $k=> &$v) {
				if(strpos($v['thumb'],"http")===false){
					$v['thumb'] = $_W['attachurl'].$v['thumb'];
				}
			}
		}

		output_data($list);
		include $this->template('orders_jf');
	}
	public function doMobileOrders_edit(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$id=$_GPC['id'];

		if($op=='qx'){
			pdo_update("lh_xiche_order",array("type ="=>0),array("o_id"=>$id));
			output_data("取消成功");
		}

		if($op=='sh'){
			pdo_update("lh_xiche_order",array("type ="=>40),array("o_id"=>$id));
			output_data("收货成功");
		}
		if($op=='wc'){
			pdo_update("lh_xiche_order",array("type ="=>30),array("o_id"=>$id));
			output_data("订单完成");
		}
	}
	
	//购物车
	public function doMobileCart(){
    global $_GPC,$_W;
    $uniacid = $_W['uniacid'];
	$u_id = $_SESSION['u_id'];
	$op = !empty($_GPC['op'])?$_GPC['op']:"display";

    //$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
    // $list = pdo_getall('lh_xiche_cart', array('uniacid' => $uniacid,'u_id' => $u_id,), array('*'));
   	$sql=" select a.*,b.name,b.jiage,b.jiage,b.thumb,b.stock from".tablename('lh_xiche_cart')." as a left join  ".tablename('lh_xiche_goods')." as b on a.g_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id }";

    if($op=='add'){
       	$id = $_GPC['specid'];
        $g_num = $_GPC['number'];
        $data = array();
        $data['uniacid'] = $uniacid;
        $data['u_id']  = $u_id;
        $data['g_id'] = $id;
        //查询商品
        
        // $goods = pdo_get('hyb_o2o_goods', "uniacid={$uniacid} and id ={$id}", '*');
        $goods =pdo_get('lh_xiche_goods',array('id'=>$id,'uniacid'=>$_W['uniacid']));
        //查询购物车中是否有此商品
        $cartgoods = pdo_get('lh_xiche_cart', array('uniacid' => $uniacid,'g_id' => $id,'u_id' => $u_id));

        $maxguigestock = $goods['stock'];
        $data['g_num'] = $g_num;
        $data['addtime'] = date("Y-m-d H:i:s",time());
        if (empty($cartgoods)) {
            if ($maxguigestock<$g_num) {
                $maxnum = $maxguigestock;
                $tishi = "最多可购买".$maxnum."个商品";
                output_error($tishi);
            }else{
                pdo_insert("lh_xiche_cart",$data);
            }
        }else{
            $cartnum = $cartgoods['g_num']+$g_num;
            if ($maxguigestock<$cartnum) {
                $maxnum = $maxguigestock-$cartgoods['g_num'];
                if ($maxnum=='0') {
                    $tishi = "库存不足,请先结算购物车中的商品";
                }else{
                    $tishi = "最多可购买".$maxnum."个商品";
                }
                output_error($tishi);
            }else{

				$g_num=(int)$g_num;
                pdo_update("lh_xiche_cart",array("g_num +="=>$g_num,"addtime"=>$data['addtime']),array("id"=>$cartgoods['id']));
            }
        }   
        //查询购物车数量
        $cartnum = pdo_fetchcolumn("SELECT count(*) FROM ".tablename("lh_xiche_cart")." WHERE uniacid=:uniacid and u_id=:u_id",array(":uniacid"=>$uniacid,":u_id"=>$user['u_id']));
		output_data("添加成功");
    }

    $id = $_GPC['id'];
    if($op=='edit'){
    	pdo_update("lh_xiche_cart",array("g_num ="=>$_GPC['num']),array("id"=>$id));
    	output_data("修改成功");
    }

    if($op=='del'){
    	pdo_delete("lh_xiche_cart",array("id"=>$id));
    	output_data("删除成功");
    }


  	$list = pdo_fetchall($sql);

			foreach ($list as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
				$value['flag'] = true;
			}
  	if($_GPC['v']==1){
  		output_data($list);	
  	}
		include $this->template('cart');
	}

	
public function doMobileMy_qianming(){//设置修改自己的签名
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];

		if(!empty($u_id)){
			pdo_update("lh_xiche_user",['qianming'=>$_GPC['qianming']],array('id'=>$u_id));
			output_data('设置成功');
		}
		output_error('请登陆后设置');


}
	//个人中心
	public function doMobileMy(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$base = pdo_get('lh_xiche_base', array('uniacid' => $uniacid));
		$renwu = pdo_get('lh_xiche_renwu', array('u_id' => $u_id));
/*		$users = pdo_get('mc_mapping_fans', array('openid' =>$info['w_openid']));
		$wq_user = pdo_get('mc_members',  array('uid' =>$users['uid']));
		$info['jifen'] = $wq_user['credit1'];*/

		if(!empty($info['phone'])){
			//header("location:".$_W['siteroot'].$this->createMobileUrl('relieve'));die;
			$shanghu = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_shanghu_temp")." where phone='{$info['phone']}' and lq_uid=0");

			if($shanghu['jifen']>0){
				pdo_update('lh_xiche_shanghu_temp',['lq_uid'=>$u_id,'lq_time'=>date('Y-m-d H:i:s')],['id'=>$shanghu['id']]);
				$this->user_jifen_edit($u_id,$shanghu['jifen'],"商户转账",3);

			}
		}



		if(empty($info)){
			$info['is_login'] = false;
		}else{
			$info['is_login'] = true;
			if(empty($renwu)){
				pdo_insert("lh_xiche_renwu",array('uniacid' => $uniacid,'u_id'=>$u_id));
			}
	
			$sort = $info['type']+1;
			$user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where uniacid=$uniacid and  sort=$sort");
			if(!empty($user_type)&&$info['sum_money']>=$user_type['money']&&$info['jifen_sum']>=$user_type['sj_jifen']){//判断积分，佣金是否达到省级要求
				//赠送积分和佣金
				if($user_type['jl_jifen']>0){
					$this->user_jifen_edit($u_id,$user_type['jl_jifen'],"会员等级提升奖励积分",3);
				}
				if($user_type['jl_money']>0){
					$data_jf = array();
					$data_jf['uniacid'] = $uniacid;
					$data_jf['u_id'] = $u_id;
					$data_jf['nums'] = $user_type['jl_money'] ;
					$data_jf['content'] = "会员等级提升奖励";
					$data_jf['type'] = "40";
					$data_jf['addtime'] = date("Y-m-d H:i:s",time());
					pdo_insert("lh_xiche_yongjin_log",$data_jf);
				
}			}
		}

		if(empty($info['thumb'])){
			$info['thumb'] = STYLE_PATH.'images/touxiang.png';
		}
		$menu= $this->menu;
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		$url = 'https://'.$_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('index').'&f_id='.$info['id'];

		if(strpos($base['kefu_wx'],"http")===false){
			$base['kefu_wx'] = $_W['attachurl'].$base['kefu_wx'];
		}

		if(strpos($base['hbbg'],"http")===false){
			$base['hbbg'] = $_W['attachurl'].$base['hbbg'];
		}

		$shangjia =  pdo_fetch("SELECT status FROM ".tablename("lh_xiche_shangjia")." where uniacid=$uniacid and u_id={$info['id']}");
		
 		if($_GPC['v']==1){
 			$user_type = pdo_fetch("SELECT name FROM ".tablename("lh_xiche_user_type")." where uniacid=$uniacid and sort={$info['type']}");
 			output_data(array('info'=>$info,
 				'menu'=>$menu,
 				'shangjia'=>$shangjia,
 				'url'=>$url,
 				'kefu_wx'=>$base['kefu_wx'],
 				'hbbg'=>$base['hbbg'],
 				'kefu_tel'=>$base['kefu_tel'],'user_type'=>$user_type));
 		}
		include $this->template('my');
	}
	
	//生日短信
	public function doMobileShengri(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$shijian = date('m-d',time());
		$list = pdo_getall('lh_xiche_user', "birthday like '%{$shijian}'", array('*'));
		// var_dump($list);
		foreach($list as $key=>$val){
		//	消费送用户积分
		$data_jf = array();
		$data_jf['uniacid'] =  $val['uniacid'];
		$data_jf['u_id'] = $val['id'];
		$data_jf['nums'] = 100;
		$data_jf['content'] = "生日赠送";
		$data_jf['type'] = "5";
		$data_jf['addtime'] = date("Y-m-d H:i:s",time());
		pdo_insert("lh_xiche_jifen_log",$data_jf);

		pdo_update('lh_xiche_user', array('jifen +='=>100), array('id' =>$val['id']));

			$body = '尊敬的会员，今日是您的生日，祝您生日快乐！你的生日积分系统已派发，请您查收！【好事多直购】';
			echo $val['phone'];
			$re = $this->sendSMS($val['phone'],$body);
			var_dump($re);
		}
	}
	//个人信息修改
	public function doMobileUser_info(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		if($op=='post'){
			$data = array();
			$data['truename'] = $_GPC['truename'];
			$data['phone'] = $_GPC['phone'];
			$data['sex'] = $_GPC['sex'];
			$data['birthday'] = $_GPC['birthday'];
			$data['career'] = $_GPC['career'];
			$data['address'] = $_GPC['address'];
			$data['xxaddress'] = $_GPC['xxaddress'];
			pdo_update('lh_xiche_user', $data, array('id' =>$u_id));
			output_data('修改成功');
		}
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$base = pdo_get('lh_xiche_base', array('uniacid'=>$uniacid));
		include $this->template('user_info');
	}
	//个人信息修改
	public function doMobileUser_reg(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";


		if($op=='add'){
		  	$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'phone' => $_GPC['phone']), array('*'));
		  	
		  	if(!empty($user_info)){
		  		output_error('该手机号已被注册');
		  	}
			if($_SESSION['yzg']!=$_GPC['code']){
				output_error('验证码错误');
			}

			$_GPC['pass'] = substr(md5($_GPC['pass']), 2,20) ;
			$data = array();
			$data['uniacid'] = $uniacid;
			$data['phone'] = $_GPC['phone'];
			$data['pass'] = $_GPC['pass'];
			$data['addtime'] = date('Y-m-d H:i:s',time());
			pdo_insert('lh_xiche_user',$data);
			output_data('注册成功');
		}

		if($op=="send_emal"){
			$code = $_SESSION['yzg'] = rand('100000','999999');
		  	$search = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/';
		  	$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'phone' => $phone), array('*'));
		  	
		  	if(!empty($user_info)){
		  		output_error('该账户已被注册');
		  	}
		    if (!preg_match($search,trim($_GPC['phone']))) {
		    	output_error('邮箱号码格式错误');
		    }

/**/    if($_GPC['t_code']!=$_SESSION['t_code']){
	    	output_error('图形验证码错误');
	    }
			if(!empty($_SESSION['yzg_time'])&&(($_SESSION['yzg_time']+60)>time())){
				output_error('60秒内请勿重复发送');
			}

			$_SESSION['yzg_time'] = time();

			$content1 = "您的验证码是：$code";
			$body = array(
			    $content1,
			    ' '
			);
			load()->func('communication');
			$response = ihttp_email($_GPC['phone'], '验证码', $content1);
			output_data('发送成功');
		}

		include $this->template('user_reg');
	}


		//个人信息修改
	public function doMobileUser_login(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		if($op=='post'){
			$pass = substr(md5($_GPC['pass']), 2,20) ;
			
			$user_info = pdo_get('lh_xiche_user',array('uniacid' => $uniacid,'phone' => $_GPC['phone'],'pass' => $pass) , array('*'));

			if(!empty($user_info)){
				$_SESSION['u_id']=$user_info['id'];
				output_data('登陆成功');
			}else{
				output_error('用户名密码错误');
			}
		}
		include $this->template('user_login');
	}
	public function doMobileUser_logout(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id']="";

		echo "<script>alert('注销成功')</script><script>window.location.href='". $this->createMobileUrl('user_login')."'</script>";die;
	}
		//个人信息修改
	public function doMobileUser_reset(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		include $this->template('user_reset');
	}

	//会员权益
	public function doMobileMy_member_quanyi(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$_GPC['id'] = !empty($_GPC['id'])?$_GPC['id']:"1";
		$output_data['list'] = $list = pdo_fetchall("SELECT id,thumb,name,money,content,sj_jifen,sort,jiage,tianshu FROM ".tablename("lh_xiche_user_type")." where  uniacid=$uniacid  order by sort asc");
		// var_dump($output_data['list']);die;
		$output_data['user_info'] = $user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$info = pdo_get('lh_xiche_user_type', array('uniacid' => $uniacid,'sort' => $user_info['type']), array('*'));

		$output_data['user_info']['typename'] = $info['name'];
			foreach ($output_data['list'] as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		if($_GPC['v']==1){
			output_data($output_data);
		}
		include $this->template('my_member_quanyi');
	}
	//会员特权
	public function doMobileMy_member_tequan(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		include $this->template('my_member_tequan');
	}

	//常见问题
	public function doMobileMy_question(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		if($op=='info'){
			$info =$list = pdo_get('lh_xiche_question', array('uniacid' => $uniacid,'id'=>$_GPC['id']), array('*'));
		}else{
			$list = pdo_getall('lh_xiche_question', array('uniacid' => $uniacid), array('*'));
		}

		if($_GPC['v']==1){
			output_data($list);
		}
		
		include $this->template('my_question');
	}

	//留言反馈
	public function doMobileMy_feedback(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$_GPC['thumbs'] = explode(',',$_GPC['thumbs']);
		if($op=="add"){
			$data = array();
			$data['u_id'] = $u_id;
			$data['uniacid'] = $uniacid;
			$data['addtime'] = date('Y-m-d',time());
			$data['body'] = $_GPC['body'];
			$data['type'] = $_GPC['type'];
			$data['title'] = $_GPC['title'];
			$data['thumbs'] = serialize($_GPC['thumbs']);
			pdo_insert('lh_xiche_feedback',$data);
			output_data("反馈成功");
		}

		if($op='list'){
			if(!empty($_GPC['types'])){
				$where = " and a.types='{$_GPC['types']}'";
			}
			if($_GPC['id']>0){
				
			}
			$where = " and a.u_id={$u_id}";
			$list = pdo_fetchall("SELECT a.*,b.name,b.thumb FROM ".tablename("lh_xiche_feedback")." as a left join ".tablename("lh_xiche_user")." as b on a.u_id=b.id WHERE a.uniacid=:uniacid $where order by a.addtime desc  ",array(":uniacid"=>$uniacid));

			output_data($list);
		}
	}

	//我的分享
	public function doMobileMy_share(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		if(empty($user_info['phone'])){
			//header("location:".$_W['siteroot'].$this->createMobileUrl('relieve'));die;
		}
		$yaoqingma = substr(md5($u_id),0,5);
		pdo_update('lh_xiche_user', array('yaoqingma'=>$yaoqingma), array('id' => $u_id));
		// include(IA_ROOT.'/addons/lh_xiche/inc/lib/phpqrcode.php');

		$url = $_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('index').'&f_id='.$user_info['id'];
		// $img =  QRcode::png($url);
        // $path = IA_ROOT.'/attachment/images/'.$uniacid.'/user'.$user_info['id'].'png';  //图片文件夹路径
        // //创建存放图片的文件夹
        // if (!is_dir($path)) {
        //    $res = mkdir($path, 0777, true);
       	// }
       	// imagepng($img,$path);
		include $this->template('my_share');
	}
	//我的签到
	public function doMobileMy_sign(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";


		if($op=="sign"){
			$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
			$fp = fopen(dirname(__FILE__)."/sign.txt", "w+");

			if(!flock($fp,LOCK_EX | LOCK_NB)){
				output_error('系统繁忙，请稍后再试');
				return;
			}
			$data = array();
			$data['u_id'] = $u_id; 
			$data['uniacid'] = $uniacid;
			$data['addtime'] = date('Y-m-d',time());
			$info = pdo_get('lh_xiche_sign_log', array('addtime' => $data['addtime'],'u_id' => $u_id), array('*'));
			if(!empty($info)){
				output_error('今日已经签过到，请明天再来！');
			}

			pdo_insert('lh_xiche_sign_log',$data);
/*			$jfguizhe = pdo_get('lh_xiche_jfguizhe', array('uniacid' =>$uniacid));

			$test = explode(',',$jfguizhe['sgin']);
			if(strstr($jfguizhe['sgin'], ',')){
				$jfguizhe['sgin'] = mt_rand($test[0],$test[1]);
			}else{
				$jfguizhe['sgin'] = $jfguizhe['sgin'];
			}*/
			$user_type = pdo_get('lh_xiche_user_type',array('sort'=>$user_info['type']),'*');




			$jfguizhe['sgin'] = $user_type['sgin'];
			$this->user_jifen_edit($u_id,$jfguizhe['sgin'],"用户签到奖励",2);

			flock($fp,LOCK_UN);//释放锁
			fclose($fp);
			output_data('签到成功,奖励'.$jfguizhe['sgin'].'金币');
		}
	$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
	date_default_timezone_set("PRC");
	$y = date('Y-m',time()).'-01';
    $lists = pdo_getall('lh_xiche_sign_log',  array('uniacid' => $uniacid,'u_id' => $u_id,'addtime >='=>$y), array('addtime'));
    $time = getdate();
    $mday = $time["mday"];
    $mon = $time["mon"];
    $year = $time["year"];
	$list=array();
	$output_data=array();
    foreach($lists as $v){
		 $list[] = $v['addtime'];
		 $output_data[] = intval(date('d',strtotime($v['addtime'])));
	}
   

    if($mon==4||$mon==6||$mon==9||$mon==11){
        $day = 30;
    }elseif($mon==2){
        if(($year%4==0&&$year%100!=0)||$year%400==0){
            $day = 29;
        }else{
            $day = 28;
        }
    }else{
        $day = 31;
    }
    
    $w = getdate(mktime(0,0,0,$mon,1,$year))["wday"];

        $arr = array();
        for($i=1;$i<=$day;$i++){
            array_push($arr,$i);
        }
        if($w>=1&&$w<=6){
            for($m=1;$m<=$w;$m++){
                array_unshift($arr,"");
            }
        }
        $n=0;

        //if($n!=7)echo "</tr>";
		if($_GPC['v']==1){
			output_data($output_data);
		}

		include $this->template('my_sign');
	}

		//我的签到
	public function doMobileMy_sign_new(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";

		if($op=="sign_shipin"){//观看视频翻倍
			$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
			 $this->user_jifen_edit($u_id,20,"用户签到翻倍奖励",2);
		}
		if($op=="sign"){
			$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
			$user_sign = pdo_get('lh_xiche_sign', array('uniacid' => $uniacid,'u_id' => $u_id), array('*'));
			$fp = fopen(dirname(__FILE__)."/sign.txt", "w+");

			if(!flock($fp,LOCK_EX | LOCK_NB)){
				output_error('系统繁忙，请稍后再试');
				return;
			}
			$data = array();
			$data['u_id'] = $u_id; 
			$data['uniacid'] = $uniacid;
			$data['addtime'] = date('Y-m-d',time());
			$info = pdo_get('lh_xiche_sign_log', array('addtime' => $data['addtime'],'u_id' => $u_id), array('*'));
			if(!empty($info)){
				output_error('今日已经签过到，请明天再来！');
			}
			$data_lx = array();
			if(empty($user_sign)){
				$data_lx['u_id'] = $u_id; 
				$data_lx['uniacid'] = $uniacid;
				$data_lx['lianxu'] = 1;
				$data_lx['zuotian'] = date('Ymd');
				pdo_insert('lh_xiche_sign',$data_lx);
				//插入
			}else{
				if(($user_sign['zuotian']+1)==date('Ymd')){//昨天签到有没有中断
					$data_lx['lianxu'] = $user_sign['lianxu']+1;
					$data_lx['zuotian'] = date('Ymd');
					pdo_update('lh_xiche_sign',$data_lx,['u_id'=>$u_id]);
				}else{
					$data_lx['lianxu'] = 1;
					$data_lx['zuotian'] = date('Ymd');
					pdo_update('lh_xiche_sign',$data_lx,['u_id'=>$u_id]);
				}
			}
			pdo_insert('lh_xiche_sign_log',$data);
/*			$jfguizhe = pdo_get('lh_xiche_jfguizhe', array('uniacid' =>$uniacid));

			$test = explode(',',$jfguizhe['sgin']);
			if(strstr($jfguizhe['sgin'], ',')){
				$jfguizhe['sgin'] = mt_rand($test[0],$test[1]);
			}else{
				$jfguizhe['sgin'] = $jfguizhe['sgin'];
			}*/
			$user_type = pdo_get('lh_xiche_user_type',array('sort'=>$user_info['type']),'*');
			
			$jfguizhe['sgin'] = $user_type['sgin'];
			$this->user_jifen_edit($u_id,$jfguizhe['sgin'],"用户签到奖励",2);

			flock($fp,LOCK_UN);//释放锁
			fclose($fp);
			output_data('签到成功,奖励'.$jfguizhe['sgin'].'金币');
		}
	$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
	$renwu_set = pdo_get('lh_xiche_renwu_set', array('uniacid' => $uniacid));
	date_default_timezone_set("PRC");
	$user_sign = pdo_get('lh_xiche_sign', array('uniacid' => $uniacid,'u_id' => $u_id), array('*'));
	$lxts=0;
	if(($user_sign['lianxu']>0)&&(($user_sign['zuotian']+1)>=date('Ymd'))){
		$lxts = $user_sign['lianxu'];
	}

$renwu_qiandao = explode('|', $renwu_set['qiandao']);
$renwu_qiandao_tian = [];
$renwu_qiandao_jifen = [];
	foreach ($renwu_qiandao as $key => $value) {
		$ss = explode(',', $value);
		$renwu_qiandao_tian[$ss[0]] = $ss[1];
	}
	$list = [];
// var_dump($renwu_qiandao_tian);die;
        for($i=1;$i<=30;$i++){
        	
        	if($i<=$lxts){
        		$state = 0;
        	}else{
        		$state =1;
        	}

        	$arr = ['state'=>$state,'gold'=>20];
        	if(array_key_exists($i, $renwu_qiandao_tian)){
        		$award = $renwu_qiandao_tian[$i];
        		$arr = ['state'=>$state,'gold'=>20,'award'=>$award];
        	}
            array_push($list,$arr);
        }

			output_data($list);
	}


	//我的卡券
	public function doMobileMy_card(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		
		include $this->template('my_card');
	}

	//分享的下级
	public function doMobileMy_share_list(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		// $u_id = 35;
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";

		$output_data['user_info'] =$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$user_sum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('lh_xiche_user')." where uniacid=$uniacid  and (f_id=$u_id or ff_id=$u_id)");


		$list_er = pdo_getall('lh_xiche_user',' ff_id=$u_id', array('id'));

		$f_id = 0;
		foreach ($list_er as $key => &$v) {
			$f_id .=','.$v['id'];
		}
		if($_GPC['type']==1){
			$where = "uniacid=$uniacid and ff_id=$u_id";
		}else{
			$where = "uniacid=$uniacid and f_id=$u_id";
		}
		
		// $where = "uniacid=$uniacid and (f_id=$u_id or ff_id=$u_id or f_id in($f_id))";
		//$sql ="select * from ".tablename('lh_xiche_user')." as a left join  ".tablename('lh_xiche_user')." as b on a.id=b.fid where uniacid=$uniacid  and (f_id=$u_id or ff_id=$u_id)";
		$output_data['list'] = $list = pdo_getall('lh_xiche_user', $where, array('id','f_id','ff_id','name','addtime','thumb','phone'),'','id desc');

		$yi_sum = pdo_fetchcolumn("SELECT count(*) FROM ".tablename("lh_xiche_user")." WHERE uniacid=$uniacid and f_id=$u_id");

		$er_sum = pdo_fetchcolumn("SELECT count(*) FROM ".tablename("lh_xiche_user")." WHERE uniacid=$uniacid and ff_id=$u_id");

		$output_data['fx_zong'] = $yi_sum+$er_sum;

		if($_GPC['v']==1){
			output_data($output_data);
		}
		include $this->template('my_share_list');
	}
      //分销商提现添加
/*public function doMobileTixian()
{
    global $_W,$_GPC;


   pdo_update("hyb_o2o_userfenxiao",$datas,array("f_id"=>$fenxiao['f_id']));
   $data = array("uniacid"=>$uniacid,"t_fopenid"=>$openid,"t_fid"=>$fenxiao['f_id'],"t_name"=>$fenxiao['f_name'],"t_money"=>$tx_money,"t_shouxufei"=>$shouxufei,"t_time"=>date("Y-m-d H:i:s",time()),"t_status"=>"待审核","t_type"=>$type);
   pdo_insert("hyb_o2o_fenxiaotixian",$data);
   output_error('金额不足');
}*/

	//解绑手机号
	public function doMobileMy_info(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
			$fp = fopen(dirname(__FILE__)."/renwu.txt", "w+");

			if(!flock($fp,LOCK_EX | LOCK_NB)){
				output_error('系统繁忙，请稍后再试');
				return;
			}
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('id','phone','truename','type','thumb','name'));

		$user_info['truename'] = empty($user_info['truename'])?$user_info['name']:$user_info['truename'];
		if($user_info['truename']==null){
			$user_info['truename']=$user_info['name'];
		}

		if($op=="bang"){
			if(($_SESSION['yzg']==$_GPC['yzm']&&!empty($_GPC['yzm']))||$user_info['phone']==$_GPC['phone']&&!empty($_GPC['phone'])){
				$renwu = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_renwu")." where  uniacid=$uniacid and u_id=$u_id");
				$user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where  uniacid=$uniacid and sort={$user_info['type']}");
					if(!empty($renwu)&&$renwu['bang_tel']!=1){
						$this->user_jifen_edit($u_id,$user_type['bang_tel'],"绑定手机奖励",3);
					}
				
						$arr = array();
						$arr['phone'] = $_GPC['phone'];
						$arr['truename'] = $_GPC['truename'];
					pdo_update('lh_xiche_renwu', array('bang_tel'=>1), array('u_id' =>$u_id));
					$user_info = pdo_update('lh_xiche_user', $arr, array('id' =>$u_id));
				output_data('绑定成功');
			}else{
				output_error('验证码错误');
			}
		}

		flock($fp,LOCK_UN);//释放锁
		fclose($fp);
		output_data($user_info);

	}


	//解绑手机号
	public function doMobileRelieve(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));

		if($op=="jie_bang"){

			if($_SESSION['yzg']==$_GPC['yzm']){
				$user_info = pdo_update('lh_xiche_user', array('phone'=>''), array('id' =>$u_id));
				output_data('解绑成功');
			}else{
				output_error('验证码错误');
			}
		}

		if($op=="bang"){
			if($_SESSION['yzg']==$_GPC['yzm']){

				$user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where  id=1");
				if(empty($user_info['f_id'])){
					$yaoqingma = $_GPC['yaoqingma'];
					$user_info_f = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and yaoqingma=:yaoqingma ",array(":uniacid"=>$uniacid,":yaoqingma"=>$yaoqingma));
					if(empty($user_info_f)){
						output_error('邀请码错误');
					}

					if(!empty($user_info_f)){
						$arr = array();
						$arr['f_id'] = $user_info_f['id'];
						$arr['ff_id'] = $user_info_f['f_id'];
						$arr['phone'] = $_GPC['phone'];
						$jfguizhe = pdo_get('lh_xiche_jfguizhe', array('uniacid' =>$uniacid));
					      $this->user_jifen_edit($user_info_f['id'],$jfguizhe['share'],"推荐新用户奖励",2);
					      //一级用户加1
					      pdo_update('lh_xiche_user', array('yi_sum +=' =>1,'yaoq_day +='=>1), array('id' =>$user_info_f['id']));
					      if(!empty($user_info_f['f_id'])){
					      	//二级+1
					      	pdo_update('lh_xiche_user', array('er_sum +=' =>1), array('id' =>$user_info_f['f_id']));
					      }
					      pdo_update('lh_xiche_user', $arr, array('id' =>$u_id));
					}

					//pdo_insert("lh_xiche_jifen_log",$data_jfs);
					$this->user_jifen_edit($user_info['id'],$user_type['zc_song'],"新用户注册赠送奖励",3);
					$user_info = pdo_update('lh_xiche_user', $arr, array('id' =>$u_id));
				}else{//二次绑定用户，不给与奖励

					$user_info = pdo_update('lh_xiche_user', array('phone'=>$_GPC['phone'],'jifen'=>$user_type['zc_song']), array('id' =>$u_id));					
				}

				output_data('绑定成功');
			}else{
				output_error('验证码错误');
			}
		}

		include $this->template('relieve');
	}

	public function doMobileSendsms(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$phone = $_GPC['phone'];
		$code = $_SESSION['yzg'] = rand('100000','999999');
	  	$search = '/^1[3-9]\d{9}$/';
	  	$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'phone' => $phone), array('*'));
	  	
	  	if(!empty($user_info)){
	  		output_error('该手机号已被注册');
	  	}
	    if (!preg_match($search,trim($_GPC['phone']))) {
	    	output_error('手机号码格式错误');
	    }

/*	    if($_GPC['t_code']!=$_SESSION['t_code']){
	    	output_error('图形验证码错误');
	    }*/
		if(!empty($_SESSION['yzg_time'])&&(($_SESSION['yzg_time']+60)>time())){
			output_error('60秒内请勿重复发送');
		}
		if($op=='yzm'){
			// $body = '您的验证码是'.$code;
			// $re = $this->sendSMS($phone,$body);
			$_SESSION['yzg_time'] = time();
			//$re = $this->sendSMS_new($phone,$code);
			

            $params = array ();
            $params["PhoneNumbers"] = $phone; 
	        $params["TemplateCode"] = 'yzm';
	        $params['TemplateParam'] = Array (
	            'code'=>$code,
	            "product"=>"sms"
	            );

        $this->send_sms($params);

		}

		output_data('发送成功');
	}

	//积分明细
	public function doMobileJifen_log(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;

		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$where = " uniacid=$uniacid and u_id=$u_id";
		if($_GPC['type']==1){
			$arr =array('uniacid' => $uniacid,'u_id' => $u_id,'nums >'=>0); 
			$where .= "  and nums>0";
		}
		if($_GPC['type']==2){
			$arr =array('uniacid' => $uniacid,'u_id' => $u_id,'nums <'=>0); 
			 $where .= " and nums<=0";
		}
		$limit = (($pindex - 1) * $psize).',' . $psize;
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jifen_log")." where $where  order by id desc limit $limit" );

		// $users = pdo_get('mc_mapping_fans', array('openid' =>$user_info['w_openid']));//
		//$list = pdo_getall('mc_credits_record', array('uniacid' => $uniacid,'uid' => $users['uid']), array('*'),'','id desc');

		if($_GPC['v']==1){
			$arr['jifen'] = $user_info['jifen'];
			$arr['list'] = $list;
			output_data($arr);
		}
		include $this->template('jifen_log');
	}


	//佣金明细
	public function doMobileYongjin_log(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		
		$where = " uniacid=$uniacid and u_id=$u_id";
		if($_GPC['type']==1){
			$arr =array('uniacid' => $uniacid,'u_id' => $u_id,'nums >'=>0); 
			$where .= "  and nums>0";
		}
		if($_GPC['type']==2){
			$arr =array('uniacid' => $uniacid,'u_id' => $u_id,'nums <'=>0); 
			 $where .= " and nums<=0";
		}
		$limit = (($pindex - 1) * $psize).',' . $psize;
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_yongjin_log")." where $where  order by id desc limit $limit" );

		if($_GPC['v']==1){
			$arr['jifen'] = $user_info['money'];
			$arr['list'] = $list;
			output_data($arr);
		}
		$list = pdo_getall('lh_xiche_yongjin_log', array('uniacid' => $uniacid,'u_id' => $u_id), array('*'));
		include $this->template('yongjin_log');
	}

  //提现
  public function doMobileTixian(){
    global $_GPC,$_W;
    $uniacid = $_W['uniacid'];
    $u_id = $_SESSION['u_id'];
    $op = !empty($_GPC['op'])?$_GPC['op']:"display";
    $money = $_REQUEST['money'];
    $user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
	$fenxiao_set = pdo_get('lh_xiche_fenxiao', array('uniacid' => $uniacid), array('*'));
    if($op=="post"){
        	output_error('暂不支持提现');
    	if($money>$user_info['money']){
    		output_error('金额不足');
    	}
    	if($money<$fenxiao_set['tx_money']){
    		output_error('最低提现金额'.$fenxiao_set['tx_money']);
    	}
/*	    $fenxiao = pdo_get('lh_xiche_fenxiao', array('uniacid' => $uniacid), array('*'));

	    $shouxufeis = $money*$_REQUEST['shouxufei']*0.01;
	    $shouxufei = sprintf("%.2f", $shouxufeis);
	    $counttx = $money+$shouxufei;
	    if ($counttx>$fenxiao['f_money']) {
	        $tx_money = $money-$shouxufei;
	        $type = "1";
	        $countmoney = $money;
	    }else{
	       $tx_money = $money;
	       $type = "2";
	       $countmoney = $money+$shouxufei;
	   }*/

	   // if(empty($user_info['phone'])){
	   // 		output_error('请到个人中心绑定手机号再提现');
	   // }

	   pdo_update("lh_xiche_user",array("money -="=>$money),array("id"=>$user_info['id']));
	   $data = array("uniacid"=>$uniacid,
	    "u_id"=>$u_id,
	    "tnum"=>time(),
	    "money"=>$money,
	    "s_money"=>0,
	    "time"=>date("Y-m-d H:i:s",time()),
	    "statue"=>"待提现",
	    "xingshi"=>"微信",
	    "zhanghao"=>$_GPC['zhanghao'],
	    "xingming"=>$_GPC['truename'],
	    "kaihuhang"=>$_GPC['kaihuhang'],
	    );
		if($_GPC['type']==1){
			$data['xingshi'] ='支付宝';
		}
		if($_GPC['type']==2){
			$data['xingshi'] ='银行卡';
		}
	   pdo_insert("lh_xiche_usertixian",$data);
	   output_data('提交成功');
    }

    if($_GPC['v']==1){
    	output_data(['user_info'=>$user_info]);
    }
  

    include $this->template('tixian');
  }
public function doMobileTixian_list(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];

		$where = " and statue='待提现'";
		if($_GPC['type']==1){
			$where = " and (statue='已提现' or statue='已拒绝')";
		}
		$list = pdo_getall('lh_xiche_usertixian',"uniacid=$uniacid and u_id=$u_id $where" , array('*'));
		output_data($list);
}
	//积分明细
	public function doMobileJifen_dhlog(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		$list = pdo_getall('lh_xiche_jforder',"uniacid=$uniacid and u_id=$u_id and type!=1" , array('*'));

		include $this->template('jifen_dhlog');
	}
	//积分首页
	public function doMobileJifen_index(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));

		if(!empty($_GPC['id'])){
			$where = " and id=".$_GPC['id'];
			$list = pdo_getall('lh_xiche_jfgoods', array('uniacid' => $uniacid,'status' => 1,'type' => $_GPC['id']), array('*'));
		}else{
					$list = pdo_getall('lh_xiche_jfgoods', array('uniacid' => $uniacid,'status' => 1), array('*'));
		}

		$lh_xiche_jfthumb = pdo_getall('lh_xiche_jfthumb', array('uniacid' => $uniacid), array('*'));
		

		include $this->template('jifen_index');
	}

	public function doMobilejfthumb(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		// error_reporting(E_ALL);
		$menu = pdo_getall('lh_xiche_jfthumb', ['uniacid'=>$uniacid]);
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
		$u_id = $_SESSION['u_id'];
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		if(empty($user_info)){
			$jifen=0;
		}else{
			$jifen = $user_info['jifen'];
		}
		output_data(['lubo'=>$menu,'jifen'=>$jifen]);
	}
	public function doMobileJifen_cart(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['specid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$user_info = pdo_get('lh_xiche_user', array('uniacid' => $uniacid,'id' => $u_id), array('*'));
		if($op=='add'){
			$info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id), array('*'));
			
			if(empty($info)){
				output_error('数量不足');
			}
			$jforder = pdo_get('lh_xiche_jfcart', array('uniacid' => $uniacid,'j_id' => $id,'u_id'=>$u_id), array('*'));
			if(!empty($jforder)){
				output_error('您已经兑换过一次了');
			}
			$data = array();
			$data['u_id']=$u_id;
			$data['uniacid']=$uniacid;
			$data['time']=date('Y-m-d',time());
			$data['j_id'] = $info['id'];
			$data['guige'] = $_GPC['guige'];
			$data['nums'] = $_GPC['nums'];
			$data['ordersn'] = date("YmdHis").mt_rand(0,99);
			$res=pdo_insert('lh_xiche_jfcart',$data);
			output_data('添加成功');
		}

		if($op=='del'){
		   $id = $_GPC['id'];
			pdo_delete("lh_xiche_jfcart",array("id"=>$id));
			output_data('删除成功');
		}
/*  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `name` varchar(155) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `thumbs` longtext,
  `content` longtext,
  `num` int(10) DEFAULT NULL,
  `status` int(10) DEFAULT '0',
  `tuijian` int(5) DEFAULT '0',
  `type` int(5) DEFAULT NULL,
  `stock` int(10) DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `yunfei` decimal(10,2) DEFAULT '0.00',*/

		$sql=" select a.*,b.name,b.thumb,b.num,b.stock,b.yunfei from ".tablename('lh_xiche_jfcart')." as a left join  ".tablename('lh_xiche_jfgoods')." as b on a.j_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id }";

		$list = pdo_fetchall($sql);
		output_data($list);
		include $this->template('jifen_cart');
	}
	//积分详情页
	public function doMobileJifen_page(){
		
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		$info['thumbs'] = unserialize($info['thumbs']);
		foreach ($info['thumbs'] as $key => &$value) {
			if(strpos($value,"http")===false){
				$value = $_W['attachurl'].$value;
			}
		}
		if(strpos($info['thumb'],"http")===false){
			$info['thumb'] = $_W['attachurl'].$info['thumb'];
		}
		if(!empty($info['guige'])){
			$info['guige'] = explode(',',$info['guige']);
		}
		if($_GPC['v']==1){
			output_data($info);
		}
		include $this->template('jifen_page');

	}
	//积分详情页
	public function doMobileJifen_order(){
		
		global $_GPC,$_W;
		$u_id = $_SESSION['u_id'];
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$op = $_GPC['op'];
		error_reporting(E_ALL);
		$info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		if($_GPC['num']<1){
			$_GPC['num']=1;
		}
		$_GPC['num'] = ceil($_GPC['num']);
		if(!empty($_GPC['address_id'])){
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'id'=>$_GPC['address_id'],'u_id'=>$u_id));

		}else{
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'checked'=>1,'u_id'=>$u_id));
		}
		if(empty($address)){
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));
		}
		if($op=='add'&&!empty($id)){

			$goods_info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
			if(strpos($goods_info['thumb'],"http")===false){
				$goods_info['thumb'] = $_W['attachurl'].$goods_info['thumb'];
			}

			// var_dump($goods_info);die;
			$list['list'][]= $goods_info;
			$list['sum_money'] = $goods_info['num']*$_GPC['num'];
			$list['sum_yunfei'] =  $goods_info['yunfei'];
			$list['sum_num'] = $list['g_num'] =  $_GPC['num'];
		}

		if($op=='addcart'){
			$cart_id = substr($_GPC['cart_id'],0,strlen($_GPC['cart_id'])-1);
			// $cart_id = $_GPC['cart_id'];
		  	$sql=" select a.*,b.*,a.guige as aguige from".tablename('lh_xiche_jfcart')." as a left join  ".tablename('lh_xiche_jfgoods')." as b on a.j_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id} and a.id in({$cart_id})";
		  	$list['list'] = pdo_fetchall($sql);

		  	$list['sum_money']=0;
		  	$list['sum_num']=0;
		  	$list['sum_yunfei']=0;
			$j_ids="0";
			$guige = array();
		  	foreach ($list['list'] as $key => $value) {
		  		$list['sum_money']+=$value['nums']*$value['num'];
		  		$list['sum_num']+=$value['nums'];
		  		$list['sum_yunfei']+=$value['yunfei'];
				$j_ids .=",".$value['id'];
				$guige[$value['j_id']]['guige'] = $value['aguige'];
				$guige[$value['j_id']]['nums'] = $value['nums'];
		  	}
		}

		output_data(['address'=>$address,'list'=>$list]);
	}

	public function doMobileJifen_order_add(){
		
		global $_GPC,$_W;
		$u_id = $_SESSION['u_id'];
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$op = $_GPC['op'];
		$info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		if($_GPC['num']<1){
			$_GPC['num']=1;
		}
		$_GPC['num'] = ceil($_GPC['num']);
		// $address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));


				if($_GPC['addr_id']>0){
					$_GPC['address_id'] = $_GPC['addr_id'];
				}

		if(!empty($_GPC['address_id'])){
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'id'=>$_GPC['address_id'],'u_id'=>$u_id));

		}else{
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'checked'=>1,'u_id'=>$u_id));
		}
		if(empty($address)){
			$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));
		}
		if($op=='add'||!empty($id)&&($_GPC['id']!='undefined')){

			$goods_info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
			if(strpos($goods_info['thumb'],"http")===false){
				$goods_info['thumb'] = $_W['attachurl'].$goods_info['thumb'];
			}
			if($goods_info['xiangou']>0){//查询是否限购

			}
			// var_dump($goods_info);die;
			$list['list'][]= $goods_info;
			$list['sum_money'] = $goods_info['num']*$_GPC['num'];
			$list['sum_yunfei'] =  $goods_info['yunfei'];
			$list['sum_num'] = $list['g_num'] =  $_GPC['num'];
			$j_ids = $id;
		}

		if($op=='addcart'||!empty($_GPC['cart_id'])&&($_GPC['cart_id']!='undefined')){
			$cart_id = substr($_GPC['cart_id'],0,strlen($_GPC['cart_id'])-1);
			// $cart_id = $_GPC['cart_id'];
		  	$sql=" select a.*,b.*,a.guige as aguige from".tablename('lh_xiche_jfcart')." as a left join  ".tablename('lh_xiche_jfgoods')." as b on a.j_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id} and a.id in({$cart_id})";
		  	$list['list'] = pdo_fetchall($sql);

		  	$list['sum_money']=0;
		  	$list['sum_num']=0;
		  	$list['sum_yunfei']=0;
			$j_ids="0";
			$guige = array();
		  	foreach ($list['list'] as $key => $value) {
		  		$list['sum_money']+=$value['nums']*$value['num'];
		  		$list['sum_num']+=$value['nums'];
		  		$list['sum_yunfei']+=$value['yunfei'];
				$j_ids .=",".$value['id'];
				$guige[$value['j_id']]['guige'] = $value['aguige'];
				$guige[$value['j_id']]['nums'] = $value['nums'];
		  	}
		}
		// lh_xiche_jforder
		$data = array();
		$data['uniacid']=$uniacid;
		$data['u_id']=$u_id;
		$ordersn = $data['ordersn']= date("YmdHis").mt_rand(0,99);//0
		$data['time']=date('Y-m-d H:i:s',time());
		$data['jifen']=$list['sum_money'];
		$data['statues']=0;  //`statues` int(10) DEFAULT NULL COMMENT '0-未支付 1-等待发货 2-等待收货 -3完成',
	
		$data['address']=$address['address'];
		$data['xxaddress']=$address['xxaddress'];
		$data['username']=$address['uname'];
		$data['usertel']=$address['phone'];
		$data['type']=1;
		$data['j_ids']=$j_ids;
		// $data['guige']= serialize($guige);
		$data['guige']= $_GPC['guige_name'];
		$data['yunfei']=$list['sum_yunfei'];

		$user_info = pdo_get('lh_xiche_user',['id'=>$_SESSION['u_id']]);

        if($user_info['jifen']<$data['jifen']){
            output_error('积分不足');
        }
		pdo_insert('lh_xiche_jforder',$data);
		$o_id = pdo_insertid();

		output_data($o_id);
		include $this->template('jifen_order');
	}
	//积分详情页
/*	public function doMobileJifen_orders_add(){
		
		global $_GPC,$_W;
		$u_id = $_SESSION['u_id'];
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
			$cart_id = substr($_GPC['cart_id'],0,strlen($_GPC['cart_id'])-1);
		   	$sql=" select a.*,b.* from".tablename('lh_xiche_jfcart')." as a left join  ".tablename('lh_xiche_jfgoods')." as b on a.j_id=b.id where a.uniacid={$_W['uniacid']} and a.u_id={$u_id} and a.id in({$cart_id})";
		  	$list['list'] = pdo_fetchall($sql);

		  	$list['sum_money']=0;
		  	$list['sum_num']=0;
		  	$list['sum_yunfei']=0;
		  	foreach ($list['list'] as $key => $value) {
		  		$list['sum_money']+=$value['nums']*$value['num'];
		  		$list['sum_num']+=$value['nums'];
		  		$list['sum_yunfei']+=$goods_jifen_yunfei;
		  	}
		$info = pdo_get('lh_xiche_jfgoods', array('uniacid' => $uniacid,'id' => $id,), array('*'));
		$address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));
		header("location:./index.php?i=".$_GPC['i']."&c=".$_GPC['c']."&do=jifen_order&m=".$_GPC['m'].'&cart_id='.$_GPC['cart_id']);
	}*/

		//商品分类
	public function doMobileJifen_class(){



		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		// $list = pdo_getall('lh_xiche_goods_style', array('uniacid'=>$uniacid));
		if(!empty($_GPC['id'])){
			$where = " and id=".$_GPC['id'];
		}
		$index_list = pdo_getall('lh_xiche_jftype', "uniacid=$uniacid ");

	    foreach ($index_list as &$value) {
	    	$value['goods_list'] = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jfgoods")." where uniacid=:uniacid and type in({$value['id']})",array(":uniacid"=>$uniacid));
	    }


		include $this->template('jifen_class');
	}


	public function doMobileJifen_goods(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$where =" and status=1 ";
		$pindex = max(0, intval($_GPC['page']));
		$psize = 4;
		if($id>0){
			$where .= " and type=".$id;
		}

		$sort = ' order by id desc';
		if($_GPC['xiaoLOrder']=='1'){
			$sort = ' order by xiaoliang asc';
		}
		
		if($_GPC['xiaoLOrder']=='2'){
			$sort = ' order by xiaoliang desc';
		}

		if($_GPC['priceOrder']=='1'){
			$sort = ' order by num asc';
		}

		if($_GPC['priceOrder']=='2'){
			$sort = ' order by num desc';
		}

		$limit = ($pindex * $psize).',' . $psize;
		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jfgoods")."  where uniacid=$uniacid {$where} $sort  limit $limit");
		$class = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jftype")."  where uniacid=$uniacid  ");
		array_unshift($class,array("id"=>"0","name"=>"全部"));

		foreach ($list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}
		if($_GPC['v']==1){
			$menu = $this->menu;
			foreach ($menu as $key => &$value) {
				if(strpos($value['thumb'],"http")===false){
					$value['thumb'] = $_W['attachurl'].$value['thumb'];
				}
			}
			output_data(['list'=>$list,'class'=>$class,'menu'=>$menu]);
		}
	}
	//商品列表
	public function doMobileSearch(){
		// error_reporting(E_ALL);
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$search = $_GPC['search'];
		$where="";

		if(!empty($_GPC['search'])){
			$where = " and name like '%{$_GPC['search']}%'";
		}

		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_jfgoods")."  where uniacid=:uniacid {$where} order by id desc ",array(":uniacid"=>$uniacid));

		include $this->template('search');
	}

	//收货地址管理
	public function doMobileAddress(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = $_GPC['op'];
		$u_id = $_SESSION['u_id'];
		if($op=='default'){
			pdo_update('lh_xiche_address', array('checked'=>0), array('u_id' =>$u_id));
			pdo_update('lh_xiche_address', array('checked'=>1), array('id'=>$_GPC['id']));
			output_data('设置成功');
		}
		
		if($op=='post'){
			if($_GPC['id']>0){
			$data = array();
			$data['uname'] = addslashes(htmlspecialchars($_GPC['uname']));
			$data['phone'] = addslashes(htmlspecialchars($_GPC['phone']));
			$data['address'] = addslashes(htmlspecialchars($_GPC['address']));
			$data['xxaddress'] = addslashes(htmlspecialchars($_GPC['xxaddress']));
			$data['checked'] = $_GPC['checked'];
			$data['u_id'] = $u_id;
			$data['uniacid'] = $uniacid;
			pdo_update('lh_xiche_address', $data, array('id' =>$_GPC['id'],'u_id' =>$u_id));
			output_data('修改成功');
			}else{
			$data = array();
			$data['uname'] = addslashes(htmlspecialchars($_GPC['uname']));
			$data['phone'] = addslashes(htmlspecialchars($_GPC['phone']));
			$data['address'] = addslashes(htmlspecialchars($_GPC['address']));
			$data['xxaddress'] = addslashes(htmlspecialchars($_GPC['xxaddress']));
			$data['checked'] = $_GPC['checked'];
			$data['u_id'] = $u_id;
			$data['uniacid'] = $uniacid;
			$res=pdo_insert('lh_xiche_address',$data);
			output_data('添加成功');
			}
		}

		if($op=='del'){
			$res = pdo_delete('lh_xiche_address',array('id'=>$_GPC['id'],'u_id' =>$u_id));
			output_data('删除成功');
		}
		
		if($_GPC['id']>0){
			$list = $info = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'id'=>$_GPC['id']));
		}else{
			$list = pdo_getall('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));
			if(empty($list)){ $op='add';}	
		}
// 
		$user_address = pdo_get('lh_xiche_address', array('uniacid'=>$uniacid,'u_id'=>$u_id));

		if($_GPC['v']>0){
			output_data($list);
		}
		include $this->template('address');
	}
	
	
	//商品列表
	public function doMobileQiye(){
		// error_reporting(E_ALL);
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$type = !empty($_GPC['type'])?$_GPC['type']:1;
		$op = !empty($_GPC['op'])?$_GPC['op']:"list";

		if($op=='post'){
			$info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_qiye")." where uniacid=:uniacid and id=:id order by sort desc  ",array(":uniacid"=>$uniacid,":id"=>$_GPC['id']));
			output_data($info);
		}

		$where=" and type=$type";

		if(!empty($_GPC['search'])){
			$where .= " and name like '%{$_GPC['search']}%'";
		}

		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_qiye")."  where uniacid=:uniacid {$where} order by sort desc ",array(":uniacid"=>$uniacid));
		foreach ($list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}

		output_data($list);
	}


	//商品列表
	public function doMobileHuodongzx(){
		// error_reporting(E_ALL);
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$type = !empty($_GPC['type'])?$_GPC['type']:1;
		$op = !empty($_GPC['op'])?$_GPC['op']:"list";

		if($op=='post'){
			$info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_huodongzx")." where uniacid=:uniacid and id=:id order by sort desc  ",array(":uniacid"=>$uniacid,":id"=>$_GPC['id']));
			output_data($info);
		}

		$where=" and type=$type";

		if(!empty($_GPC['search'])){
			$where .= " and name like '%{$_GPC['search']}%'";
		}

		$list = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_huodongzx")."  where uniacid=:uniacid {$where} order by sort desc ",array(":uniacid"=>$uniacid));
		foreach ($list as $key => &$value) {
			if(strpos($value['thumb'],"http")===false){
				$value['thumb'] = $_W['attachurl'].$value['thumb'];
			}
		}

		output_data($list);
	}
	
	public function doMobileLogin(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		// error_reporting(E_ALL);
	if($op=='weixin'){
			$yuming = 'https://'.$_SERVER['HTTP_HOST'];
			// echo $_SESSION['lailu'];die;
			if(!empty($_SESSION['w_openid'])){//判断防止多次重复微信授权。
				$user_infos = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and w_openid=:w_openid ",array(":uniacid"=>$uniacid,":w_openid"=>$_SESSION['w_openid']));

				if(!empty($user_infos)){//判断session 是否有效。针对数据库删除用户，浏览器还存在登录状态情况
					// header("location:".$_W['setting']['site']['url'].'/addons/lh_xiche/html/#/');die;
					if(empty($_SESSION['lailu'])){
						header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
					}else{
						header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
						header("location:".$_SESSION['lailu']);die;
					}

					if($_SESSION['u_phone']==''){
						header("location:".$_W['siteroot'].$this->createMobileUrl('index'));	
					}else{
						header("location:".$_W['siteroot'].$this->createMobileUrl('index'));		
					}
				}
			}

			require_once IA_ROOT."/addons/lh_xiche/inc/lib/WxPay.Config.php";
			$config = new WxPayConfig();
			//处理微信回调
			$mchid = $config->GetMerchantId();
			$appid = $config->GetAppId();  
			$apiKey = $config->GetKey();  
			$secret = $config->GetAppSecret(); 
			// $redirect_uri = urlencode("https://webstrongtech.com/app/index.php?i=9&c=entry&do=login&m=lh_xiche&op=weixin");\
			// echo $_W['siteroot'].$this->createMobileUrl('login',array('op'=>'weixin','v'=>$_GPC['v']));die;
			$redirect_uri = urlencode($_W['siteroot'].$this->createMobileUrl('login',array('op'=>'weixin','v'=>$_GPC['v']))); 
			$snsapi='snsapi_userinfo';
				$code = isset($_GET['code'])?$_GET['code']:'';

				if(!$code){
					$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
					$url = $http_type.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					// $url = $_SESSION['lailu'];
					$oauth_url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($url).'&response_type=code&scope='.$snsapi.'&state=wxbase#wechat_redirect';
					header('Location: ' . $oauth_url);
					exit;
				}

				load()->func('communication');
				$getOauthAccessToken = ihttp_get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code');
				$json = json_decode($getOauthAccessToken['content'],true);


				if (!empty($json['errcode']) && ($json['errcode'] == '40029' || $json['errcode'] == '40163')){
					$url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':'?');
					$parse=parse_url($url);
					if(isset($parse['query'])){
						parse_str($parse['query'],$params);
						unset($params['code']);
						unset($params['state']);
						$url = 'https://'.$_SERVER['HTTP_HOST'].$parse['path'].'?'.http_build_query($params);
					}
					$oauth_url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($url).'&response_type=code&scope='.$snsapi.'&state=wxbase#wechat_redirect';
					header('Location: ' . $oauth_url);
					exit;
				}
				if( $snsapi == "snsapi_userinfo" ){
					$userinfo = ihttp_get('https://api.weixin.qq.com/sns/userinfo?access_token='.$json['access_token'].'&openid='.$json['openid'].'&lang=zh_CN');
					$userinfo = $userinfo['content'];
				}
				elseif ( $snsapi == "snsapi_base" )
				{
					$userinfo = array();
					$userinfo['openid'] = $json['openid'];
				}
				$userinfostr =json_decode($userinfo,true);
				// var_dump($userinfostr);die;
			$data = array();
			$data['uniacid'] = $uniacid;
			$data['w_openid']  = $userinfostr['openid'];
			$data['unionid']  = $userinfostr['unionid'];
			$data['name']  = $userinfostr['nickname'];
			$data['thumb'] = $userinfostr['headimgurl'];
	        $data['ip'] = get_ip();
	        $data['end_login_time'] = date("Y-m-d H:i:s",time());
			$user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and unionid=:unionid or w_openid=:w_openid",array(":uniacid"=>$uniacid,":unionid"=>$data['unionid'],":w_openid"=>$data['w_openid']));
			// var_dump($userinfostr);die;
			if(empty($data['w_openid'])){
				die;
			}
			$_SESSION['w_openid'] = $data['w_openid'];
			if(empty($user_info)){
				//注册
				// $jfguizhe = pdo_get('lh_xiche_jfguizhe', array('uniacid' =>$uniacid));
				//查询上上级uid
				if(!empty($_SESSION['f_id'])){
					$user_info_f = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and id=:id ",array(":uniacid"=>$uniacid,":id"=>$_SESSION['f_id']));
					if(!empty($user_info_f)){
						$data['ff_id'] = $user_info_f['f_id'];

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
				}
				
				$data['f_id'] = $_SESSION['f_id'];
				$data['addtime']  = date('Y-m-d H:i:s',time());
				$res = pdo_insert("lh_xiche_user",$data);
				$u_id = pdo_insertid();
				$user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and id=:u_id ",array(":uniacid"=>$uniacid,":u_id"=>$u_id));

				$_SESSION['u_id'] = $user_info['id'];
				$_SESSION['phone'] = $user_info['phone'];
				//新用户奖励积分
				if(empty($user_type)){
					$user_type = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user_type")." where uniacid=:uniacid and sort=:type ",array(":uniacid"=>$uniacid,":type"=>1));
				}
				if($user_type['new_user']>0){
					$this->user_jifen_edit($u_id,$user_type['new_user'],"新用户奖励",2);
				}
				
/*				if($_SESSION['f_id']>0&&$jfguizhe['new_user_share']>0){
					$this->user_jifen_edit($u_id,$jfguizhe['new_user_share'],"通过分享码赠送",2);
				}*/

			}else{
				//登录
				$_SESSION['u_id'] = $user_info['id'];
				$_SESSION['u_phone'] = $user_info['phone'];
				//更新 微信openid
				pdo_update("lh_xiche_user",$data,array("id"=>$user_info['id']));
			}
			// header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
			if(empty($_SESSION['lailu'])){
				header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
			}else{
				header("location:".$_SESSION['lailu']);die;
			}

				header("location:".$yuming.'/addons/lh_xiche/html/#/');die;	
				die;
	}
	if($op=='app'){
		include $this->template('login_app');
		die;
	}
	if($op=='login_app'){
		$data = $_GPC['data'];
		// $json =json_decode($data,true);
		if(empty($_GPC['access_token'])){
			output_error('授权获取失败');
		}
// 一单下面下级会员有一条线的某个会员成为砖石会员，终断本条线下会员的复购奖励
        $userinfo = ihttp_get('https://api.weixin.qq.com/sns/userinfo?access_token='.$_GPC['access_token'].'&openid='.$_GPC['openid'].'&lang=zh_CN');
        $userinfo = $userinfo['content'];
        $userinfostr =json_decode($userinfo,true);
            
		$data = array();
		$data['uniacid'] = $uniacid;
		$data['app_openid']  = $userinfostr['openid'];
		$data['unionid']  = $userinfostr['unionid'];
		$data['name']  = $userinfostr['nickname'];
		$data['thumb'] = $userinfostr['headimgurl'];
		$user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and unionid=:unionid ",array(":uniacid"=>$uniacid,":unionid"=>$data['unionid']));
		// var_dump($userinfostr);die;
		$_SESSION['w_openid'] = $data['w_openid'];
		if(empty($user_info)){
			//注册
			$res = pdo_insert("lh_xiche_user",$data);
			$u_id = pdo_insertid();
			$user_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_user")." where uniacid=:uniacid and u_id=:u_id ",array(":uniacid"=>$uniacid,":u_id"=>$u_id));

			$_SESSION['u_id'] = $user_info['id'];
			$_SESSION['phone'] = $user_info['phone'];
			setcookie("u_id", $_SESSION['u_id'], time()+60*60*24*30);

		}else{
			//登录
			$_SESSION['u_id'] = $user_info['id'];
			$_SESSION['phone'] = $user_info['phone'];
			setcookie("u_id", $_SESSION['u_id'], time()+60*60*24*30);
			//更新 微信openid
			pdo_update("lh_xiche_user",$data,array("id"=>$user_info['id']));
		}
		if(empty($user_info['u_phone'])){
			output_data($_W['siteroot'].$this->createMobileUrl('index'));	
		}else{
			output_data($_W['siteroot'].$this->createMobileUrl('index'));		
		}
	}


			header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
			if(empty($_SESSION['lailu'])){
				header("location:".$yuming.'/addons/lh_xiche/html/#/');die;
			}else{
				header("location:".$_SESSION['lailu']);die;
			}
	}

  	function getJsApiTicket($appId,$appSecret) {
  	      global $_W;
      $uniacid = $_W['uniacid'];
      $data = cache_load('lh_xiche_wx_ticket');

      if ($data['expire_time'] < time()) {
      	 $info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_parment")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid));
	       if($info['access_token_time']<=(time()+3000)){
	          $accessToken =$this->getAccessToken_new($info['appid'],$info['appsecret']);
	          pdo_update('lh_xiche_parment',['access_token'=>$accessToken,'access_token_time'=>time()+3000],["uniacid"=>$uniacid]);
	       }else{
	          $accessToken = $info['access_token'];
	       }

        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
        $res = json_decode($this->httpGet($url),true);
        $ticket = $res['ticket'];
        if ($ticket) {
          $data = array();
          $data['expire_time'] = time() + 3000;
          $data['jsapi_ticket'] = $ticket;
          cache_write('lh_xiche_wx_ticket', $data);
        }
      } else {
        $ticket = $data['jsapi_ticket'];
      }
      return $ticket;
    }

	function getAccessToken($appId,$appSecret) {

		$data = cache_load('lhshopwxToken');
		if ($data['expire_time'] < time()) {
		// 如果是企业号用以下URL获取access_token
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
		$res = json_decode($this->httpGet($url),true);

		$access_token = $res['access_token'];
		if ($access_token) {
			$data=array();
			$data['expire_time'] = time() + 3000;
			$data['access_token'] = $access_token;
			cache_write('lhshopwxToken', $data);
		}
		} else {
		$access_token = $data['access_token'];
		}
		return $access_token;
	}

	function getAccessToken_new($appId,$appSecret,$gengxin=false) {

		// 如果是企业号用以下URL获取access_token
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
		$res = json_decode($this->httpGet($url),true);
		
		$this->log_data($res);
		$access_token = $res['access_token'];
		return $access_token;
	}

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
	// curl请求  get方式
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
	// 话费，视频直充下单
	function cz_order_api($ordersn){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];

		$cz_set = pdo_get('lh_xiche_cz_set',['uniacid'=>$uniacid]);
		// 参数
		$userId = $cz_set['userid'];
		$privatekey = $cz_set['key'];
		if(empty($userId)){
			output_error('请先设置代理商用户编码');
		}
		if(empty($privatekey)){
			output_error('请先设置密钥');
		}

		$order_info = pdo_get('lh_xiche_cz_order',['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
		// 参数
		$checkItemFacePrice = $order_info['goods_yprice']*1000;
		$dtCreate = date('YmdHis',strtotime($order_info['addtime']));
		$itemId = $order_info['ids'];
		$itemPrice = $order_info['goods_price']*1000;
		$serialno = $order_info['ordersn'];
		$uid = $order_info['zhanghao'];
		$sign = MD5($checkItemFacePrice.$dtCreate.$itemId.$itemPrice.$serialno.$uid.$userId.$privatekey);

		$url = "http://101.201.253.153:6160/unicomAync/buy.do?checkItemFacePrice=$checkItemFacePrice&dtCreate=$dtCreate&itemId=$itemId&itemPrice=$itemPrice&serialno=$serialno&uid=$uid&userId=$userId&sign=$sign";
		$headers = [
			'Accept: application/json;charset=UTF-8'
		];

		// 余额接口
		// $yue_sign = MD5($userId.$privatekey);
		// $yue_url = "http://101.201.253.153:6160/unicomAync/queryBalance.do?userId=$userId&sign=$yue_sign";
		// $response = $this->httpGet($yue_url,$headers);
		// $response  = json_decode($response,true);
		$response = $this->httpGet($url,$headers);
		$response  = json_decode($response,true);

		if($response['status']=="success"){//
			pdo_update('lh_xiche_cz_order',['status'=>41,'code'=>$response['code'],'desc'=>$response['desc']],['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
		}else if(in_array($response['code'],[23,31,50])){
			// 人工核实
			pdo_update('lh_xiche_cz_order',['status'=>40],['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
		}else{
			// 下单失败，自动退款
			$refund_sn = 'tk'.time().mt_rand(0,999999).$order_info['u_id'];
			pdo_update('lh_xiche_cz_order',['status'=>50,'refund_sn'=>$refund_sn,'code'=>$response['code'],'desc'=>$response['desc']],['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
			$result = $this->refund_api($ordersn);
			if(($result['return_code']=='SUCCESS') && ($result['result_code']=='SUCCESS')){  
				//退款成功  
				pdo_update('lh_xiche_cz_order',['status'=>51],['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
			}else if(($result['return_code']=='FAIL') || ($result['result_code']=='FAIL')){  
				//退款失败  
				$refund_err = (empty($result['err_code_des'])?$result['return_msg']:$result['err_code_des']); 
				pdo_update('lh_xiche_cz_order',['status'=>52,'refund_err'=>$refund_err],['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
			}
		}
		return $response;
	}
	// 退款
	function refund_api($ordersn){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$order_info = pdo_get('lh_xiche_cz_order',['uniacid'=>$uniacid,'ordersn'=>$ordersn]);
		// 退款到余额
		// if('余额'){
		// 	pdo_update('lh_xiche_user',['money +='=>$order_info['count_money']],['uniacid'=>$uniacid,'id'=>$order_info['u_id']]);
		// 	pdo_update('lh_xiche_order',['status'=>60,'refund_yuanyin'=>$order_info['desc'],'refund_addtime'=>date('Y-m-d H:i:s')]);
	
		// 	output_data('已退款到余额');
		// }

		// 微信退款接口
		$url ="https://api.mch.weixin.qq.com/secapi/pay/refund";
		$info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_parment")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid)); 

		$appid = $info['appid'];
		$mch_id = $info['mchid'];
		$nonce_str = md5(time().mt_rand(0,999999));
		$transaction_id = $order_info['transaction_id'];
		$out_refund_no = $order_info['refund_sn'];
		$total_fee = $order_info['count_money']*100;
		$refund_fee = $order_info['count_money']*100;
		$notify_url = '';
		$key = $info['wxkey'];

		$param = array(
			'appid'=> $appid,
			'mch_id'=> $mch_id,
			'nonce_str'=>$nonce_str,
			'out_refund_no'=> $out_refund_no ,
			'transaction_id'=> $transaction_id ,//微信订单号
			'total_fee'=>  $total_fee,
			'refund_fee'=> $refund_fee,
		);
		$sign = $this->getSignsss($param,$key);
		$data = "<xml>
					<appid>$appid</appid>
					<mch_id>$mch_id</mch_id>
					<nonce_str>$nonce_str</nonce_str>
					<transaction_id>$transaction_id</transaction_id>
					<out_refund_no>$out_refund_no</out_refund_no>
					<total_fee>$total_fee</total_fee>
					<refund_fee>$refund_fee</refund_fee>
					<sign>$sign</sign>
				</xml>";	
		// 请求接口
		$response = $this->refund_curl($url,$data);
		$response = $this->xml_to_array($response);
		return $response;
	}
	function refund_curl($url,$data,$headers='') {
		$curl = curl_init();
		if($headers != ''){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		//以下两种方式需选择一种
		//微信支付证书
		//第一种方法，cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($curl,CURLOPT_SSLCERTTYPE,'PEM');
		// curl_setopt($ch,CURLOPT_SSLCERT,$ssl['apiclient_cert']); IA_ROOT
		curl_setopt($curl,CURLOPT_SSLCERT,IA_ROOT."/web/cert/lh_xiche/apiclient_cert.pem");
		//默认格式为PEM，可以注释
		curl_setopt($curl,CURLOPT_SSLKEYTYPE,'PEM');
		// curl_setopt($ch,CURLOPT_SSLKEY,$ssl['apiclient_key']);
		curl_setopt($curl,CURLOPT_SSLKEY,IA_ROOT.'/web/cert/lh_xiche/apiclient_key.pem');
	
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

		curl_setopt($curl, CURLOPT_POST, true);  
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		if($res){
			echo "<pre>";print_r($res);echo "</pre>";
		}else{
			var_dump(curl_errno($curl));
		}
		curl_close($curl);
		return $res;
	}
	function getSignsss($Obj,$KEY){
		foreach ($Obj as $k => $v){
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMapsss($Parameters, false);
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$KEY;
		//签名步骤三：MD5加密
		$String = md5($String);
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		return $result_;
	}
	function formatBizQueryParaMapsss($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar = "";
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	//XML转为数组
	function xml_to_array($xml){
		return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	}
	//获取jsapi签名
	function getSignPackage() {
		global $_W;
		$uniacid = $_W['uniacid'];
		$info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_parment")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid)); 
		$jsapiTicket = $this->getJsApiTicket($info['appid'],$info['appsecret']);
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$_W['siteurl'];
		$url = "{$protocol}{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}";

		if(!empty($_REQUEST['url'])){
			$url = urldecode($_REQUEST['url']);
		}

		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";

		$signature = sha1($string);
		// var_dump($signature);die;
		$signPackage = array(
			"appId"     => $info['appid'],
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string,
			"f_id" => $_SESSION['u_id']
		);
		return $signPackage; 
	}

	function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

 
    /**
     * 发送模板消息
     * @param $openid
     * @param $template_id
     * @param $data
     * @param null $url
     * @return bool|mixed
     */
    function sendTemplate($openid, $template_id, $data, $url = null)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_parment")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid));
        $moban = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_tongzhiwx")." where uniacid=:uniacid",array(":uniacid"=>$uniacid));
 
        $template_id = $moban[$template_id];

        //$accessToken = $this->getAccessToken_new($info['appid'],$info['appsecret']);

	       if($info['access_token_time']<=(time()+3000)){
	          $accessToken =$this->getAccessToken_new($info['appid'],$info['appsecret']);
	          pdo_update('lh_xiche_parment',['access_token'=>$accessToken,'access_token_time'=>time()+3000],["uniacid"=>$uniacid]);
	       }else{
	          $accessToken = $info['access_token'];
	       }

        if(!$accessToken) {
            return false;
        }
        $msgArr["touser"] = $openid;
        $msgArr["template_id"] = $template_id;
        $msgArr["data"] = $data;
        if(!empty($url)) {
            $msgArr["url"] = $url;
        }
        $msgJson = json_encode($msgArr);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$accessToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $msgJson);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,true);
    }

    public function send_sms($params){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        require_once dirname(__FILE__) .'/inc/func/SignatureHelper.php';
        $aliduanxin = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_news")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid)); 
        $accessKeyId = $aliduanxin['accessKeyId'];
        $accessKeySecret = $aliduanxin['accessKeySecret'];
        $params["SignName"] = $aliduanxin['SignName'];
        $params["TemplateCode"] = $aliduanxin[$params['TemplateCode']];
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"]);
        }
        $helper = new SignatureHelper();
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
        $this->log_data($content);
        return $content;
    }

	public function sendSMS($tel,$body) {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        require_once dirname(__FILE__) .'/inc/func/SignatureHelper.php';
        $aliduanxin = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_news")." WHERE uniacid=:uniacid ",array("uniacid"=>$uniacid)); 
        $accessKeyId = $aliduanxin['accessKeyId'];
        $accessKeySecret = $aliduanxin['accessKeySecret'];
        $params["SignName"] = $aliduanxin['SignName'];
        $params["TemplateCode"] = $aliduanxin[$params['TemplateCode']];
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"]);
        }
        $helper = new SignatureHelper();
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );


		return $result; // 返回数据     
	}


	 public function sendSMS_new($tel,$body) {
	// public function doMobilesendSMS_new() {
	require_once IA_ROOT . "/addons/lh_xiche/inc/lib/SmsSingleSender.php";
	require_once IA_ROOT . "/addons/lh_xiche/inc/lib/SmsSenderUtil.php";

	// 短信应用SDK AppID
	$appid ='' ; // 1400开头
	$appkey = "";
	$phoneNumbers = [$tel];
	// 短信模板ID，需要在短信应用中申请
	$templateId = 467057;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
	$smsSign = "时源科技"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
		// 指定模板ID单发短信
    //实现短信对象 传入appid  key
    $ssender = new SmsSingleSender($appid, $appkey);
    $params = [$body];
    /**
     * 指定模板单发
     *
     * @param string $nationCode 国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param int $templId 模板 id
     * @param array $params 模板参数列表，如模板 {1}...{2}...{3}，那么需要带三个参数
     * @param string $sign 签名，如果填空串，系统会使用默认签名
     * @param string $extend 扩展码，可填空串
     * @param string $ext 服务端原样返回的参数，可填空串
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    // 签名参数未提供或者为空时，会使用默认签名发送短信
    $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId, $params, $smsSign, "", "");

    $rsp = json_decode($result);
    //输出请求结果
    // var_dump($rsp);
		return $rsp; // 返回数据     
	}

    /**
     * todo:加入字符,生成图片,并加入干扰线，干扰素
     * @param int $width 图片宽度
     * @param int $height 图片高度
     */
    public function doMobileImg_yzg($width = 80, $height =30)
    {
        // $_SESSION['t_code'] = $verifyCode =  rand(100000, 999999);
   /*     $image      = imagecreatetruecolor($width, $height);
        //白色背景
        $white = imagecolorallocate($image, 255, 255, 255);
        //字体颜色
        $fontStyle = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));;
        imagefill($image, 0, 0, $white);
        // 使用默认字体，无法修改文字大小
        // imagestring($image, 5, 10, 10, $verifyCode, $fontStyle);
        // 导入自定义字体，修改文字大小
        imagettftext($image, 18, 0, 5, 20, $fontStyle, IA_ROOT.'/app/resource/fonts/fontawesome-webfont.ttf', $verifyCode);
        //加入干扰点
        for ($i = 0; $i < 80; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $color);
        }
        //干扰线
        for ($i = 0; $i < 5; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        }
        //输出图片
        header("Content-type: image/png");
        imagepng($image);
        //释放资源
        imagedestroy($image);*/
         $num = 4;
	    // 设置宽度
	    $width = 100;
	    // 设置高度
	    $height = 30;
	    //生成验证码，也可以用mt_rand(1000,9999)随机生成
	     $Code = ""; 
	     for ($i = 0; $i < $num; $i++) { 
	        $Code .= mt_rand(0,9); 
	     } 

	    // 将生成的验证码写入session
    	 $_SESSION['t_code'] = $Code ;

	    // 设置头部
	    header("Content-type: image/png");

	    // 创建图像（宽度,高度）
	    $img = imagecreate($width,$height);

	    //创建颜色 （创建的图像，R，G，B）
	    $GrayColor = imagecolorallocate($img,230,230,230);
	    $BlackColor = imagecolorallocate($img, 0, 0, 0);
	    $BrColor = imagecolorallocate($img,52,52,52);

	    //填充背景（创建的图像，图像的坐标x，图像的坐标y，颜色值）
	    imagefill($img,0,0,$GrayColor);

	    //设置边框
	    imagerectangle($img,0,0,$width-1,$height-1, $BrColor);

	    //随机绘制两条虚线 五个黑色，五个淡灰色
	    $style = array ($BlackColor,$BlackColor,$BlackColor,$BlackColor,$BlackColor,$GrayColor,$GrayColor,$GrayColor,$GrayColor,$GrayColor);  
	    imagesetstyle($img, $style);
	    imageline($img,0,mt_rand(0,$height),$width,mt_rand(0,$height),IMG_COLOR_STYLED);
	    imageline($img,0,mt_rand(0,$height),$width,mt_rand(0,$height),IMG_COLOR_STYLED);

	    //随机生成干扰的点
	    for ($i=0; $i < 200; $i++) {
	        $PointColor = imagecolorallocate($img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
	        imagesetpixel($img,mt_rand(0,$width),mt_rand(0,$height),$PointColor);
	    }

	    //将验证码随机显示
	    for ($i = 0; $i < $num; $i++) {
	        $x = ($i*$width/$num)+mt_rand(5,12);
	        $y = mt_rand(5,10);
	        imagestring($img,7,$x,$y,substr($Code,$i,1),$BlackColor); 
	    }
	    //输出图像
	    imagepng($img);
	    //结束图像
	    imagedestroy($img);
    }

	public function doMobileupload_img(){
    //图片上传
        global $_W, $_GPC;  
        $uniacid = $_W['uniacid'];
       //查询远程存储
        $cunchu = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_cunchu")." where uniacid=:uniacid",array(":uniacid"=>$uniacid));
        $cunchu = 0;//测试
        if ($_W['setting']['remote']['type']==0 && $cunchu['type']==0) {   //什么都不开启
            $uptypes = array('image/jpg', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/gif', 'image/bmp', 'image/x-png');
            $max_file_size = 20000000;
            $destination_folder = '../attachment/images/'.$uniacid.'/';  //图片文件夹路径
            //创建存放图片的文件夹
            // var_dump($_FILES);
            if (!is_dir($destination_folder)) {
               $res = mkdir($destination_folder, 0777, true);
            }
            if (!is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                output_error('图片不存在!');
                die;
            }
            $file = $_FILES['upfile'];
            if ($max_file_size < $file['size']) {
                output_error('文件太大!');
                die;
            }
            if (!in_array($file['type'], $uptypes)) {
                output_error('文件类型不符!' . $file['type']);
                die;
            }
            $filename = $file['tmp_name'];
            $pinfo = pathinfo($file['name']);
            $ftype = $pinfo['extension'];
            $destination = $destination_folder . str_shuffle(time() . rand(111111, 999999)) . '.' . $ftype;
            if (file_exists($destination) && $overwrite != true) {
                output_error('同名文件已经存在了');
                die;
            }
            if (!move_uploaded_file($filename, $destination)) {
                output_error('移动文件出错');
                die;
            }
            $pinfo = pathinfo($destination);
            $fname = $_W['siteroot'].'/attachment/images/'.$uniacid.'/'.$pinfo['basename'];
            output_data($fname);
        }
        else if($_W['setting']['remote']['type']==2 && $cunchu['type']==0)    //全局的远程存储 oss
        {       
            //将本地图片先上传到服务器
            load()->func('file');
            $file = $_FILES['upfile'];
            $filename = $file['tmp_name'];
            $destination_folder = '../attachment/images/'.$_W['uniacid'].'/'.date('Y/m/').'/';  //图片文件夹路径
            //创建存放图片的文件夹
            if (!is_dir($destination_folder)) {
               $res = mkdir($destination_folder, 0777, true);
            }
            if (!is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                echo '图片不存在!';
                die;
            }
           
            $pinfo = pathinfo($file['name']);
            $ftype = $pinfo['extension'];
            $destination = $destination_folder . str_shuffle(time() . rand(111111, 999999)) . '.' . $ftype;
            if (file_exists($destination) && $overwrite != true) {
                echo '同名文件已经存在了';
                die;
            }
            if (!move_uploaded_file($filename, $destination)) {
                echo '移动文件出错';
                die;
            }
            $pinfo = pathinfo($destination);
            $filename = 'images/'.$_W['uniacid'].'/'.date('Y/m/').$pinfo['basename'];

            //将服务器上的图片转移到阿里云oss
           
            $remote = $_W['setting']['remote'];
            require_once(IA_ROOT . '/framework/library/alioss/autoload.php');
            load()->model('attachment');
            $endpoint = 'https://'.$remote['alioss']['ossurl'];
            try {
                $ossClient = new \OSS\OssClient($remote['alioss']['key'], $remote['alioss']['secret'], $endpoint);              
                $ossClient->uploadFile($remote['alioss']['bucket'],$filename, ATTACHMENT_ROOT.$filename);
            } catch (\OSS\Core\OssException $e) {
              //echo  'error--->'.$e->getMessage();
                return error(1, $e->getMessage());
              
            }
            if ($auto_delete_local) {
                unlink($filename);
            }
            //删除服务器上的上传文件
            unlink(ATTACHMENT_ROOT.$filename);
           $fname = $remote['alioss']['url'].'/'.$filename;
           output_data($fname);
            
        }else if($_W['setting']['remote']['type']==3 && $cunchu['type']==0)   //全局的远程存储 七牛云
        {
            /*
                上传文件名       $filekey     $_FILES['upfile']['name']
                上传文件的路径   $filePath    $_FILES['upfile']['tmp_name']
                上传凭证         $upToken    
            */
             require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');
             $qiniu = $_W['setting']['remote']['qiniu'];
             $accessKey=$qiniu['accesskey'];
             $secretKey=$qiniu['secretkey'];
             $bucket=$qiniu['bucket'];
             //转码时使用的队列名称
             // $pipeline = $qiniu['qn_queuename'];
              //$pipeline = 'yinyue';
             //要进行转码的转码操作
             $fops = "avthumb/mp4/ab/64k/ar/44100/acodec/libfaac";
             $auth = new Qiniu\Auth($accessKey, $secretKey); 

             $filekey=$_FILES['upfile']['name'];         //上传文件名
             $filePath=$_FILES['upfile']['tmp_name'];    //上传文件的路径

             //可以对转码后的文件进行使用saveas参数自定义命名，当然也可以不指定文件会默认命名并保存在当间
             $savekey =  Qiniu\base64_urlSafeEncode($bucket.':'.$filekey.'_1');
             $fops = $fops.'|saveas/'.$savekey;
             $policy = array(
                     'persistentOps' => $fops,
                     //'persistentPipeline' => $pipeline
             );
             $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);    //上传凭证
             //上传文件的本地路径
             $uploadMgr = new Qiniu\Storage\UploadManager();
             $ss = $uploadMgr->putFile($uptoken, $filekey, $filePath);
             load()->func("logging");
             $error=logging_run("qiniu:error".$err."成个");
             if ($err !== null) {
                 load()->func("logging");
                 logging_run("qiniu:error");
                 return false;
             }
             $fname=$qiniu['url'].'/'.$ss[0]['key'];
             output_data($fname);
        }elseif ($_W['setting']['remote']['type']==4 && $cunchu['type']==0) {  //全局的远程存储 腾讯云
            $cosurl = $_W['setting']['remote']['cos']['url'];
            $uptypes = array('image/jpg', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/gif', 'image/bmp', 'image/x-png');
            $max_file_size=2000000;     //上传文件大小限制, 单位BYTE  
            $destination_folder = '../attachment/images/'.$_W['uniacid'].'/'.date('Y/m/').'/';  //图片文件夹路径
            if (!is_uploaded_file($_FILES["upfile"]['tmp_name'])) //是否存在文件
            {
              echo "图片不存在!";  
                exit;  
            }  
            $file = $_FILES["upfile"];
            if($max_file_size < $file["size"])
            {  
                echo "文件太大!"; 
                exit;
            }  
            if(!in_array($file["type"], $uptypes))   //检查文件类型  
            {
                echo "文件类型不符!".$file["type"];
                exit;
            }
            if(!file_exists($destination_folder))
            {
              mkdir($destination_folder);
            }  

            $filename=$file["tmp_name"];  
            $image_size = getimagesize($filename);  
            $pinfo=pathinfo($file["name"]);  
            $ftype=$pinfo['extension'];  
            $destination = $destination_folder.time().".".$ftype;  
            if (file_exists($destination) && $overwrite != true)  
            {  
                echo "同名文件已经存在了";  
                exit;  
            }  
            if(!move_uploaded_file ($filename, $destination))  
            {  
                echo "移动文件出错";  
                exit;
            }
            $pinfo=pathinfo($destination);  
            $fname=$pinfo['basename'];  
        
            @require_once (IA_ROOT . '/framework/function/file.func.php');
            @$filename='images/'.$_W['uniacid'].'/'.date('Y/m/').'/'.$fname;
            @file_remote_upload($filename);
            $fname= $cosurl.'/images/'.$_W['uniacid'].'/'.date('Y/m/').$fname;
            output_data($fname);
        }
        else if ($cunchu['type']==2 && $_W['setting']['remote']['type']==0) {      //模块内的oss
            //将本地图片先上传到服务器
            load()->func('file');
            $file = $_FILES['upfile'];
            $filename = $file['tmp_name'];
            $destination_folder = '../attachment/images/'.$_W['uniacid'].'/'.date('Y/m/').'/';  //图片文件夹路径
            //创建存放图片的文件夹
            if (!is_dir($destination_folder)) {
               $res = mkdir($destination_folder, 0777, true);
            }
            if (!is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                echo '图片不存在!';
                die;
            }
            $file = $_FILES['upfile'];
            $filename = $file['tmp_name'];
            $pinfo = pathinfo($file['name']);
            $ftype = $pinfo['extension'];
            $destination = $destination_folder . str_shuffle(time() . rand(111111, 999999)) . '.' . $ftype;
            if (file_exists($destination) && $overwrite != true) {
                echo '同名文件已经存在了';
                die;
            }
            if (!move_uploaded_file($filename, $destination)) {
                echo '移动文件出错';
                die;
            }
            $pinfo = pathinfo($destination);
            $filename = 'images/'.$_W['uniacid'].'/'.date('Y/m/').$pinfo['basename'];

            //将服务器上的图片转移到阿里云oss
            
            require_once(IA_ROOT . '/framework/library/alioss/autoload.php');
            load()->model('attachment');
            $endpoint = 'https://'.$cunchu['alioss_ossurl'];
            try {
                $ossClient = new \OSS\OssClient($cunchu['alioss_key'], $cunchu['alioss_secret'], $endpoint);              
                $ossClient->uploadFile($cunchu['alioss_bucket'],$filename, ATTACHMENT_ROOT.$filename);
            } catch (\OSS\Core\OssException $e) {
             // echo  'error--->'.$e->getMessage();
                return error(1, $e->getMessage());
              
            }
            //删除服务器上的上传文件
            unlink(ATTACHMENT_ROOT.$filename);
           $fname = $cunchu['alioss_url'].'/'.$filename;
          output_data($fname);
        }
        else if ($cunchu['type']==3 && $_W['setting']['remote']['type']==0) {      //模块内的七牛
             /*
                上传文件名       $filekey     $_FILES['upfile']['name']
                上传文件的路径   $filePath    $_FILES['upfile']['tmp_name']
                上传凭证         $upToken    
            */
             require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');
             $accessKey=$cunchu['qiniu_accesskey'];
             $secretKey=$cunchu['qiniu_secretkey'];
             $bucket=$cunchu['qiniu_bucket'];
             //转码时使用的队列名称
             // $pipeline = $qiniu['qn_queuename'];
              //$pipeline = 'yinyue';
             //要进行转码的转码操作
             $fops = "avthumb/mp4/ab/64k/ar/44100/acodec/libfaac";
             $auth = new Qiniu\Auth($accessKey, $secretKey); 

             $filekey=$_FILES['upfile']['name'];         //上传文件名
             $filePath=$_FILES['upfile']['tmp_name'];    //上传文件的路径

             //可以对转码后的文件进行使用saveas参数自定义命名，当然也可以不指定文件会默认命名并保存在当间
             $savekey =  Qiniu\base64_urlSafeEncode($bucket.':'.$filekey.'_1');
             $fops = $fops.'|saveas/'.$savekey;
             $policy = array(
                     'persistentOps' => $fops,
                     //'persistentPipeline' => $pipeline
             );
             $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);    //上传凭证
             //上传文件的本地路径
             $uploadMgr = new Qiniu\Storage\UploadManager();
             $ss = $uploadMgr->putFile($uptoken, $filekey, $filePath);
             load()->func("logging");
             $error=logging_run("qiniu:error".$err."成个");
             if ($err !== null) {
                 load()->func("logging");
                 logging_run("qiniu:error");
                 return false;
             }
             $fname=$cunchu['qiniu_url'].'/'.$ss[0]['key'];
             output_data($fname);
         }
	}
	//留言
	public function doMobileLiuyan(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		if($_GPC['op']=='add'){
			$data = array();
			$data['uniacid'] =  $uniacid;
			$data['name'] = $_GPC['name'];
			$data['sfz'] = $_GPC['sfz'];
			$data['wxh'] = $_GPC['wxh'];
			$data['tel'] = $_GPC['tel'];
			$data['hy'] = $_GPC['hy'];
			$data['city'] = $_GPC['city'];
			$data['addtime'] = date("Y-m-d H:i:s",time());
			pdo_insert("lh_xiche_liuyan",$data);

			echo "<script>alert('提交成功')</script><script>window.location.href='". $this->createMobileUrl('liuyan')."'</script>";die;
		}
		include $this->template('liuyan');
	}
	//话费充值分享
	public function doMobilecz_fengxiang(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$data['u_id'] = $_SESSION['u_id'];
		$sss = pdo_get('lh_xiche_cz_set',['uniacid'=>$uniacid]);
		$base = pdo_get('lh_xiche_base',['uniacid'=>$uniacid]);
		$data['desc'] = $sss['fx_desc'];
		if(strpos($base['logo'],"http")===false&&!empty($base['logo'])){
			$base['logo'] = $_W['attachurl'].$base['logo'];
		}
		$data['thumb'] = $base['logo'];
		output_data($data);
	}

	public function doMobileZhuanpan(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$u_id = $_SESSION['u_id'];
		$op = !empty($_GPC['op'])?$_GPC['op']:"display";
		$list = pdo_getall('lh_xiche_zhuanpan',array('uniacid'=>$uniacid));
		$z_base = pdo_getall('lh_xiche_jfguizhe',array('uniacid'=>$uniacid));
		//
		$user_info = pdo_get('lh_xiche_user',array('uniacid'=>$uniacid,'id'=>$u_id));
		$zhuanpan_log = pdo_fetchall("SELECT * FROM ".tablename("lh_xiche_zhuanpan_log")." WHERE uniacid=$uniacid order by id desc limit 20");
		$zhuanpan_user_log = pdo_getall('lh_xiche_zhuanpan_log',array('uniacid'=>$uniacid,'u_id'=>$u_id));
		if(empty($user_info)){
			$user_info['jifen']=0;
		}
		// var_dump($list);
		if(empty($z_base)){
			$z_base['zhuanpan'] = 100;
		}
		if($op=='choujiang'){
			if($user_info['jifen']<$z_base['zhuanpan']){
				output_error('积分不足');
			}
			//最后确认相加等于100
			$times = 100;
			$max = 0;
			foreach ($list as $k => $v) {
			    $max = $v['gailv'] * $times + $max;
			    $row['v'] = $max;
			    $row['k'] = $k;
			    $row['name'] = $v['name'];
			    $prizeZone[] = $row;
			}
			$max--; //临界值
			$rand = mt_rand(0, $max);
			$zone = 1;
			foreach ($prizeZone as $k => $v) {
			    if ($rand >= $v['v']) {
			        if ($rand >= $prizeZone[$k + 1]['v']) {
			            continue;
			        } else {
			            $zone = $prizeZone[$k + 1]['k'];
			            break;
			        }
			    }
			    $zone = $v['k'];
			    break;
			}
			// 扣除积分
			$data = array();
			$data['uniacid'] =  $uniacid;
			$data['u_id'] = $u_id;
			$data['z_id'] = $list[$zone]['id'];
			$data['z_name'] = $list[$zone]['name'];
			$data['u_name'] = $user_info['name'];//phone
			$data['addtime'] = date("Y-m-d H:i:s",time());
			pdo_insert("lh_xiche_zhuanpan_log",$data);


	      $this->user_jifen_edit($u_id,-$z_base['zhuanpan'],"抽奖消费",5);
		  output_data($zone);
		}

		include $this->template('zhuanpan');
	} 
	// 积分修改
	public function user_jifen_edit($u_id,$nums,$content,$type){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		// 6  发布消耗
		// 5 抽奖消费
		//error_reporting(E_ALL);
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
	      $user_data = [];
	      $user_data['jifen +='] = $nums;
	      if($nums>0){
	      	$user_data['jifen_sum +='] = $nums;
	      }

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
				pdo_update('lh_xiche_user', $user_data, array('id' =>$u_id));
	      }else{
	      		pdo_update('lh_xiche_user', $user_data, array('id' =>$u_id));
	      }
	      
	}
	// 佣金修改
	public function user_yongjin_edit($u_id,$nums,$content,$type){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		// 6  发布消耗
		// 5 抽奖消费

			$data_yj = array();
			$data_yj['uniacid'] = $uniacid;
			$data_yj['u_id'] = $u_id;
			$data_yj['nums'] = $nums;
			$data_yj['content'] = $content;
			$data_yj['type'] = $type;
			$data_yj['addtime'] = date("Y-m-d H:i:s",time());
			pdo_insert("lh_xiche_yongjin_log",$data_yj);
			//更新微擎积分
			$user = pdo_get('lh_xiche_user', array('id' =>$u_id));
			$user_info = pdo_get('mc_mapping_fans', array('openid' =>$user['w_openid']));
			// $user_info = pdo_get('mc_mapping_fans', array('openid' =>'odNNX56C4qxV011KYm-39t2YQZrg'));
			// var_dump($user_info);
			$user_data = [];
			$user_data['money +='] = $nums;
			// if($nums>0){
			// 	$user_data['jifen_sum +='] = $nums;
			// }

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
				pdo_update('lh_xiche_user', $user_data, array('id' =>$u_id));
			}else{
					pdo_update('lh_xiche_user', $user_data, array('id' =>$u_id));
			}
			
	}


	public function doMobileTesta(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		require IA_ROOT.'/addons/lh_xiche/Payjs.php';

		$id = $_GPC['o_id'];
		$order_info = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_order")." where o_id=:id and uniacid=:uniacid ",array(":uniacid"=>$uniacid,":id"=>$id));
		// var_dump($order_info);die;
		$order = [
		    'body'         => '商品订单',         // 订单标题
		    'out_trade_no' => $order_info['ordersn'],         // 订单号
		    'total_fee'    => $order_info['count_money']*100,            // 金额,单位:分
		    // 'openid'       => 'oLw30sg23TALpZWY0fUM1zqqNmZc',  // 通过openid接口获取到的openid
		    'openid'       => $_GET['openid'],  // 通过openid接口获取到的openid
		    'notify_url'       => $_W['siteroot']."addons/lh_xiche/new_notify.php",  // 通过openid接口获取到的openid
		];
		$payjs = new Payjs($order);
		$rst = $payjs->pay();
		// 返回结果中包含`jsapi`字段，该字段的值即是前端发起时所需的6个支付参数
		$rst = json_decode($rst,true);
		// print_r($rst['jsapi']['appId']);die;

		$sucess_url = $_W['siteroot'].'app/'.$this->createMobileUrl('pay_sucess',array('o_id'=>$id,'attach'=>'wx_goods'));
echo    <<<EOB
<!DOCTYPE html>	
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<title>微信安全支付</title>
</head>
<body>
<script type="text/javascript">
function jsApiCall() {
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
         {
                    // 以下6个支付参数通过payjs的jsapi接口获取
                    // **************************
                    "appId": "{$rst['jsapi']['appId']}",
                    "timeStamp": "{$rst['jsapi']['timeStamp']}",
                    "nonceStr": "{$rst['jsapi']['nonceStr']}",
                    "package": "{$rst['jsapi']['package']}",
                    "signType": "{$rst['jsapi']['signType']}",
                    "paySign": "{$rst['jsapi']['paySign']}"
                    // **************************
        },
        function(res) {
            var h;
            if (res && res.err_msg == "get_brand_wcpay_request:ok") {
                // success;
                h = '{$sucess_url}';
                location.href = h;
            } else {
                // fail;
                h = '';
                location.href =window.history.go(-1);
            }

        }
    );
}
window.onload = function() {
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    } else {
        jsApiCall();
    }
}
</script>
</body>
</html>
EOB;
exit;

	}

	public function doMobileTest_login(){
		if(empty($_GET['openid'])){
			$url = 'http://'. $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI'];
			header("location:https://payjs.cn/api/openid?mchid=1578362031&callback_url=$url");die;
		}else{
			// ECHO "====";
			// echo $_GET['openid'];die;
			header("location:https://{$_SERVER['SERVER_NAME']}/app/index.php?i=3&c=entry&id=6&openid={$_GET['openid']}&o_id={$_GET['o_id']}&do=testa&m=lh_xiche");
		}
		
	}
	


	public function doMobileWeizhi(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$url = "https://apis.map.qq.com/ws/geocoder/v1/?get_poi=1&location={$_GPC['lat']},{$_GPC['lon']}&key=6JCBZ-7YXYD-FXS4A-HIOMM-VPU73-65BES";
		$body = file_get_contents($url);
		$body = json_decode($body,true);
		// output_data('北京市');
		// $body =$this->httpGet($url);
		output_data($body['result']['address_component']['city']);
	}

	// 平台收益   id  收益  备注   类型
	public function pingtai_shouyi($id,$money,$content,$type){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];

		$data = array(
			'uniacid'	=> $uniacid,
			'o_id'	=> $id,
			'money'	=> $money,
			'beizhu'	=> $content,
			'type'	=> $type,
			'addtime'	=> date('Y-m-d H:i:s'),
		);
		pdo_insert('lh_xiche_pingtai_shouyi',$data);

		output_data(array('id'=>pdo_insertid()));
	}


	public function doMobilehbCode(){
		output_data('http://app.icekun.com/attachment/images/3/2020/07/lT33lxLD2tDz52J2L15Idi3D2yPd4t.jpg');
	}
}

