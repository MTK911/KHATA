<?php
include_once 'configuration.php'; //include all variables from config file

//File Maker
if (!is_file($filename)) {
    $myfile = @fopen($filename, "w") or die("Unable to open file!");
	fclose($myfile);
}

//GET User IP
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
$user_ip = getUserIP();

//Start collecting stuff
$date = date('m/d/Y', time());
$dime = date('h:i:sa');

//To handle JSON request
if(isset($_SERVER["CONTENT_TYPE"]) && ($_SERVER["CONTENT_TYPE"] == 'application/json')){
$data = file_get_contents("php://input");
serialize($data);
$data=str_replace(",","﹐",$data);

//To handle XML request
}elseif(isset($_SERVER["CONTENT_TYPE"]) && ($_SERVER["CONTENT_TYPE"] == 'application/xml'||'text/xml')){
$data = '';
$data = file_get_contents("php://input");
$data=str_replace(",","﹐",$data);
}else{

//To handle rest of the requests
$data = array_merge($_GET, $_POST);
if ($data==null){
$data = '';
}
serialize($data);
$data=json_encode($data);
$data=str_replace(",","﹐",$data);
}

//Putting all data in one place
$simple_sit = sprintf(
	'%s,%s,%s,%s,%s,%s,%s',
	$date,
	$dime,
	$user_ip,
	str_replace(",","-",$_SERVER['HTTP_USER_AGENT']),//Had to replace that comma for CSV and Table
	$_SERVER['REQUEST_METHOD'],
	$_SERVER['REQUEST_URI'],
	$data
);

$size=filesize($filename); //limit log size to 1 GB to prevent DOS/Memory Exhaustion attacks
if ($size >= $filesize) { 
	echo '<img src="https://i.giphy.com/12XMGIWtrHBl5e.gif" style="display:block;margin-left: auto;margin-right: auto;width: 75%;" alt-text="File Size limit reached">';
} else {

$simple_string = htmlentities($simple_sit); //encoding because don't want JS pop-ups or worst
$message = openssl_encrypt($simple_string, $ciphering, $key, $options, $iv);
//if(false === $message)
//{
//    echo openssl_error_string();
//}
//Write Encrypted data on file
$fwlog = fopen(__DIR__ . sprintf('/%s', $filename), 'a+');
fwrite($fwlog, "\n" . $message, strlen($message));
fclose($fwlog);

//Remove empty lines from file;
file_put_contents("$filename", implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));

if (isset($data['CONTACT'])) {
	die($data['CONTACT']);
} else {
	die('MTK'); //Response to show on getting request
}
}