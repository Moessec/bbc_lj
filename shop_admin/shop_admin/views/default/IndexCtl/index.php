<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<!-- author：309558639 | team：http://www.yuanfengerp.com/ -->
<script>
	var CONFIG = {
		DEFAULT_PAGE: true,
		//SERVICE_URL: './<?=Yf_Registry::get('url')?>?ctl=Service'
	};
	//系统参数控制
	var SYSTEM = {
		version: 1,
		skin: "default",
		curDate: '1423619990432',  //系统当前日期
		DBID: '88887785', //账套ID
		serviceType: '15', //账套类型，13：表示收费服务，12：表示免费服务
		userName: 'Demo', //用户名
		companyName: '系统管理中心',	//公司名称
		companyAddr: '',	//公司地址
		phone: '',	//公司电话
		fax: '',	//公司传真
		postcode: '',	//公司邮编
		startDate: '2015-11-18', //启用日期
		invEntryCount: '',//试用版单据分录数
		rights: {},//权限列表
		taxRequiredCheck: 1,
		taxRequiredInput: 13,
		isAdmin: true, //是否管理员
		siExpired: false,//是否过期
		siType: 2, //服务版本，1表示基础版，2表示标准版
		siVersion: 4, //1表示试用、2表示免费（百度版）、3表示收费，4表示体验版
		shortName: ""//shortName


	};
	SYSTEM.categoryInfo = {};
	//区分服务支持
	SYSTEM.servicePro = SYSTEM.siType === 2 ? 'forbscm3' : 'forscm3';
	var cacheList = {};	//缓存列表查询
	//全局基础数据


	//缓存登陆用户
	function getUser()
	{
		Public.ajaxGet('', {}, function (data)
		{
			if (data.status === 200)
			{
				SYSTEM.realName = (data.data[0].user_account);
			}
			else if (data.status === 250)
			{
				SYSTEM.realName = '';
			}
			else
			{
				Public.tips({type: 1, content: data.msg});
			}
		});
	}
	;


	//缓存时间
	function initDate()
	{
		var a = new Date,
			b = a.getFullYear(),
			c = ("0" + (a.getMonth() + 1)).slice(-2),
			d = ("0" + a.getDate()).slice(-2);
		SYSTEM.beginDate = b + "-" + c + "-01", SYSTEM.endDate = b + "-" + c + "-" + d
	}

	//左上侧版本标识控制
	function markupVension()
	{
		var imgSrcList = {
			base: '/css/default/img/icon_v_b.png',	//基础版正式版
			baseExp: '/css/default/img/icon_v_b_e.png',	//基础版体验版
			baseTrial: '/css/default/img/icon_v_b_t.png',	//基础版试用版
			standard: '/css/default/img/icon_v_s.png', //标准版正式版
			standardExp: './shop_admin/static/default/css/img/icon_v_s_e.png', //标准版体验版
			standardTrial: '/css/default/img/icon_v_s_t.png' //标准版试用版
		};
		var imgModel = $("<img id='icon-vension' src='' alt=''/>");
		if (SYSTEM.siType === 1)
		{
			switch (SYSTEM.siVersion)
			{
				case 1:
					imgModel.attr('src', imgSrcList.baseTrial).attr('alt', '基础版试用版');
					break;
				case 2:
					imgModel.attr('src', imgSrcList.baseExp).attr('alt', '免费版（百度版）');
					break;
				case 3:
					imgModel.attr('src', imgSrcList.base).attr('alt', '基础版');//标准版
					break;
				case 4:
					imgModel.attr('src', imgSrcList.baseExp).attr('alt', '基础版体验版');//标准版
					break;
			}
		}
		else
		{
			switch (SYSTEM.siVersion)
			{
				case 1:
					imgModel.attr('src', imgSrcList.standardTrial).attr('alt', '标准版试用版');
					break;
				case 3:
					imgModel.attr('src', imgSrcList.standard).attr('alt', '标准版');//标准版
					break;
				case 4:
					imgModel.attr('src', imgSrcList.standardExp).attr('alt', '标准版体验版');//标准版
					break;
			}
		}

	}

	//全局基础数据
	(function ()
	{
		/*
		 * 判断IE6，提示使用高级版本
		 */
		/*
		 if(Public.isIE6) {
		 var Oldbrowser = {
		 init: function(){
		 this.addDom();
		 },
		 addDom: function() {
		 var html = $('<div id="browser">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、<a href="http://download.microsoft.com/download/4/C/A/4CA9248C-C09D-43D3-B627-76B0F6EBCD5E/IE9-Windows7-x86-chs.exe" target="_blank">IE9</a>、或<a href=http://firefox.com.cn/" target="_blank">火狐浏览器</a>，以便更好的使用！<a id="bClose" title="关闭">x</a></div>').insertBefore('#container').slideDown(500);
		 this._colse();
		 },
		 _colse: function() {
		 $('#bClose').click(function(){
		 $('#browser').remove();
		 });
		 }
		 };
		 Oldbrowser.init();
		 };	*/
		getUserInfo();
		getGoodsState();
		//getUser();
		initDate();

	})();

	$(function()
	{
		getGoodsCatTree();
	});

	//缓存商品分类
	function getGoodsCatTree()
	{
		Public.ajaxPost(SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_cat&is_delete=2', {}, function(data) {
			if (data.status === 200 && data.data) {
				SYSTEM.goodsCatInfo = data.data.items;
				SYSTEM.goodsCatInfo.unshift({name:'全部分类',id:-1});
			} else {
			}
		});
	}


	//缓存客户信息
	function getBrand() {
		if(true) {
			Public.ajaxGet('./c.php', { rows: 5000 }, function(data){
				if(data.status === 200) {
					SYSTEM.brandInfo = data.data.rows;
				} else if (data.status === 250){
					SYSTEM.brandInfo = [];
				} else {
					Public.tips({type: 1, content : data.msg});
				}
			});
		} else {
			SYSTEM.brandInfo = [];
		}
	};

	//缓存管理员
	function getUserInfo()
	{
		if (true)
		{
			Public.ajaxGet(SITE_URL + '?ctl=Category&met=listUser&typ=json&type_number=user', {}, function (data)
			{
				if (data.status === 200)
				{
					SYSTEM.categoryInfo['user'] = data.data.items;
				}
				else if (data.status === 250)
				{
					SYSTEM.categoryInfo['user'] = {};
				}
				else
				{
					Public.tips({type: 1, content: data.msg});
				}
			});
		}
		else
		{
			SYSTEM.categoryInfo['user'] = {};
		}
	};

	//state verify
	function getGoodsState()
	{
		Public.ajaxGet(SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_state', {}, function (data)
		{
			if (data.status === 200)
			{
				for(var key in  data.data){
					SYSTEM.categoryInfo[key] = data.data[key];
				}
			}
			else
			{
				SYSTEM.categoryInfo['state'] = {};
				SYSTEM.categoryInfo['verify'] = {};
				SYSTEM.categoryInfo['type'] = {};
			}
		});
	};
