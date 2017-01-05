var queryConditions = {
	matchCon: ""
},
SYSTEM = system = parent.SYSTEM,
hiddenAmount = !1,
billRequiredCheck = system.billRequiredCheck,
THISPAGE = {
	init: function(a) {
		SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_OUTAMOUNT || (hiddenAmount = !0),
		this.mod_PageConfig = Public.mod_PageConfig.init("salesOrderList"),
		this.initDom(),
		this.loadGrid(),
		this.addEvent()
	},
	initDom: function() {
		this.$_matchCon = $("#matchCon"),
		this.$_beginDate = $("#beginDate").val(system.beginDate),
		this.$_endDate = $("#endDate").val(system.endDate),
		this.$_matchCon.placeholder(),
		this.$_beginDate.datepicker(),
		this.$_endDate.datepicker()
	},
	loadGrid: function() {
		function a(a, b, c) {
			var d = '<div class="operating" data-id="' + c.order_id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
			return d
		}
		function b(a, b, c) {
			return 150601 === a ? "销货": (d.markRow.push(c.order_id), "退货")
		}
		var c = Public.setGrid(),
		d = this;
		queryConditions.beginDate = this.$_beginDate.val(),
		queryConditions.endDate = this.$_endDate.val(),
		d.markRow = [];

		function statusFmatter(a, b, c) {
			var d = a == 1 ? "有退货": "",
			e = a == 1 ? "ui-label-default": "";
			return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + "</span>"
		}
		
		var e = [{
			name: "operating",
			label: "操作",
			width: 35,
			fixed: !0,
			formatter: a,
			align: "center",
			sortable: !1
		},
		{
			name: "order_create_time",
			label: "订单日期",
			index: "order_create_time",
			width: 150,
			align: "center"
		},
		{
			name: "order_id",
			label: "订单编号",
			index: "order_id",
			width: 200,
			align: "center"
		},
		{
			name: "shop_name",
			label: "销售人员",
			width: 80,
			align: "center"
		},
		{
			name: "buyer_user_name",
			label: "客户",
			index: "buyer_user_name",
			width: 110,
			align: "center"
		},
		{
			name: "order_goods_num",
			label: "总数量",
			index: "order_goods_num",
			width: 70,
			align: "center"
		},
		{
			name: "discount_rate",
			label: "优惠率",
			index: "discount_rate",
			width: 70,
			align: "right"
		},
		{
			name: "order_discount_fee",
			label: "优惠金额",
			index: "order_discount_fee",
			width: 70,
			align: "right",
			formatter: "currency"
		},
		{
			name: "order_payment_amount",
			label: "付款金额",
			index: "order_payment_amount",
			width: 70,
			align: "right",
			formatter: "currency"
		},
		{
			name: "return",
			label: "备注",
			index: "des",
			width: 150,
			title: !0,
			sortable: !1,
			formatter:statusFmatter,
			align:'center'
		},
		{
			name: "disEditable",
			label: "不可编辑",
			index: "disEditable",
			hidden: !0
		}];

		this.mod_PageConfig.gridReg("grid", e),
		e = this.mod_PageConfig.conf.grids.grid.colModel,
		$("#grid").jqGrid({
            url: SITE_URL + '?ctl=Order&met=getOrderList&typ=json',
			postData: queryConditions,
			datatype: "json",
			autowidth: !0,
			height: c.h,
			altRows: !0,
			gridview: !0,
			multiselect: !0,
			colModel: e,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			pager: "#page",
			rowNum: 100,
			rowList: [100, 200, 500],
			viewrecords: !0,
			shrinkToFit: !1,
			forceFit: !1,
			jsonReader: {
				root: "data.items",
				records: "data.records",
				total: "data.total",
				repeatitems: !1,
				id: "order_id"
			},
			loadComplete: function(a) {
				var b = d.markRow.length;
				if (b > 0) for (var c = 0; b > c; c++) $("#" + d.markRow[c]).addClass("red")
			},
			loadError: function(a, b, c) {},
			ondblClickRow: function(a, b, c, d) {
				$("#" + a).find(".ui-icon-pencil").trigger("click")
			},
			resizeStop: function(a, b) {
				THISPAGE.mod_PageConfig.setGridWidthByIndex(a, b, "grid")
			}
		}).navGrid("#page", {
			edit: !1,
			add: !1,
			del: !1,
			search: !1,
			refresh: !1
		}).navButtonAdd("#page", {
			caption: "",
			buttonicon: "ui-icon-config",
			onClickButton: function() {
				THISPAGE.mod_PageConfig.config()
			},
			position: "last"
		})
	},
	reloadData: function(a) {
		this.markRow = [],
		$("#grid").jqGrid("setGridParam", {
         url: SITE_URL + '?ctl=Order&met=getOrderList&typ=json',
         datatype: "json",
		 postData: a
		}).trigger("reloadGrid")
	},
	addEvent: function() {
		var a = this;
        //查看订单详情信息
		if ($(".grid-wrap").on("click", ".ui-icon-pencil",
		function(a) {
			a.preventDefault();
			var b = $(this).parent().data("id"),
			c = $("#grid").jqGrid("getRowData", b),
			d = 1 == c.disEditable ? "&disEditable=true": "";
			parent.tab.addTabItem({
				tabid: "sales-salesOrder",
				text: "下单记录",
				url: SITE_URL + "?ctl=Order&met=getOrderInfo&id=" + b
			});
			$("#grid").jqGrid("getDataIDs");
			parent.cacheList.salesOrderId = $("#grid").jqGrid("getDataIDs")
		}));

		//刷新页面
		$("#btn-refresh").click(function (t)
		{
			t.preventDefault();
			$("#grid").trigger("reloadGrid")
		});
		
		$("#search").click(function() {
			queryConditions.salesId = null,
			queryConditions.matchCon = "请输入订单编号" === a.$_matchCon.val() ? "": $.trim(a.$_matchCon.val()),
			queryConditions.beginDate = a.$_beginDate.val(),
			queryConditions.endDate = a.$_endDate.val(),
			THISPAGE.reloadData(queryConditions)
		}),
		$("#refresh").click(function() {
			THISPAGE.reloadData(queryConditions)
		}),
		
		$("#add").click(function(a) {
			a.preventDefault(),
			Business.verifyRight("SO_ADD") && parent.tab.addTabItem({
				tabid: "sales-salesOrder",
				text: "下单",
				url: SITE_URL+"?ctl=Order&met=ordering"
			})
		}),
		$(window).resize(function() {
			Public.resizeGrid()
		})
	}
};
$(function() {
	THISPAGE.init()
});