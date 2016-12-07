$(function ()
{
                  var info = '上海市';
                  var map = new BMap.Map("container1");
                  var localSearch = new BMap.LocalSearch(map);

                    function searchByStationName(info) {
                        map.clearOverlays();//清空原来的标注
                        var keyword = info;

                        localSearch.setSearchCompleteCallback(function (searchResult) {
                            var poi = searchResult.getPoi(0);

                            map.centerAndZoom(poi.point, 13);
                            var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
                            map.addOverlay(marker);
                            // var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
                            alert(poi.point.lng);
                            alert(poi.point.lat);
                           
                            // $.post('ajax_back_end.php', { shoplng:poi.point.lng,shoplat:poi.point.lat }, function (distance, status) { da.shop_stamp=distance;
                            //     // console.log(da);
                            //  $("#shopinfo").html(template.render('shop_info', da));   
                            //  });

                        });
                        localSearch.search(keyword);
                    } 
                    searchByStationName(info); 
 alert($.cookie('trans_city'));
// console.log(trans_city);
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=cat&typ=json&cat_parent_id=0", function (t)
    {
        console.info(t);
    });



});


