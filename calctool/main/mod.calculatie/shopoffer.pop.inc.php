<?php
/**
 * Project hour registration
 * - Markup correction
 * - Code Safety
 *	 - Escape
 *	 - User based selection
 * - Freeing results
 * - Error handling
 */

# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_list_qry = sprintf("SELECT * FROM (SELECT * FROM tbl_project_calc_material AS m WHERE Project_id='%s' UNION ALL SELECT * FROM tbl_project_calc_physical AS p WHERE Project_id='%s') AS T ORDER BY T.Price DESC", $rs_project_perm_check_row['Project_id'], $rs_project_perm_check_row['Project_id']);
$rs_project_list_result = mysql_query($rs_project_list_qry) or die("Error: " . mysql_error());

# No projects have been found
if(!$rs_project_perm_check_row){
	$error_message = "Er zijn geen gegevens gevonden";
	$hide_page = 1;
}

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Boodschappenlijst</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>
<div style="background-color:#FFF">
	<div style="margin: 0 auto; width: 870px;">
		<div class="entry">
		<div style="height:100px;" id="title">
			<span style="float:right; margin:35px 50px;color:#000;">BOODSCHAPPENLIJST</span>
			<span style="float:left"><img src="http://static-4.cdnhub.nl/nl/images/logos/van-dale-logo-big.gif" alt="Logo" width="200" height="100"></span>
		</div>
	<div id="content-main">
		<div id="intern">
			<div id="table">
				<table width="100%" style="border:solid 1px #000000">
					<tr>
						<td width="26%"><b>Projectnaam</b></td>
						<td width="25%"><?php echo $rs_project_perm_check_row['Name']; ?></td>
						<td width="49%">&nbsp;</td>
					</tr>
					<tr>
						<td><b>Projectnummer</b></td>
						<td><?php echo $rs_project_perm_check_row['Project_id']; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b>Datum</b></td>
						<td><?php echo date("d-m-Y"); ?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td width="26%">Gebruikte cataloges</td>
						<td width="74%">X</td>
					</tr>
					<tr>
						<td width="26%">&nbsp;</td>
						<td width="74%">Y</td>
					</tr>
					<tr>
						<td width="26%">Gesoorteerd op</td>
						<td width="74%">Y</td>
					</tr>
				</table>
				<br />
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<td width="338" align="left"><b>Mareriaal / Materieel</b></td>
						<td align="right"><b>Prijs</b></td>
						<td align="center"><b>Eenheid</b></td>
						<td align="center"><b>Stuk</b></td>
						<td><b>Hoofdstuk</b></td>
						<td align="right"><b>Werkzaamheid</b></td>
					</tr>
					<?php while($rs_project_list_row = mysql_fetch_assoc($rs_project_list_result)){ ?>
					<tr>
						<td><?php echo $rs_project_list_row['Materialtype']; ?></td>
						<td width="92" align="right">&euro;&nbsp;<?php echo $rs_project_list_row['Price']; ?></td>
						<td width="90" align="center"><?php echo $rs_project_list_row['Unit']; ?></td>
						<td width="80" align="center"><?php echo $rs_project_list_row['Amount']; ?></td>
						<td width="113"></td>
						<td width="145" align="right">&nbsp;</td>
					</tr>
					<?php } ?>
				</table><br />
				<table width="50%" border="0">
					<tr>
						<td width="20%"><b>Aantekening</b></td>
						<td width="80%"><?php echo nl2br($_GET['note']); ?></td>
					</tr>
					</table>
				<div style="margin: 15px 0 10px 0">
					<?php echo nl2br($rs_project_offer_row['Posttext']); ?>
				</div>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
</div>
</div>
</div>
</body>
</html>