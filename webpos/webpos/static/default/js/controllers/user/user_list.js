	function initEvent()
	{	
		//筛选会员
		$_matchCon = $("#matchCon"), 
		$_matchCon.placeholder(), 
		$("#search").on("click", function (a)
		{
			a.preventDefault();
			var b = "用户名" === $_matchCon.val() ? "" : $.trim($_matchCon.val());
			$("#grid").jqGrid("setGridParam", {page: 1, postData: {skey: b}}).trigger("reloadGrid")
		});

		//刷新页面
		$("#btn-refresh").click(function (t)
		{
			t.preventDefault();
			$("#grid").trigger("reloadGrid")
		});
		
		//操作，修改会员信息
		$("#grid").on("click", ".operating .ui-icon-pencil", function (t)
		{
			t.preventDefault();
			if (Business.verifyRight("INVLOCTION_UPDATE"))
			{
				var e = $(this).parent().data("id");
				handle.operate("edit", e)
			}
		});

		//重置表格
		$(window).resize(function ()
		{
			Public.resizeGrid()
		})
	}
	
	function initGrid()
	{
		var t = ["用户名","手机","性别","会员卡号","会员积分","生日","QQ", "邮箱"],
			e = [
				{name: "user_name", index: "user_name", align: "center", width: 120},
				{name: "user_mobile", index: "user_mobile", align: "center", width: 100},
				{name: "user_sex", index: "user_sex", align: "center", width:40},
				{name: "user_cardnum", index: "user_cardnum", align: "center", width: 150},
				{name: "user_points", index: "user_points", align: "center", width: 65},
				{name: "user_birth", index: "user_birth", align: "center", width: 70},
				{name: "user_qq", index: "user_qq", align: "center", width: 100},
				{name: "user_email", index: "user_email", align: "center", width: 150}
			];
    
		$("#grid").jqGrid({
            url: SITE_URL + '?ctl=User&met=getUserList&typ=json',
			datatype: "json",
			height: Public.setGrid().h,
			colNames: t,
			colModel: e,
			autowidth: !0,
			pager: "#page",
			viewrecords: !0,
			cmTemplate: {sortable: !1, title: !1},
			page: 1,
			rowNum: 50,
			rowList: [50, 100, 200],
			shrinkToFit: !1,
			jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "user_id"},
			loadComplete: function (t)
			{
				if (t && 200 == t.status)
				{
					var e = {};
					t = t.data;
					for (var i = 0; i < t.items.length; i++)
					{
						var a = t.items[i];
						e[a.user_id] = a;
					}
					$("#grid").data("gridData", e);
					0 == t.items.length && parent.Public.tips({type: 2, content: "没有会员数据！"})
				}
				else
				{
					parent.Public.tips({type: 2, content: "获取会员数据失败！" + t.msg})
				}
			},
			loadError: function ()
			{
				parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
			}
		})
	}

	var handle = 
	{
		operate: function (t, e)
		{
			if ("add" == t)
			{
				var i = "新增会员", a = {oper: t, callback: this.callback};
			}
            else if('edit' == t)
			{
				var i = "修改会员", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
			}
			$.dialog({
				title: i,
                content: "url:"+SITE_URL + '?ctl=User&met=userManage&typ=e',
				data: a,
				width: 640,
				height: 400,
				max: !1,
				min: !1,
				cache: !1,
				lock: !0
			})
		}, callback: function (t, e, i)
		{
			var a = $("#grid").data("gridData");
			if (!a)
			{
				a = {};
				$("#grid").data("gridData", a)
			}
			a[t.user_id] = t;
			if ("edit" == e)
			{
				$("#grid").jqGrid("setRowData", t.user_id, t);
				i && i.api.close()
			}
			else
			{
				$("#grid").jqGrid("addRowData", t.user_id, t, "last");
				i && i.api.close()
			}
		}
	};
	
	initEvent();
	initGrid(); //初始化表格