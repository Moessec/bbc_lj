function initField()
{
    if (rowData.bespeak_id)
    {
        $("#bespeak_title").val(rowData.bespeak_title);
        $("#bespeak_com").val(rowData.bespeak_com);
        $("#opentime").val(rowData.opentime);
        $("#bes_img").val(rowData.bespeak_img);
        $("#true_name").val(rowData.true_name);
        $("#usercontact").val(rowData.usercontact);
        $("#bespeak_img").attr('src',rowData.bespeak_img);
        $("#some_class_1").val(rowData.opentime);
        $("#some_class_2").val(rowData.outtime);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.bespeak_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var bespeak_title = $.trim($("#bespeak_title").val()),
        bespeak_com = $.trim($("#bespeak_com").val()),
        outtime = $.trim($("#some_class_2").val()),
        bes_img = $.trim($("#bes_img").val()),
        true_name = $.trim($("#true_name").val()),
        usercontact = $.trim($("#usercontact").val()),
        opentime = $.trim($("#some_class_1").val()),
        n = "add" == t ? "新增预约" : "修改预约";

    params = rowData.bespeak_id ? {
        bespeak_id: e,
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        opentime : opentime,
        true_name : true_name,
        usercontact : usercontact,
        outtime : outtime,
        bes_img : bes_img
    } : {
        bespeak_title: bespeak_title,
        true_name : true_name,
        bespeak_com : bespeak_com,
        bes_img : bes_img,
        outtime : outtime,
        usercontact : usercontact,
        opentime : opentime,
    };
    console.log(params);
    Public.ajaxPost(SITE_URL +"?ctl=Goods_Bespeak&typ=json&met=" + ("add" == t ? "addGoodsbespeak" : "editGoodsbespeak"), params, function (e)
    {
        if (200 == e.status)
        {
            parent.parent.Public.tips({content: n + "成功！"});
            callback && "function" == typeof callback && callback(e.data, t, window)
        }
        else
        {
            parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
        }
    })
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#bespeak_title").val("");
    $("#bespeak_com").val("");
    $("#some_class_1").val("");
    $("#bes_img").val("");
    $("#true_name").val("");
    $("#some_class_2").val("");
    $("#usercontact").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
$("#area_info").on("click", function ()
{
    $.areaSelected({
        success: function (a)
        {
            $("#area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
        }
    })
})
initPopBtns();
initField();