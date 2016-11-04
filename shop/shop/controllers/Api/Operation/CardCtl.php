<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Operation_CardCtl extends Api_Controller
{

	const PAY_SITE = "";
	public $contractTypeModel = null;


	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function getCardList()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['page']      = request_int('page', 1);
		$formvars['rows']      = request_int('rows', 10);
		$formvars['cardName']  = request_string('card_name');
		$formvars['beginDate'] = request_string('start_time');
		$formvars['endDate']   = request_string('end_time');
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		//$rs   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=getCardBaseList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$rs   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Paycen_PayCard&met=getCardBaseList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		fb($rs);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}

	public function getCardInfoList()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id'] = request_int('id');
		$formvars['page']    = request_int('page', 1);
		$formvars['rows']    = request_int('rows', 10);

		$rs   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=getCardInfoList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}

	public function delCard()
	{
		$card_id  = request_int('id');
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id'] = $card_id;

		$rs              = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=delCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data['card_id'] = $card_id;
		$data['rs']      = json_encode($rs);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addCardBase()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id']         = request_string("card_id");
		$formvars['card_name']  = request_string("card_name");
		$formvars['card_num']   = request_int("card_num");
		$formvars['source']     = request_string("app_id");
		$formvars['start_time'] = request_string("start_time");
		$formvars['end_time']   = request_string("end_time");
		$formvars['card_desc']  = request_string("card_desc");
		$formvars['card_image'] = request_string("card_image");
		$formvars['money']      = request_int("money");
		$formvars['point']      = request_int("point");

		$rs              = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=addCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data['card_id'] = request_string("card_id");
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editCardBase()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id']         = request_string("id");
		$formvars['card_name']  = request_string("card_name");
		$formvars['card_num']   = request_int("card_num");
		$formvars['start_time'] = request_string("start_time");
		$formvars['end_time']   = request_string("end_time");
		$formvars['card_desc']  = request_string("card_desc");
		$formvars['card_image'] = request_string("card_image");
		$formvars['money']      = request_int("money");
		$formvars['point']      = request_int("point");

		$rs              = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=editCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data['card_id'] = request_string("id");
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getDetail()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id'] = request_string("id");

		$rs             = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=getCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data           = $rs['data'];
		$data['detail'] = request_int("detail");
		$this->data->addBody(-140, $data);
	}

	public function manageCard()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id'] = request_string("id");

		$rs   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=getCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}


}

?>