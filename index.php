<?php
include_once 'configuration.php'; //Include all variables from configuration file
error_reporting(0);//LOL
ini_set('session.cookie_secure', '0');//if running on HTTPS turn it on;
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'secure');

//Security Headers because why not
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header_remove("X-Powered-By");
session_start();

//Session string generator
$hash = md5($random1.$password.$random2); 

//Responder file maker
if (!is_file($responder)) {
    $myresponder = @fopen($responder, "w") or die("Unable to make responder file!");
	fwrite($myresponder, "<?php echo 'MTK' ?>");
	fclose($myresponder);
}

//Write data on responder file
if(isset($_POST['responderdata'], $_POST['Token']))
{
	if ($_POST['Token'] != $_SESSION['ANTI']){
    header("Location: ".$_SERVER['PHP_SELF']);

	}else{
	
	$responder_open = fopen($responder,"w+");
	fwrite($responder_open, $_POST['responderdata']);
	fclose($responder_open);
}
}

//Reading contents of responder file and to display it on textarea window
$responder_disp = htmlspecialchars(implode("",file($responder)));

//Logout function
if(isset($_POST['logout'], $_POST['Token']))
{
	if ($_POST['Token'] != $_SESSION['ANTI']){
    header("Location: ".$_SERVER['PHP_SELF']);

	}else{
	unset($_SESSION['login']);
	header("Location: ".$_SERVER['PHP_SELF']);
	die;

}
}
//Clear Log file function
if(isset($_POST['purge'], $_POST['Token']))
{
	if ($_POST['Token'] != $_SESSION['ANTI']){
    header("Location: ".$_SERVER['PHP_SELF']);

	}else{
	
	@unlink("$filename");
	header("Location: ".$_SERVER['PHP_SELF']);
	die;
	exit("Logs Removed");
}
}



//On Successful Login show all this
if (isset($_SESSION['login']) && $_SESSION['login'] == $hash) {
//Anti-CSRF token genrator
$tokes = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['ANTI']=$tokes
?>

<?php header('Cache-Control: max-age=84600'); //added cache becasue so much to load from internet
 header("Refresh: $refresh");//60 second auto refresh configured in configuration file?>
<head>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<style>
//headache
body {
  margin: 2rem;
}
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    border-color: #d6d6d6;
    outline: #d6d6d6;
}
div.dataTables_length {
	float: left;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
	outline: none !important;
    box-shadow: none !important;
	background-color: black;
    border-color: black;
	color: white;
}
.pagination>li>a, .pagination>li>span  {
	color: black;
	border-color: black;
	outline: none !important;
    box-shadow: none !important;
}

.pagination>li>a:focus,.pagination>li>a:hover {
	color: white;
	background-color: black;
	border-color: black;
}

.pagination>.disabled>a, .pagination>.disabled>a:focus, .pagination>.disabled>a:hover, .pagination>.disabled>span, .pagination>.disabled>span:focus, .pagination>.disabled>span:hover {
    color: #777;
    cursor: not-allowed;
    background-color: #fff;
    border-color: #d6d6d6;
}

div.dataTables_wrapper div.dataTables_filter input {
	width: 180px;
	margin-top: 10px;
}
.form-control:focus {
    border-color: #000911;
    box-shadow: 1px 1px 1px rgba(9, 1, 1, 9) inset, 1px 1px 1px rgba(11, 9, 11, 9);
}
.btn:hover, btn:active, btn:focus {
  cursor: pointer;
  background-color: #000;
  color: #fff;
  outline: none !important;
  box-shadow: none !important;
}
.btn:focus {
  border-color: rgba(0, 0, 0, 1);
  box-shadow: 0px 0px 0px rgba(0, 0, 0, 1) inset, 0px 0px 0px rgba(0, 0, 0, 1);
  outline: 0 none;
}
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
  -webkit-appearance: none;
   margin: 0;
}
.modalDialog {
    position: fixed;
    font-family: Arial, Helvetica, sans-serif;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 99999;
    opacity:0;
    -webkit-transition: opacity 400ms ease-in;
    -moz-transition: opacity 400ms ease-in;
    transition: opacity 400ms ease-in;
    pointer-events: none;
}
.modalDialog:target {
    opacity:1;
    pointer-events: auto;
}
.modalDialog > div {
	width: 500px;
	position: relative;
	margin: 5% auto;
	padding: 15px;
	border-radius: 10px;
	background: #fff;
}
</style>
</head>
<body style="margin:10;padding:0">
<!-- Logout Clear Log Buttons-->
<form action="" method="post">
<button tabindex="-1" class="btn btn-default buttons-print" style="float:right;margin-left:10px;" name="logout" value="true" type="submit">Logout</button>
<button tabindex="-1" class="btn btn-default buttons-print" style="float:right;" name="purge" value="true" type="submit" >Clear Logs</button>
<a tabindex="-1" class="btn btn-default buttons-print" style="float:right;margin-right:10px;" href="#responder" type="button" >Responder</a>
<input type='hidden' name='Token' value='<?php echo($_SESSION['ANTI']) ?>' />
</form>
<div id="responder" class="modalDialog">
  <div>
        <form action="" method="post">
              <div>
                <textarea wrap="hard" type="text" style="width: 100%; max-width: 100%; margin-bottom: 10px;height:350px; resize: none;" name="responderdata"><?php echo $responder_disp ?></textarea>
                <button tabindex="1" class="btn btn-default buttons-print" type="submit">Submit</button>
				<a class="btn btn-default buttons-print" type="button" href="#close">Close</a>
				<input type='hidden' name='Token' value='<?php echo($_SESSION['ANTI']) ?>' />
			  </div>
        </form>
   </div>
