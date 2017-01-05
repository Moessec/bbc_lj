<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
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
		companyName: 'WebPos收银系统',	//公司名称
		companyAddr: '',	//公司地址
		phone: '',	//公司电话
		fax: '',	//公司传真
		postcode: '',	//公司邮编
		startDate: '2015-11-18', //启用日期
		invEntryCount: '',//试用版单据分录数
		rights: {},//权限列表
		taxRequiredCheck: 0,
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
			standardExp: './webpos/static/default/css/img/icon_v_s_e.png', //标准版体验版
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

</script>
<link href="<?= $this->view->css ?>/base.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css ?>/default.css" rel="stylesheet" type="text/css" id="defaultFile">
<script src="<?= $this->view->js_com ?>/tabs.js?ver=20140430"></script>
</head>
<body>

<div id="container" class="cf">
    <div id="col-side">
        <img id="icon-vension" src="<?= $this->view->img?>/img/icon_v_s_e.png" alt="标准版体验版">
        <ul id="nav" class="cf static">
            <li class="item item-setting">
                <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=User&met=user" rel="pageTab" tabid="setting-member" tabtxt="会员" class="vip main-nav">
                    <i class="iconfont">&#xe60a;</i><p>会员</p><s></s><span class="arrow">&gt;</span>
                </a>
            </li>

            <li class="item item-purchase">
                <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=ordering" rel="pageTab" tabid="setting-sales" tabtxt="下单" class="purchase main-nav">
                    <i class="iconfont">&#xe627;</i><p>下单</p><s></s><span class="arrow">&gt;</span>
                </a>
            </li>
            <li class="item item-sales">
                <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=orderList" rel="pageTab" tabid="setting-order" tabtxt="订单" class="sales main-nav">
                    <i class="iconfont">&#xe625;</i><p>订单</p><s></s><span class="arrow">&gt;</span>
                </a>
            </li>
            <li class="item item-storage">
                <a href="javascript:void(0);"  class="report main-nav">
                    <i class="iconfont">&#xe621;</i><p>报表</p><s></s><span class="arrow">&gt;</span>
                </a>
                <div class="new_sub_nav">
                    <ul class="cf" id="setting-base">
                        <li>
                            <i class="iconfont"></i>
                            <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=daily" rel="pageTab" tabid="setting-baseConfig" tabtxt="日销售">日销售</a>
                            <em></em>
                        </li>
                        <li>
                            <i class="iconfont"></i>
                            <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=weekly" rel="pageTab" tabid="setting-baseConfig" tabtxt="周销售">周销售</a>
                            <em></em>
                        </li>
                        <li>
                            <i class="iconfont"></i>
                            <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=monthly" rel="pageTab" tabid="setting-baseConfig" tabtxt="月销售">月销售</a>
                            <em></em>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>

    <div id="col-main">
        <div id="main-hd" class="cf">
            <div class="tit"> <a class="company" id="companyName" href="javascript:;" title=""></a> <span class="period" id="period"></span> </div>
            <ul class="user-menu">
                <li class="qq"><a href="" onclick="return false;" id="wpa">你好，<?=Perm::$row['user_account']?></a></li>
                <li class="space">|</li>
                <li><a href="<?=Yf_Registry::get('url')?>?ctl=Login&met=loginout">退出</a></li>
            </ul>
        </div>
        <div id="main-bd"><div class="page-tab" id="page-tab"></div></div>
    </div>
</div>




<!--暂时屏蔽未开发菜单-->
<script>
	$('.soon').click(function ()
	{
		parent.Public.tips({type: 2, content: '为防止测试人员乱改数据，演示站功能受限，暂时屏蔽。'});
	});
</script>
<script src="<?= $this->view->js ?>/controllers/default.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>



