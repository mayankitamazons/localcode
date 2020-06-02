<?php
include("config.php");


$REfID = isset($_SESSION['referral_id']) ? $_SESSION['referral_id'] : '' ;
$CUserID = isset($_SESSION['login']) ? $_SESSION['login'] : '';
$invitation_id = isset($_SESSION['invitation_id']) ? $_SESSION['invitation_id'] : '' ;

$chatFr = array();
$SQL = mysqli_query($conn, "select id from users where referred_by ='$REfID' ") ;
while($USERlist =  mysqli_fetch_assoc($SQL)){ 
    array_push($chatFr ,$USERlist['id']) ;
}

$chat_friends = implode(',' , $chatFr);

if(empty($chat_friends)){
   
   $chat_friends =  $invitation_id ;
   
    
}

?>
<script>
var chat_appid = '52013';
</script> 
<?php 
//print_r($_SESSION) ; 



	if(isset($CUserID) && $CUserID > 0) { ?>
	 <script>
		var chat_id = "<?php echo $CUserID; ?>";
		var chat_friends = '<?php echo $chat_friends ?>'; //Similarly populate it with user's friends' site user id's eg: 14,16,20,31
		var chat_name = "<?php echo $_SESSION["name"]; ?>"; 
	
		</script>
	<?php } ?>
<script>
var chat_height = '100%';
var chat_width = '100%';

document.write('<div id="cometchat_embed_synergy_container" style="width:'+chat_width+';height:'+chat_height+';max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;"></div>');

var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchatx_xcorex_xembedcode.js';

chat_js.onload = function() {
    var chat_iframe = {};chat_iframe.module="synergy";chat_iframe.style="min-height:"+chat_height+";min-width:"+chat_width+";";chat_iframe.width=chat_width.replace('px','');chat_iframe.height=chat_height.replace('px','');chat_iframe.src='https://'+chat_appid+'.cometondemand.net/cometchat_embedded.php'; if(typeof(addEmbedIframe)=="function"){addEmbedIframe(chat_iframe);}
}

var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
</script>
