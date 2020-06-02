<?php
include("config.php");
class Push {
    function send_push($re) {
       global $site_url;
      extract($re);
	// $push_email="62960123115670";
      $data = array(
          'appId'=>'HAZX1A6SDRWD6Z1XHO8WO3B7',
          'campaignName'=>$camp_name,
          'signature'=>$sign,
          'requestType'=>'push',
          'targetPlatform'=>['WEB'],
          'targetAudience'=>'User',
          'targetUserAttributes'=>array(
          'attribute'=>'USER_ATTRIBUTE_UNIQUE_ID',
          'isCustom'=>true,
          'comparisonParameter'=>'is',
          'attributeValue'=>$push_email
          ),
          'payload'=>array('WEB'=>array(
              'message'=>$message,
              'title'=>$title,
              'redirectURL'=>$redirectURL,
              'image'=>$site_url.'/kodo.jpg',
              'iconURL'=>$site_url."/kodo.jpg",
              'fallback'=>array()
              )),
          'campaignDelivery'=>array('type'=>'soon'),
          'advancedSettings'=>array('ttl'=>array(
                    'ANDROID'=>12,
                    'WEB'=>24
          )),
          'ignoreFC'=>'false',
          'sendAtHighPriority'=>'true'

          );

      $data_json=json_encode($data);
      $make_call = $this->callAPI('POST', 'https://pushapi.moengage.com/v2/transaction/sendpush', $data_json);
      $response = json_decode($make_call, true);
   // print_R($response);
   // die;                 
      return $response;
    }
    function callAPI($method, $url, $data){
       $curl = curl_init();

       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          default:
             if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
       }

       // OPTIONS:
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(

          'Content-Type: application/json',
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

       // EXECUTE:
       $result = curl_exec($curl);
       if(!$result){die("Connection Failure");}
       curl_close($curl);
       return $result;
    }


}
?>
