<?php
 $HOST = preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']);
  date_default_timezone_set("Asia/Kuala_Lumpur");
 $domain_name = $HOST;

switch (trim($HOST)) 
{

	case "localhost":
	 define( 'DEBUG', true );
       
	 break;
	case "koofamilies.com":
	 define( 'DEBUG', false );
	 break;
	default:
    // live server
	define( 'DEBUG', false );
	 break;
   }
    // Start a session if not already started...
   if ( !isset( $_SESSION ) )
    session_start();
	   /**
   * 
   * Database config
   * deb and prod
   */
   if(DEBUG == true)
   {
   	define ('DB_DSN', 'mysql:host=localhost;dbname=koofamilies');
	define ('DB_USER', 'root');
	define ('DB_PASSWORD', '');
   }
   else
   {
    //Production
	define ('DB_DSN', 'mysql:host=localhost;dbname=koofamilies');
	define ('DB_USER', 'root');
	define ('DB_PASSWORD', '');
   }
   
   $ROOT_SITE = "http://{$_SERVER['SERVER_NAME']}";
   $SECURE_SITE = (DEBUG == TRUE) ? "http://{$_SERVER['SERVER_NAME']}" : "https://{$_SERVER['SERVER_NAME']}";  
   $BASE_FILEPATH = $_SERVER['DOCUMENT_ROOT'];
  
   $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
	// The first part of the script path is
	// a subdomain on the development server...
	$pos= strpos( $SCRIPT_NAME, "/", 1 );
	//echo $scriptName. $pos;
	
	if ( $pos )
	{
	 $ROOT_SITE .= (substr( $SCRIPT_NAME, 0, $pos ));
	 $BASE_FILEPATH .= substr( $SCRIPT_NAME, 0, $pos );
	}
	
	define('BASE_URL',  $ROOT_SITE);
	define('SECURE_BASE_URL',  $SECURE_SITE);
	define('BASE_FILEPATH',  $BASE_FILEPATH);
  
	$db;
	if ( !isset($db))
    {
		include_once("Pdodb.class.php");
		//MySQL
		$db = new Pdodb(DB_DSN, DB_USER ,DB_PASSWORD );
	}
	
?>