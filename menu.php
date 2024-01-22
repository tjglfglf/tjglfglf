<?php
defined('IN_IA') or exit ('Access Denied');
global $_GPC, $_W;
//此文件配置购买的产品
$uniacid = $_W['uniacid'];
if($uniacid==3){


$navemenu[16] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=fabu_type&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon> 发布管理</a>',
    'items' => array(
        1 => $this->createMainMenu('发布分类', $do, 'fabu_type', ''),
        2 => $this->createMainMenu('发布管理', $do, 'fabu', ''),
        // 3 => $this->createMainMenu('发布明细', $do, 'fabu_log', ''),
        4 => $this->createMainMenu('发布设置', $do, 'city', ''),
        5 => $this->createMainMenu('推广明细', $do, 'tuiguang_log', ''),
        6 => $this->createMainMenu('轮播图设置', $do, 'fabu_slide', ''),
    )
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

$navemenu[18] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=duobao_goods&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>夺宝管理</a>',
    'items' => array(
            1 => $this->createMainMenu('夺宝商品', $do, 'duobao_goods', ''),
            2 => $this->createMainMenu('夺宝分类', $do, 'duobao_type', ''),
            3 => $this->createMainMenu('中奖纪录', $do, 'duobao_order', ''),
            4 => $this->createMainMenu('夺宝设置', $do, 'duobao_set', ''),
            5 => $this->createMainMenu('夺宝码', $do, 'duobao_code', ''),
            6 => $this->createMainMenu('晒单管理', $do, 'duobao_saidan', ''),
            // 3 => $this->createMainMenu('资讯评价', $do, 'article_pingjia', ''),
    )
);


$navemenu[400] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=guanggao_set&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>广告管理</a>',
    'items' => [
                
                $this->createMainMenu('广告设置', $do, 'guanggao_set', ''),
                $this->createMainMenu('任务设置', $do, 'renwu_set', ''),
            ],
);
/*$navemenu[19] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=huashu&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>话术管理</a>',
    'items' => array(
            1 => $this->createMainMenu('话术列表', $do, 'huashu', ''),
            2 => $this->createMainMenu('话术分类', $do, 'huashu_type', ''),
            4 => $this->createMainMenu('话术设置', $do, 'huashu_set', ''),
            5 => $this->createMainMenu('卡密管理', $do, 'kami', ''),
    )
);

$navemenu[200] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=jz_job&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>兼职管理</a>',
    'items' => array(
            // 1 => $this->createMainMenu('岗位列表', $do, 'huashu', ''),
            2 => $this->createMainMenu('兼职信息', $do, 'jz_job', ''),
            // 3 => $this->createMainMenu('付费设置', $do, 'jz_fufei_set', ''),
            4 => $this->createMainMenu('兼职设置', $do, 'jz_job_set', ''),
    )
);

array_push($navemenu[4]['items'],$this->createMainMenu('招聘方申请', $do, 'jz_merchant', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('认证标志', $do, 'jz_renzheng', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('简历列表', $do, 'jz_resume', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('砍价列表', $do, 'jz_bargain', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('砍价设置', $do, 'jz_bargain_set', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('付费列表', $do, 'jz_fufei', ''));
array_push($navemenu[4]['items'],$this->createMainMenu('VIP设置', $do, 'jz_vip', ''));

*/
}
/*
*/




$navemenu[500] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=qiye&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>吃喝玩乐</a>',
    'items' => [
                $this->createMainMenu('旅游直通车', $do, 'zhitongche', ''),
                $this->createMainMenu('上榜企业', $do, 'qiye', ''),
				 $this->createMainMenu('活动咨询', $do, 'huodongzx', ''),
                $this->createMainMenu('景点管理', $do, 'jingdian&types=jingdian', ''),
                $this->createMainMenu('酒店管理', $do, 'jingdian&types=jiudian', ''),
                $this->createMainMenu('美食管理', $do, 'jingdian&types=meishi', ''),
                $this->createMainMenu('娱乐管理', $do, 'jingdian&types=yule', ''),
                $this->createMainMenu('购物管理', $do, 'jingdian&types=gouwu', ''),
                // $this->createMainMenu('影像管理', $do, 'jingdian&types=yingxiang', ''),
                $this->createMainMenu('土特产管理', $do, 'jingdian&types=tute', ''),
                $this->createMainMenu('VR管理', $do, 'jingdian&types=vr', ''),
            ],
);


$navemenu[501] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=gonglue&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>游玩攻略</a>',
    'items' => [
                $this->createMainMenu('游玩攻略', $do, 'gonglue', ''),
            ],
);
/*
$navemenu[502] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=qiye&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>VR管理</a>',
    'items' => [
                $this->createMainMenu('VR管理', $do, 'qiye', ''),
            ],
);*/

$navemenu[504] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=shipin&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>影像管理</a>',
    'items' => [
                $this->createMainMenu('影像管理', $do, 'shipin', ''),
                // $this->createMainMenu('VR管理', $do, 'vr', ''),
                // $this->createMainMenu('直播管理', $do, 'zhibo', ''),
            ],
);
$navemenu[505] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=ditu&m=lh_xiche" class="panel-title wytitle" id="yframe-200"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>地图标注</a>',
    'items' => [
                $this->createMainMenu('地图管理', $do, 'ditu', ''),
            ],
);

$navemenu[507] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=jingdian_order&m=lh_xiche" class="panel-title wytitle" id="yframe-5"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>景点订单</a>',
    'items' => array(
        0 => $this->createMainMenu('景点订单 ', $do, 'jingdian_order', ''),
    )
);

$navemenu[17] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=article&m=lh_xiche" class="panel-title wytitle" id="yframe-14"><icon style="color:#8d8d8d;" class="fa fa-cog"></icon>旅游资讯</a>',
    'items' => array(
            1 => $this->createMainMenu('旅游资讯', $do, 'article', ''),
            2 => $this->createMainMenu('资讯分类', $do, 'article_type', ''),

            // 3 => $this->createMainMenu('资讯评价', $do, 'article_pingjia', ''),
    )
);

if($uniacid==3){
$navemenu[508] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=cz_huafei&m=lh_xiche" class="panel-title wytitle" id="yframe-5"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>充值产品</a>',
    'items' => array(
        0 => $this->createMainMenu('话费产品 ', $do, 'cz_huafei', ''),
        1 => $this->createMainMenu('会员产品 ', $do, 'cz_huiyuan', ''),
        2 => $this->createMainMenu('流量产品 ', $do, 'cz_liuliang', ''),
        3 => $this->createMainMenu('产品分类 ', $do, 'cz_type', ''),
        4 => $this->createMainMenu('红包设置 ', $do, 'cz_hongbao_set', ''),
        5 => $this->createMainMenu('充值设置 ', $do, 'cz_set', ''),
    )
);
$navemenu[509] = array(
    'title' => '<a href="index.php?c=site&a=entry&op=display&do=cz_order&m=lh_xiche" class="panel-title wytitle" id="yframe-5"><icon style="color:#8d8d8d;" class="fa fa-bars"></icon>充值订单</a>',
    'items' => array(
        0 => $this->createMainMenu('充值订单 ', $do, 'cz_order', ''),
    )
);
}