</script>
<link href="<?= $this->view->css ?>/base.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css ?>/default.css" rel="stylesheet" type="text/css" id="defaultFile">
<script src="<?= $this->view->js_com ?>/tabs.js?ver=20140430"></script>
</head>
<body>
<div id="container" class="cf">

	<div class="col-hd cf">
		<div class="left"><a class="company" id="companyName" href="javascript:;" title=""></a></div>

		<div class="right cf">
			<!--
			<ul class="nav">
				<li class="cur" id="fast">平台</li>
				<li>商城</li>
			</ul>
			-->
			<?php
			$ucenter_api_url_row = parse_url(Yf_Registry::get('ucenter_api_url'));
			$ucenter_admin_url = Yf_Registry::get('ucenter_admin_api_url');

			$paycenter_api_url_row = parse_url(Yf_Registry::get('paycenter_api_url'));
			$paycenter_admin_api_url = Yf_Registry::get('paycenter_admin_api_url');
			?>
			<ol>
				<li><a href="<?= $ucenter_admin_url ?>" target="_blank"><i class="nav_href">UCenter</i></a></li>
				<li><a href="<?= $paycenter_admin_api_url ?>" target="_blank"><i class="nav_href">PayCenter</i></a></li>
				<!--<li><a href="<?/*= Yf_Registry::get('url') */?>?ctl=Login&met=loginout"><i class="nav_href">广告系统</i></a></li>
				<li><a href="<?/*= Yf_Registry::get('url') */?>?ctl=Login&met=loginout"><i class="nav_href">大数据</i></a></li>
				<li><a href="<?/*= Yf_Registry::get('url') */?>?ctl=Login&met=loginout"><i class="nav_href">备份系统</i></a></li>
				<li><a href="<?/*= Yf_Registry::get('url') */?>?ctl=Login&met=loginout"><i class="nav_href">IM系统</i></a></li>-->
				<li><img src="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?=Perm::$userId?>"><div><span><?=Perm::$row['user_account']?></span><div></li>
				<li><a href="#"><i class="iconfont icon-top01"></i></a></li>
				<li><a href="<?= Yf_Registry::get('shop_api_url') ?>" target="_blank"><i class="iconfont icon-top02"></i></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Login&met=loginout"><i class="iconfont icon-top03"></i></a></li>
			</ol>
		</div>
	</div>

	<div class="col-bd">
		<div id="col-side">
			<div class="nav-wrap hidden cf"><!--商品-->
				<ul id="nav" class="cf">
					<?
						if(Perm::$row['user_account']=='yuyue'){
							$hidden='display:none';
						}else{
							$hidden='none';
						}
					?>
					<li class="item item-setting cur" style="<?=$hidden?>">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde02"></i>

							<p>设置</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde03"></i>

							<p>商品</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde04"></i>

							<p>店铺</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde05"></i>

							<p>会员</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde06"></i>

							<p>交易</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde07"></i>

							<p>运营</p><s></s></a>
					</li>
					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde08"></i>

							<p>促销</p><s></s></a>
					</li>

					<li class="item item-setting">
						<a href="javascript:void(0);" class="setting main-nav"><i class="iconfont icon-silde09"></i>
							<p>手机端</p><s></s></a>
					</li>
				</ul>
				<div id="sub-nav"><!--商城设置-->
					<ul class="cur cf" id="setting-base">
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site" rel="pageTab" tabid="base-setting"
													   tabtxt="基础设置">基础设置</a>
						</li>

						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=api&config_type%5B%5D=api" rel="pageTab" tabid="api-setting"
																  tabtxt="API设置">API设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=plugin&config_type%5B%5D=plugin" rel="pageTab" tabid="sys-setting"
													   tabtxt="系统工具">系统工具</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=shop&config_type%5B%5D=setting" rel="pageTab" tabid="shop-setting"
													   tabtxt="商城设置">商城设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=photo&config_type%5B%5D=photo" rel="pageTab" tabid="shop-photo"
													   tabtxt="图片设置">图片设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&config_type%5B%5D=seo" rel="pageTab" tabid="shop-seo"
													   tabtxt="SEO设置">SEO设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=News_Message&met=template&type=2" rel="pageTab" tabid="Message-template" tabtxt="消息通知">消息通知</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Logistics_Express&met=express" rel="pageTab" tabid="Express-express" tabtxt="快递公司">快递公司</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Logistics_Waybill&met=tpl" rel="pageTab" tabid="Logistics_Waybill" tabtxt="运单模板">运单模板</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Base_Cron&met=index" rel="pageTab" tabid="base-cron" tabtxt="计划任务">计划任务</a>

						</li>

						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=siteTheme&config_type%5B%5D=site" rel="pageTab" tabid="site-theme" tabtxt="模板风格">模板风格</a>
						</li>

						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Article_Group&met=index" rel="pageTab" tabid="help_setting" tabtxt="帮助设置">帮助设置</a>
						</li>

						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Platform_Nav&met=index" rel="pageTab" tabid="system-navigation" tabtxt="页面导航">页面导航</a>
						</li>

						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=logistics&config_type%5B%5D=logistics&typ=e" rel="pageTab" tabid="logistics-setting"
													   tabtxt="物流查询设置">物流设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Base&met=index" rel="pageTab" tabid="shop-setting"
													   tabtxt="权限设置">权限设置</a>
						</li><!--
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Subsite_Config&met=index" rel="pageTab" tabid="sub-site-setting"
													   tabtxt="分站管理">分站管理</a>
						</li>-->
					</ul>

					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common" rel="pageTab" tabid="shop-goods-common"
													   tabtxt="商品管理">商品管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Cat&met=cat" rel="pageTab" tabid="shop-goods-cat"
													   tabtxt="分类管理">分类管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Brand&met=brand" rel="pageTab" tabid="shop-goods-brand"
													   tabtxt="品牌管理">品牌管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Type&met=type" rel="pageTab" tabid="shop-goods-type"
													   tabtxt="类型管理">类型管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Spec&met=spec" rel="pageTab" tabid="shop-goods-spec"
													   tabtxt="规格管理">规格管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Album&met=album" rel="pageTab" tabid="shop-goods-album"
													   tabtxt="图片空间">图片空间</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=recommend" rel="pageTab" tabid="shop-goods-recommend"
													   tabtxt="商品推荐">商品推荐</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Bespeak&met=bespeak" rel="pageTab" tabid="shop-goods-bespeak"
													   tabtxt="预约管理">预约管理</a>
						</li>
					</ul>
					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Manage&met=indexs" rel="pageTab" tabid="shop-goods"
													   tabtxt="店铺管理">店铺管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Grade&met=indexs" rel="pageTab" tabid="Store_level"
													   tabtxt="店铺等级">店铺等级</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Class&met=indexs" rel="pageTab" tabid="Store_Cat"
													   tabtxt="店铺分类">店铺分类</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=domain&config_type%5B%5D=domain" rel="pageTab" tabid="Store_Domain"
													   tabtxt="二级域名">二级域名</a>
						</li>
						<!--						<li>
													<i class="iconfont"></i><a data-right="BU_QUERY" href="<--?= Yf_Registry::get('url') ?>?ctl=Shop_Dynamic&met=indexs" rel="pageTab" tabid="Store_Dynamic"
																			   tabtxt="店铺动态">店铺动态</a>
												</li>-->
						<!--						<li>
													<i class="iconfont"></i><a data-right="BU_QUERY" href="<--?= Yf_Registry::get('url') ?>?ctl=Shop_Help&met=indexs" rel="pageTab" tabid="Store_Help"
																			   tabtxt="店铺帮助">店铺帮助</a>
												</li>-->
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=join_slider&config_type%5B%5D=join_slider" rel="pageTab" tabid="join_slider"
													   tabtxt="商家入驻">商家入驻</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Selfsupport&met=indexs" rel="pageTab" tabid="Store_Selfsupport"
													   tabtxt="自营店铺">自营店铺</a>
						</li>
					</ul>

					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Info&met=info" rel="pageTab" tabid="User_Info"
													   tabtxt="会员管理">会员管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Grade&met=grade" rel="pageTab" tabid="User_Grade"
													   tabtxt="等级经验值">等级经验值</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Points&met=points" rel="pageTab" tabid="User_Points"
													   tabtxt="积分管理">积分管理</a>
						</li>
						<!--<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Shared&met=shared" rel="pageTab" tabid="User_Shared"
													   tabtxt="分享绑定">分享绑定</a>
						</li> -->
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Tag&met=tag" rel="pageTab" tabid="User_Tag"
													   tabtxt="会员标签">会员标签</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User_Message&met=message" rel="pageTab" tabid="User_Message"
													   tabtxt="会员消息">会员消息</a>
						</li>
					</ul>
					<ul>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Order&met=order" rel="pageTab" tabid="shop-orders" tabtxt="商品订单">商品订单</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Order&met=virtualOrder" rel="pageTab" tabid="shop-orders-virtual" tabtxt="虚拟订单">虚拟订单</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Return&met=refundWait&otyp=1" rel="pageTab" tabid="return_cash" tabtxt="退款管理">退款管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Return&met=refundWait&otyp=2" rel="pageTab" tabid="return_goods" tabtxt="退货管理">退货管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Return&met=refundWait&otyp=3" rel="pageTab" tabid="return_virtual" tabtxt="虚拟订单退款">虚拟订单退款</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Consult&met=consult" rel="pageTab" tabid="consult_index" tabtxt="咨询管理">咨询管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Report&met=baseDo" rel="pageTab" tabid="shop-orders" tabtxt="举报管理">举报管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Order&met=evaluate" rel="pageTab" tabid="shop-orders" tabtxt="评价管理">评价管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i>
							<a data-right="BU_ORDER" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=1" rel="pageTab" tabid="shop-goods"
													   tabtxt="投诉管理">投诉管理</a>
						</li>
					</ul>
					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=operation&config_type%5B%5D=operation" rel="pageTab" tabid="operation-setting"
													   tabtxt="运营设置">运营设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=settlement" rel="pageTab" tabid="operation-settlement"
													   tabtxt="结算管理">结算管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=settlement&otyp=1" rel="pageTab" tabid="operation-virtual-account"
													   tabtxt="虚拟订单结算">虚拟订单结算</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Custom&met=custom" rel="pageTab" tabid="operation-custom"
													   tabtxt="平台客服">平台客服</a>
						</li>
