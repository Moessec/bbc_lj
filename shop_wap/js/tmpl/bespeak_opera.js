$(function ()
{
    var wc = getCookie("key");
    var cnm='';
    var place='';
    var res='';
    var disce='';
    $.ajax({
        type: "post", url: ApiUrl + "/index.php?ctl=Buyer_User&met=address&typ=json", data: {k: wc, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e.data.address_list == null)
                {
                    return false
                }
                var s = e.data.address_list;
                for(v in s){
                    if(s[v].user_address_default==1){
                        res += s[v].address_info;
                        dis(res);
                    }
                }
            }
    })

    function dis(res)
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Goods_Goods&met=index&typ=json", data: {k:wc,u:getCookie('id')}, dataType: "json", success: function (nmb)
            {
                var r = nmb.data.items;
                 var temp = '';
                for(var i in r)
                {
                    temp = r[i];
                    dq=temp.business_license_location;
                    place=temp.company_address_detail;
                    var km=jl(dq,res,place);
                }
            }
        });
    }

    function jl(one,two,place){
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=getplace&typ=json", data: {k:wc,u:getCookie('id'),one:one,two:two}, dataType: "json", success: function (e)
                {
                    disce='<li style="height:28px;line-height:20px;font-size: 0.6rem;color: #000;" data-flag="'+e.data.dis+'">'+place+'</li>';
                    $("#area").append(disce);
                    console.log(e.data.dis,place);
                }
        })
    }

    var a = getCookie("key");
    $.sValid.init({
        rules: {true_name: "required", usercontact: "required", area_info: "required", address: "required", bespeak_title: "required"},
        messages: {true_name: "姓名必填！", usercontact: "手机号必填！", area_info: "地区必填！", address: "街道必填！", bespeak_title: "报修物品"},
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
            var e = $("#true_name").val();
            var ru = $("#usercontact").val();
            var rt = $("#starttime").val();
            var rc = $("#bespeak_com").val();
            var bes_info = $("#area_info").val();
            var n = $("#address").val();
            var img = $("#img").val();
            var bespeak_place = $("#bespeak_place").val();

              var partten = /^1[3,4,5,7,8]\d{9}$/;
              if(partten.test(ru))
              {
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=addBespeakInfo&typ=json",
                    data: {k:a,u:getCookie('id'), true_name: e, usercontact: ru,  bespeak_address: n, bes_address: bes_info, bespeak_com: rc, bespeak_place:bespeak_place, img:img, starttime: rt},

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
              }
              else
              {
                   alert('不是手机号码');
                   return false;
              }
        }
    });
});