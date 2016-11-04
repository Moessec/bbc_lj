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

$Order_SettlementModel = new Order_SettlementModel();
//开启事物
$Order_SettlementModel->sql->startTransactionDb();
//调用封装好的结算功能
//1.实物订单结算
$Order_SettlementModel->settleNormalOrder();

//2.虚拟订单结算
$Order_SettlementModel->settleVirtualOrder();

if ($Order_SettlementModel->sql->commitDb())
{
	$msg    = _('success');
	$status = 200;
	$flag = true;
}
else
{
	$Order_SettlementModel->sql->rollBackDb();
	$m      = $Order_SettlementModel->msg->getMessages();
	$msg    = $m ? $m[0] : _('failure');
	$status = 250;
	$flag = false;
}


return $flag;
?>