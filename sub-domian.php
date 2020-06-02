<?php

include("config.php");

// 2 merchnat ;



$UserType = $_GET['ut'] ;
$number = $_GET['num'] ;
$subDomain = $number;

$PPU = urlencode("$site_url/structure_merchant.php?sid=$number") ;

if($subDomain ==''){
    
    echo "sub domain can not be empty" ;
    exit();
    die();
    
}

function create_subdomain($subDomain,$cPanelUser,$cPanelPass,$rootDomain) {
 
//  $buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain;
 
    $buildRequest = "/frontend/paper_lantern/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/subdomains/" . $subDomain;
 
    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }
	
	
 
    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";
 
    fputs($openSocket, $buildHeaders);
    while(!feof($openSocket)) {
    fgets($openSocket,128);
    }
    fclose($openSocket);
 
    $newDomain = "http://" . $subDomain . "." . $rootDomain . "/";
 
  return "$newDomain";
  


 
}
//https://koofamilies.com/structure_merchant.php?countrycode=MY&merchant=127275670





if($UserType=='merchant'){
 $SURL =   create_subdomain($subDomain , 'koofamilies' , 'AAaa_9639' , 'koofamilies.com') ;
 echo '"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='. $PPU .'%2F&choe=UTF-8"' ;
  echo'<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='. $PPU .'&choe=UTF-8" title="Link to Google.com" />';
  
  $myfile =fopen("/home/koofamilies/public_html/subdomains/" . $subDomain ."/index.php", "w") or die("Unable to open file!");

$txt = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='. $PPU .'&choe=UTF-8\" title=\"Link to Google.com\" />";
fwrite($myfile, $txt);
fclose($myfile);
}else{
    
    echo "user is not merchant" ;
    
}
?>