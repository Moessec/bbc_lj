<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_SettledCtl extends Seller_Controller
{
	public $messageModel       = null;
	public $shopBaseModel      = null;
	public $shopClassModel     = null;
	public $shopGradeModel     = null;
	public $shopTemplateModel  = null;
	public $shopCompanyModel   = null;
	public $goodsCatModel      = null;
	public $shopClassBindModel = null;

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
		$this->shopBaseModel      = new Shop_BaseModel();
		$this->shopClassModel     = new Shop_ClassModel();
		$this->shopGradeModel     = new Shop_GradeModel();
		$this->shopTemplateModel  = new Shop_TemplateModel();
		$this->shopCompanyModel   = new   Shop_CompanyModel();
		$this->messageModel       = new MessageModel();
		$this->goodsCatModel      = new Goods_CatModel();
		$this->shopClassBindModel = new Shop_ClassBindModel();
	}

	public function index()
	{

		if (Perm::checkUserPerm())
		{
			//重新设置登录信息
			{
                                $web['web_logo']       = Web_ConfigModel::value("setting_logo");//首页logo
				$User_BaseModel = new User_BaseModel();
                                $ShopHelpModel = new Shop_HelpModel();
                                $shop_xieyi = $ShopHelpModel->getByWhere(array('page_show'=>2));
				//本地数据校验登录
				$user_row = $User_BaseModel->getOne(Perm::$userId);

				$data            = array();
				$data['user_id'] = $user_row['user_id'];

				//获取一级地址
				$district_parent_id = request_int('pid', 0);
				$baseDistrictModel  = new Base_DistrictModel();
				$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

				//
				$Seller_BaseModel = new Seller_BaseModel();
				$seller_rows      = $Seller_BaseModel->getByWhere(array('user_id' => $data['user_id']));

				if ($seller_rows)
				{
					$data['shop_id_row'] = array_column($seller_rows, 'shop_id');
					$data['shop_id']     = current($data['shop_id_row']);

					Perm::encryptUserInfo($data);
				}
				else
				{
					$data['shop_id'] = 0;
				}

			}

			$user_id  = Perm::$userId;
			$cond_row = array("user_id" => $user_id);
			// $shop_id =  Perm::$shopId;
			$shop_info = $this->shopBaseModel->getBaseOneList($cond_row);
			if ($shop_info)
			{
				$shop_company = $this->shopBaseModel->getbaseCompanyList($shop_info['shop_id']);
				//判断用户是否有开店过了
				if ($shop_info['shop_status'] == 3)
				{
					header("Location:index.php?ctl=Seller_Index&met=index&typ=e");
					die;
				}
			}
			$shop_class = $this->shopClassModel->getByWhere();
			$shop_grade = $this->shopGradeModel->getByWhere();
			$op         = request_string("op");
			switch ($op)
			{
				case "step1":
				{
					$page = "step1";
					break;
				}
				case "step2":
				{
					if (empty($shop_company['shop_company_name']))
					{
						$page = "step2";
					}
					else
					{
						$page = "step3";
					}
					break;
				}
				case "step3":
				{
					if (empty($shop_company['shop_company_name']))
					{
						$page = "step2";
					}
					else
					{
						$page = "step3";
					}
					break;
				}
				case "step4":
				{

					if (!empty($shop_company['shop_company_name']))
					{
						if (!empty($shop_company['bank_name']))
						{
							$page = "step4";
						}
						else
						{
							$page = "step3";
						}
					}
					else
					{
						$page = "step2";
					}

					break;
				}
				case "step5":
				{
					if (!empty($shop_company) && ($shop_company['shop_status'] == 1 || $shop_company['shop_status'] == 2))
					{
						$page = "step5";
					}
					else
					{
						$page = "step1";
					}
					break;
				}
				//                default:
				//                {
				//                        $page = $shop['status'] == 1 ? "step5" : "step1";
				//                        break;
				//                }

			}
			if (!empty($page) && !empty($shop_company))
			{
				$page = $shop_company && $shop_company['bank_name'] && $shop_company['shop_status'] == 0 ? "step4" : $page;
				$page = $shop_company && ($shop_company['shop_status'] == 1 || $shop_company['shop_status'] == 2) ? "step5" : $page;
			}
			if (!empty($page))
			{
				$this->view->setMet($page);
			}
			else
			{
				
				$shop_help     = $ShopHelpModel->getByWhere(array('page_show'=>1), array('help_sort' => "asc"));
			}

			//传递数据
			if ('json' == $this->typ)
			{
				$data['shop_help']    = empty($shop_help) ? array() : $shop_help;
				$data['shop_class']   = empty($shop_class) ? array() : $shop_class;
				$data['shop_grade']   = empty($shop_grade) ? array() : $shop_grade;
				$data['shop_info']    = empty($shop_info) ? array() : $shop_info;
				$data['shop_company'] = empty($shop_company) ? array() : $shop_company;
				$this->data->addBody(-140, $data);

			}
			else
			{
				include $this->view->getView();
			}
		}
		else
		{
			header("Location:" . Yf_Registry::get('url'), "请先登录！");
		}
	}


	public function addShopCompany()
	{


		$user_id   = Perm::$userId;
		$cond_row  = array("user_id" => $user_id);
		$shop_info = $this->shopBaseModel->getBaseOneList($cond_row);
		if (empty($shop_info))
		{
			$shop_company['shop_company_name']      = request_string('shop_company_name');
			$shop_company['shop_company_address']   = request_string('shop_company_address');
			$shop_company['company_address_detail'] = request_string('company_address_detail');
			$shop_company['company_phone']          = request_string('company_phone');
			$shop_company['company_employee_count'] = request_string('company_employee_count');

			$shop_company['company_registered_capital']   = request_string('company_registered_capital');
			$shop_company['contacts_name']                = request_string('contacts_name');
			$shop_company['contacts_phone']               = request_string('contacts_phone');
			$shop_company['contacts_email']               = request_string('contacts_email');
			$shop_company['business_id']                  = request_string('business_id');
			$shop_company['business_license_location']    = request_string('business_license_location');
			$shop_company['business_license_electronic']  = request_string('business_license_electronic');
			$shop_company['business_licence_start']       = request_string('business_licence_start');
			$shop_company['business_licence_end']         = request_string('business_licence_end');
			$shop_company['organization_code']            = request_string('organization_code');
			$shop_company['organization_code_electronic'] = request_string('organization_code_electronic');
			$shop_company['general_taxpayer']             = request_string('general_taxpayer');
			//开启事物
			$this->messageModel->sql->startTransactionDb();

			$flag = $this->shopCompanyModel->addCompany($shop_company, TRUE);

			$shop_base['shop_id']   = $flag;
			$shop_base['user_id']   = Perm::$userId;
			$shop_base['user_name'] = Perm::$row['user_account'];
			$flag1                  = $this->shopBaseModel->addBase($shop_base);


			if ($flag1 && $flag && $this->messageModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$this->messageModel->sql->rollBackDb();
				$status = 250;
				$msg    = _('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = _('请勿重复提交！');
		}
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	//修改公司信息
	public function editShopCompany()
	{

		$shop_id                                                 = request_string('shop_id');
		$shop_company['bank_account_name']                       = request_string('bank_account_name');
		$shop_company['bank_account_number']                     = request_string('bank_account_number');
		$shop_company['bank_name']                               = request_string('bank_name');
		$shop_company['bank_code']                               = request_string('bank_code');
		$shop_company['bank_address']                            = request_string('bank_address');
		$shop_company['bank_licence_electronic']                 = request_string('bank_licence_electronic');
		$shop_company['taxpayer_id']                             = request_string('taxpayer_id');
		$shop_company['tax_registration_certificate']            = request_string('tax_registration_certificate');
		$shop_company['tax_registration_certificate_electronic'] = request_string('tax_registration_certificate_electronic');

		$flag = $this->shopCompanyModel->editCompany($shop_id, $shop_company);


		if ($flag !== FALSE)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	//修改店铺信息
	public function editShopBase()
	{

		$shop_id                       = request_int('shop_id');
		$shop_list['shop_name']        = request_string("shop_name");
		$shop_list['shop_grade_id']    = request_int('shop_grade_id');
		$shop_list['joinin_year']      = request_int('joinin_year');
		$shop_list['shop_class_id']    = request_int('shop_class_id');
		$product_class_id              = request_row('product_class_id');
		$commission_rate               = request_row('commission_rate');
		$shop_list['shop_status']      = 1;
		$shop_list['shop_create_time'] = get_date_time();
		$shop_list['shop_end_time']    = date("Y-m-d H:i:s", strtotime(" $shop_list[shop_create_time] + $shop_list[joinin_year] year"));

		$flag = $this->shopBaseModel->editBase($shop_id, $shop_list);

		foreach ($product_class_id as $key => $value)
		{
			$shop_class['product_class_id']       = $value;
			$shop_class['commission_rate']        = $commission_rate[$key];
			$shop_class['shop_class_bind_enable'] = 2;
			$shop_class['shop_id']                = $shop_id;
			$flag1                                = $this->shopClassBindModel->addClassBind($shop_class);
		}


		if ($flag !== FALSE)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function shopCatBind()
	{
		$cat_id   = request_int("cat_id");
		$data     = $this->goodsCatModel->getCatParent($cat_id);
		$cat_list = $this->goodsCatModel->getOne($cat_id);
		$data[]   = $cat_list;

		if ($data)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function shopPaystatus()
	{

		$shop_id                              = request_int('shop_id');
		$shop_list['payment_voucher']         = request_string("payment_voucher");
		$shop_list['payment_voucher_explain'] = request_string('payment_voucher_explain');
		$shop_base['shop_payment']            = 1;
		//开启事物
		$SellerBaseModel = new Seller_BaseModel();
		$this->messageModel->sql->startTransactionDb();
		$flag  = $this->shopBaseModel->editBase($shop_id, $shop_base);
		$flag1 = $this->shopCompanyModel->editCompany($shop_id, $shop_list);
                
                //添加二级域名
                //平台设置二级域名
		$Web_ConfigModel = new Web_ConfigModel();
		$shop_domain     = $Web_ConfigModel->getByWhere(array('config_type' => 'domain'));
                $domain['shop_id'] = $shop_id;
                $domain['shop_edit_domain'] = $shop_domain['domain_modify_frequency']['config_value'];
                $domain['shop_self_domain'] = $shop_domain['retain_domain']['config_value'];
                
                //初始化用户的二级域名
                $Shop_DomainModel = new Shop_DomainModel();
                $flag2 = $Shop_DomainModel->addDomain($domain);
		//添加卖家
		$seller_base['shop_id']         = $shop_id;
		$seller_base['user_id']         = Perm::$userId;
		$seller_base['seller_name']     = Perm::$row['user_account'];
		$seller_base['seller_is_admin'] = 1;
		$seller_add                     = $SellerBaseModel->addBase($seller_base);

		if ($flag1 && $seller_add && $flag2 && $this->messageModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->messageModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}