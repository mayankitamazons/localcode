<?php
error_reporting(E_ALL);
$result=exec("/usr/bin/python myscript.py");
$resultarray=explode(",",$result);
if (count($resultarray)>0) {
  // code...
  $data['camp_name']=$camp_name=$resultarray[0];
  $data['sign']=$sign=$resultarray[1];
  $data['push_email']='mayank@gmail.com';
  $data['message']='Congrulation ! your order is Ready';
  $data['title']='Order Ready';
  $data['redirectURL']='https://www.koofamilies.com';
  include 'push.php';
  $user = new Push();
  $result = $user->send_push($data);
  print_R($result);
  die;
}

?>
