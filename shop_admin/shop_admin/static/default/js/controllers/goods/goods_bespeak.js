function initField()
{
    if (rowData.bespeak_id)
    {
        $("#bespeak_title").val(rowData.bespeak_title);
        $("#bespeak_com").val(rowData.bespeak_com);
        $("#opentime").val(rowData.opentime);
        $("#usercontact").val(rowData.usercontact);
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
        img = $.trim($("#img").val()),
        usercontact = $.trim($("#usercontact").val()),
        opentime = $.trim($("#some_class_1").val()),
        n = "add" == t ? "新增预约" : "修改预约";

    params = rowData.bespeak_id ? {
        bespeak_id: e,
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        opentime : opentime,
        usercontact : usercontact,
        outtime : outtime,
        img : img
    } : {
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        img : img,
        outtime : outtime,
        usercontact : usercontact,
        opentime : opentime,
    };
    console.log(url_path);
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
    $("#img").val("");
    $("#some_class_2").val("");
    $("#usercontact").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();