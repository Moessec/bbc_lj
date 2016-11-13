<?php
$ctl       = request_string('ctl');
$met       = request_string('met');
$act       = request_string("act");
$level_row = array();
$seller_menu = array(
	10000 => array(
		'menu_id' => '10000',
		'menu_parent_id' => '-1',
		'menu_name' => '首页',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Index',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
	),
	11000 => array(
		'menu_id' => '11000',
		'menu_parent_id' => '-1',
		'menu_name' => '商品',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Goods',
		'menu_url_met' => 'online',
		'menu_url_parem' => '',
		'sub' => array(
			110002 => array(
				'menu_id' => '110002',
				'menu_parent_id' => '11000',
				'menu_name' => '出售中的商品',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'online',
				'menu_url_parem' => '',
				'sub' => array(
					1110001 => array(
						'menu_id' => '1110001',
						'menu_parent_id' => '110002',
						'menu_name' => '出售中的商品',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'online',
						'menu_url_parem' => 'op=faf',
					)
				)
			),
			110001 => array(
				'menu_id' => '110001',
				'menu_parent_id' => '11000',
				'menu_name' => '商品发布',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'add',
				'menu_url_parem' => '',
			),
			110003 => array(
				'menu_id' => '110003',
				'menu_parent_id' => '11000',
				'menu_name' => '仓库中的商品',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'offline',
				'menu_url_parem' => '',
				'sub' => array(
					1130001 => array(
						'menu_id' => '1130001',
						'menu_parent_id' => '110003',
						'menu_name' => '仓库中的商品',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'offline',
						'menu_url_parem' => 'op=1',
					),
					1130002 => array(
						'menu_id' => '1130002',
						'menu_parent_id' => '110003',
						'menu_name' => '违规的商品',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'lockup',
						'menu_url_parem' => 'op=2',
					),
					1130003 => array(
						'menu_id' => '1130003',
						'menu_parent_id' => '110003',
						'menu_name' => '等待审核的商品',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'verify',
						'menu_url_parem' => 'op=3',
					)
				),
			),

			/*
			110004 => array(
				'menu_id' => '110004',
				'menu_parent_id' => '11000',
				'menu_name' => '预约/到货通知',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'appointment',
				'menu_url_parem' => '',
			),
			*/
			110005 => array(
				'menu_id' => '110005',
				'menu_parent_id' => '11000',
				'menu_name' => '关联版式',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'format',
				'menu_url_parem' => '',
                'sub' => array(

                    1160004 => array(
                        'menu_id' => '1160004',
                        'menu_parent_id' => '110005',
                        'menu_name' => '关联版式',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Goods',
                        'menu_url_met' => 'format',
                        'menu_url_parem' => '',
                    ),
                    1160005 => array(
                        'menu_id' => '1160005',
                        'menu_parent_id' => '110005',
                        'menu_name' => '编辑版式',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Goods',
                        'menu_url_met' => 'addformat',
                        'menu_url_parem' => '',
                    )
                )
			),
			110006 => array(
				'menu_id' => '110006',
				'menu_parent_id' => '11000',
				'menu_name' => '商品规格',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods_Spec',
				'menu_url_met' => 'spec',
				'menu_url_parem' => '',
				'sub' => array(
					1160006 => array(
						'menu_id' => '1160006',
						'menu_parent_id' => '110006',
						'menu_name' => '商品规格',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods_Spec',
						'menu_url_met' => 'spec',
						'menu_url_parem' => '',
					)
				),
			),
			110007 => array(
				'menu_id' => '110007',
				'menu_parent_id' => '11000',
				'menu_name' => '图片空间',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Album',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
					1100071 => array(
						'menu_id' => '1100071',
						'menu_parent_id' => '110007',
						'menu_name' => '未分组',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Album',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					)
				)
			),
		),
	),
	12000 => array(
		'menu_id' => '12000',
		'menu_parent_id' => '-1',
		'menu_name' => '订单物流',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Trade_Order',
		'menu_url_met' => 'physical',
		'menu_url_parem' => '',
		'sub' => array(
			120001 => array(
				'menu_id' => '120001',
				'menu_parent_id' => '12000',
				'menu_name' => '实物交易订单',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Order',
				'menu_url_met' => 'physical',
				'menu_url_parem' => '',
				'sub' => array(
					1200011 => array(
						'menu_id' => '1200011',
						'menu_parent_id' => '120001',
						'menu_name' => '所有订单',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'physical',
						'menu_url_parem' => '',
					),
					1200012 => array(
						'menu_id' => '1200012',
						'menu_parent_id' => '120001',
						'menu_name' => '待付款',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalNew',
						'menu_url_parem' => '',
					),
					1200013 => array(
						'menu_id' => '1200013',
						'menu_parent_id' => '120001',
						'menu_name' => '已付款',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalPay',
						'menu_url_parem' => '',
					),
					/*1200014 => array(
						'menu_id' => '1200014',
						'menu_parent_id' => '120001',
						'menu_name' => '待自提',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalNotakes',
						'menu_url_parem' => '',
					),*/
					120005 => array(
						'menu_id' => '120005',
						'menu_parent_id' => '120001',
						'menu_name' => '已发货',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalSend',
						'menu_url_parem' => '',
					),
					120006 => array(
						'menu_id' => '120006',
						'menu_parent_id' => '120001',
						'menu_name' => '已完成',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalSuccess',
						'menu_url_parem' => '',
					),
					120007 => array(
						'menu_id' => '120007',
						'menu_parent_id' => '120001',
						'menu_name' => '已取消',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalCancel',
						'menu_url_parem' => '',
					),
					120008 => array(
						'menu_id' => '120008',
						'menu_parent_id' => '120001',
						'menu_name' => '发货',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'send',
						'menu_url_parem' => '',
					),
					120009 => array(
						'menu_id' => '120009',
						'menu_parent_id' => '120001',
						'menu_name' => '订单详情',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'physicalInfo',
						'menu_url_parem' => '',
					)
				)
			),
			120002 => array(
				'menu_id' => '120002',
				'menu_parent_id' => '12000',
				'menu_name' => '虚拟兑码订单',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Order',
				'menu_url_met' => 'virtual',
				'menu_url_parem' => '',
				'sub' => array(
					1200021 => array(
						'menu_id' => '1200021',
						'menu_parent_id' => '120002',
						'menu_name' => '所有订单',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtual',
						'menu_url_parem' => '',
					),
					1200022 => array(
						'menu_id' => '1200022',
						'menu_parent_id' => '120002',
						'menu_name' => '待付款',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualNew',
						'menu_url_parem' => '',
					),
					1200023 => array(
						'menu_id' => '1200023',
						'menu_parent_id' => '120002',
						'menu_name' => '已付款',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualPay',
						'menu_url_parem' => '',
					),
					1200024 => array(
						'menu_id' => '1200024',
						'menu_parent_id' => '120002',
						'menu_name' => '已完成',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualSuccess',
						'menu_url_parem' => '',
					),
					1200025 => array(
						'menu_id' => '1200025',
						'menu_parent_id' => '120002',
						'menu_name' => '已取消',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualCancel',
						'menu_url_parem' => '',
					),
					1200026 => array(
						'menu_id' => '1200026',
						'menu_parent_id' => '120002',
						'menu_name' => '订单详情',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtualInfo',
						'menu_url_parem' => '',
					),
					1200027 => array(
						'menu_id' => '1200027',
						'menu_parent_id' => '120002',
						'menu_name' => '兑换码兑换',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtualExchange',
						'menu_url_parem' => '',
					),
				)
			),
			120003 => array(
				'menu_id' => '120003',
				'menu_parent_id' => '12000',
				'menu_name' => '发货',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Deliver',
				'menu_url_met' => 'deliver',
				'menu_url_parem' => '',
				'sub' => array(
					1200031=>array(
						'menu_id' => '1200031',
						'menu_parent_id' => '120003',
						'menu_name' => '待发货',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'deliver',
						'menu_url_parem' => '',
					),
					1200032=>array(
						'menu_id' => '1200032',
						'menu_parent_id' => '120003',
						'menu_name' => '发货中',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'delivering',
						'menu_url_parem' => '',
					),
					1200033=>array(
						'menu_id' => '1200033',
						'menu_parent_id' => '120003',
						'menu_name' => '已收货', 
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'delivered',
						'menu_url_parem' => '',
					)
				)
			),
			120004 => array(
				'menu_id' => '120004',
				'menu_parent_id' => '12000',
				'menu_name' => '发货设置',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Deliver',
				'menu_url_met' => 'deliverSetting',
				'menu_url_parem' => '',
				'sub' => array(
					1240001=>array(
							'menu_id' => '1240001',
							'menu_parent_id' => '120004',
							'menu_name' => '地址库',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Trade_Deliver',
							'menu_url_met' => 'deliverSetting',
							'menu_url_parem' => '',
					),
					1240002 => array(
						'menu_id' => '1240002',
						'menu_parent_id' => '120004',
						'menu_name' => '默认物流公司',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'express',
						'menu_url_parem' => '',
					),
					1240003 => array(
						'menu_id' => '1240003',
						'menu_parent_id' => '120004',
						'menu_name' => '免运费额度',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'freightAmount',
						'menu_url_parem' => '',
					),
					1240004 => array(
						'menu_id' => '1240004',
						'menu_parent_id' => '120004',
						'menu_name' => '默认配送地区',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'deliverArea',
						'menu_url_parem' => '',
					),
					1240005 => array(
						'menu_id' => '1240005',
						'menu_parent_id' => '120004',
						'menu_name' => '发货单打印设置',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'printSetting',
						'menu_url_parem' => '',
					),
					1240006 => array(
						'menu_id' => '1240006',
						'menu_parent_id' => '120004',
						'menu_name' => '新增地址',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'addAddress',
						'menu_url_parem' => '',
					),
				),
			),
			120005 => array(
				'menu_id' => '120005',
				'menu_parent_id' => '12000',
				'menu_name' => '运单模板',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Waybill',
				'menu_url_met' => 'waybillManage',
				'menu_url_parem' => '',
				'sub' => array(
					1200051 => array(
						'menu_id' => '1200051',
						'menu_parent_id' => '120005',
						'menu_name' => '模板绑定',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillManage',
						'menu_url_parem' => '',
					),
					1200052 => array(
						'menu_id' => '1200052',
						'menu_parent_id' => '120005',
						'menu_name' => '自建模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillIndex',
						'menu_url_parem' => '',
					),
					1200053 => array(
						'menu_id' => '1200053',
						'menu_parent_id' => '120005',
						'menu_name' => '选择模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillBind',
						'menu_url_parem' => '',
					),
					1200054 => array(
						'menu_id' => '1200054',
						'menu_parent_id' => '120005',
						'menu_name' => '模板设置',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillSetting',
						'menu_url_parem' => '',
					),
					1200055 => array(
						'menu_id' => '1200055',
						'menu_parent_id' => '120005',
						'menu_name' => '添加模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'addTpl',
						'menu_url_parem' => '',
					),
					1200056 => array(
						'menu_id' => '1200056',
						'menu_parent_id' => '120005',
						'menu_name' => '编辑模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'editTpl',
						'menu_url_parem' => '',
					),
					1200057 => array(
						'menu_id' => '1200057',
						'menu_parent_id' => '120005',
						'menu_name' => '设计模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'designTpl',
						'menu_url_parem' => '',
					),
					1200058 => array(
						'menu_id' => '1200058',
						'menu_parent_id' => '120005',
						'menu_name' => '自配送模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'zps',
						'menu_url_parem' => '',
					),
					1200059 => array(
						'menu_id' => '1200059',
						'menu_parent_id' => '120005',
						'menu_name' => '自配送模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'zpsIndex',
						'menu_url_parem' => '',
					),
					1200060 => array(
						'menu_id' => '1200060',
						'menu_parent_id' => '120005',
						'menu_name' => '添加自配送模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'zpsAdd',
						'menu_url_parem' => '',
					),
					1200061 => array(
						'menu_id' => '1200061',
						'menu_parent_id' => '120005',
						'menu_name' => '编辑自配送模板',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'editZps',
						'menu_url_parem' => '',
					),
				),
			),
			120006 => array(
				'menu_id' => '120006',
				'menu_parent_id' => '12000',
				'menu_name' => '评价管理',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods_Evaluation',
				'menu_url_met' => 'evaluation',
				'menu_url_parem' => '',
				'sub' => array(
						1120006 => array(
							'menu_id' => '1120006',
							'menu_parent_id' => '120006',
							'menu_name' => '来自买家的评价',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Goods_Evaluation',
							'menu_url_met' => 'evaluation',
							'menu_url_parem' => '',
						),
				),
			),
			120007 => array(
				'menu_id' => '120007',
				'menu_parent_id' => '12000',
				'menu_name' => '物流工具',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Transport',
				'menu_url_met' => 'transport',
				'menu_url_parem' => '',
			),
		),
	),

	13000 => array(
		'menu_id' => '13000',
		'menu_parent_id' => '-1',
		'menu_name' => '促销',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			130001 => array(
				'menu_id' => '130001',
				'menu_parent_id' => '13000',
				'menu_name' => '团购管理',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
					1130001 => array(
						'menu_id' => '1130001',
						'menu_parent_id' => '130001',
						'menu_name' => '团购列表',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					1130002 => array(
						'menu_id' => '1130002',
						'menu_parent_id' => '130001',
						'menu_name' => '新增团购',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'add',
						'menu_url_parem' => '',
					),
					1130003 => array(
						'menu_id' => '1130003',
						'menu_parent_id' => '130001',
						'menu_name' => '新增虚拟团购',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'addVr',
						'menu_url_parem' => '',
					),
					1130004 => array(
						'menu_id' => '1130004',
						'menu_parent_id' => '130001',
						'menu_name' => '套餐管理',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'combo',
						'menu_url_parem' => '',
					)
				),
			),
            130002 => array(
                'menu_id' => '130002',
                'menu_parent_id' => '13000',
                'menu_name' => '加价购',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Increase',
                'menu_url_met' => 'index',
                'menu_url_parem' => 'op=list',
                'sub' => array(
                    1230001 => array(
                        'menu_id' => '1230001',
                        'menu_parent_id' => '130002',
                        'menu_name' => '活动列表',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => 'op=list',
                    ),
                    1230002 => array(
                        'menu_id' => '1230002',
                        'menu_parent_id' => '130002',
                        'menu_name' => '添加活动',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1230003 => array(
                        'menu_id' => '1230003',
                        'menu_parent_id' => '130002',
                        'menu_name' => '套餐管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            130003 => array(
                'menu_id' => '130003',
                'menu_parent_id' => '13000',
                'menu_name' => '限时折扣',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Discount',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    1330001 => array(
                        'menu_id' => '1330001',
                        'menu_parent_id' => '130003',
                        'menu_name' => '活动列表',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1330002 => array(
                        'menu_id' => '1330002',
                        'menu_parent_id' => '130003',
                        'menu_name' => '新增活动',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1330003 => array(
                        'menu_id' => '1330003',
                        'menu_parent_id' => '130003',
                        'menu_name' => '套餐管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                   /* 1330004 => array(
                        'menu_id' => '1330004',
                        'menu_parent_id' => '130003',
                        'menu_name' => '商品管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'manage',
                        'menu_url_parem' => '',
                    ),*/

                ),
            ),
			130004 => array(
				'menu_id' => '130004',
				'menu_parent_id' => '13000',
				'menu_name' => '满即送',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
                'sub' => array(
                    1430001 => array(
                        'menu_id' => '1430001',
                        'menu_parent_id' => '130004',
                        'menu_name' => '满送列表',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1430002 => array(
                        'menu_id' => '1430002',
                        'menu_parent_id' => '130004',
                        'menu_name' => '新增活动',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1430003 => array(
                        'menu_id' => '1430003',
                        'menu_parent_id' => '130004',
                        'menu_name' => '套餐管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),

                )
			),
            130011 => array(
                'menu_id' => '130011',
                'menu_parent_id' => '13000',
                'menu_name' => '代金券管理',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Voucher',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    130011 => array(
                        'menu_id' => '130011',
                        'menu_parent_id' => '130011',
                        'menu_name' => '代金券管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1130012 => array(
                        'menu_id' => '1130002',
                        'menu_parent_id' => '130011',
                        'menu_name' => '添加代金券',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
                    1130013 => array(
                        'menu_id' => '1130003',
                        'menu_parent_id' => '130011',
                        'menu_name' => '套餐管理',
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            /*130010 => array(
				'menu_id' => '130010',
				'menu_parent_id' => '13000',
				'menu_name' => '手机专享',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Marketing',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),*/
            /*130009 => array(
                'menu_id' => '130009',
                'menu_parent_id' => '13000',
                'menu_name' => '推荐组合',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130008 => array(
                'menu_id' => '130008',
                'menu_parent_id' => '13000',
                'menu_name' => '码商品',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130007 => array(
                'menu_id' => '130007',
                'menu_parent_id' => '13000',
                'menu_name' => '预售商品',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130006 => array(
                'menu_id' => '130006',
                'menu_parent_id' => '13000',
                'menu_name' => '推荐展位',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130005 => array(
                'menu_id' => '130005',
                'menu_parent_id' => '13000',
                'menu_name' => '优惠套装',
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
		),
	),

	14000 => array(
		'menu_id' => '14000',
		'menu_parent_id' => '-1',
		'menu_name' => '店铺',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Shop_Setshop',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			140001 => array(
				'menu_id' => '140001',
				'menu_parent_id' => '14000',
				'menu_name' => '店铺设置',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Setshop',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
                                'sub' => array(
                                     1400001 => array(
                                            'menu_id' => '1400001',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => '店铺设置',
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'index',
                                            'menu_url_parem' => '',

                                    ),  
                                    1400002 => array(
                                            'menu_id' => '1400002',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => '店铺幻灯',
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'slide',
                                            'menu_url_parem' => '',

                                    ),  
                                    1400003 => array(
                                            'menu_id' => '1400003',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => '店铺模板',
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'theme',
                                            'menu_url_parem' => '',

                                    ),        
                                    1400004 => array(
                                            'menu_id' => '1400004',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => '店铺装修',
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Decoration',
                                            'menu_url_met' => 'decoration',
                                            'menu_url_parem' => '',

                                    ),  
                      
                                   ),
			),  
                        140003 => array(
				'menu_id' => '140003',
				'menu_parent_id' => '14000',
				'menu_name' => '店铺导航',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Nav',
				'menu_url_met' => 'nav',
				'menu_url_parem' => '',
			),
                        140008 => array(
                                    'menu_id' => '140008',
                                    'menu_parent_id' => '14000',
                                    'menu_name' => '供货商',
                                    'menu_icon' => '',
                                    'menu_url_ctl' => 'Seller_Shop_Supplier',
                                    'menu_url_met' => 'supplier',
                                    'menu_url_parem' => '',
                            ),
                        140006 => array(
                                    'menu_id' => '140006',
                                    'menu_parent_id' => '14000',
                                    'menu_name' => '店铺分类',
                                    'menu_icon' => '',
                                    'menu_url_ctl' => 'Seller_Shop_Cat',
                                    'menu_url_met' => 'cat',
                                    'menu_url_parem' => '',
                            ),
			140009 => array(
				'menu_id' => '140009',
				'menu_parent_id' => '14000',
				'menu_name' => '实体店铺',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Entityshop',
				'menu_url_met' => 'entityShop',
				'menu_url_parem' => '',
			),
			
			140007 => array(
				'menu_id' => '140007',
				'menu_parent_id' => '14000',
				'menu_name' => '品牌申请',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Brand',
				'menu_url_met' => 'brand',
				'menu_url_parem' => '',
			),
			
			140005 => array(
				'menu_id' => '140005',
				'menu_parent_id' => '14000',
				'menu_name' => '店铺信息',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Info',
				'menu_url_met' => 'info',
				'menu_url_parem' => '',
                 
			),
			/*140004 => array(
				'menu_id' => '140004',
				'menu_parent_id' => '14000',
				'menu_name' => '店铺动态',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Sns',
				'menu_url_met' => 'sns',
				'menu_url_parem' => '',
			),*/
			
			140010 => array(
				'menu_id' => '140010',
				'menu_parent_id' => '14000',
				'menu_name' => '消费者保障服务',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Contract',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
		),
	),
                
	15000 => array(
		'menu_id' => '15000',
		'menu_parent_id' => '-1',
		'menu_name' => '售后服务',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Service_Consult',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			150001 => array(
				'menu_id' => '150001',
				'menu_parent_id' => '15000',
				'menu_name' => '咨询管理',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Consult',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			150002 => array(
				'menu_id' => '150002',
				'menu_parent_id' => '15000',
				'menu_name' => '投诉管理',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Complain',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			150003 => array(
				'menu_id' => '150003',
				'menu_parent_id' => '15000',
				'menu_name' => '退款记录',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Return',
				'menu_url_met' => 'orderReturn',
				'menu_url_parem' => '',
			),
			150004 => array(
				'menu_id' => '150004',
				'menu_parent_id' => '15000',
				'menu_name' => '退货记录',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Return',
				'menu_url_met' => 'goodsReturn',
				'menu_url_parem' => '',
			),
		),
	),
	16000 => array(
		'menu_id' => '16000',
		'menu_parent_id' => '-1',
		'menu_name' => '统计结算',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Analysis_General',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(

			160001 => array(
				'menu_id' => '160001',
				'menu_parent_id' => '16000',
				'menu_name' => '店铺概况',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_General',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			160002 => array(
				'menu_id' => '160002',
				'menu_parent_id' => '16000',
				'menu_name' => '商品分析',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Goods',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
					1160002 => array(
						'menu_id' => '1160002',
						'menu_parent_id' => '160002',
						'menu_name' => '商品详情',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Analysis_Goods',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					2160002 => array(
						'menu_id' => '2160002',
						'menu_parent_id' => '160002',
						'menu_name' => '热卖商品',
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Analysis_Goods',
						'menu_url_met' => 'hot',
						'menu_url_parem' => '',
					),
				),
			),
			160003 => array(
				'menu_id' => '160003',
				'menu_parent_id' => '16000',
				'menu_name' => '运营报告',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Operation',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			/*
			160004 => array(
				'menu_id' => '160004',
				'menu_parent_id' => '16000',
				'menu_name' => '行业分析',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Class',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			160005 => array(
				'menu_id' => '160005',
				'menu_parent_id' => '16000',
				'menu_name' => '流量统计',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Flow',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			*/
			160006 => array(
				'menu_id' => '160006',
				'menu_parent_id' => '16000',
				'menu_name' => '实物结算',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Order_Settlement',
				'menu_url_met' => 'normal',
				'menu_url_parem' => '',
				'sub' => array(
						1160006 => array(
							'menu_id' => '1160006',
							'menu_parent_id' => '160006',
							'menu_name' => '实物订单结算',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Order_Settlement',
							'menu_url_met' => 'normal',
							'menu_url_parem' => '',
						),
				),		
			),
			160007 => array(
				'menu_id' => '160007',
				'menu_parent_id' => '16000',
				'menu_name' => '虚拟结算',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Order_Settlement',
				'menu_url_met' => 'virtual',
				'menu_url_parem' => '',
				'sub' => array(
						1160007 => array(
							'menu_id' => '1160007',
							'menu_parent_id' => '160007',
							'menu_name' => '虚拟订单结算',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Order_Settlement',
							'menu_url_met' => 'virtual',
							'menu_url_parem' => '',
						),
				),
			),
		),
	),
	17000 => array(
		'menu_id' => '17000',
		'menu_parent_id' => '-1',
		'menu_name' => '客服消息',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Message',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			 170001 => array(
				'menu_id' => '170001',
				'menu_parent_id' => '17000',
				'menu_name' => '客服设置',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
						1700011 => array(
							'menu_id' => '1700011',
							'menu_parent_id' => '170001',
							'menu_name' => '客服设置',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'index',
							'menu_url_parem' => '',
						),
					),
			), 
			170002 => array(
				'menu_id' => '170002',
				'menu_parent_id' => '17000',
				'menu_name' => '系统消息',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'message',
				'menu_url_parem' => '',
				'sub' => array(
						1700021 => array(
							'menu_id' => '1700021',
							'menu_parent_id' => '170002',
							'menu_name' => '系统消息',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'message',
							'menu_url_parem' => '',
						),
						1700022 => array(
							'menu_id' => '1700022',
							'menu_parent_id' => '170002',
							'menu_name' => '系统公告',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'messageAnnouncement',
							'menu_url_parem' => '',
						),
						1700023 => array(
							'menu_id' => '1700023',
							'menu_parent_id' => '170002',
							'menu_name' => '消息接收设置',
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'messageManage',
							'menu_url_parem' => '',
						),
					),
			),
			/* 170003 => array(
				'menu_id' => '170003',
				'menu_parent_id' => '17000',
				'menu_name' => '聊天记录查询',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'chat',
				'menu_url_parem' => '',
			), */
		),
	),
	/* 18000 => array(
		'menu_id' => '18000',
		'menu_parent_id' => '-1',
		'menu_name' => '账号',
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Account',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			180001 => array(
				'menu_id' => '180001',
				'menu_parent_id' => '18000',
				'menu_name' => '账号列表',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			180002 => array(
				'menu_id' => '180002',
				'menu_parent_id' => '18000',
				'menu_name' => '账号组',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			180003 => array(
				'menu_id' => '180003',
				'menu_parent_id' => '18000',
				'menu_name' => '账号日志',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			180004 => array(
				'menu_id' => '180004',
				'menu_parent_id' => '18000',
				'menu_name' => '店铺消费',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			180005 => array(
				'menu_id' => '180005',
				'menu_parent_id' => '18000',
				'menu_name' => '门店账号',
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
		),
	), */
);

//根据后台配置，去除一些不需要限时的功能模块

/*if(Web_ConfigModel::value('groupbuy_allow') == 0)//团购功能关闭
{
    unset($seller_menu[13000]['sub'][130001]);
}

if(!Web_ConfigModel::value('promotion_allow'))//促销功能关闭，对应需关闭限时折扣、加价购、满送活动
{
    unset($seller_menu[13000]['sub'][130002]);
    unset($seller_menu[13000]['sub'][130003]);
    unset($seller_menu[13000]['sub'][130004]);
}
if(!(Web_ConfigModel::value('pointshop_isuse') && Web_ConfigModel::value('pointprod_isuse') && Web_ConfigModel::value('voucher_allow')))//代金券功能开启限制，代金券功能、积分功能、积分中心启用后，商家可以申请代金券活动
{
    unset($seller_menu[13000]['sub'][130011]);
}*/

//行
global $seller_menu_rows;
$seller_menu_rows = array();


function get_menu_rows($seller_menu, &$seller_menu_rows)
{
	foreach ($seller_menu as $id=>$item)
	{
		if (isset($item['sub']) && $item['sub'])
		{
			get_menu_rows($item['sub'], $seller_menu_rows);

			unset($item['sub']);
			$seller_menu_rows[$id] = $item;
		}
		else
		{
			$seller_menu_rows[$id] = $item;
		}

	}
}

get_menu_rows($seller_menu, $seller_menu_rows);


//$ctl       = request_string('ctl');
//$met       = request_string('met');
//$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";

function get_menu_id($seller_menu, $level = 0, &$level_row, $ctl, $met)
{
	global $seller_menu_rows;

	$level++;

	foreach ($seller_menu as $menu_row)
	{
		if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met)
		{
			$level_row[$ctl][$met][$level]     = $menu_row['menu_id'];
			$level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

			//向上查找一次
			if (isset($seller_menu_rows[$menu_row['menu_parent_id']]))
			{
				$level_row[$ctl][$met][$level - 2] = $seller_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
			}
		}
		else
		{
		}

		if (isset($menu_row['sub']))
		{
			get_menu_id($menu_row['sub'], $level, $level_row, $ctl, $met);
		}
	}
}

function get_menu_url_map($seller_menu, &$level_row, $seller_menu_ori)
{
	foreach ($seller_menu as $menu_row)
	{
		get_menu_id($seller_menu, 0, $level_row, $menu_row['menu_url_ctl'], $menu_row['menu_url_met']);

		if (isset($menu_row['sub']))
		{
			get_menu_url_map($menu_row['sub'], $level_row, $seller_menu_ori);
		}
	}
}

//缓存点亮规则
//get_menu_url_map($seller_menu, $level_row, $seller_menu);

//计算当前高亮
get_menu_id($seller_menu, 0, $level_row, $ctl, $met);
$level_row = $level_row[$ctl][$met];

return $seller_menu;
?>