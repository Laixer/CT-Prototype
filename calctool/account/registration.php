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

/* Check is logged in */
include_once("../../private_html/conn_db_common.php");

$username = mysql_real_escape_string(base64_decode($_GET['r_utm']));
$password = mysql_real_escape_string(base64_decode($_GET['ref']));

$rs_first_qry = sprintf("SELECT * FROM tbl_user WHERE Name='%s' AND Password=stf_cryptor('%s') AND Confirmed='N' LIMIT 1", $username, $password);
$rs_first_result = mysql_query($rs_first_qry);
$rs_first_row = mysql_fetch_assoc($rs_first_result);
mysql_free_result($rs_first_result);

$rs_agreement_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='eula' LIMIT 1");
$rs_agreement_row = mysql_fetch_assoc($rs_agreement_result);
mysql_free_result($rs_agreement_result);

if(!$rs_first_row){
	header("Location: /");
	exit();
}

/* Signup data */
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit_first-use'] > NULL)){
	$password = mysql_real_escape_string($_POST['fld_password']);
	$confirm_password = mysql_real_escape_string($_POST['fld_confirm_password']);
	$checkbox = mysql_real_escape_string($_POST['chk_agree']);
	
	if(!$password){
		$password_empty = 1;
		$error = 1;
	}
	
	if($password != $confirm_password){
		$confirm_password_failed = 1;
		$error = 1;
	}
	
	if($checkbox != "on"){
		$agree_failed = 1;
		$error = 1;
	}
	
	if(!$error){
		$rs_activated_qry = sprintf("UPDATE tbl_user SET Timestamp_date=UNIX_TIMESTAMP(), Password=stf_cryptor('%s'), Confirmed='Y' WHERE User_id='%s'", $password, $rs_first_row['User_id']);
		mysql_query($rs_activated_qry);

		$_SESSION['SES_User_id'] = $rs_first_row['User_id'];

		header("Location: /main");
		exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Calculatietool - Activatie</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="css/main.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="login-header"></div>
	<div id="page">
		<div id="page-bgtop">
			<div id="sidebar-first-use"></div>
		  <div id="content-first-use">
			  <div id="content-header">Account activatie</div>
			  <div class="post1">
					<div class="entry">
                    <?php if($agree_failed){ ?><div class="login-failed">U moet de algemene voorwaarde accepteren</div><?php } ?>
                    <?php if($password_empty){ ?><div class="login-failed">Vul een wachtwoord in</div><?php } ?>
                    <?php if($tmp_password_failed){ ?><div class="login-failed">Het tijdelijk wachtwoord klopt niet</div><?php } ?>
                    <?php if($confirm_password_failed){ ?><div class="login-failed">De opgegeven wachtwoorden komen niet overeen</div><?php } ?>
					  <form id="frm_first_use" method="post" action="">
					    <table width="100%" border="0">
					      <tr>
					        <td width="188" valign="top">Algemene Voorwaarde</td>
					        <td width="402" align="right"><textarea name="txt_agreement" readonly="readonly" id="txt_agreement"><?php echo $rs_agreement_row['Option_value']; ?></textarea></td>
				          </tr>
					      <tr>
					          <td>Nieuw wachtwoord</td>
					          <td align="right"><input type="password" name="fld_password" id="fld_password" /></td>
					          </tr>
					      <tr>
					          <td>Bevestig wachtwoord</td>
					          <td align="right"><input type="password" name="fld_confirm_password" id="fld_confirm_password" /></td>
					          </tr>
					      <tr>
					          <td>&nbsp;</td>
					          <td align="left"><input type="checkbox" name="chk_agree" id="checkbox" />
Ik ga akkoord met de <strong>Algemene Voorwaarde</strong></td>
					          </tr>
					      <tr>
					          <td>&nbsp;</td>
					          <td align="right"><input type="submit" name="btn_submit_first-use" id="btn_submit_first-use" value="Doorgaan" /></td>
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
