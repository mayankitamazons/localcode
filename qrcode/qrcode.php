<?php
include("vendor/autoload.php");

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Response;

if(isset($_GET['text']) && $_GET['text'] != "")
{
	$text = htmlentities(addslashes($_GET['text']));
}
else
{
	die;
}

// Create a basic QR code
$qrCode = new QrCode($text);
 //~ $qrCode = new QrCode($text);
 $qrCode->setSize(512);

// Set advanced options
$qrCode->setWriterByName('png');
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0]);
$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]);
$qrCode->setLabel('Scan QR Code', 16, './assets/noto_sans.otf', LabelAlignment::CENTER);
$qrCode->setValidateResult(false);

// Directly output the QR code
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();

die;
?>
