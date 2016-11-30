$(function ()
{
    var a = getQueryString("bespeak_id");
    var e = getCookie("key");
    $.ajax({
        type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=bespeak&act=edit&typ=json", data: {k:e,u:getCookie('id'), id: a}, dataType: "json", success: function (a)
        {
            checkLogin(a.login);
            // console.log(a);
            $("#true_name").val(a.data.true_name);
            $("#usercontact").val(a.data.usercontact);
            $("#bespeak_title").val(a.data.bespeak_title);
            $("#bespeak_com").val(a.data.bespeak_com);
            $("#area_info").val(a.data.bes_address).attr({"data-areaid1": a.data.province_id, "data-areaid2": a.data.city_id, "data-areaid3": a.data.area_id, "data-areaid": a.data.province_id});
            $("#address").val(a.data.bespeak_address);
            var e = a.data.bespeak_default == "1" ? true : false;
            $("#is_default").prop("checked", e);
            if (e)
            {
                $("#is_default").parents("label").addClass("checked")
            }
        }
    });
    $.sValid.init({
        rules: {true_name: "required", usercontact: "required", area_info: "required", address: "required", bespeak_title: "required"},
        messages: {true_name: "姓名必填！", usercontact: "手机号必填！", area_info: "地区必填！", address: "街道必填！", bespeak_title: "报修物品"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var d = "";
                $.map(e, function (a, e)
                {
                    d += "<p>" + a + "</p>"
                });
                errorTipsShow(d)
            }
            else
            {
                errorTipsHide()
            }
        }
    });
    $(".btn").click(function ()
    {
        if ($.sValid())
        {
            var true_name = $("#true_name").val();
            var ru = $("#usercontact").val();
            var rt = $("#bespeak_title").val();
            var rc = $("#bespeak_com").val();
            var d = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var address = $("#address").val();
            var bes_address = $("#area_info").val();
            var img = $("#img").val();

            var province_id = $("#area_info").attr("data-areaid1");
            var city_id = $("#area_info").attr("data-areaid2");
            var area_id = $("#area_info").attr("data-areaid3");
            var area_info = province_id+'-'+city_id+'-'+area_id;
            var partten = /^1[3,4,5,7,8]\d{9}$/;
              if(partten.test(ru))
              {
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=editBespeakInfo&typ=json",
                    data: {k:e,u:getCookie('id') , id:a, true_name: true_name, usercontact: ru, bespeak_area_info: area_info, bespeak_address: address, bes_address: bes_address, bespeak_com: rc, bespeak_title: rt},
                    dataType: "json",
                    success: function (a)
                    {
                        if (a)
                        {
                            location.href = WapSiteUrl + "/tmpl/member/bespeak_list.html"
                        }
                        else
                        {
                            location.href = WapSiteUrl
                        }
                    }
                })
            }else{
                alert('手机格式不正确');
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