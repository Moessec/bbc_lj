<?php
if (!defined('ROOT_PATH'))
{
    if (is_file('../../../shop/configs/config.ini.php'))
    {
        require_once '../../../shop/configs/config.ini.php';
    }
    else
    {
        die('请先运行index.php,生成应用程序框架结构！');
    }

    //不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
    $Base_CronModel = new Base_CronModel();
    $rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

    //终止执行下面内容, 否则会执行两次
    return ;
}


Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

fb($crontab_file);


    //执行任务
    $falgs = array();
    $goodsCommonModel = new Goods_CommonModel();
    //查找出定时发布的商品
    $conid['common_state'] = Goods_CommonModel::GOODS_STATE_TIMING;
    $common_base = $goodsCommonModel->getByWhere( $conid );

    foreach($common_base as $key => $val)
    {
        $now_time = time();     // 当前时间

        $common_sell_time = strtotime($val['common_sell_time']);    //商品发布时间

        if ( $common_sell_time <= $now_time )
        {
            $common_id = $val['common_id'];
            $update_data['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;

            $falg = $goodsCommonModel->editCommon($common_id, $update_data);
            $falgs[$common_id] = $falg;
        }
    }

    fb($falgs);

	return true;
?>