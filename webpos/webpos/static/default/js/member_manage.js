function init()
{
    typeof cRowId != "undefined" ? Public.ajaxPost("./member.php?met=get", {
		member_id: cRowId.member_id
	}, function (rs)
    {
        200 == rs.status ? (rowData = rs.data, initField(), initEvent()) : parent.$.dialog({
            title: "系统提示",
            content: "获取会员数据失败，暂不能修改会员，请稍候重试",
            icon: "alert.gif",
            max: !1,
            min: !1,
            cache: !1,
            lock: !0,
            ok: "确定",
            ok: function ()
            {
                return !0
            },
            close: function ()
            {
                api.close()
            }
        })
    }) : (initField(), initEvent())
}

function initPopBtns()
{
    var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: a[0], focus: !0, callback: function ()
        {
            return cancleGridEdit(), $_form.trigger("validate"), !1
        }
    }, {id: "cancel", name: a[1]})
}

function initValidator()
{
    $_form.validator({
        messages: {
			required: "请填写{0}"
		},
        fields: {
			member_name: "required;",
			member_email: "email;",
			member_mobile: "integer[+];",
			member_qq: "integer[+];"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增会员" : "修改会员", b = getData(), c = b.firstLink || {};
			delete b.firstLink, 
			
			Public.ajaxPost("./member.php?met=" + ("add" == oper ? "add" : "edit"), b, function (e)
			{	
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: a + "成功！"});
					callback && "function" == typeof callback && callback(e.data, oper, window)
				}
				else
				{
					parent.parent.Public.tips({type: 1, content: a + "失败！" + e.msg})
				}
			})
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    })
}

function getData()
{
    var link_info = getEntriesData(), links = link_info.entriesData, data = cRowId ? {
        member_id: cRowId.member_id,
        member_id: $.trim($("#member_id").val()),
        member_realname: $.trim($("#member_realname").val()),
		member_sex: sex.getValue(),
		member_points: $.trim($("#member_points").val()),
        member_email: $.trim($("#member_email").val()),
        member_mobile: $.trim($("#member_mobile").val()),
        member_qq: $.trim($("#member_qq").val()),
        member_ww: $.trim($("#member_ww").val()),
        member_birth: $.trim($("#date").val()),
		identification:$.trim($("#identification").val())
    } : {
        member_id: $.trim($("#member_id").val()),
        member_realname: $.trim($("#member_realname").val()),
		member_sex: sex.getValue(),
		member_points: $.trim($("#member_points").val()),
        member_email: $.trim($("#member_email").val()),
        member_mobile: $.trim($("#member_mobile").val()),
        member_qq: $.trim($("#member_qq").val()),
        member_ww: $.trim($("#member_ww").val()),
        member_birth: $.trim($("#date").val()),
		identification:$.trim($("#identification").val())
	};
    return data.firstLink = link_info.firstLink, data
}

function getEntriesData()
{
	for (var a = {}, b = [], c = $grid.jqGrid("getDataIDs"), d = !1, e = 0, f = c.length; f > e; e++)
	{
		var g, h = c[e], i = $grid.jqGrid("getRowData", h);
		if ("" == i.member_contacter_name) break;
		g = {
			member_contacter_name: i.member_contacter_name,
            member_contacter_mobile: i.member_contacter_mobile,
            member_contacter_telephone: i.member_contacter_telephone,
            member_contacter_code: i.member_contacter_code
		};
		var j = $("#" + h).data("addressInfo") || {};
		g.member_contacter_province = j.province, 
		g.member_contacter_city = j.city, 
		g.member_contacter_county = j.county,
		g.member_contacter_address = j.address, 
		g.member_contacter_id = "edit" == oper ? h : 0, 
		b.push(g)
	}
	return  a.entriesData = b, a
}
function initField()
{	
	//初始化数据
    if (rowData.member_id)
    {
 		$("#member_id").val(rowData.member_id);//会员名称
        $("#member_realname").val(rowData.member_realname);//会员真实姓名
        $("#member_email").val(rowData.member_email);//会员邮箱
        $("#member_mobile").val(rowData.member_mobile);//会员手机号码
        $("#member_qq").val(rowData.member_qq);//会员QQ
        $("#member_ww").val(rowData.member_ww);//会员旺旺
        $("#date").val(rowData.member_birth);//会员出生日期
		$("#identification").val(rowData.identification);//会员出生日期
        $("#member_points").val(rowData.member_points);//会员积分
		$("#member_cardnum").val(rowData.member_cardnum);//会员卡号
		if(rowData.operator){
			$("#operator").val(rowData.operator);//操作员
		}
		
    }
    else
    {	
		//系统默认开始时间
		$("#date").val(parent.parent.SYSTEM.startDate);
    }
}

