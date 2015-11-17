<?php
/*
 * Copyright © 2012
 * All rights reserved
 *
 * calculatietool.com
 *
 * Client logon page
 *
 */
 
include_once("../../private/conn_db_common.php");

if($_SESSION['SES_User_id']){
	# temp redirect
	header("Location: /maintoolv2/");
	exit();
}

/* Login data */
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit_login'] > NULL)){
	$username = mysql_real_escape_string($_POST['fld_username']);
	$password = mysql_real_escape_string($_POST['fld_password']);

	$rs_login_qry = sprintf("SELECT * FROM tbl_user WHERE Name='%s' AND Password=stf_cryptor('%s') LIMIT 1", $username, $password);
	$rs_login_result = mysql_query($rs_login_qry) or die("Error: " . mysql_error());
	$rs_login_row = mysql_fetch_assoc($rs_login_result);
	mysql_free_result($rs_login_result);

	if($rs_login_row){
		if($rs_login_row['User_id'] < 0){
			$user_deleted = 1;
		}else if($rs_login_row['Confirmed'] == 'N'){
			$user_unconfirmed = 1;
		}else if($rs_login_row['Banned'] == 'Y'){
			$user_block = 1;
		}else{
			$_SESSION['SES_User_id'] = $rs_login_row['User_id'];
			setcookie('_UTMUser_id', $username, time() + (86400 * 21));

			mysql_query("UPDATE tbl_user SET Timestamp_date=NOW() WHERE User_id='".$rs_login_row['User_id']."' LIMIT 1") or die("Error: " . mysql_error());
			header("Location: /maintoolv2/");
			exit();
		}
	}else{
		$login_failed = 1;
	}
}

/* Signup data */
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit_signup'] > NULL)){
	$username = mysql_real_escape_string($_POST['fld_username']);
	$email = mysql_real_escape_string($_POST['fld_email']);
	$password = md5(mt_rand());
	$remote_addr = $_SERVER['REMOTE_ADDR'];
	
	if(!$username){
		$register_username_failed = 1;
		$error = 1;
	}
	
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)){
		$register_email_failed = 1;
		$error = 1;
	}

	$rs_exist_name_qry = sprintf("SELECT COUNT(*) AS Total FROM tbl_user WHERE Name='%s' LIMIT 1", $username);
	$rs_exist_name_result = mysql_query($rs_exist_name_qry) or die("Error: " . mysql_error());
	$rs_exist_name_row = mysql_fetch_assoc($rs_exist_name_result);
	mysql_free_result($rs_exist_name_result);
	
	if($rs_exist_name_row['Total'] >= 1){
		$register_username_exist = 1;
		$error = 1;
	}

	$rs_exist_mail_qry = sprintf("SELECT COUNT(*) AS Total FROM tbl_user WHERE Email='%s' LIMIT 1", $email);
	$rs_exist_mail_result = mysql_query($rs_exist_mail_qry) or die("Error: " . mysql_error());
	$rs_exist_mail_row = mysql_fetch_assoc($rs_exist_mail_result);
	mysql_free_result($rs_exist_mail_result);
	
	if($rs_exist_mail_row['Total'] >= 1){
		$register_mail_exist = 1;
		$error = 1;
	}

	if(!$error){
		$rs_create_qry = sprintf("INSERT INTO tbl_user (Create_date, Timestamp_date, Name, Password, Email, IP) VALUES (UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '%s', stf_cryptor('%s'), '%s', '%s')", $username, $password, $email, $remote_addr);
		mysql_query($rs_create_qry) or die("Error: " . mysql_error());

		// subject
		$subject = 'Registratie - Calculatietool';
		
		// message
		$message = '
		<html>
		<head>
		  <title>Registatiegegevens</title>
		</head>
		<body>
		  <h3>Beste '.$username.',</h3>
		  <p>Bedankt voor uw aanmelding bij <strong>Calculatietool.com</strong><br>
	      Klik op de onderstaande link om uw registratie te voltooien.</p>
	    <p><a href="http://calctool.nl/register/?r_utm='.base64_encode($username).'&ref='.base64_encode($password).'">Registratie Voltooien</a></p>
	    <p>Voor verdere informatie en vragen kunt u een email sturen naar:<br>
        info@calculatietool.com</p>
	    <p>Met vriendelijke groet,<br>Calculatietool.com</p>
		  <p><font color="#666666">Wanneer u zich niet had aangemeld bij Calculatietool.com hoeft u verder niets te doen.</font></p>
		  <p>&nbsp;</p>
		</body>
		</html>
		';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'To: ' . $username . ' <' . $email . "\r\n";
		$headers .= 'From: Calculatietool.com <info@calculatietool.com>' . "\r\n";
	
		// Mail it
		$status = @mail($email, $subject, $message, $headers);
		if($status){
			$register_mail_success = 1;
		}else{
			echo "Mailing error";
		}
	}
}

