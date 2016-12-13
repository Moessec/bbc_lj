var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
         var e = getQueryString("bespeak_id");
        var a = getCookie("key");
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=getbespeaklist1&typ=json", data: {k:a,u:getCookie('id'), id: e}, dataType: "json", success: function (a)
            {
                checkLogin(a.login);
                console.log(a.data);
                $.each(a.data, function(key, value){
                       tem='<dl><img src="'+value.bespeak_img+'" style="width:100%;height:332px">';
                       // tem+='<span style="color:#FF8000;display:block;margin-top:10px;margin-left:5px;font-size:0.9rem">预约说明</span><div class="com">';
                       // tem+='<span id="com" style="width:90%;margin:auto">'+value.bespeak_com+'</span></div>';
                       tem+='<div class="ul"><table><tr><td class="left" style="font-size:13px;padding-left:30px">开始时间：</td><td><span style="font-size:13px">'+value.opentime+'</span></td></tr>';
                       tem+='<tr><td class="left" style="font-size:13px;padding-left:30px">结束时间：</td><td><span style="font-size:13px">'+value.outtime+'</span></td></tr>';
                       tem+='<tr><td class="left" style="font-size:13px;padding-left:30px">活动地址：</td><td><span style="font-size:13px">'+value.bes_address+'</span></td></tr>';
                       tem+='<tr><td class="left" style="font-size:13px;padding-left:30px">联系人：</td><td><span style="font-size:13px">'+value.true_name+'</span></td></tr>';
                       tem+='<tr><td class="left" style="font-size:13px;padding-left:30px">联系方式：</td><td><span style="font-size:13px">'+value.usercontact+'</span></td></tr></table></div>'
                       // tem+='<div class="error-tips"></div><div class="form-btn"><a style="    margin-bottom: 15px;" class="btn" href="'+value.bespeak_id+'">'+value.bespeaka+'</a></div></dl>';
                       if(value.bespeaka=='已预约')
                       {
                      tem+='<button style="width:90%;height: 1.8rem;font-size:0.8rem;text-align:center;border:none;border-radius:0.2rem;margin-top:20px;margin:20px 5%;">已参与</button>';

                       }
                       else
                       {
                      tem+='<a href="'+value.bespeak_id+'"><button style="width:90%;background-color:#FF8000;color:white;height: 1.8rem;font-size:0.8rem;text-align:center;border:none;border-radius:0.2rem;margin-top:20px;margin:20px 5%">我要预约</button></a>';

                       }
                     var reg = new RegExp("\n","g");
                      var c=tem.replace(reg,'<br>');

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