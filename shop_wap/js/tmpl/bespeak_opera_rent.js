$(function ()
{
    var e = getQueryString("bespeak_id");
    var a = getCookie("key");
    $.ajax({
        type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=addRentBespeak&act=edit&typ=json", data: {k:a,u:getCookie('id'), id: e}, dataType: "json", success: function (a)
        {
            checkLogin(a.login);
            console.log(a);
            $("#bespeak_title").val(a.data.bespeak_title);
            $("#bespeak_com").val(a.data.bespeak_com);
            var e = a.data.bespeak_default == "1" ? true : false;
            $("#is_default").prop("checked", e);
            if (e)
            {
                $("#is_default").parents("label").addClass("checked")
            }
        }
    });
    $.sValid.init({
        rules: {true_name: "required", mob_phone: "required"},
        messages: {true_name: "姓名必填！", mob_phone: "手机号必填！"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var i = "";
                $.map(e, function (a, e)
                {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            }
            else
            {
                errorTipsHide()
            }
        }
    });
    $("#header-nav").click(function ()
    {
        $(".btn").click()
    });
    $(".btn").click(function ()
    {
        if ($.sValid())
        {
            var true_name = $("#true_name").val();
            var bespeak_com = $("#bespeak_com").val();
            var ru = $("#usercontact").val();
            var rt = $("#bespeak_title").val();
            var starttime = $("#starttime").val();
            var outtime = $("#outtime").val();
            var partten = /^1[3,4,5,7,8]\d{9}$/;
            if(partten.test(ru))
            {
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=addRentBespeak&typ=json",
                    data: {k:a,u:getCookie('id') , id:e, true_name: true_name,bespeak_com: bespeak_com, usercontact: ru,  bespeak_title: rt, starttime:starttime, outtime:outtime},
                    dataType: "json",
                    success: function (a)
                    {
                        if (a)
                        {

                            location.href = WapSiteUrl + "/tmpl/member/bespeak_rent.html"
                        }
                        else
                        {
                            location.href = WapSiteUrl
                        }
                    }
                })
            }else{
                alert('手机格式不正确')
                return false;
            }
        }
    });

    $("#area_info").on("click", function ()
    {
        $.areaSelected({
            success: function (a)
            {
                $("#area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
            }
        })
    })

});