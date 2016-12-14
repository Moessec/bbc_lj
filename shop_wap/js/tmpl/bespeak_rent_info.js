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
                       tem='<dl><img src="'+value.bespeak_img+'" style="width:100%">';
                       tem+='<span style="width:90%;margin:auto;margin-left:9px;font-size: 0.7rem;line-height: 3rem;color: #000;">预约说明</span><div class="com">';
                       tem+='<span style="width:90%;margin:auto">'+value.bespeak_com+'</span></div>';
                       tem+='<div class="ul"><table><tr><td class="left">租赁价格：</td><td><span>'+value.rent_price+'</span></td></tr>';
                       tem+='<tr><td class="left">地址：</td><td><span>'+value.bes_address+value.bespeak_address+'</span></td></tr>';
                       tem+='<tr><td class="left">联系人：</td><td><span>'+value.true_name+'</span></td></tr>';
                       tem+='<tr><td class="left">联系方式：</td><td><span>'+value.usercontact+'</span></td></tr></table></div>'
                       tem+='<div class="error-tips"></div><div class="form-btn"><a style="    margin-bottom: 15px;" class="btn" href="'+value.bespeak_id+'">'+value.bespeaka+'</a></div></dl>';
                    var reg = new RegExp("&lt;","g")
                     var teg = new RegExp("&gt;","g")
                      var b=tem.replace(reg,'<');
                      var c=b.replace(teg,'>');
                    $("#bespeak_list").append(c);
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