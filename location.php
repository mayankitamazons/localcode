<?php

include("config.php");   
$latitude = $_SESSION['latitude'];
$longitude = $_SESSION['longitude'];
$address_person = $_SESSION['address_person'];
$combLat = $latitude." , ". $longitude ;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div style="background-color: blue;
    color: white;
    font-size: 50px;
    padding: 10px;
    margin: 0px auto;
    border-radius: 5px;
    text-align: center;"><a href="https://www.google.com/maps/dir/<?php echo $address_person ?>" target="_new" onclick="showLocation()">Direction</a></div>
<iframe src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&origin=Oslo+Norway&destination=46.414382,10.013988&avoid=tolls|highways" 
width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>

<script>
$(document).ready(function(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(showLocation);
    }else{ 
        $('#location').html('Geolocation is not supported by this browser.');
    }
});

function showLocation(position){
    var latitude= position.coords.latitude;
    var longitude  = position.coords.longitude;
    var orignal = latitude + ' , ' + longitude; 
  
   $("a").attr("href", "https://www.google.com/maps/embed/v1/directions?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&origin=" + orignal + "&destination=<?php echo $address_person ?>") ;

    $("iframe").attr("src", "https://www.google.com/maps/embed/v1/directions?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&origin=" + orignal + "&destination=<?php echo $combLat ?>") ;

}



</script>






