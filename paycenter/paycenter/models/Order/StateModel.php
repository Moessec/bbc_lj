<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_StateModel
{
	const ORDER_WAIT_PAY = 1;                //待付款     等待买家付款	     下单
	const ORDER_PAYED = 2;                   //待配货     等待卖家配货	     付款
	const ORDER_WAIT_PREPARE_GOODS = 3;      //待发货     等待卖家发货	     配货
	const ORDER_WAIT_CONFIRM_GOODS = 4;      //已发货     等待买家确认收货	 出库
	const ORDER_RECEIVED = 5;                //已签收     买家已签收	         已签收
	const ORDER_FINISH = 6;                  //已完成     交易成功	         交易成功
	const ORDER_CANCEL = 7;                  //已取消     交易关闭	         交易关闭

}
?>