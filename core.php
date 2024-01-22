<?php
defined('IN_IA') or exit ('Access Denied');

class Core extends WeModuleSite
{

    public function getMainMenu()
    {
        global $_W, $_GPC;

        $_W['pt_type'] =$base['pt_type'];
        $do = $_GPC['do'];
        $uniacid = $_W['uniacid'];
        $navemenu = array();
        $cur_color = ' style="color:#d9534f;" ';

            $navemenu[0] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=welcome&m=lh_xiche" class="panel-title wytitle" id="yframe-0"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>  数据概况</a>',
                'items' => array(
                    0 => $this->createMainMenu('数据概况', $do, 'welcome', ''),
                )
            );
                        $navemenu[4] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=user&m=lh_xiche" class="panel-title wytitle" id="yframe-4"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>会员管理</a>',
                'items' => array(
                    0 => $this->createMainMenu('会员列表 ', $do, 'user', ''), 
                    1 => $this->createMainMenu('会员等级 ', $do, 'usertype', ''),              
                    3 => $this->createMainMenu('积分日志 ', $do, 'jifen_log', ''),/**/              
                                
                )
            );
    
   /*         $navemenu[1] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=goods&m=lh_xiche" class="panel-title wytitle" id="yframe-1"><icon style="color:#8d8d8d;" class="fa fa-life-ring"></icon>商品管理</a>',
                'items' => array(
                    0 => $this->createMainMenu('商品列表 ', $do, 'goods', ''),
                    1 => $this->createMainMenu('商品分类', $do, 'goodsstyle', ''),
                    2=> $this->createMainMenu('商品设置', $do, 'goods_set', ''),
                   // 2 => $this->createMainMenu('商品保障列表', $do, 'goods_baozhang', ''),
                )
            );
*/

                $navemenu[5] = array(
                    'title' => '<a href="index.php?c=site&a=entry&op=display&do=orderjifen&m=lh_xiche" class="panel-title wytitle" id="yframe-5"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>积分兑换订单</a>',
                    'items' => array(

                        3 => $this->createMainMenu('积分兑换订单', $do, 'orderjifen', ''),
                        // 4 => $this->createMainMenu('代下单', $do, 'daixiadan', ''),
                    )
                );
                $navemenu[52] = array(
                    'title' => '<a href="index.php?c=site&a=entry&op=display&do=cars_orders&m=lh_xiche" class="panel-title wytitle" id="yframe-5"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>洗车订单</a>',
                    'items' => array(

                        3 => $this->createMainMenu('洗车订单', $do, 'cars_orders', ''),
                        // 4 => $this->createMainMenu('代下单', $do, 'daixiadan', ''),
                    )
                );
   

            $navemenu[8] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=jfgoods&m=lh_xiche" class="panel-title wytitle" id="yframe-8"><icon style="color:#8d8d8d;" class="fa fa-star-half-o"></icon> 积分商城</a>',
                'items' => array(
                    0 => $this->createMainMenu('商品列表', $do, 'jfgoods', ''),
                    1 => $this->createMainMenu('积分幻灯片', $do, 'jfthumb', ''),
                    2 => $this->createMainMenu('导航分类', $do, 'jftype', ''),
       /*             3 => $this->createMainMenu('积分获取规则', $do, 'yinxiaobase', ''),
                    4 => $this->createMainMenu('定点打卡设置', $do, 'saoma', ''),/**/ 
                )
            );
            $navemenu[9] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=tixian&m=lh_xiche" class="panel-title wytitle" id="yframe-9"><icon style="color:#8d8d8d;" class="fa fa-users"></icon> 提现申请</a>',
                'items' => array(
                    2 => $this->createMainMenu('提现申请', $do, 'tixian', ''),
                    3 => $this->createMainMenu('资金明细', $do, 'yongjin_log', ''),
                )
            );
              /* $navemenu[10] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=youhuiquan&m=lh_xiche" class="panel-title wytitle" id="yframe-10"><icon style="color:#8d8d8d;" class="fa fa-gift"></icon> 营销设置</a>',
                'items' => array(
                    // 0 => $this->createMainMenu('满减活动', $do, 'yingxiao', ''),
                    0 => $this->createMainMenu('优惠券', $do, 'youhuiquan', ''),
                    2 => $this->createMainMenu('大转盘', $do, 'zhuanpan', ''),
                )
            );*/
            $navemenu[11] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=pingjiagoods&m=lh_xiche" class="panel-title wytitle" id="yframe-11"><icon style="color:#8d8d8d;" class="fa fa-comments"></icon> 评价管理</a>',
                'items' => array(
                    1 => $this->createMainMenu('评论管理', $do, 'pingjiagoods', ''),
                    2 => $this->createMainMenu('投诉建议', $do, 'pingjia', ''),
                )
            ); 


            $navemenu[13] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=gonggao&m=lh_xiche" class="panel-title wytitle" id="yframe-13"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 公告设置</a>',
                'items' => array(
                    0 => $this->createMainMenu('公告设置', $do, 'gonggao', ''),
                    // 1 => $this->createMainMenu('活动设置', $do, 'huodong', ''),
                )
            );
            
            $navemenu[14] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=peiz&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 系统设置</a>',
                'items' => array(
                    0 => $this->createMainMenu('微信配置', $do, 'peiz', ''),
                     1 => $this->createMainMenu('证书配置', $do, 'pay', ''),
                    // 2 => $this->createMainMenu('模板消息', $do, 'template_wx', ''), 
                    // 11 => $this->createMainMenu('订阅消息', $do, 'template', ''), 
                    // 3 => $this->createMainMenu('短信通知', $do, 'new', ''), 
                    4 => $this->createMainMenu('基本设置', $do, 'base', ''),  
                    // 6 => $this->createMainMenu('模块存储', $do, 'cunchu', ''),
                    7 => $this->createMainMenu('使用帮助', $do, 'questions', ''), 
                     // 8 => $this->createMainMenu('前台入口', $do, 'rukou', ''), 
                    // 9 => $this->createMainMenu('首页头部菜单', $do, 'menutop', ''), 
                    10 => $this->createMainMenu('底部菜单', $do, 'menu', ''), 
                )
            );