$rs_opt_version_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='website_version' LIMIT 1") or die("Error: " . mysql_error());
$rs_opt_version_row = mysql_fetch_assoc($rs_opt_version_result);
mysql_free_result($rs_opt_version_result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Calculatietool - Inloggen</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../css/main.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="login-header"></div>
	<div id="page">
		<div id="page-bgtop">
			<div id="sidebar"></div>
		  <div id="content-login">
			  <div id="content-header">Aanmelden</div>
			  <div class="post1">
					<div class="entry">
                    <?php if(($login_failed) || ($user_deleted)){ ?>
                    <div class="login-failed"><img src="../images/error.png" alt="Error" title="Error" width="17" height="17" /> Login mislukt, probleer opnieuw</div><?php } ?>
                    <?php if($user_unconfirmed){ ?>
                    <div class="login-failed"><img src="../images/error.png" alt="Error" title="Error" width="17" height="17" /> Dit account is niet bevestigd</div><?php } ?>
                    <?php if($user_block){ ?>
                    <div class="login-failed"><img src="../images/error.png" alt="Error" title="Error" width="17" height="17" /> Dit account is geblokkeerd</div><?php } ?>
					  <form id="frm_login" method="post" action="">
					    <table width="280" border="0">
					      <tr>
					        <td width="98">Gebruikersnaam</td>
					        <td width="172" align="right"><input type="text" name="fld_username" id="fld_username" value="<?php echo ($_COOKIE['_UTMUser_id']!='' ? $_COOKIE['_UTMUser_id'] : ''); ?>" /></td>
				          </tr>
					      <tr>
					        <td>Wachtwoord</td>
					        <td align="right"><input type="password" name="fld_password" id="fld_password" /></td>
				          </tr>
					      <tr>
					        <td>&nbsp;</td>
					        <td align="right"><input type="submit" name="btn_submit_login" id="btn_submit_login" value="Inloggen" /></td>
				          </tr>
					      <tr>
					        <td colspan="2"><a href="/forgot/" style="text-decoration: none; color: #000;">Wachtwoord vergeten »</a></td>
				          </tr>
				         </table>
					  </form>
				</div>
			  </div>
		  </div>
          
          <div id="content-separate"></div>
          
		  <div id="content-register">
			  <div id="content-header">Registreren</div>
			  <div class="post1">
					<div class="entry">
                    <?php if($register_email_failed){ ?>
                    <div class="login-failed"><img src="images/error.png" alt="Error" title="Error" width="17" height="17" /> Het email adres is niet geldig</div><?php } ?>
                    <?php if($register_username_failed){ ?><div class="login-failed"><img src="images/error.png" alt="Error" title="Error" width="17" height="17" />Geef een gebruikersnaam op</div><?php } ?>
                    <?php if($register_username_exist){ ?>
                    <div class="login-failed"><img src="images/error.png" alt="Error" title="Error" width="17" height="17" /> Deze gebruikersnaam bestaat al</div><?php } ?>
                    <?php if($register_mail_exist){ ?>
                    <div class="login-failed"><img src="images/error.png" alt="Error" title="Error" width="17" height="17" /> Dit email adres bestaat al</div><?php } ?>
                    <?php if($register_mail_success){ ?>
                    <div class="login-succes"><img src="images/ok.png" alt="Succes" title="Success" width="17" height="17" /> De registratiebevestiging is naar uw email adres gestuurd</div><?php } ?>
					  <form id="frm_signup" method="post" action="">
					    <table width="300" border="0">
					      <tr>
					        <td width="118">Gebruikersnaam</td>
					        <td width="172" align="right"><input type="text" name="fld_username" id="fld_username" /></td>
				          </tr>
					      <tr>
					        <td>Email</td>
					        <td align="right"><input type="text" name="fld_email" id="fld_email" /></td>
				          </tr>
					      <tr>
					        <td>&nbsp;</td>
					        <td align="right"><input type="submit" name="btn_submit_signup" id="btn_submit_signup" value="Registreren" /></td>
				          </tr>
				        </table>
				      </form>
				</div>
			  </div>
		  </div>
			<div style="clear: both; font-size: 9px; padding-top: 30px;">&nbsp;Versie <?php echo $rs_opt_version_row['Option_value']; ?></div>
		</div>
	</div>
	<div id="login-footer">
		<div id="footer">
			<p>Calculatietool.com | 2012<a href="http://www.rickyswebtemplates.com/" target="_blank"></a></p></div>
  </div>
</div>
</div>
</body>
</html>