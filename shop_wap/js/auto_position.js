　　if(navigator.geolocation) { // 判断设备是否支持定位

　　　　navigator.geolocation.getCurrentPosition(function(position) { 　　

　　　　　　alert(position.coords.latitude); // 纬度

　　　　　　alert(position.coords.longitude); // 经度

　　　　}, function(error) {

　　　　　　alert(error.message);

　　　　}, {

　　　　　　timeout: 90000

　　　　});

　　}else {

　　　　alert("不支持定位");

　　}



function getPositionError(error) {
      //  HTML5 定位失败时，调用百度地图定位   
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                var pt = r.point;

                $.cookie("lng",pt.lng,{expires:7});
                $.cookie("lat",pt.lat,{expires:7});

                // $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
                              
            }
        },{enableHighAccuracy: true});
    }

    function getPositionSuccess(position){
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;

      var ggPoint = new BMap.Point(lng,lat);
      //转换成百度地图坐标
      var trunback = function (point){
           $.cookie("lng",point.lng,{expires:7});
           $.cookie("lat",point.lat,{expires:7});
           // $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
      }
      BMap.Convertor.translate(ggPoint,0,trunback);     
    }



  // 先HTML5定位，定位不到再百度地图定位
  if(!$.cookie("lng")  || !$.cookie("lat") || "$act" == "renew")
  {
    var position_option = {enableHighAccuracy: true,maximumAge: 30000,timeout: 20000};
    navigator.geolocation.getCurrentPosition(getPositionSuccess, getPositionError, position_option);
  }
  else
  {
    var t_lng = $.cookie("lng");
    // alert( t_lng );
    var t_lat = $.cookie("lat");
    if(!t_lng || !t_lat)
    {
      $.post("ajax_back_end.php",{"act":"reposition","lng":$.cookie("lng"),"lat":$.cookie("lat")},function(){})
    }
  }

  // 地址定位
  function showArea(BMap)
  {
    var point = new BMap.Point($.cookie("lng"),$.cookie("lat"));
    var geoc = new BMap.Geocoder();    
    geoc.getLocation(point, function(rs){
      var addComp = rs.addressComponents;
      // var address = addComp.province + "" + addComp.city + "" + addComp.district + "" + addComp.street + "" + addComp.streetNumber;
      var address = addComp.city;

      if($.cookie('trans_city'))
      {
        $(".area").html($.cookie('trans_city'));
      }else if(address){
        $.cookie("address",address,{expires:7});
      $(".area").html(address);
    }
    });        
   
  }

 