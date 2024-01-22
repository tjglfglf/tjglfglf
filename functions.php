<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//加载模型
if (!function_exists('m')) {
    function m($name = '')
    {

        static $_modules = array();
        if (isset($_modules[$name])) {

            return $_modules[$name];
        }
       $model = IA_ROOT."/addons/lh_xiche/inc/model/" . strtolower($name) . '.php';

        if (!is_file($model)) {
            die(' Model ' . $name . ' Not Found!');
        }

        require_once $model;
        $class_name = ucfirst($name) . "_Model";
        $_modules[$name] = new $class_name();
        return $_modules[$name];
    }
}



/**
 * 根据经纬度，计算距离
 */
function get_distance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6378137;
    $EARTH_RADIUS = 6378137;   //地球半径
    $RAD = pi() / 180.0;

    $radLat1 = $lat1 * $RAD;
    $radLat2 = $lat2 * $RAD;
    $a = $radLat1 - $radLat2;    // 两点纬度差
    $b = ($lng1 - $lng2) * $RAD;  // 两点经度差
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $EARTH_RADIUS;
   return $s = round($s * 10000) / 10000;


    $lat1 = ($lat1 * pi()) / 180;
    $lng1 = ($lng1 * pi()) / 180;

    $lat2 = ($lat2 * pi()) / 180;
    $lng2 = ($lng2 * pi()) / 180;

    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;

    return round($calculatedDistance);
}

/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
 function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}

/**
 * 返回JSON格式数据
 * @param type $status
 * @param type $return
 */
function output_data($datas, $extend_data = array(), $error = false) {
    $data = array();
    $data['code'] = 200;
    if($error) {
        $data['code'] = 400;
    }

    if(!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }

    $data['datas'] = $datas;


    header('Content-type: application/json; charset=utf-8');
        // header('Content-type: text/plain; charset=utf-8');
    


    if (!empty($_GET['callback'])) {
        echo $_GET['callback'].'('.json_encode($data).')';die;
    } else {
        // header("Access-Control-Allow-Origin:*");
        echo json_encode($data);die;
    }
}

function output_error($datas, $extend_data = array()) {
    $data = array();
    $data['code'] = 400;
    $data['datas'] = $datas;


    header('Content-type: application/json; charset=utf-8');
        // header('Content-type: text/plain; charset=utf-8');


    if (!empty($_GET['callback'])) {
        echo $_GET['callback'].'('.json_encode($data, $jsonFlag).')';die;
    } else {
        header("Access-Control-Allow-Origin:*");
        echo json_encode($data, $jsonFlag);die;
    }
}

function setMyCookie($name, $value, $expire='3600', $path='', $domain='', $secure=false){
    if (empty($path)) $path = '/';
    if (empty($domain)) $domain = SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '';
    $expire = intval($expire)?intval($expire):(intval(SESSION_EXPIRE)?intval(SESSION_EXPIRE):3600);
    $result = setcookie($name, $value, time()+$expire, $path, $domain, $secure);
    $_COOKIE[$name] = $value;
}


    // 下载文件函数
    function down_remote_file($url,$save_dir='',$filename='',$type=1){

        if(trim($url)==''){
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./';
        }
        if(trim($filename)==''){//保存文件名
            $ext=strrchr($url,'.');

            $filename=time().$ext;
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }

        if($type){
            $ch=curl_init();
            $timeout=100;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            // curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//是否显示头信息
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//重复跳转
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start(); 
            @readfile($url);
            $img=ob_get_contents(); 
            ob_end_clean(); 
        }
        //$size=strlen($img);
        //文件大小 
        $fp2=fopen($save_dir.$filename,'w');

        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
    }

    //解压zip文件并覆盖
    function get_zip_originalsize($filename, $path) {
      //先判断待解压的文件是否存在
      if(!file_exists($filename)){
        return false;
      }
      $starttime = explode(' ',microtime()); //解压开始的时间

      //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
      $filename = iconv("utf-8","gb2312",$filename);
      $path = iconv("utf-8","gb2312",$path);
      //打开压缩包
      $resource = zip_open($filename);
      $i = 1;
      //遍历读取压缩包里面的一个个文件
      while ($dir_resource = zip_read($resource)) {
        //如果能打开则继续
        if (zip_entry_open($resource,$dir_resource)) {
          //获取当前项目的名称,即压缩包里面当前对应的文件名
          $file_name = $path.zip_entry_name($dir_resource);
          //以最后一个“/”分割,再用字符串截取出路径部分
          $file_path = substr($file_name,0,strrpos($file_name, "/"));
          //如果路径不存在，则创建一个目录，true表示可以创建多级目录
          if(!is_dir($file_path)){
            mkdir($file_path,0777,true);
          }
          //如果不是目录，则写入文件
          if(!is_dir($file_name)){
            //读取这个文件
            $file_size = zip_entry_filesize($dir_resource);
            //最大读取6M，如果文件过大，跳过解压，继续下一个
            if($file_size<(1024*1024*30)){
              $file_content = zip_entry_read($dir_resource,$file_size);
              file_put_contents($file_name,$file_content);
            }else{
              echo "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312","utf-8",$file_name)." </p>";
            }
          }
          //关闭当前
          zip_entry_close($dir_resource);
        }
      }
      //关闭压缩包
      zip_close($resource);
      $endtime = explode(' ',microtime()); //解压结束的时间
      $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
      $thistime = round($thistime,3); //保留3为小数
      return true;
    }

//过滤html标签
function cutstr_html($string){  

    $string = html_entity_decode($string);  
    $string = strip_tags($string);  

    $string = trim($string);  

    $string = ereg_replace("\t","",$string);  

    $string = ereg_replace("\r\n","",$string);  

    $string = ereg_replace("\r","",$string);  

    $string = ereg_replace("\n","",$string);  

    $string = ereg_replace(" ","",$string);  

    return trim($string);  

}

/**
* 
* 中英混合的字符串截取
* @param 待截取字符串 $sourcestr
* @param 截取长度 $cutlength
*/
function sub_str($sourcestr, $cutlength) {
  $returnstr = '';//待返回字符串
  $i = 0;
  $n = 0;
  $str_length = strlen ( $sourcestr ); //字符串的字节数 
  while ( ($n < $cutlength) and ($i <= $str_length) ) {
    $temp_str = substr ( $sourcestr, $i, 1 );
    $ascnum = Ord ( $temp_str ); //得到字符串中第$i位字符的ascii码 
    if ($ascnum >= 224) {//如果ASCII位高与224，
      $returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); //根据UTF-8编码规范，将3个连续的字符计为单个字符  
      $i = $i + 3; //实际Byte计为3
      $n ++; //字串长度计1
    } elseif ($ascnum >= 192){ //如果ASCII位高与192，
      $returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
      $i = $i + 2; //实际Byte计为2
      $n ++; //字串长度计1
    } elseif ($ascnum >= 65 && $ascnum <= 90) {//如果是大写字母，
      $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
      $i = $i + 1; //实际的Byte数仍计1个
      $n ++; //但考虑整体美观，大写字母计成一个高位字符
    }elseif ($ascnum >= 97 && $ascnum <= 122) {
      $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
      $i = $i + 1; //实际的Byte数仍计1个
      $n ++; //但考虑整体美观，大写字母计成一个高位字符
    } else {//其他情况下，半角标点符号，
      $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
      $i = $i + 1; 
      $n = $n + 0.5; 
    }
  }
  return $returnstr;
}

function get_ip() {
    //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    return $res;
    //dump(phpinfo());//所有PHP配置信息
}