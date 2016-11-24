$(function() {
 var key = getCookie('key');
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.data.items;
            var html = '';
            var html1='';
            // console.info( data );
            var list = new Array();

     // console.log(list);
            $.each(data, function(k, v) {
                
                $.each(v, function(kk, vv) {
                   
                   alert(vv.cat_id);
                });
            });

$("#product-contain").html(template.render('goods', data));

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
                                        $('#cart_count,#cart_count1').html('<sup>'+getCookie('cart_count')+'</sup>');
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
