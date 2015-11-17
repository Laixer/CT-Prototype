<?php
/*
 * Copyright © 2012
 * All rights reserved
 *
 * calculatietool.com
 *
 * Client logon page
 */

/* Check is logged in */
include_once("../../private_html/conn_db_common.php");

/* Signup data */
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'] > NULL)){
	$email = mysql_real_escape_string($_POST['fld_email']);
	$password = md5(mt_rand());
	
	$rs_exist_mail_qry = sprintf("SELECT * FROM tbl_user WHERE Email='%s' LIMIT 1", $email);
	$rs_exist_mail_result = mysql_query($rs_exist_mail_qry) or die("Error: " . mysql_error());
	$rs_exist_mail_row = mysql_fetch_assoc($rs_exist_mail_result);
	mysql_free_result($rs_exist_mail_result);
	
	if($rs_exist_mail_row){
		$rs_forgot_update_qry = sprintf("UPDATE tbl_user SET Timestamp_date=UNIX_TIMESTAMP(), Password=stf_cryptor('%s'), Confirmed='N' WHERE User_id='%s'", $password, $rs_exist_mail_row['User_id']);
		mysql_query($rs_forgot_update_qry) or die("Error: " . mysql_error());

		// subject
		$subject = 'Wachtwoord vertegen - Calculatietool';
		
		// message
		$message = '
		<html>
		<head>
		  <title>Wachtwoord vertegen</title>
		</head>
		<body>
		  <h3>Beste ' . $rs_exist_mail_row['Name'] . ',</h3>
		  <p>	Klik op de onderstaande link om uw wachtwoord opnieuw in te stellen.</p>
	    <p><a href="http://beta.calculatietool.com/registration.php?r_utm='.base64_encode($rs_exist_mail_row['Name']).'&ref='.base64_encode($password).'">Wachtwoord instellen</a></p>
	    <p>Voor verdere informatie en vragen kunt u een email sturen naar:<br>
        info@calculatietool.com</p>
		  <p>Met vriendelijke groet,<br>
        Calculatietool.com</p>
		  <p><font color="#666666">U zult opnieuw de algemene voorwaarde moeten accepteren</font></p>
		  <p>&nbsp;</p>
		</body>
		</html>
		';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'To: ' . $rs_exist_mail_row['Name'] . ' <' . $rs_exist_mail_row['Email'] . "\r\n";
		$headers .= 'From: Calculatietool.com <info@calculatietool.com>' . "\r\n";
	
		// Mail it
		$status = @mail($rs_exist_mail_row['Email'], $subject, $message, $headers);
		if($status){
			$reset_mail_success = 1;
		}else{
			echo "Mailing error";
		}
	}else{
		$unknown_email = 1;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Calculatietool - Wachtwoord Vergeten</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../css/main.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="login-header"></div>
	<div id="page">
		<div id="page-bgtop">
			<div id="sidebar-first-use"></div>
		  <div id="content-forgot">
			  <div id="content-header">Wachtwoord vergeten</div>
			  <div class="post1">
					<div class="entry">
                    <?php if($unknown_email){ ?><div class="login-failed">Dit email adres bestaat niet</div><?php } ?>
                    <?php if($reset_mail_success){ ?>
                    <div class="login-succes">De bevestiging is naar uw email adres gestuurd</div>
                    <?php } ?>

					  <form id="frm_forgot" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					    <table width="70%" border="0">
					      <tr>
					          <td width="188">Email adres</td>
					          <td width="402" align="right"><input type="text" name="fld_email" id="fld_email" /></td>
					          </tr>
					      <tr>
					          <td>&nbsp;</td>
					          <td align="right"><input type="submit" name="btn_submit" id="btn_submit" value="Versturen" /></td>
					          </tr>
					      <tr>
					          <td colspan="2"><a href="/" style="text-decoration: none; color: #000;">« Inloggen</a></td>
					          </tr>
					      </table>
					  </form>
				</div>
			  </div>
		  </div>
		  <div style="clear: both;">&nbsp;</div>
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
