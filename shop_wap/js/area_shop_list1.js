$(function ()
{
  var map = new BMap.Map("container1");
  var localSearch = new BMap.LocalSearch(map);   
  var div = '';
  var city = $.cookie('trans_city');
  $("#city").html(city);
  var flag = 0;
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=index&typ=json", function (t)
    {
        var r = t.data.items;
        console.log(r);
         var temp = '';
        for(var i in r)
        {
                  temp = r[i].shop_company_address;
                  tem = temp.split(' ')[1];
                  // alert(tem);
                  // alert(city);
                  if(tem==city)
                  {
                    flag = 1;
                     div += '<div class="list"><a href="../tmpl/member/bespeak_opera.html?lj='+r[i].shop_id+'"><div class="list_left"><img src="'+r[i].shop_logo+'" alt=""></div><div class="list_right"><dl><dd class="title">'+r[i].shop_name+'</dd><dd>地址:'+r[i].company_address_detail+'</dd><dd>电话:'+r[i].company_phone+'</dd></dl></div></a></div>'
                  }
        }
      
        $("#shop_info").html(div);
        if(div==''&&flag==0)
        {
            var txt=  "该社区暂时没有门店!";
            window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.confirm);

         }
    });



});

  // function searchByStationName(info) 
  //    {
  //       map.clearOverlays();//清空原来的标注
  //       var keyword = info;

  //       localSearch.setSearchCompleteCallback(function (searchResult) {
  //           var poi = searchResult.getPoi(0);

  //           map.centerAndZoom(poi.point, 13);
  //           var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
  //           map.addOverlay(marker);
  //           // var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
  //           // alert(poi.point.lng);
  //           // alert(poi.point.lat);
  //           //=============================================================
  //           var point = new BMap.Point(poi.point.lng,poi.point.lat);
  //           var geoc = new BMap.Geocoder();    
  //           geoc.getLocation(point, function(rs){
  //             var addComp = rs.addressComponents;
  //             // var address = addComp.province + "" + addComp.city + "" + addComp.district + "" + addComp.street + "" + addComp.streetNumber;
  //             var address = addComp.city; 
         
  //                });
  //           //==============================================================                       

  //           });
  //          localSearch.search(keyword);
  //    } 