</div>
<table id="main" class="table table-striped table-bordered" cellspacing="0" width="100%">
<?php
//Where the magic happens
$file = fopen("$filename", 'r') or die("<img src='https://bigmemes.funnyjunk.com/gifs/Nothing_2479d8_2822723.gif' style='display:block;margin-left: auto;margin-right: auto;width: 75%;' alt='No logs to show'>");//MEME of infinite Loop
echo "<thead><tr><th>Date</th><th>Time</th><th>IP</th><th>User-Agent</th><th>Request-Method</th><th>URI</th><th>JSON-DATA</th></tr></thead><tbody>";
//Read the file line by line until EOF
while(! feof($file))
{
	
	$line = fgets($file);
	//Decrypt encrypted data
	$decryption=openssl_decrypt($line, $ciphering, $key, $options, $iv);
	if(false === $decryption)//If decryption will fail it will skip that line it is a problem but it is the easy way right now
{
    continue;
}
	$myArray_fin = explode(',', $decryption);
    $myArray = array_filter($myArray_fin);
	echo '<tr>';
	foreach($myArray as $my_Array){
    echo '<td>'.$my_Array.'</td>';
}
echo '</tr>';
}
fclose($file);
?>
</tbody>
</table>
<!-- Wooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>
<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
<!-- veeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee -->
<script>
$(document).ready(function() {
  // DataTable initialisation
  $('#main').DataTable({
    "dom": '<"top"B>frt<"bottom">lp<"clear">',
    "paging": true,
    "autoWidth": true,
    "buttons": [
				'colvis',
				'copyHtml5',
                'csvHtml5',
				'excelHtml5',
				'pdfHtml5',
				'print'
			],
	"order": [[ 1, "desc" ]]

  });
});
</script>


<?php
}
//Check Login Function
//To check all parameters are present in request
else if (isset($_POST['submit'] , $_POST['csrfToken'] , $_POST['answer'] , $_POST['username'] , $_POST['password'])) {
	if ($_POST['csrfToken'] != $_SESSION['csrfToken']){
	
		display_login_form();
		echo '<h4 style="color:red;text-align:center;font-weight:bold">CSRF attack detected</h4>';
		} else {
	if ($_POST['answer'] != $_SESSION['result']){
	
		display_login_form();
		echo '<h4 style="color:red;text-align:center;font-weight:bold">Wrong Captcha</h4>';
		} else {
	if ($_POST['username'] == $username && (hash('sha256',$_POST['password']) == $password)){

		$_SESSION["login"] = $hash;
		header("Location: $_SERVER[PHP_SELF]");

	} else {
		
		display_login_form();
		echo '<h4 style="color:red;text-align:center;font-weight:bold">Username or password is invalid</h4>';
		
	}
}	
}
}
else { 

	display_login_form();

}

//CAPTCHA GENRATOR
function display_login_form(){ 

//magic adder
$captcha_1 = rand(1,9);
$captcha_2 = rand(1,9);
$_SESSION['result'] = $captcha_1+$captcha_2;

//Captcha image 1 generators
$im = imagecreate(11, 20);
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 3, 3, 3);

imagestring($im, 6, 1, 0, $captcha_1, $textcolor);
imagepng($im);
$imgData=ob_get_contents();ob_clean();
imagedestroy($im);

