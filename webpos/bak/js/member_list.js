	function initEvent()
	{	
		//筛选会员
		$_matchCon = $("#matchCon"), 
		$_matchCon.placeholder(), 
		$("#search").on("click", function (a)
		{
			a.preventDefault();
			var b = "输入会员卡号/手机号/身份证号/昵称" === $_matchCon.val() ? "" : $.trim($_matchCon.val());
			$("#grid").jqGrid("setGridParam", {page: 1, postData: {skey: b}}).trigger("reloadGrid")
		});
		
		/* //新增会员
		$("#btn-add").click(function (t)
		{
			t.preventDefault();
			Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
		}); */
		
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
		
		//操作，删除会员
		$("#grid").on("click", ".operating .ui-icon-trash", function (t)
		{
			t.preventDefault();
			if (Business.verifyRight("INVLOCTION_DELETE"))
			{
				var e = $(this).parent().data("id");
				handle.del(e)
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
		var t = ["操作",/*  "会员名称",  */"会员卡号", "会员积分", "姓名/昵称", "性别", "出生日期", "手机", "身份证号","QQ", "邮箱"], 
			e = [
				{name: "operate",width:35,fixed: !0,align: "center",formatter: Public.operFmatter}, 
				/* {name: "member_name", index: "member_name", align: "center", width: 150}, */
				{name: "member_cardnum", index: "member_cardnum", align: "center", width: 110},
				{name: "member_points", index: "member_points", align: "center", width: 65},
				{name: "member_realname", index: "member_realname", align: "center", width: 110},
				{name: "member_sex", index: "member_sex", align: "center", width:40,formatter: function (a, b, c){return a == 1 ? "男" : (a == 2 ? "女" : "未填");}},
				{name: "member_birth", index: "member_birth", align: "center", width: 70},
				{name: "member_mobile", index: "member_mobile", align: "center", width: 95},
				{name: "identification", index: "identification", align: "center", width: 145},
				{name: "member_qq", index: "member_qq", align: "center", width: 100},
				{name: "member_email", index: "member_email", align: "center", width: 150}
			];
    
		$("#grid").jqGrid({
			url: "./member.php?op=index",
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
			jsonReader: {root: "data.rows", records: "data.records", total: "data.total", repeatitems: !1, id: "member_id"},
			loadComplete: function (t)
			{
				if (t && 200 == t.status)
				{
					var e = {};
					t = t.data;
					for (var i = 0; i < t.rows.length; i++)
					{
						var a = t.rows[i];
						e[a.member_id] = a;
					}
					$("#grid").data("gridData", e);
					0 == t.rows.length && parent.Public.tips({type: 2, content: "没有会员数据！"})
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
			}else
			{
				var i = "修改会员", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
			}
			$.dialog({
				title: i,
				content: "url:./member.php?op=manage",
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
			a[t.member_id] = t;
			if ("edit" == e)
			{
				$("#grid").jqGrid("setRowData", t.member_id, t);
				i && i.api.close()
			}
			else
			{
				$("#grid").jqGrid("addRowData", t.member_id, t, "last");
				i && i.api.close()
			}
		}, del: function (t)
		{
			$.dialog.confirm("删除的会员将不能恢复，请确认是否删除？", function ()
			{
				Public.ajaxPost("./member.php?met=remove&typ=json", {member_id: t}, function (e)
				{
					if (e && 200 == e.status)
					{
						parent.Public.tips({content: "会员删除成功！"});
						$("#grid").jqGrid("delRowData", t)
					}
					else
					{
						parent.Public.tips({type: 1, content: "会员删除失败！" + e.msg})
					}
				})
			})
		}
	};
	
	initEvent();
	initGrid();