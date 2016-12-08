var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
         var e = getQueryString("bespeak_id");
        var a = getCookie("key");
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=getbespeaklist&typ=json", data: {k:a,u:getCookie('id'), id: e}, dataType: "json", success: function (a)
            {
                checkLogin(a.login);
                console.log(a);
                $.each(a.data, function(key, value){
                       tem='<dl><img src="'+value.bespeak_img+'" style="width:100%"><div class="com">';
                       tem+='<span style="width:90%;margin:auto">'+value.bespeak_com+'</span></div>';
                       tem+='<div class="ul"><tr><td>活动开始时间：</td><td><span>'+value.opentime+'</span></td></tr>';
                       tem+='<tr><td>活动截止时间：</td><td><span>'+value.outtime+'</span></td></tr>';
                       tem+='<tr><td>联系人：</td><td><span>'+value.true_name+'</span></td></tr>';
                       tem+='<tr><td>联系方式：</td><td><span>'+value.usercontact+'</span></td></tr></div>'
                       tem+='<div class="error-tips"></div><div class="form-btn"><a class="btn" href="bespeak_opera_adv.html?bespeak_id='+value.bespeak_id+'">申请预约</a></div></dl>';
                    $("#bespeak_list").append(tem);
                })
                $("#img").attr('src',a.data.bespeak_img);
                $("#com").val(a.data.bespeak_com);
                $("#name").val(a.data.true_name);
                $("#usercontact").val(a.data.usercontact);
                var e = a.data.bespeak_default == "1" ? true : false;
                $("#is_default").prop("checked", e);
                if (e)
                {
                    $("#is_default").parents("label").addClass("checked")
                }
            }
        })
    }

    s();

});