//Captcha image 2 generators
$im2 = imagecreate(11, 20);
$bg2 = imagecolorallocate($im2, 255, 255, 255);
$textcolor2 = imagecolorallocate($im2, 3, 3, 3);
imagestring($im2, 6, 1, 0, $captcha_2, $textcolor2);
imagepng($im2);
$imgData2=ob_get_contents();ob_clean();
imagedestroy($im2);

//Anti-CSRF Token GENRATOR
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['csrfToken']=$token
?>
<?php header('Cache-Control: max-age=84600');?>
<!doctype html>
<html>
<head>
<title>Admin Login Area</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
//headache
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #fffff;
}
.form-control:focus {
        border-color: #000911;
        box-shadow: 1px 1px 1px rgba(9, 1, 1, 9) inset, 1px 1px 1px rgba(11, 9, 11, 9);
}
.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}

.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.captcha {
  font-size:18px;
  float:left;
  margin-right:10px;
  font-weight:bold
}
.answer {
  float:left;
  width:12%;
  margin-bottom:10px;
  text-align:center;
  font-size: 16px;
  font-weight: bold;
}
.answer:focus {
border-color: rgba(0, 0, 0, 1);
box-shadow: 0px 0px 0px rgba(0, 0, 0, 1) inset, 0px 0px 0px rgba(0, 0, 0, 1);
outline: 0 none;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
.btn-group {
  padding: 1rem 0;
}

button {
  background-color: #fff;
  border: 1px solid #000;
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  border-radius: 6px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.33;
  width: 100%;
}

button:hover, button:active, button:focus {
  cursor: pointer;
  background-color: #000;
  color: #fff;
  font-weight: bold;
    outline: none !important;
  box-shadow: none !important;
}
.blinking-cursor {
  pointer-events: none;
  user-select: none;
  animation: blink 1s steps(2, start) infinite;
}
@keyframes blink {
  to {
    visibility: hidden;
  }
}
</style>
</head>
<body>
<div class="container">

    <form class="form-signin" role="form" method="POST" action="" autocomplete="off">
		<img src="data:image/png;base64,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" style='display:block;margin-left: auto;margin-right: auto;width: 75%;'>
<h2 class="form-signin-heading"><span id="type-text"></span><span class="blinking-cursor">_</span></h2>
        <input style="margin-bottom:10px;" type="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <input  type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="captcha"><?php echo '<img src="data:image/png;base64,'.base64_encode($imgData).'" />'; ?> + <?php echo '<img src="data:image/png;base64,'.base64_encode($imgData2).'" />'; ?> =</div>
		<input type="number" class="answer" name="answer" required>
		<input type='hidden' name='csrfToken' value='<?php echo($_SESSION['csrfToken']) ?>' />
        <button name="submit" value="submit" >Sign in</button>
    </form>
</div>

<script>
// Animation stuff
function init () {

  wait(1000).then(typeLoop)  
  function typeLoop() {
    typeText('Welcome to KHATA')
      .then(() => wait(2000))
      .then(() => removeText('KHATA'))
	  .then(() => wait(1000))
	  .then(() => typeText('کھاتہ'))
	  .then(() => wait(2000))
	  .then(() => removeText('Welcome to کھاتہ'))
	  .then(() => wait(1000))
      .then(() => typeText('Please Sign in'))
  }
}

const elementNode = document.getElementById('type-text')
let text = ''

function updateNode () {
  elementNode.innerText = text
}

function pushCharacter (character) {
  text += character
  updateNode()
}

function popCharacter () {
  text = text.slice(0, text.length -1)
  updateNode()
}

function clearText () {
  text = ''
  updateNode()
}


function wait (time) {
  return new Promise(resolve => {
    setTimeout(resolve, time)
  })
}

function typeCharacter (character) {
  return new Promise(resolve => {
    const randomMsInterval = 100 * Math.random()
    const msInterval = randomMsInterval < 50 ? 10 : randomMsInterval
    
    pushCharacter(character)
    wait(msInterval).then(resolve)
  })
}

function removeCharacter () {
  return new Promise(resolve => {
    const randomMsInterval = 100 * Math.random()
    const msInterval = randomMsInterval < 50 ? 10 : randomMsInterval
    
    popCharacter()
    wait(msInterval).then(resolve)
  })
}

function typeText (text) {
  return new Promise(resolve => {
    (function _type (index) {
      typeCharacter(text[index]).then(() => {
        if (index + 1 < text.length) _type(index + 1)
        else resolve()
      })
    })(0)
  })
}

function removeText ({ length:amount }) {
  return new Promise(resolve => {
    (function _remove (index) {
      removeCharacter().then(() => {
        if (index + 1 < amount) _remove(index + 1)
        else resolve()
      })
    })(0)
  })
}


init()
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php  } ?>
