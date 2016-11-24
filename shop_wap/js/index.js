$(function() {
 var key = getCookie('key');
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.data;
            var html = '';
            var html1='';
            // console.info( data );
            // var list = new Array();

     // console.log(list);
 //            $.each(data, function(k, v) {
 //                 alert(v.cat_id);
 // if(v.cat_id==1)
 // {
 //    list[v.cat_id][cat_id]=v.cat_id;
 //    list[v.cat_name][k][cat_name]=v.cat_name;
 // }
 //  });
// console.log(list);
$("#product-contain").html(template.render('goods', data));
$("#product-contain1").html(template.render('goods1', data));
// $("#product-contain2").html(template.render('goods2', data));

        }
    });
//*************************************
// alert(1);
setTimeout(function(){
    $('.add').each(function(){

    $(this).click(function(){
     // alert(1);
        var goods_id = $(this).find('input').val();
        var quantity = 1;
        // alert(gid);
                            $.ajax({
                            url:ApiUrl+"/index.php?ctl=Buyer_Cart&met=addCart&typ=json",
                            data:{k:key,u:getCookie('id'),goods_id:goods_id,goods_num:quantity},
                            type:"post",
                            success:function (result){
                                /*var rData = $.parseJSON(result);*/
                                if(checkLogin(result.login)){
                                    if(result.status == 200){
                                        // show_tip();
                                        // 更新购物车中商品数量
                                        delCookie('cart_count');
                                        getCartCount();
                                        alert(getCookie('cart_count'));
                                        // $('#cart_count').html(getCookie('cart_count'));
                                    }else{
                                        // $.sDialog({
                                        //     skin:"red",
                                        //     content:result.msg,
                                        //     okBtn:false,
                                        //     cancelBtn:false
                                        // });
                                        alert('商品下架');
                                    }
                                }
                            }
                        });
    });        
    });

},2000)







});
