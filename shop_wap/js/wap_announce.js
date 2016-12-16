$(function(){
		var article_id = getQueryString('article_id');
        $.getJSON(ApiUrl + "/index.php?ctl=Article_Base&met=get&typ=json&article_id="+article_id, function (t)
             {

                 $("#announ").html(template.render('announcement', t));
               $('#desc').append(t.data['article_desc']);
               
        
             });	
})