function initEvent()
{ 
	var member_sex = rowData.member_sex-1;
	sex = $("#sex").combo({
		data: [{
			id: "1",
			name: "男"
		}, {
			id: "2",
			name: "女"
		}, {
			id: "0",
			name: "未填"
		}],
		value: "id",
		text: "name",
		width: 210,
		defaultSelected: member_sex || void 0
	}).getCombo();
 
	/* $("#salesman").data("defItem",["employee_id",rowData.member_salesman]);
	salesman = $("#salesman").combo({
		data: "./member.php?op=manage&met=queryAllEmployee&typ=json",
		ajaxOptions: {
			formatData: function (e)
			{	
				return e.data.rows;
			}
		},
		value: "employee_id",
		text: "employee_name",
		width: 210,
		defaultSelected: rowData.member_salesman ? $("#salesman").data("defItem") : void 0,
		editable: true,
		maxListWidth: 500,
	}).getCombo();
	 */
	var b = $("#date");
    b.blur(function ()
    {
        "" == b.val() && b.val(parent.parent.SYSTEM.startDate)
    }), b.datepicker({
        onClose: function ()
        {
            var a = /^\d{4}-((0?[1-9])|(1[0-2]))-\d{1,2}/;
            a.test(b.val()) || b.val("")
        }
    });

	/* $(".grid-wrap").on("click", ".ui-icon-ellipsis", function (a)
    {
        a.preventDefault();
        var b = $(this).siblings(),
			c = $(this).closest("tr"), 
			d = c.data("addressInfo");
        parent.$.dialog({
            title: "联系地址",
            content: "url:./member.php?op=manage&met=index",
            data: {
                rowData: d, callback: function (a, d)
                {
                    if (a)
                    {
                        var e = {};
                        e.province = a.province || "", 
						e.city = a.city || "", 
						e.county = a.area || "", 
						e.address = a.address || "", 
						b.val(e.province + e.city + e.county + e.address), 
						c.data("addressInfo", e);
                    }
                    d.close()
                }
            },
            width: 640,
            height: 210,
            min: !1,
            max: !1,
            cache: !1,
            lock: !1
        })
    }),  */
	$(document).on("click.cancle", function (a)
    {
        var b = a.target || a.srcElement;
        !$(b).closest("#grid").length > 0 && cancleGridEdit()
    }), bindEventForEnterKey(), initValidator()
}

function bindEventForEnterKey()
{
    Public.bindEnterSkip($("#base-form"), function ()
    {
        $("#grid tr.jqgrow:eq(0) td:eq(0)").trigger("click")
    })
}
/* function initGrid(links)
{
    if (links || (links = []), links.length < 4)
    {
        for (var b = 4 - links.length, c = 0; b > c; c++)
        {
            links.push({});
        }
    }
    links.push({}), 
	$grid.jqGrid({
        data: links,
        datatype: "local",
        width: 910,
        gridview: !0,
        onselectrow: !1,
        colModel: [
		{	
			name: "member_contacter_name", 
			label: "联系人", 
			width: 100, 
			title: !1, 
			editable: !0
		}, 
		{
            name: "member_contacter_mobile",
            label: "手机",
            width: 150,
            title: !1,
            editable: !0
        }, 
		{
			name: "member_contacter_telephone", 
			label: "座机", 
			width: 150, 
			title: !1, 
			editable: !0
		},
		{
            name: "member_contacter_address",
            label: "联系地址",
            width: 300,
            title: !0,
            formatter: addressFmt,
            classes: "ui-ellipsis",
            editable: !0,
            edittype: "custom",
            editoptions: {
                custom_element: addressElem,
                custom_value: addressValue,
                handle: addressHandle,
                trigger: "ui-icon-ellipsis"
            }
        }, 
		{
			name: "member_contacter_code", 
			label: "邮编", 
			width: 100, 
			title: !1, 
			editable: !0
		}],
        cmTemplate: {sortable: !1},
        shrinkToFit: !0,
        forceFit: !0,
        cellEdit: !0,
        cellsubmit: "clientArray",
        localReader: {root: "items", records: "records", repeatitems: !0, id: 'id'},
        loadComplete: function (link_data)
        {
            if ($grid.setGridHeight($grid.height() > 185 ? "185" : "auto"), $grid.setGridWidth(910), "add" != oper)
            {
                if (!link_data || !link_data.items)
                {
                    return void(linksIds = []);
                }
                linksIds = [];
                for (var items = link_data.items, c = 0; c < items.length; c++)
                {
                    var item = items[c];
                    if (item.id)
                    {
                        linksIds.push(Number(item.id));
                        var e = {
							province: item.member_contacter_province, 
							city: item.member_contacter_city, 
							county: item.member_contacter_county, 
							address: item.member_contacter_address
						};
                        $("#" + item.id).data("addressInfo", e);
                    }
                }
            }
        },
        afterEditCell: function (a, b, c)
        {
            $("#" + a).find("input").val(c)
        },
        afterSaveCell: function (a, b, c)
        {
            if ("first" == b && (c = "boolean" == typeof c ? c ? "1" : "0" : c, "1" === c))
            {
                for (var d = $grid.jqGrid("getDataIDs"), e = 0; e < d.length; e++)
                {
                    var f = d[e];
                    f != a && $grid.jqGrid("setCell", f, "first", "0")
                }
            }
        }
    })
}
function addressFmt(a, b, c)
{
	if (!c.member_contacter_address)
    {
		if(a)
		  return a;
    }
    var d = {};
    return d.province = c.member_contacter_province || "", 
	d.city = c.member_contacter_city || "", 
	d.county = c.member_contacter_county || "",
	d.address = c.member_contacter_address || "",
	$("#" + c.id).data("addressInfo", d), 
	d.province + d.city + d.county + d.address || "&#160;"
}
function addressElem()
{
    var a = $(".address")[0];
    return a
}
function addressValue(a, b, c)
{
    if ("get" === b)
    {
        var d = $.trim($(".address").val());
        return "" !== d ? d : ""
    }
    "set" === b && $("input", a).val(c)
}
function addressHandle()
{
    $(".hideFile").append($(".address").val("").unbind("focus.once"))
} */
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, cRowId = api.data.rowData, rowData = {}, linksIds = [], callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns(), init();