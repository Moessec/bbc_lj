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
class Api_Trade_ReturnCtl extends Api_Controller
{

	const PAY_SITE = "http://paycenter.yuanfeng021.com/";
	//const PAY_SITE	 = "http://localhost/repos/paycenter/";
	public $Order_BaseModel         = null;
	public $Order_ReturnModel       = null;
	public $Order_ReturnReasonModel = null;
	public $Order_GoodsModel        = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->Order_BaseModel         = new Order_BaseModel();
		$this->Order_ReturnModel       = new Order_ReturnModel();
		$this->Order_ReturnReasonModel = new Order_ReturnReasonModel();
		$this->Order_GoodsModel        = new Order_GoodsModel();

	}

	public function getReasonList()
	{
		$page                             = request_int('page', 1);
		$rows                             = request_int('rows', 10);
		$oname                            = request_string('sidx');
		$osort                            = request_string('sord');
		$cond_row                         = array();
		$sort                             = array();
		$sort['order_return_reason_sort'] = "ASC";
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$data = array();
		$data = $this->Order_ReturnReasonModel->getReturnReasonList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function addReasonBase()
	{
		$field['order_return_reason_content'] = request_string("order_return_reason_content");
		$field['order_return_reason_sort']    = request_int("order_return_reason_sort");
		$flag                                 = $this->Order_ReturnReasonModel->addReturn($field, true);
		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editReason()
	{
		$id   = request_int("id");
		$data = $this->Order_ReturnReasonModel->getOne($id);
		$this->data->addBody(-140, $data);
	}

	public function editReasonBase()
	{
		$id                                   = request_int("order_return_reason_id");
		$field['order_return_reason_content'] = request_string("order_return_reason_content");
		$field['order_return_reason_sort']    = request_int("order_return_reason_sort");
		$flag                                 = $this->Order_ReturnReasonModel->editReturn($id, $field);
		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function delReason()
	{
		$id   = request_int("id");
		$flag = $this->Order_ReturnReasonModel->removeReturn($id);
		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getReturnWaitList()
	{
		$type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
		$return_code         = request_string("return_code");
		$seller_user_account = request_string("seller_user_account");
		$buyer_user_account  = request_string("buyer_user_account");
		$order_goods_name    = request_string("order_goods_name");
		$order_number        = request_string("order_number");
		$start_time          = request_string("start_time");
		$end_time            = request_string("end_time");
		$min_cash            = request_float("min_cash");
		$max_cash            = request_float("max_cash");

		$page     = request_int('page', 1);
		$rows     = request_int('rows', 10);
		$oname    = request_string('sidx');
		$osort    = request_string('sord');
		$cond_row = array();
		$sort     = array();
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
		if ($seller_user_account)
		{
			$cond_row['seller_user_account'] = $seller_user_account;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account'] = $buyer_user_account;
		}
		if ($order_goods_name)
		{
			$cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
		}
		if ($start_time)
		{
			$cond_row['return_add_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['return_add_time:<='] = $end_time;
		}
		if ($min_cash)
		{
			$cond_row['return_cash:>='] = $min_cash;
		}
		if ($max_cash)
		{
			$cond_row['return_cash:<='] = $max_cash;
		}
		$cond_row['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
		$cond_row['return_type']  = $type;
		$data                     = array();
		$data                     = $this->Order_ReturnModel->getReturnList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}


	public function getReturnWaitExcel()
	{
		$type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
		$return_code         = request_string("return_code");
		$seller_user_account = request_string("seller_user_account");
		$buyer_user_account  = request_string("buyer_user_account");
		$order_goods_name    = request_string("order_goods_name");
		$order_number        = request_string("order_number");
		$start_time          = request_string("start_time");
		$end_time            = request_string("end_time");
		$min_cash            = request_float("min_cash");
		$max_cash            = request_float("max_cash");

		$oname    = request_string('sidx');
		$osort    = request_string('sord');
		$cond_row = array();
		$sort     = array();
//        if($oname != "number") {
//            $sort[$oname] = $osort;
//        }

		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
		if ($seller_user_account)
		{
			$cond_row['seller_user_account'] = $seller_user_account;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account'] = $buyer_user_account;
		}
		if ($order_goods_name)
		{
			$cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
		}
		if ($start_time)
		{
			$cond_row['return_add_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['return_add_time:<='] = $end_time;
		}
		if ($min_cash)
		{
			$cond_row['return_cash:>='] = $min_cash;
		}
		if ($max_cash)
		{
			$cond_row['return_cash:<='] = $max_cash;
		}
		$cond_row['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
		$cond_row['return_type']  = $type;
		$con                      = array();
		$con                      = $this->Order_ReturnModel->getReturnExcel($cond_row, $sort);
		$tit                      = array(
			"序号",
			"退单编号",
			"退单金额",
			"申请原因",
			"申请时间",
			"涉及商品",
			"商家处理备注",
			"商家处理时间",
			"订单编号",
			"买家",
			"商家"
		);
		$key                      = array(
			"return_code",
			"return_cash",
			"return_reason",
			"return_add_time",
			"order_goods_name",
			"return_shop_message",
			"return_shop_time",
			"order_number",
			"buyer_user_account",
			"seller_user_account"
		);
		$this->excel("退款退货单", $tit, $con, $key);
	}


	public function getReturnAllList()
	{
		$type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
		$return_code         = request_string("return_code");
		$seller_user_account = request_string("seller_user_account");
		$buyer_user_account  = request_string("buyer_user_account");
		$order_goods_name    = request_string("order_goods_name");
		$order_number        = request_string("order_number");
		$start_time          = request_string("start_time");
		$end_time            = request_string("end_time");
		$min_cash            = request_float("min_cash");
		$max_cash            = request_float("max_cash");

		$page     = request_int('page', 1);
		$rows     = request_int('rows', 10);
		$oname    = request_string('sidx');
		$osort    = request_string('sord');
		$cond_row = array();
		$sort     = array();
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
		if ($seller_user_account)
		{
			$cond_row['seller_user_account'] = $seller_user_account;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account'] = $buyer_user_account;
		}
		if ($order_goods_name)
		{
			$cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
		}
		if ($start_time)
		{
			$cond_row['return_add_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['return_add_time:<='] = $end_time;
		}
		if ($min_cash)
		{
			$cond_row['return_cash:>='] = $min_cash;
		}
		if ($max_cash)
		{
			$cond_row['return_cash:<='] = $max_cash;
		}
		$cond_row['return_type'] = $type;
		$data                    = array();
		$data                    = $this->Order_ReturnModel->getReturnList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function getReturnAllExcel()
	{
		$type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
		$return_code         = request_string("return_code");
		$seller_user_account = request_string("seller_user_account");
		$buyer_user_account  = request_string("buyer_user_account");
		$order_goods_name    = request_string("order_goods_name");
		$order_number        = request_string("order_number");
		$start_time          = request_string("start_time");
		$end_time            = request_string("end_time");
		$min_cash            = request_float("min_cash");
		$max_cash            = request_float("max_cash");

		$oname    = request_string('sidx');
		$osort    = request_string('sord');
		$cond_row = array();
		$sort     = array();
//        if($oname != "number") {
//            $sort[$oname] = $osort;
//        }

		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
		if ($seller_user_account)
		{
			$cond_row['seller_user_account'] = $seller_user_account;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account'] = $buyer_user_account;
		}
		if ($order_goods_name)
		{
			$cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
		}
		if ($start_time)
		{
			$cond_row['return_add_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['return_add_time:<='] = $end_time;
		}
		if ($min_cash)
		{
			$cond_row['return_cash:>='] = $min_cash;
		}
		if ($max_cash)
		{
			$cond_row['return_cash:<='] = $max_cash;
		}
		$cond_row['return_type'] = $type;
		$con                     = array();
		$con                     = $this->Order_ReturnModel->getReturnExcel($cond_row, $sort);
		$this->data->addBody(-140, $con);
		$tit = array(
			"序号",
			"退单编号",
			"退单金额",
			"申请原因",
			"申请时间",
			"涉及商品",
			"商家处理备注",
			"商家处理时间",
			"订单编号",
			"买家",
			"商家"
		);
		$key = array(
			"return_code",
			"return_cash",
			"return_reason",
			"return_add_time",
			"order_goods_name",
			"return_shop_message",
			"return_shop_time",
			"order_number",
			"buyer_user_account",
			"seller_user_account"
		);
		$this->excel("退款退货单", $tit, $con, $key);
	}

	public function detail()
	{
		$data['id']    = request_int('id');
		$id            = request_int('id');
		$data          = $this->Order_ReturnModel->getReturnBase($id);
		$data['order'] = $this->Order_BaseModel->getOne($data['order_number']);
		$this->data->addBody(-140, $data);
	}

	public function agree()
	{
		$Order_StateModel        = new Order_StateModel();
		$order_return_id         = request_int("order_return_id");
		$return_platform_message = request_string("return_platform_message");
		$return                  = $this->Order_ReturnModel->getOne($order_return_id);
		fb($return);

		//根据order_id查找订单信息
		$order_base = $this->Order_BaseModel->getOne($return['order_number']);

		$data['return_platform_message'] = $return_platform_message;
		$data['return_state']            = Order_ReturnModel::RETURN_PLAT_PASS;
		$data['return_finish_time']      = get_date_time();
		$rs_row                          = array();
		$this->Order_ReturnModel->sql->startTransactionDb();
		$edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
		check_rs($edit_flag, $rs_row);

		if ($return['order_goods_id'])
		{
			//商品退换情况为完成2
			$goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
			$edit_flag                         = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);

			check_rs($edit_flag, $rs_row);
		}
		else
		{
			$order_data['order_refund_status'] = Order_BaseModel::REFUND_COM;
			$edit_flag                         = $this->Order_BaseModel->editBase($return['order_number'], $order_data);
			check_rs($edit_flag, $rs_row);
		}
		$sum_data['order_refund_amount']         = $return['return_cash'];
		$sum_data['order_commission_return_fee'] = $return['return_commision_fee'];
		$edit_flag = $this->Order_BaseModel->editBase($return['order_number'], $sum_data, true);
		check_rs($edit_flag, $rs_row);

		if($edit_flag)
		{
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('paycenter_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');

			$formvars             = array();
			$formvars['app_id']        = $shop_app_id;
			$formvars['user_id']  = $return['buyer_user_id'];
			$formvars['user_account'] = $return['buyer_user_account'];
			$formvars['seller_id'] = $return['seller_user_id'];
			$formvars['seller_account'] = $return['seller_user_account'];
			$formvars['amount']   = $return['return_cash'];
			$formvars['order_id'] = $return['order_number'];
			$formvars['goods_id'] = $return['order_goods_id'];
			$formvars['uorder_id'] = $order_base['payment_other_number'];


			$rs                   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=refundTransfer&typ=json', $url), $formvars);

			if ($rs['status'] == 200)
			{
				check_rs(true, $rs_row);
			}
			else
			{
				check_rs(false, $rs_row);
			}
			$edit_flag = is_ok($rs_row);
		}



		if ($edit_flag && $this->Order_ReturnModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->Order_ReturnModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');
		}
		$this->data->addBody(-140, $data, $msg, $status);

	}

	function excel($title, $tit, $con, $key)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("mall_new");
		$objPHPExcel->getProperties()->setLastModifiedBy("mall_new");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription($title);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($title);
		$letter = array(
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'Q',
			'R',
			'S',
			'T'
		);
		foreach ($tit as $k => $v)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($letter[$k] . "1", $v);
		}
		foreach ($con as $k => $v)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($letter[0] . ($k + 2), $k + 1);
			foreach ($key as $k2 => $v2)
			{

				$objPHPExcel->getActiveSheet()->setCellValue($letter[$k2 + 1] . ($k + 2), $v[$v2]);
			}
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$title.xls\"");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		die();
	}
}

?>