define(["jquery", "print"],
function(a, b, c) {
	function d() {
		Business.filterCustomer(),
		Business.filterGoods(),
		Business.filterSaler(),
		Business.moreFilterEvent(),
		k("#conditions-trigger").trigger("click"),
		k("#filter-fromDate").val(l.beginDate || ""),
		k("#filter-toDate").val(l.endDate || ""),
		k("#filter-fromDeliveryDate").val(l.fromDeliveryDate || ""),
		k("#filter-toDeliveryDate").val(l.toDeliveryDate || ""),
		k("#filter-customer input").val(l.customerNo || ""),
		k("#filter-goods input").val(l.goodsNo || ""),
		l.beginDate && l.endDate && (k("#selected-period").text(l.beginDate + "至" + l.endDate), k("div.grid-subtitle").text("日期: " + l.beginDate + " 至 " + l.endDate)),
		k("#filter-fromDate, #filter-toDate, #filter-fromDeliveryDate, #filter-toDeliveryDate").datepicker();
		var a = k("#status-wrap").cssCheckbox();
		k("#filter-submit").on("click",
		function(b) {
			b.preventDefault();
			var c = k("#filter-fromDate").val(),
			d = k("#filter-toDate").val(),
			e = k("#filter-fromDeliveryDate").val(),
			f = k("#filter-toDeliveryDate").val();
			return c && d && new Date(c).getTime() > new Date(d).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (l = {
				beginDate: c,
				endDate: d,
				beginDeliveryDate: e,
				endDeliveryDate: f,
				customerNo: k("#filter-customer input").val() || "",
				goodsNo: k("#filter-goods input").val() || "",
				status: a.chkVal().join(),
				salesId: k("#filter-saler input").val() || ""
			},
			k("#selected-period").text(c + "至" + d), k("div.grid-subtitle").text("日期: " + c + " 至 " + d), j(), void k("#filter-menu").removeClass("ui-btn-menu-cur"))
		}),
		k("#filter-reset").on("click",
		function(b) {
			b.preventDefault(),
			k("#filter-fromDate").val(l.beginDate),
			k("#filter-toDate").val(l.endDate),
			k("#filter-fromDeliveryDate").val(l.fromDeliveryDate),
			k("#filter-toDeliveryDate").val(l.toDeliveryDate),
			k("#filter-customer input").val(""),
			k("#filter-goods input").val(""),
			k("#filter-saler input").val(""),
			a.chkNot()
		})
	}
	function e() {
		k("#refresh").on("click",
		function(a) {
			a.preventDefault(),
			k("#filter-submit").click()
		}),
		k("#btn-print").click(function(a) {
			a.preventDefault(),
			Business.verifyRight("SALESORDER_PRINT") && k("div.ui-print").printTable()
		}),
		k("#btn-export").click(function(a) {
			a.preventDefault(),
			Business.verifyRight("SALESORDER_EXPORT") && Business.getFile(m, l)
		}),
		k("#config").click(function(a) {
			o.config()
		})
	}
	function f() {
		var a = k(window).height() - k(".grid-wrap").offset().top - 65 - 70,
		b = [
		//{name: "invNo",label: "商品编号",frozen: !0,width: 80}, 
		{
			name: "name",
			label: "商品名称",
			frozen: !0,
			width: 200,
			classes: "ui-ellipsis",
			title: !0
		},
		{
			name: "spec_value",
			label: "规格型号",
			width: 60
		},
		//{name: "unit",label: "单位",width: 50,align: "center"},
		//{name: "date",label: "订单日期",width: 80,align: "center"},
		{
			name: "order_id",
			label: "销售订单编号",
			width: 150,
			align: "center"
		},
		{
			name: "billId",
			label: "销售订单ID",
			width: 0,
			hidden: !0
		},
		//{name: "salesName",label: "销售人员",width: 80}, 
		{
			name: "buName",
			label: "客户",
			width: 120,
			align:"center"
		},
		{
			name: "num",
			label: "销售数量",
			width: 80,
			align: "right"
		},
		{
			name: "price",
			label: "商品单价",
			width: 80,
			align: "right"
		},
		{
			name: "deduction",
			label: "折扣额",
			width: 80,
			align: "right"
		},
		{
			name: "tax",
			label: "税额",
			width: 80,
			align: "right"
		},
		{
			name: "taxAmount",
			label: "价税合计",
			width: 100,
			align: "right"
		},
		//{name: "unQty",label: "未出库数量",width: 80,align: "right"}, 
		{
			name: "createTime",
			label: "创建日期",
			width: 100,
			align: "center"
		},
		{
			name: "status",
			label: "状态",
			width: 60,
			formatter: statusFmatter,
		},
		//{name: "inDate",label: "出库日期",width: 80,align: "center"}
		],
		c = "local",
		d = "#";
		l.autoSearch && (c = "json", d = n),
		o.gridReg("grid", b),
		b = o.conf.grids.grid.colModel,
		k("#grid").jqGrid({
			url: d,
			postData: l,
			datatype: c,
			autowidth: !0,
			height: a,
			gridview: !0,
			colModel: b,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 3e3,
			loadonce: !0,
			viewrecords: !0,
			shrinkToFit: !1,
			forceFit: !0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(a) {
				var b = k("#grid").getRowData(a).billId;
				b && parent.tab.addTabItem({
					tabid: "sales-salesOrder",
					text: "销货订单",
					url: "/sales/salesOrder.jsp?id=" + b
				})
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.rows.length;
					b = c ? 31 * c: 1
				}
				g(b)
			},
			gridComplete: function() {
				var a = k("#grid").find('td[aria-describedby="grid_invNo"]');
				a.each(function(a) {
					var b = k(this);
					"&nbsp;" === b.html() && b.parent().addClass("fb")
				})
			},
			resizeStop: function(a, b) {
				o.setGridWidthByIndex(a, b + 1, "grid")
			}
		}),
		l.autoSearch ? (k(".no-query").remove(), k(".ui-print").show()) : k(".ui-print").hide()
	}
	
	function statusFmatter(a, b, c) {
		var d = a == 5 ? "已退款": "已成功",
		e = a == 5 ? "ui-label-default": "ui-label-success";
		return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + "</span>"
	}
	
	function g(a) {
		a && (g.h = a);
		var b = h(),
		c = k(window).height() - k(".grid-wrap").offset().top - 65 - 70,
		d = (i(), k("#grid"));
		k("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		}),
		d.jqGrid("setGridHeight", c),
		d.jqGrid("setGridWidth", b)
	}
	function h() {
		return k(window).width() - (h.offsetLeft || (h.offsetLeft = k("#grid-wrap").offset().left)) - 36 - 20
	}
	function i() {
		return k(window).height() - (i.offsetTop || (i.offsetTop = k("#grid").offset().top)) - 36 - 16
	}
	function j() {
		k(".no-query").remove(),
		k(".ui-print").show(),
		k("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: l,
			url: n
		}).trigger("reloadGrid")
	}
	var k = a("jquery"),
	l = (parent.SYSTEM, k.extend({
		beginDate: parent.SYSTEM.beginDate,
		endDate: parent.SYSTEM.endDate,
		fromDeliveryDate: "",
		toDeliveryDate: "",
		customerNo: "",
		goodsNo: "",
		status: ""
	},
	Public.urlParam())),
	m = "./order.php?action=detailExporter",
	n = "./order.php?action=detail";
	a("print");
	var o = Public.mod_PageConfig.init("salesOrderTracking");
	d(),
	e(),
	f();
	var p;
	k(window).on("resize",
	function(a) {
		p || (p = setTimeout(function() {
			g(),
			p = null
		},
		50))
	})
});