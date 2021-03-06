function initField()
{
    if (rowData.bespeak_id)
    {
        $("#bespeak_title").val(rowData.bespeak_title);
        $("#bespeak_com").val(rowData.bespeak_com);
        $("#bes_img").val(rowData.bespeak_img);
        $("#true_name").val(rowData.true_name);
        $("#usercontact").val(rowData.usercontact);
        $("#address").val(rowData.bespeak_address);
        $("#rent_price").val(rowData.rent_price);
        $("#bespeak_img").attr('src',rowData.bespeak_img);
        if (rowData.bespeak_area_info)
         {
            var addres = rowData.bespeak_area_info.split(" "); 
            console.log(addres);
            // var area_1 = $("#area_1").find('option:[name="'+addres[0]+'"]');
             $("#area_1 option[name='"+addres[0]+"']").attr("selected", true);

             if(addres[1]) 
             {
                var $this = $("#area_1 option[name='"+addres[0]+"']"), pid = $("#area_1 option[name='"+addres[0]+"']").val(), BigCity = [1, 2, 9, 22];

                //排除直辖市
                if( Number(pid) ==1 ) {
                    pid = '36';
                }else if(Number(pid)==2){
                    pid = '40';
                }else if(Number(pid)==9){
                    pid ='39';
                }else if(Number(pid)==22){
                    pid='62';
                }
                $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (data) {
                        var data = data.data;
                        if (data.items && data.items.length > 0) {
                            var options = null, select = null;
                            for ( var i = 0; i < data.items.length; i++ ) {
                                if ( i == 0 ) $('#_area_2').val(data.items[i]['district_id']);
                                options += '<option name=' + data.items[i]['district_name'] + ' value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                            }
                            $('#area_2').show();
                            $('#area_2').html(options);
                            $("#area_2 option[name='"+addres[1]+"']").attr("selected", true);
                            $("#area_2 option[value='"+addres[1]+"']").attr("selected", true);
                        }
                    });                
              $("#area_2").css({'display':'block','position':'relative','left':'83px','top':'-24px'});  
              $(".form-item").css("height","40px");

                
             }

               if(addres[2])
               {
                setTimeout(function(){

                    var $this = $("#area_2 option[name='"+addres[1]+"']"), pid = $("#area_2 option[name='"+addres[1]+"']").val();
                    var a1 = $('#area_1').val(), BigCity = [1, 2, 9, 22];
                    if($.inArray(Number(a1),BigCity) != -1){
                        return false;
                    }
                    //排除直辖市
                    $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (list) {
                        var data = list.data;
                        if (data.items && data.items.length > 0) {
                            var options = null, select = null;
                            for ( var i = 0; i < data.items.length; i++ ) {
                                if ( i == 0 ) $('#_area_3').val(data.items[i]['district_id']);
                                options += '<option name=' + data.items[i]['district_name'] + ' value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                            }

                            $('#area_3').show();
                            $('#area_3').html(options);
                            $("#area_3 option[name='"+addres[2]+"']").attr("selected", true);
                            $("#area_3 option[value='"+addres[2]+"']").attr("selected", true);
                        }
                    }); 
              $("#area_3").css({'display':'block','position':'relative','left':'200px','top':'-45px'});  
                                  
                },500) ;
               }


         };        
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
        true_name = $.trim($("#true_name").val()),
        bes_img = $.trim($("#bes_img").val()),
        rent_price = $.trim($("#rent_price").val()),
        usercontact = $.trim($("#usercontact").val()),
        opentime = $.trim($("#some_class_1").val()),
        address = $.trim($("#address").val()),
        area_info = $.trim($("#_area_1").val()+' '+$("#_area_2").val()+' '+$("#_area_3").val()),
        n = "add" == t ? "新增预约" : "修改预约";

    params = rowData.bespeak_id ? {
        bespeak_id: e,
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        usercontact : usercontact,
        true_name : true_name,
        rent_price : rent_price,
        area_info:area_info,
        address:address,
        bes_img : bes_img
    } : {
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        bes_img : bes_img,
        true_name : true_name,
        rent_price : rent_price,
        area_info:area_info,
        address:address,
        usercontact : usercontact,
    };
    console.log(params);
    Public.ajaxPost(SITE_URL +"?ctl=Goods_Bespeak&typ=json&met=" + ("add" == t ? "addGoodsbespeak1" : "editGoodsbespeak"), params, function (e)
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
    $("#bes_img").val("");
    $("#true_name").val("");
    $("#usercontact").val("");
    $("#rent_price").val("");
}




$('#area_1').on('change', function () {
    var $this = $(this), pid = $(this).val(), BigCity = [1, 2, 9, 22];

    //排除直辖市
    if( Number(pid) ==1 ) {
        pid = '36';
    }else if(Number(pid)==2){
        pid = '40';
    }else if(Number(pid)==9){
        pid ='39';
    }else if(Number(pid)==22){
        pid='62';
    }
    $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (data) {
            var data = data.data;
            if (data.items && data.items.length > 0) {
                var options = null, select = null;
                for ( var i = 0; i < data.items.length; i++ ) {
                    if ( i == 0 ) $('#_area_2').val(data.items[i]['district_id']);
                    options += '<option name=' + data.items[i]['district_name'] + ' value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                }
                $('#area_2').show();
                $('#area_2').html(options);
            }
        });

});

$('#area_2').on('change', function () {
    var $this = $(this), pid = $(this).val();
    var a1 = $('#area_1').val(), BigCity = [1, 2, 9, 22];
    if($.inArray(Number(a1),BigCity) != -1){
        return false;
    }
    //排除直辖市
    $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {nodeid: pid}, function (list) {
        var data = list.data;
        if (data.items && data.items.length > 0) {
            var options = null, select = null;
            for ( var i = 0; i < data.items.length; i++ ) {
                if ( i == 0 ) $('#_area_3').val(data.items[i]['district_id']);
                options += '<option name=' + data.items[i]['district_name'] + ' value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
            }

                $('#area_3').show();
            $('#area_3').html(options);
        }
    });
});
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();