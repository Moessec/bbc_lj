$(function() {

    // var headerClone = $('#header').clone();
    // $(window).scroll(function(){
    //     if ($(window).scrollTop() <= $('#main-container1').height()) {
    //         headerClone = $('#header').clone();
    //         $('#header').remove();
    //         headerClone.addClass('transparent').removeClass('');
    //         headerClone.prependTo('.nctouch-home-top');
    //     } else {
    //         headerClone = $('#header').clone();
    //         $('#header').remove();
    //         headerClone.addClass('').removeClass('transparent');
    //         headerClone.prependTo('body');
    //     }
    // });
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Index&met=index&typ=json",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.data;
            var html = '';
            var html1='';
            // console.info( data );
            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'slider_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;

                        case 'home1':
                        // case 'goods':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                            // case 'goods':html1 = template.render('goods1',vv);alert(kk);break;

                    }
                    if (k == 2) {
                        $("#product-contain").html(template.render(kk, vv));
                    }// } else {
                    //     html += template.render(kk, vv);
                    // }
                    return false;
                });
            });



              // html1 = template.render('goods1',goods1);
            // $("#product-contain").html(html);

            // $('.slider_list').each(function() {
            //     if ($(this).find('.item').length < 2) {
            //         return;
            //     }

            //     Swipe(this, {
            //         startSlide: 2,
            //         speed: 400,
            //         auto: 3000,
            //         continuous: true,
            //         disableScroll: false,
            //         stopPropagation: false,
            //         callback: function(index, elem) {},
            //         transitionEnd: function(index, elem) {}
            //     });
            // });

        }
    });

});
