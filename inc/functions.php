<?php
require_once 'config.php';
$db;
if ( !isset($db))
{
	include_once("Pdodb.class.php");
	//MySQL
	$db = new Pdodb(DB_DSN, DB_USER ,DB_PASSWORD );
}
require_once("config.php");
/*Custom error log*/
function custom_error_log($msg)
{
	$log_file = "error_log.txt";
	if(file_exists($log_file)) 
	{
	$logcontent = date('Y-m-d h:ia')." ".$msg."\r\n";
	$logcontent = $logcontent.file_get_contents($log_file);
	file_put_contents($log_file, $logcontent);
	}
}
/*Sending email using PHP mail function*/
function send_email($from ='', $to = '',  $reply_to = '',  $cc = '', $subject = '',$msg = '') 
{
    $ret;
    try
    {
    	$message = '<html>';
	    $message = $msg;
	    $message .= '</html>';
	    // To send HTML mail, the Content-type header must be set
	    $headers  = 'MIME-Version: 1.0' . "\r\n";
	    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	    // Additional headers
	    $headers .= 'From: '.$from.'' . "\r\n";
	    $headers .= "Cc: bala121083@gmail.com" . "\r\n";
	    $headers .= "Cc: $cc" . "\r\n";
	    $headers .= "Reply-To: $reply_to" . "\r\n";
	    $ret = @mail($to, $subject,$message, $headers);
	}
	catch(Exception $e) {
			$msg = 'Mail issue: '. $e->getMessage();
			custom_error_log($msg);
		}
    return $ret;
}
/*Get files from ftp*/
function get_files_list_data_from_ftp()
{
   // connect and login to FTP server
   try
   {
 	$ret_output = array(); 
	$ftp_server = FTP_HOST;
	$ftp_conn = ftp_connect($ftp_server);
	if(!$ftp_conn)
	{
		$msg = "Could not connect to $ftp_server";
		custom_error_log($msg);
		 die("Couldn't connect to $ftp_server");
		
	}
	$login = ftp_login($ftp_conn, FTP_USER, FTP_PWD);
	if(!$login)
	{
		$msg = 'Ftp login not working: '.FTP_HOST;
		custom_error_log($msg);
	}
	else
	{
		// get file list of csv directory
		$path ="/csv";
		
	    $file_lists = ftp_nlist($ftp_conn, $path); 
	    $f_cnt = 0;
		foreach($file_lists as $file)
		{
			if (strpos($file, '.csv') !== false) 
			{ 
			   $remote_path = $path."/".$file;
			   $tmp_handle = fopen('php://temp', 'r+');
			   
			   $csv_data = array();
			   if (ftp_fget($ftp_conn, $tmp_handle, $remote_path, FTP_ASCII)) 
			   {
				   rewind($tmp_handle);
				 
				   while (!feof($tmp_handle) ) {
				        $data = fgetcsv($tmp_handle, 1024);
				        $csv_data[] = $data;
				    }
				    $csv_data = array_filter($csv_data);
				    
				     array_walk($csv_data, function(&$a) use ($csv_data) {
				      $a = array_map("utf8_encode", $a);
				      $a = array_combine($csv_data[0], $a);
				    });
				    array_shift($csv_data); # remove column header
				    
				}
				$ret_output[$file] = $csv_data;
				fclose($tmp_handle);
			}
		}
	
	}
	
	//close connection
	ftp_close($ftp_conn);
	}
	catch(Exception $e) {
			$msg = 'Ftp issue: '. $e->getMessage();
			custom_error_log($msg);
		}
	return $ret_output;
}

function move_ftp_file($file)
{
   // connect and login to FTP server
   try
   {
   	
	$ftp_server = FTP_HOST;
	$ftp_conn = ftp_connect($ftp_server);
	if(!$ftp_conn)
	{
		$msg = "Could not connect to $ftp_server";
		custom_error_log($msg);
		 die("Couldn't connect to $ftp_server");
		
	}
	$login = ftp_login($ftp_conn, FTP_USER, FTP_PWD);
	if(!$login)
	{
		$msg = 'Ftp login not working: '.FTP_HOST;
		custom_error_log($msg);
	}
	else
	{
		// get file list of csv directory
		$input_path ="/csv/";
		$input_file = $input_path.$file;
		$output_path = "archived/";
		$local_file = $output_path.$file;
		//echo $output_file."<br/>";
		//echo $input_file."<br/>";
		
		
		//if(ftp_rename($ftp_conn, $input_file, $output_file)) 
		 $tmp_handle = fopen('php://temp', 'r+');
			   
	   if (ftp_fget($ftp_conn, $tmp_handle, $input_file, FTP_ASCII)) 
	   {
       		file_put_contents($local_file,$tmp_handle);
       		ftp_delete($ftp_conn,$input_file);
		} 
		else 
		{
		       custom_error_log("ERROR!!!. The file could not be moved $input_file to $output_path");
		}
	
	 //  exit;
	}
	
	//close connection
	ftp_close($ftp_conn);
	}
	catch(Exception $e) {
			$msg = 'Ftp issue: '. $e->getMessage();
			custom_error_log($msg);
		}
}


function getExtension($str) 
{
         $i = strrpos($str,".");
         if (!$i) { return ""; } 
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

?>