    
        var province=0;
        var city=0;
        var district=0;
        var street=0;

        
        
        function enter() {
            if (navigator.geolocation) {  //调用导航器geolocation函数
                navigator.geolocation.getCurrentPosition(loand); //进入总显示函数loand，函数名由自己定
            } else {
                alert("您的浏览器不支持地理定位");//不支持
            }
        }
        function loand(position) {  //主函数
            var lat = position.coords.latitude;//y，纬度，通过上面的getCurrentPosition函数定位浏览器位置，从而获取地址
            var lon = position.coords.longitude;//x，经度
            //alert(lat);
            var map = new BMap.Map("container"); //初始化地图类
            var point = new BMap.Point(lon,lat); //这里设置刚开始的点所在处
            var gc = new BMap.Geocoder();  //初始化，Geocoder类
            gc.getLocation(point, function (rs) {   //getLocation函数用来解析地址信息，分别返回省市区街等
                var addComp = rs.addressComponents;
                province = addComp.province;//获取省份
                city = addComp.city;//获取城市
                district = addComp.district;//区
                street = addComp.street;//街


                var marker = new BMap.Marker(point); //地图事件类
                var opts = {
                    width: 25,     // 信息窗口宽度  
                    height: 120,     // 信息窗口高度  
                    title: "我所在的地点:<hr />"  // 信息窗口标题 ，这里声明下，可以在自己输出的信息里面嵌入html标签的
                }
                var infoWindow = new BMap.InfoWindow("省份:" + province + ";" + "城市:"
                    + city + ";<br /><br />" + "县/区:" + district + ";" + "街道:" + street + ".", opts);
                // 创建信息窗口对象，把信息在初始化 地图信息窗口类的同时写进去
                

                marker.enableDragging(); //启用拖拽事件
                marker.addEventListener("dragend", function (e) {
                    gc.getLocation(point, function (rs) {
                        //由于在getLocation函数返回信息之前，首先执行它下面的代码的，所以要把重新拖动后的代码放到它里面
                        var addComp = rs.addressComponents;
                        province = addComp.province;//获取省份
                        city = addComp.city;//获取城市
                        district = addComp.district;//区
                        street = addComp.street;//街
                        opts = {
                            width: 25,     // 信息窗口宽度  
                            height: 160,     // 信息窗口高度  
                            title: "现在的位置:<hr />"  // 信息窗口标题  
                        }
                        point = new BMap.Point(e.point.lng, e.point.lat); //标记新坐标（拖拽以后的坐标）
                        marker = new BMap.Marker(point);  //事件类


                        infoWindow = new BMap.InfoWindow("省份:" + province + ";" + "城市:"
                        + city + ";<br /><br />" + "县/区:" + district + ";" + "街道:" + street + ".<br />" +
                            "经度：" + e.point.lng + "<br />纬度：" + e.point.lat, opts);

                        map.openInfoWindow(infoWindow, point);
                        //这条函数openInfoWindow是输出信息函数，传入信息类和点坐标
                    })
                })

                map.addControl(new BMap.NavigationControl()); //左上角控件
                map.enableScrollWheelZoom(); //滚动放大
                map.enableKeyboard(); //键盘放大

                map.centerAndZoom(point, 13); //绘制地图
                map.addOverlay(marker); //标记地图

                map.openInfoWindow(infoWindow, map.getCenter());      // 打开信息窗口
            });
         }




// function getPositionError(error) {
//       //  HTML5 定位失败时，调用百度地图定位   
//         var geolocation = new BMap.Geolocation();
//         geolocation.getCurrentPosition(function(r){
//             if(this.getStatus() == BMAP_STATUS_SUCCESS){
//                 var mk = new BMap.Marker(r.point);
//                 var pt = r.point;

//                 $.cookie("lng",pt.lng,{expires:7});
//                 $.cookie("lat",pt.lat,{expires:7});

//                 // $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
                              
//             }
//         },{enableHighAccuracy: true});
//     }

//     function getPositionSuccess(position){
//       var lat = position.coords.latitude;
//       var lng = position.coords.longitude;

//       var ggPoint = new BMap.Point(lng,lat);
//       //转换成百度地图坐标
//       var trunback = function (point){
//            $.cookie("lng",point.lng,{expires:7});
//            $.cookie("lat",point.lat,{expires:7});
//            // $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
//       }
//       BMap.Convertor.translate(ggPoint,0,trunback);     
//     }



//   // 先HTML5定位，定位不到再百度地图定位
//   if(!$.cookie("lng")  || !$.cookie("lat") || "$act" == "renew")
//   {
//     var position_option = {enableHighAccuracy: true,maximumAge: 30000,timeout: 20000};
//     navigator.geolocation.getCurrentPosition(getPositionSuccess, getPositionError, position_option);
//   }
//   else
//   {
//     var t_lng = $.cookie("lng");
//     // alert( t_lng );
//     var t_lat = $.cookie("lat");
//     if(!t_lng || !t_lat)
//     {
//       $.post("ajax_back_end.php",{"act":"reposition","lng":$.cookie("lng"),"lat":$.cookie("lat")},function(){})
//     }
//   }

//   // 地址定位
//   function showArea(BMap)
//   {
//     var point = new BMap.Point($.cookie("lng"),$.cookie("lat"));
//     var geoc = new BMap.Geocoder();    
//     geoc.getLocation(point, function(rs){
//       var addComp = rs.addressComponents;
//       // var address = addComp.province + "" + addComp.city + "" + addComp.district + "" + addComp.street + "" + addComp.streetNumber;
//       var address = addComp.city;

//       if($.cookie('trans_city'))
//       {
//         $(".area").html($.cookie('trans_city'));
//       }else if(address){
//         $.cookie("address",address,{expires:7});
//       $(".area").html(address);
//     }
//     });        
   
//   }

//  