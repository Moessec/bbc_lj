var key = getCookie("key");

$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Voucher&met=voucher&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", 
            success: function (e)
            {   console.info(e.data);
                checkLogin(e.login);
                if (e.data.items == null)
                {
                    return false
                }
                var s = e.data;
                var t = template.render("svoucher_list", s);
                
                $("#voucher_list").empty();
                $("#voucher_list").append(t);
                // $(".deladdress").click(function ()
                // {
                //     var e = $(this).attr("user_address_id");
                //     $.sDialog({
                //         skin: "block", content: "确认删除吗？", okBtn: true, cancelBtn: true, okFn: function ()
                //         {
                //             a(e)
                //         }
                //     })
                // })
            }
        })
    }

    s();
    function a(a)
    {
        $.ajax({
            type: "post", url: ApiUrl + "?ctl=Buyer_User&met=delAddress&typ=json", data: {id: a, k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e)
                {
                    s()
                }
            }
        })
    }
});