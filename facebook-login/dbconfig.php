<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'koofamil_demo');    // DB username
define('DB_PASSWORD', '6bepaAQCM9r-');    // DB password
define('DB_DATABASE', 'koofamil_demo');      // DB name
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
?>