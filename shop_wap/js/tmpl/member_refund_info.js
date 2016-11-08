$(function () {
    var e = getCookie("key");
    var r = getQueryString("refund_id");
    template.helper("isEmpty", function (e) {
        for (var r in e) {
            return false
        }
        return true
    });
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&act=detail&typ=json", {key: e, id: r}, function (e) {
        $("#refund-info-div").html(template.render("refund-info-script", e.data))
    })
});