<!--						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<-?= Yf_Registry::get('url') ?>?ctl=Operation_Card&met=card" rel="pageTab" tabid="operation-card"
													   tabtxt="平台充值卡">平台充值卡</a>
						</li>-->
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Delivery&met=delivery" rel="pageTab" tabid="operation-delivery"
													   tabtxt="物流自提服务站">物流自提服务站</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Contract&met=log" rel="pageTab" tabid="operation-log"
													   tabtxt="消费者保障服务">消费者保障服务</a>
						</li>
					</ul>

					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=promotion&config_type%5B%5D=promotion" rel="pageTab" tabid="promotion-voucher"
													   tabtxt="促销设定">促销设定</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=index" rel="pageTab" tabid="promotion-tg"
													   tabtxt="团购管理">团购管理</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_ActIncrease&met=increaseList" rel="pageTab" tabid="promotiona-acti"
													   tabtxt="加价购">加价购</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Discount&met=discountList" rel="pageTab" tabid="promotion-actd"
													   tabtxt="限时折扣">限时折扣</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_ActGift&met=index" rel="pageTab" tabid="promotion-actg"
													   tabtxt="店铺满即送">店铺满即送</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Points&met=goods" rel="pageTab" tabid="promotion-ptex"
													   tabtxt="积分兑换">积分兑换</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=get" rel="pageTab" tabid="promotion-voucher"
													   tabtxt="店铺代金券">店铺代金券</a>
						</li>
					</ul>


					<ul>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=mobileIndex&config_type%5B%5D=mobile" rel="pageTab" tabid="mobile-tpl-setting"
													   tabtxt="模板设置">模板设置</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Mb_CatImage&met=index" rel="pageTab" tabid="mobile-cat-img"
													   tabtxt="分类图片">分类图片</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Mb_TplLayout&met=app" rel="pageTab" tabid="mobile-app-install"
													   tabtxt="应用安装">应用安装</a>
						</li>
						<li>
							<i class="iconfont icon-point"></i><a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Mb_TplLayout&met=wx" rel="pageTab" tabid="mobile-qrcode"
													   tabtxt="微信二维码">微信二维码</a>
						</li>
					</ul>


				</div>
			</div>
		</div>

		<div id="col-main">
			<div id="main-bd">
				<div class="page-tab" id="page-tab"></div>
			</div>
		</div>
	</div>
</div>
<div id="selectSkin" class="shadow dn">
	<ul class="cf">
		<li><a id="skin-default"><span></span>
				<small>经典</small>
			</a></li>
		<li><a id="skin-blue"><span></span>
				<small>丰收</small>
			</a></li>
		<li><a id="skin-green"><span></span>
				<small>小清新</small>
			</a></li>
	</ul>
</div>
<!--暂时屏蔽未开发菜单-->
<script>
	$('.soon').click(function ()
	{
		parent.Public.tips({type: 2, content: '为防止测试人员乱改数据，演示站功能受限，暂时屏蔽。'});
	});
</script>
<script src="<?= $this->view->js ?>/controllers/default.js"></script>
default.js
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>



