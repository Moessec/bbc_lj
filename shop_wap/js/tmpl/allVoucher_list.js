var key = getCookie("key");


$(function ()
{   
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Points&met=getVoucher&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", 
            success: function (e)
            {   console.info(e.data);
                checkLogin(e.login);
                // if (e.data.items == null)
                // {
                //     return false
                // }
                var s = e.data;
                var t = template.render("svoucher_list", s);
                
                $("#voucher_list").empty();
                $("#voucher_list").append(t);
                $(".btn_dh").click(function ()
                {
                    var e = $(this).attr("dh_id");
                    console.info(e);
                    $.sDialog({
                        skin: "block", content: "确认领取吗？", okBtn: true, cancelBtn: true, okFn: function ()
                        {
                            a(e)
                        }
                    })
                })
            }
        })
    }

    s();
    function a(a)
    {
        $.ajax({
            type: "post", url: ApiUrl + "?ctl=Voucher&met=receiveVoucher&typ=json", data: {vid: a, k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                // checkLogin(e.login);
                // if (e)
                // {
                //     s()
                // }
              var msg=e.status==200?'领取成功':'领取失败';
              alert(msg);
            }
        })
    }



});