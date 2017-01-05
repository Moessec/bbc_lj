function initField()
{
    //初始化数据
    if (rowData.user_id)
    {
        $("#user_id").val(rowData.user_id);//会员名称
        $("#user_realname").val(rowData.user_realname);//会员真实姓名
        $("#user_email").val(rowData.user_email);//会员邮箱
        $("#user_mobile").val(rowData.user_mobile);//会员手机号码
        $("#user_qq").val(rowData.user_qq);//会员QQ
        $("#user_ww").val(rowData.user_ww);//会员旺旺
        $("#date").val(rowData.user_birth);//会员出生日期
        $("#identification").val(rowData.identification);//会员出生日期
        $("#user_points").val(rowData.user_points);//会员积分
        $("#user_cardnum").val(rowData.user_cardnum);//会员卡号
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
			user_name: "required;",
			user_email: "email;",
			user_mobile: "integer[+];",
			user_qq: "integer[+];"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增会员" : "修改会员", b = getData(), c = b.firstLink || {};
			delete b.firstLink, 
			Public.ajaxPost(SITE_URL + "?ctl=User&typ=json&met=" + ("add" == oper ? "add" : "edit"), b, function (e)
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
        user_id: cRowId.user_id,
        user_id: $.trim($("#user_id").val()),
        user_realname: $.trim($("#user_realname").val()),
		user_sex: sex.getValue(),
		user_points: $.trim($("#user_points").val()),
        user_email: $.trim($("#user_email").val()),
        user_mobile: $.trim($("#user_mobile").val()),
        user_qq: $.trim($("#user_qq").val()),
        user_ww: $.trim($("#user_ww").val()),
        user_birth: $.trim($("#date").val()),
		identification:$.trim($("#identification").val())
    } : {
        user_id: $.trim($("#user_id").val()),
        user_realname: $.trim($("#user_realname").val()),
		user_sex: sex.getValue(),
		user_points: $.trim($("#user_points").val()),
        user_email: $.trim($("#user_email").val()),
        user_mobile: $.trim($("#user_mobile").val()),
        user_qq: $.trim($("#user_qq").val()),
        user_ww: $.trim($("#user_ww").val()),
        user_birth: $.trim($("#date").val()),
		identification:$.trim($("#identification").val())
	};
    return data.firstLink = link_info.firstLink, data
}

function getEntriesData()
{
	for (var a = {}, b = [], c = $grid.jqGrid("getDataIDs"), d = !1, e = 0, f = c.length; f > e; e++)
	{
		var g, h = c[e], i = $grid.jqGrid("getRowData", h);
		if ("" == i.user_contacter_name) break;
		g = {
			user_contacter_name: i.user_contacter_name,
            user_contacter_mobile: i.user_contacter_mobile,
            user_contacter_telephone: i.user_contacter_telephone,
            user_contacter_code: i.user_contacter_code
		};
		var j = $("#" + h).data("addressInfo") || {};
		g.user_contacter_province = j.province, 
		g.user_contacter_city = j.city, 
		g.user_contacter_county = j.county,
		g.user_contacter_address = j.address, 
		g.user_contacter_id = "edit" == oper ? h : 0, 
		b.push(g)
	}
	return  a.entriesData = b, a
}

function initEvent()
{ 
	var user_sex = rowData.user_sex-1;
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
		defaultSelected: user_sex || void 0
	}).getCombo();
 
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


function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, cRowId = api.data.rowData, rowData = api.data.rowData || {}, linksIds = [], callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns();
initField();
initEvent();