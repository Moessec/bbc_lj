var key = getCookie("key");
 var page = pagesize;
    var curpage = 1;
    var firstRow = 0;
    var hasmore = true;
    var footer = false;
    var rows=3;
$(function ()
{   
     var key=getCookie("key");if(!key){location.href="login.html"}
     // $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask", scroll: "#list-items-scroll"});
    
    s();
     $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            s()
        }
    });
    function s()
    {    $(".loading").remove();
            if (!hasmore)
            {
                return false
            }
            hasmore = false;
           

            console.info(firstRow);
        $.ajax({
            type: "post", url: ApiUrl + "index.php?ctl=Points&met=getAllVouchers&typ=json", data: {k: key, u:getCookie('id'), rows : page,
            page : curpage,
           firstRow : firstRow}, dataType: "json", 
            success: function (e)
            {   console.info(e);
                checkLogin(e.login);
             
                 $(".loading").remove();
                curpage++;
                var s = e.data;
                var t = template.render("svoucher_list", s);
                
                // $("#voucher_list").empty();
                $("#voucher_list").append(t);
                 if(e.data.page < e.data.total)
                   {
                       firstRow =e.data.records;

                       hasmore = true;
                   }
                    else
                   {
                       hasmore = false;
                   }
                  


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
              if(e.status==200)
              {
                 window.location.href="./my_voucher.html";
              }
             
            }
        })
    }



});