<?php 
$ip = $_SERVER['REMOTE_ADDR']; $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip; $html = file_get_contents($url);
 $json = json_decode($html); 
 var_dump($json);














//==================百度直连

    $canshu=$_SERVER["QUERY_STRING"]; 
    if($canshu=="") 
     { 
     die("文件不存在"); 
     } 
    else 
    { 
    $wangzhi="http://pan.baidu.com/share/link?".$canshu; 
    $file=file_get_contents($wangzhi); 
    $pattern='/a><a class="dbtn cancel singledbtn" href=(.*?)id="downFileButtom">/i'; 
    preg_match_all($pattern,$file,$result);  
    $tempurl=implode("",$result[1]); 
    $fileurlt=str_replace("\"","",$tempurl); 
    $fileurl=str_replace("&amp;","&",$fileurlt); 
    header("location:$fileurl"); 
    } 
    
?>