$(function ()
{

 alert($.cookie('trans_city'));
// console.log(trans_city);
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=cat&typ=json&cat_parent_id=0", function (t)
    {
        console.info(t);
    });



});


