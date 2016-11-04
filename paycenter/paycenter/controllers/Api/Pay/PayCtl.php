<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Pay_PayCtl extends Api_Controller
{
    /**
     * 验证API是否正确
     *
     * @access public
     */

    //测试接口
    public function addTest()
    {
        $test = request_string('test');

        $data['form'] = $test;
        $this->data->addBody(-140, $data);
    }

    //根据order_id查找paycenter中的订单信息与支付表信息
    public function getOrderInfo()
    {
        $order_id = request_string('order_id');

        $Union_OrderModel = new Union_OrderModel();
        $data = $Union_OrderModel->getByWhere(array('inorder' => $order_id));

        $this->data->addBody(-140, $data);

    }


    //添加交易订单信息
    public function addConsumeTrade()
    {
        $consume_trade_id     = request_string('consume_trade_id');
        $order_id             = request_string('order_id');
        $buy_id               = request_int('buy_id');
        $buyer_name			  = request_string('buyer_name');
        $seller_id            = request_int('seller_id');
        $seller_name		  = request_string('seller_name');
        $order_state_id       = request_int('order_state_id');
        $order_payment_amount = request_float('order_payment_amount');
        $trade_remark         = request_string('trade_remark');
        $trade_create_time    = request_string('trade_create_time');
        $trade_title		  = request_string('trade_title');
        $app_id               = request_int('from_app_id');

        //开启事物
        $Consume_TradeModel = new Consume_TradeModel();
        $Consume_TradeModel->sql->startTransactionDb();

        $add_row                         = array();
        $add_row['consume_trade_id']     = $consume_trade_id;
        $add_row['order_id']             = $order_id;
        $add_row['buyer_id']             = $buy_id;
        $add_row['seller_id']            = $seller_id;
        $add_row['order_state_id']       = $order_state_id;
        $add_row['order_payment_amount'] = $order_payment_amount;
        $add_row['trade_type_id']        = Trade_TypeModel::SHOPPING;
        $add_row['trade_remark']         = $trade_remark;
        $add_row['trade_create_time']    = $trade_create_time;
        $add_row['trade_amount']         = $order_payment_amount;
        $add_row['trade_payment_amount'] = $order_payment_amount;

        //1.生成交易订单
        $flag               = $Consume_TradeModel->addTrade($add_row);

        //2.生成合并支付订单
        $uorder      = "U" . date("Ymdhis", time()) . rand(100, 999);  //18位
        $union_add_row = array(
            'union_order_id' => $uorder,
            'inorder' => $order_id,
            'trade_title' => $trade_title,
            'trade_payment_amount' => $order_payment_amount,
            'create_time' => date("Y-m-d H:i:s"),
            'buyer_id' => $buy_id,
            'order_state_id' => Union_OrderModel::WAIT_PAY,
            'app_id' => $app_id,
            'trade_type_id' => Trade_TypeModel::SHOPPING,
        );

        $Union_OrderModel = new Union_OrderModel();
        $Union_OrderModel->addUnionOrder($union_add_row);

        //3.生成交易明细（付款方，收款方）
        $Consume_RecordModel = new Consume_RecordModel();
        $Trade_TypeModel = new Trade_TypeModel();
        $record_add_buy_row                  = array();
        $record_add_buy_row['order_id']      = $order_id;
        $record_add_buy_row['user_id']       = $buy_id;
        $record_add_buy_row['user_nickname'] = $buyer_name;
        $record_add_buy_row['record_money']  = $order_payment_amount;
        $record_add_buy_row['record_date']   = date('Y-m-d');
        $record_add_buy_row['record_year']	   = date('Y');
        $record_add_buy_row['record_month']	= date('m');
        $record_add_buy_row['record_day']		=date('d');
        $record_add_buy_row['record_title']  = $Trade_TypeModel->trade_type[Trade_TypeModel::SHOPPING];
        $record_add_buy_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_buy_row['trade_type_id'] = Trade_TypeModel::SHOPPING;
        $record_add_buy_row['user_type']     = 2;	//付款方
        $record_add_buy_row['record_status'] = RecordStatusModel::IN_HAND;

        $Consume_RecordModel->addRecord($record_add_buy_row);


        $record_add_seller_row                  = array();
        $record_add_seller_row['order_id']      = $order_id;
        $record_add_seller_row['user_id']       = $seller_id;
        $record_add_seller_row['user_nickname'] = $seller_name;
        $record_add_seller_row['record_money']  = $order_payment_amount;
        $record_add_seller_row['record_date']   = date('Y-m-d');
        $record_add_seller_row['record_year']	   = date('Y');
        $record_add_seller_row['record_month']	= date('m');
        $record_add_seller_row['record_day']		=date('d');
        $record_add_seller_row['record_title']  = $Trade_TypeModel->trade_type[Trade_TypeModel::SHOPPING];
        $record_add_seller_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_seller_row['trade_type_id'] = Trade_TypeModel::SHOPPING;
        $record_add_seller_row['user_type']     = 1;	//收款方
        $record_add_seller_row['record_status'] = RecordStatusModel::IN_HAND;

        $Consume_RecordModel->addRecord($record_add_seller_row);



        if ($flag && $Consume_TradeModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
            $data = array('union_order' => $uorder);
        }
        else
        {
            $Consume_TradeModel->sql->rollBackDb();
            $m      = $Consume_TradeModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
            $data = array();
        }

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //添加合并支付订单信息pay_union_order
    public function addUnionOrder()
    {
        //生成合并支付订单号
        $uorder      = "U" . date("Ymdhis", time()) . rand(100, 999);  //18位
        $inorder     = request_string('inorder');

        $inorder     = substr($inorder, 0, -1);
        $trade_title = request_string('trade_title');
        $uprice      = request_float('uprice');
        $buyer       = request_int('buyer');
        $app_id      = request_int('from_app_id');

        $add_row = array(
            'union_order_id' => $uorder,
            'inorder' => $inorder,
            'trade_title' => $trade_title,
            'trade_payment_amount' => $uprice,
            'create_time' => date("Y-m-d H:i:s"),
            'buyer_id' => $buyer,
            'order_state_id' => Union_OrderModel::WAIT_PAY,
            'app_id'=>$app_id,
            'trade_type_id' => Trade_TypeModel::SHOPPING,
        );

        $Union_OrderModel = new Union_OrderModel();
        $flag            = $Union_OrderModel->addUnionOrder($add_row);

        if ($flag)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }

        $data = array('uorder' => $uorder);
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //取消订单
    public function cancelOrder()
    {
        if(request_string('type') == 'row')
        {
            $order_id = request_row('order_id');
        }
        else
        {
            $order_id[] = request_string('order_id');
        }

        $Consume_TradeModel = new Consume_TradeModel();
        //开启事物
        $Consume_TradeModel->sql->startTransactionDb();

        //1.修改订单表（consume_trade）
        $Consume_TradeModel->editTrade($order_id,array('order_state_id' => Union_OrderModel::CANCEL));

        //2.修改交易明细(consume_record)
        $Consume_RecordModel = new Consume_RecordModel();
        $record_row = $Consume_RecordModel->getByWhere(array('order_id:IN' => $order_id));
        $record_id_row = array_column($record_row,'consume_record_id');
        $Consume_RecordModel->editRecord($record_id_row,array('record_status' => RecordStatusModel::RECORD_CANCEL));

        //2.合并支付表
        $Union_OrderModel = new Union_OrderModel();
        $union_row = $Union_OrderModel->getByWhere(array('inorder:IN' => $order_id));
        $uorder_id = array_column($union_row,'union_order_id');
        $flag = $Union_OrderModel->editUnionOrder($uorder_id,array('order_state_id' => Union_OrderModel::CANCEL));

        if ($flag && $Consume_TradeModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $Consume_TradeModel->sql->rollBackDb();
            $m      = $Consume_TradeModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //确认收货
    public function confirmOrder()
    {
        if(request_string('type') == 'row')
        {
            $order_id = request_row('order_id');
        }
        else
        {
            $order_id[] = request_string('order_id');
        }

        //1.修改订单表（consume_trade）
        $Consume_TradeModel = new Consume_TradeModel();

        //开启事物
        $Consume_TradeModel->sql->startTransactionDb();

        $Consume_TradeModel->editTrade($order_id,array('order_state_id' => Union_OrderModel::RECEIVED));

        //2.合并支付表
        $Union_OrderModel = new Union_OrderModel();
        $union_row = $Union_OrderModel->getByWhere(array('inorder:IN' => $order_id));
        $uorder_id = array_column($union_row,'union_order_id');
        $flag = $Union_OrderModel->editUnionOrder($uorder_id,array('order_state_id' => Union_OrderModel::RECEIVED));

        if ($flag && $Consume_TradeModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $Consume_TradeModel->sql->rollBackDb();
            $m      = $Consume_TradeModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //退款(虚拟商品过期，直接退款)
    public function refundTransfer()
    {
        $date = array();

        $user_id  = request_string('user_id');  //收款人
        $amount   = request_float('amount');        //付款金额
        $reason   = request_string('reason', '退款');  //付款说明
        $order_id = request_string('order_id');
        $goods_id = request_int('goods_id');

        //交易明细表
        $Consume_RecordModel = new Consume_RecordModel();
        //用户信息表
        $User_BaseModel = new User_BaseModel();
        //用户资源表
        $User_ResourceModel = new User_ResourceModel();

        if ($amount < 0)
        {
            $flag   = false;
            $date[] = '退款金额错误';
        }
        else
        {
            $user_resource = current($User_ResourceModel->getResource($user_id));

            fb($user_resource);

            $time    = time();
            $flow_id = time();

            //插入收款方的交易记录
            $record_row2 = array(
                'order_id' => $flow_id,
                'user_id' => $user_id,
                'record_money' => $amount,
                'record_date' => date("Y-m-d"),
                'record_year' => date("Y"),
                'record_month' => date("m"),
                'record_day' => date("d"),
                'record_title' => $reason,
                'record_desc' => "订单号:" . $order_id . "，商品id:" . $goods_id,
                'record_time' => date('Y-m-d h:i:s'),
                'trade_type_id' => '2',
                'user_type' => '1',
                'record_status' => RecordStatusModel::RECORD_FINISH,
                'record_paytime' => date('Y-m-d H:i:s'),
            );
            $flag1       = $Consume_RecordModel->addRecord($record_row2, true);

            if ($flag1)
            {
                //修改收款方的金额
                $user_resource_row['user_money'] = $user_resource['user_money'] + $amount;
                $flag                            = $User_ResourceModel->editResource($user_id, $user_resource_row);
            }
            else
            {
                $flag = false;
            }

        }

        if ($flag)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $date, $msg, $status);
    }

    //商家发货
    public function sendOrderGoods()
    {
        $order_id = request_string('order_id');

        //1.修改订单表（consume_trade）
        $Consume_TradeModel = new Consume_TradeModel();

        //开启事物
        $Consume_TradeModel->sql->startTransactionDb();

        $Consume_TradeModel->editTrade($order_id,array('order_state_id' => Union_OrderModel::WAIT_CONFIRM_GOODS));

        //2.合并支付表
        $Union_OrderModel = new Union_OrderModel();
        $union_row = $Union_OrderModel->getByWhere(array('inorder' => $order_id));
        $uorder_id = array_column($union_row,'union_order_id');
        $flag = $Union_OrderModel->editUnionOrder($uorder_id,array('order_state_id' => Union_OrderModel::WAIT_CONFIRM_GOODS));

        if ($flag && $Consume_TradeModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $Consume_TradeModel->sql->rollBackDb();
            $m      = $Consume_TradeModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //买家申请退货或者退款时生成退款交易明细
    public function returnMoney()
    {
        $buyer_id = request_int('buyer_user_id');
        $buyer_name = request_string('buyer_user_name');
        $seller_id = request_int('seller_user_id');
        $seller_name = request_string('seller_user_name');
        $amount = request_float('amount');

        //生成交易明细（付款方，收款方）
        $Consume_RecordModel = new Consume_RecordModel();
        $Trade_TypeModel = new Trade_TypeModel();
        $record_add_buy_row                  = array();
        $record_add_buy_row['user_id']       = $buyer_id;
        $record_add_buy_row['user_nickname'] = $buyer_name;
        $record_add_buy_row['record_money']  = $amount;
        $record_add_buy_row['record_date']   = date('Y-m-d');
        $record_add_buy_row['record_year']	   = date('Y');
        $record_add_buy_row['record_month']	= date('m');
        $record_add_buy_row['record_day']		=date('d');
        $record_add_buy_row['record_title']  = $Trade_TypeModel->trade_type[Trade_TypeModel::REFUND];
        $record_add_buy_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_buy_row['trade_type_id'] = Trade_TypeModel::REFUND;
        $record_add_buy_row['user_type']     = 1;	//1-收款方 2-付款方
        $record_add_buy_row['record_status'] = RecordStatusModel::IN_HAND;

        $Consume_RecordModel->addRecord($record_add_buy_row);


        $record_add_seller_row                  = array();
        $record_add_seller_row['user_id']       = $seller_id;
        $record_add_seller_row['user_nickname'] = $seller_name;
        $record_add_seller_row['record_money']  = $amount;
        $record_add_seller_row['record_date']   = date('Y-m-d');
        $record_add_seller_row['record_year']	   = date('Y');
        $record_add_seller_row['record_month']	= date('m');
        $record_add_seller_row['record_day']		=date('d');
        $record_add_seller_row['record_title']  = $Trade_TypeModel->trade_type[Trade_TypeModel::REFUND];
        $record_add_seller_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_seller_row['trade_type_id'] = Trade_TypeModel::REFUND;
        $record_add_seller_row['user_type']     = 2;	//收款方
        $record_add_seller_row['record_status'] = RecordStatusModel::IN_HAND;

        $Consume_RecordModel->addRecord($record_add_seller_row);

    }




}

?>