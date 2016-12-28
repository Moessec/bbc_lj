var lat1='';
var lng1 = '';


jQuery.MapConvert = {
    x_pi: 3.14159265358979324 * 3000.0 / 180.0,
    /// <summary>
    /// 中国正常坐标系GCJ02协议的坐标，转到 百度地图对应的 BD09 协议坐标
    ///  point 为传入的对象，例如{lat:xxxxx,lng:xxxxx}
    /// </summary>
    abc: function (lng,lat) {
        var x = lng, y = lat;
        var z = Math.sqrt(x * x + y * y) + 0.00002 * Math.sin(y * jQuery.MapConvert.x_pi);
        var theta = Math.atan2(y, x) + 0.000003 * Math.cos(x * jQuery.MapConvert.x_pi);
        lng1 = z * Math.cos(theta) + 0.0065;
        lat1 = z * Math.sin(theta) + 0.006;

                $.cookie("lng",lng1,{expires:0.083});
                $.cookie("lat",lat1,{expires:0.083});
    }
    /// <summary>
    /// 百度地图对应的 BD09 协议坐标，转到 中国正常坐标系GCJ02协议的坐标
    /// </summary>
    // Convert_BD09_To_GCJ02: function (point) {
    //     var x = point.lng - 0.0065, y = point.lat - 0.006;
    //     var z = Math.sqrt(x * x + y * y) - 0.00002 * Math.sin(y * jQuery.MapConvert.x_pi);
    //     var theta = Math.atan2(y, x) - 0.000003 * Math.cos(x * jQuery.MapConvert.x_pi);
    //     point.lng = z * Math.cos(theta);
    //     point.lat = z * Math.sin(theta);
    // }
}



function getPositionError(error) {
      //  HTML5 定位失败时，调用百度地图定位   
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                var pt = r.point;
                jQuery.MapConvert.abc(pt.lng,pt.lat);
                // $.cookie("lng",pt.lng,{expires:7});
                // $.cookie("lat",pt.lat,{expires:7});

                $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
                              
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
      // $.post("ajax_back_end.php",{"act":"reposition","lng":$.cookie("lng"),"lat":$.cookie("lat")},function(){})
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

 