<!DOCTYPE html>
<head>
<title>DmN MuCMS - Server Environment Checker</title>
<style>
body {font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
.pass {color:green;font-weight:bold;}
.fail {color:red;font-weight:bold;}
</style>
</head>
<body>
<h1>DmN MuCMS - Server Environment Checker</h1>
<?php
extension_check(array( 
	'curl',
	'dom', 
	'gd', 
	'iconv',
	'mbstring',
	'pcre', 
	'pdo', 
	'SimpleXML',
	'openssl',
	'json',
	'session',
	'xml',
	'Reflection'
));

function extension_check($extensions){
	$required_php_version = '7.1.0';
	$required_loader = '10.0.0';
	$fail = '';
	$pass = '';
	
	if(version_compare(phpversion(), $required_php_version, '<')){
		$fail .= '<li class="fail">You need<strong> PHP '.$required_php_version.'</strong> (or greater)</li>';
	}
	else{
		$pass .='<li class="pass">You have<strong> PHP '.$required_php_version.'</strong> (or greater)</li>';
	}
	
	foreach($extensions as $extension){
		if(!extension_loaded($extension)){
			$fail .= '<li class="fail">You are missing the <strong>'.$extension.'</strong> extension</li>';
		}
		else{	
			$pass .= '<li class="pass">You have the <strong>'.$extension.'</strong> extension</li>';
		}
	}
	
	if(!extension_loaded("pdo_odbc") && !extension_loaded("pdo_sqlsrv") && !extension_loaded("sqlsrv") && !extension_loaded("pdo_dblib")){
		$fail .= '<li class="fail">You are missing all of the following extensions: pdo_odbc, pdo_sqlserv, pdo_dblib, sqlsrv install atleast one of them</li>';
	}
	
	if(function_exists('ioncube_loader_iversion')){
		$ilv = ioncube_loader_iversion();
		$current_loader = sprintf("%d.%d.%d", $ilv / 10000, ($ilv / 100) % 100, $ilv % 100);
		if (version_compare($required_loader, $current_loader, ">")) {
			$fail .= '<li class="fail">Required IonCube loader <strong>'.$required_loader.'</strong> or later. Your IonCube loader version is <strong>'.$current_loader.'</strong></li>';
		}
		else{
			$pass .= '<li class="pass">You have IonCube loader version <strong>'.$required_loader.'</strong> (or greater)</li>';
		}
	} 
	else{
		$fail .= '<li class="fail">You are missing <strong>IonCube</strong> loaders.</li>';
	}
	
	if($fail){
		echo '<p><strong>Your server does not meet the following requirements in order to install DmN MuCMS.</strong>';
		echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for DmN MuCMS:';
		echo '<ul>'.$fail.'</ul></p>';
		echo 'The following requirements were successfully met:';
		echo '<ul>'.$pass.'</ul>';
	} 
	else{
		echo '<p><strong>Congratulations!</strong> Your server meets the requirements for DmN MuCMS.</p>';
		echo '<ul>'.$pass.'</ul>';
	}
}
?>
</body>
</html>