

function coder(str) {
    var s = "";
    if (str.length == 0) return "";
    for (var i = 0; i < str.length; i++) {
        switch (str.substr(i, 1)) {
            case "<": s += "&lt;"; break;
            case ">": s += "&gt;"; break;
            case "&": s += "&amp;"; break;
            case " ": s += "&nbsp;"; break;
            case "\"": s += "&quot;"; break;
            default: s += str.substr(i, 1); break;
        }
    }
    return s;
}




$(function(){
		var article_id = getQueryString('article_id');
        $.getJSON(ApiUrl + "/index.php?ctl=Article_Base&met=get&typ=json&article_id="+article_id, function (t)
             {

                 $("#announ").html(template.render('announcement', t));
               $('#desc').append(t.data['article_desc']);
               
        
             });	
})