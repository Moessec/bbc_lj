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

$('#area_1').on('change', function () {
    $('#_area_1').val($(this).val());
    if ( this.value == 0 ){
        if ( $('#area_2').length > 0 ) {
            $('#area_2').remove();
        }
    } else {
        $('#area_2').remove();
        var $this = $(this), pid = $(this).val(), BigCity = [1, 2, 9, 22];

        //排除直辖市
        console.log(pid);
        if( $.inArray(Number(pid), BigCity) == -1  ) {
            $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (data) {
                console.log(SITE_URL);
                var data = data.data;
                console.log(data);
                if (data.items && data.items.length > 0) {
                    var options = null, select = null;
                    for ( var i = 0; i < data.items.length; i++ ) {
                        if ( i == 0 ) $('#_area_2').val(data.items[i]['district_id']);
                        options += '<option value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                    }
                    select = '<select id="area_2" class="valid">' + options + '</select>';

                    $this.after( select );
                }
            });
        }
    }
});

$('#area_2').on('change', function () {
    $('#_area_2').val($(this).val());
    if ( this.value == 0 ){
        if ( $('#area_3').length > 0 ) {
            $('#area_3').remove();
        }
    } else {
        $('#area_3').remove();
        var $this = $(this), pid = $(this).val(), BigCity = [];

        //排除直辖市
        console.log(pid);
        if( $.inArray(Number(pid), BigCity) == -1  ) {
            $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (data) {
                console.log(SITE_URL);
                var data = data.data;
                console.log(data);
                if (data.items && data.items.length > 0) {
                    var options = null, select = null;
                    for ( var i = 0; i < data.items.length; i++ ) {
                        if ( i == 0 ) $('#_area_3').val(data.items[i]['district_id']);
                        options += '<option value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                    }
                    select = '<select id="area_3" class="valid">' + options + '</select>';

                    $this.after( select );
                }
            });
        }
    }
});

    $('#area_1').parent().on(' change', '#area_2', function () {
        $('#_area_2').val($(this).val());
    });
    $('#area_2').parent().on(' change', '#area_3', function () {
        $('#_area_3').val($(this).val());
    });

var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;

initPopBtns();
initField();