/*            $navemenu[100] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=quanxian&m=lh_xiche" class="panel-title wytitle" id="yframe-13"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 权限管理</a>',
                'items' => array(
                    0 => $this->createMainMenu('角色管理', $do, 'juese', ''),
                   1 => $this->createMainMenu('账户管理', $do, 'quanxian', ''),
                )
            );*/


/* */           $navemenu[15] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=cz_set&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 充值设置</a>',
                'items' => array(
                    1 => $this->createMainMenu('充值设置', $do, 'cz_set', ''),

                )
            );

/*            $navemenu[16] = array(
                'title' => '<a href="index.php?c=site&a=entry&op=display&do=caiwu&m=lh_xiche" class="panel-title wytitle" id="yframe-16"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 财务统计</a>',
                'items' => array(
                    0 => $this->createMainMenu('订单明细', $do, 'caiwu', ''),
                    // 1 => $this->createMainMenu('收入统计', $do, 'tongji', ''),
                )
            );*/
         @include("men*.php");
array_push($navemenu[14]['items'],$this->createMainMenu('轮播图设置', $do, 'slide', ''));
if($uniacid==3){}

    $navemenu[506] = array(
        'title' => '<a href="index.php?c=site&a=entry&op=display&do=shangjia&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>商家管理</a>',
        'items' => [
                    // $this->createMainMenu('商家分类', $do, 'shangjia_type', ''),
                    $this->createMainMenu('商家列表', $do, 'shangjia', ''),
           /*         $this->createMainMenu('商家明细', $do, 'shangjia_log', ''),
                    $this->createMainMenu('入驻设置', $do, 'shangjiaruzhu', ''),
                    $this->createMainMenu('商家设置', $do, 'shangjia_set', ''),*/
                ],
    );

$navemenu[504] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=shipin&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>视频管理</a>',
    'items' => [
                $this->createMainMenu('视频管理', $do, 'shipin', ''),
            ],
);
$navemenu[17] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=article&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>文章管理</a>',
    'items' => array(
            1 => $this->createMainMenu('文章列表', $do, 'article', ''),
            2 => $this->createMainMenu('文章分类', $do, 'article_type', ''),
            //3 => $this->createMainMenu('文章采集', $do, 'article_caiji', ''),
            //4 => $this->createMainMenu('其他设置', $do, 'article_set', ''),
            // 3 => $this->createMainMenu('资讯评价', $do, 'article_pingjia', ''),
    )
);

        //查询登录用户
        $yonghu = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_perms_user")." WHERE uniacid=:uniacid and uid=:uid and user=:user",array(":uniacid"=>$_W['uniacid'],":uid"=>$_W['uid'],":user"=>$_W['username']));
            //查询权限
        if(!empty($yonghu)){
        $quanxian = pdo_fetch("SELECT * FROM ".tablename("lh_xiche_perms")." WHERE uniacid=:uniacid and id=:id",array(":uniacid"=>$_W['uniacid'],":id"=>$yonghu['juese']));
        $quanxian['perms'] = unserialize($quanxian['perms']);
            foreach ($navemenu as $key => $value) {
                $a = false;
                foreach ($quanxian['perms'] as   $k =>$val) {
                    if(strpos($value['title'],$val) !== false){ 
                        $a = true;
                    }
                }
                if(!$a){
                    unset($navemenu[$key]);
                }
            }
        }
        return $navemenu;
    }

    function createMainMenu($title, $do, $method, $icon = "fa-image", $color = '')
    {

        $color = ' style="color:'.$color.';" ';

        return array('title' => $title, 'url' => $do != $method ? $this->createWebUrl($method, array('op' => 'display')) : '',
            'active' => $do == $method ? ' active' : '',
            'append' => array(
                'title' => '<i '.$color.' class="fa fa-angle-right"></i>',
            )
        );
    }



    function createSubMenu($title, $do, $method, $icon = "fa-image", $color = '#d9534f', $uniacid,$storeid)
    {
        $color = ' style="color:'.$color.';" ';
        $url = $this->createWebUrl2($method, array('op' => 'display', 'uid' => $uniacid,"id"=>$storeid));
        return array('title' => $title, 'url' => $do != $method ? $url : '',
            'active' => $do == $method ? ' active' : '',
            'append' => array(
                'title' => '<i class="fa '.$icon.'"></i>',
            )
        );
    }

}