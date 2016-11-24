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
            console.info( data );
            var list = Array()
            $.each(data,function(k,v){
             $.each(v,function(k,v){
                
                console.log(v);
              })
            })

            // $.each(data, function(k, v) {
            //     alert(k);
            //     $.each(v, function(kk, vv) {
            //         // console.log(v)
            //         // switch (kk) {
            //         //     case 'slider_list':
            //         //     case 'home3':
            //         //         $.each(vv.item, function(k3, v3) {
            //         //             vv.item[k3].url = buildUrl(v3.type, v3.data);
            //         //         });
            //         //         break;

            //         //     case 'home1':
            //         //     // case 'goods':
            //         //         vv.url = buildUrl(vv.type, vv.data);
            //         //         break;

            //         //     case 'home2':
            //         //     case 'home4':
            //         //         vv.square_url = buildUrl(vv.square_type, vv.square_data);
            //         //         vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
            //         //         vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
            //         //         break;
            //         //         // case 'goods':html1 = template.render('goods1',vv);alert(kk);break;

            //         // }
            //         if (k == 6) {
            //             $("#product-contain").html(template.render('goods', vv));
            //         }
            //         if (k == 7) {
            //             $("#product-contain1").html(template.render('goods1', vv));
            //         }
            //         if (k == 8) {
            //             $("#product-contain2").html(template.render('goods2', vv));
            //         }
            //         return false;
            //     });
            // });


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
