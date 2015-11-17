<?php
#cleanup todo

# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# Project data id
$project_id = mysql_real_escape_string($_GET['r_id']);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$salary = mysql_real_escape_string($_POST['fld_salary']);
	$salary_sec = mysql_real_escape_string($_POST['fld_salary_sec']);
	$profit1_material = mysql_real_escape_string($_POST['sl_profit1_material']);
	$profit1_material_sec = mysql_real_escape_string($_POST['sl_profit1_material_sec']);
	$profit1_physical = mysql_real_escape_string($_POST['sl_profit1_physical']);
	$profit1_physical_sec = mysql_real_escape_string($_POST['sl_profit1_physical_sec']);
	$profit1_other = mysql_real_escape_string($_POST['sl_profit1_other']);	
	$profit2_material = mysql_real_escape_string($_POST['sl_profit2_material']);
	$profit2_material_sec = mysql_real_escape_string($_POST['sl_profit2_material_sec']);
	$profit2_physical = mysql_real_escape_string($_POST['sl_profit2_physical']);
	$profit2_physical_sec = mysql_real_escape_string($_POST['sl_profit2_physical_sec']);
	$profit2_other = mysql_real_escape_string($_POST['sl_profit2_other']);	
	$profit3_material = mysql_real_escape_string($_POST['sl_profit3_material']);
	$profit3_material_sec = mysql_real_escape_string($_POST['sl_profit3_material_sec']);
	$profit3_physical = mysql_real_escape_string($_POST['sl_profit3_physical']);
	$profit3_physical_sec = mysql_real_escape_string($_POST['sl_profit3_physical_sec']);
	$profit3_other = mysql_real_escape_string($_POST['sl_profit3_other']);	
	$profit4_material = mysql_real_escape_string($_POST['sl_profit4_material']);
	$profit4_physical = mysql_real_escape_string($_POST['sl_profit4_physical']);
	$profit4_other = mysql_real_escape_string($_POST['sl_profit4_other']);	
	
	$salary = str_replace(',', '.', $salary);
	$salary_sec = str_replace(',', '.', $salary_sec);

	$rs_update_qry = sprintf("UPDATE tbl_project_profit SET Hour_salary='%s', Hour_salary_sec='%s', 1_Profit_material='%s', 1_Profit_material_sec='%s', 1_Profit_physical='%s', 1_Profit_physical_sec='%s', 1_Profit_item='%s', 2_Profit_material='%s', 2_Profit_material_sec='%s', 2_Profit_physical='%s', 2_Profit_physical_sec='%s', 2_Profit_item='%s', 3_Profit_material='%s', 3_Profit_material_sec='%s', 3_Profit_physical='%s', 3_Profit_physical_sec='%s', 3_Profit_item='%s', 4_Profit_material='%s', 4_Profit_physical='%s', 4_Profit_item='%s' WHERE Project_id='%s'", $salary, $salary_sec, $profit1_material, $profit1_material_sec, $profit1_physical, $profit1_physical_sec, $profit1_other, $profit2_material, $profit2_material_sec, $profit2_physical, $profit2_physical_sec, $profit2_other, $profit3_material, $profit3_material_sec, $profit3_physical, $profit3_physical_sec, $profit3_other, $profit4_material, $profit4_physical, $profit4_other, $project_id);
	mysql_query($rs_update_qry) or die("Error: " . mysql_error());
	
	$rs_update_project = sprintf("UPDATE tbl_project SET Timestamp_date=NOW() WHERE Project_id='%s' AND User_id='%s'", $project_id, $user_id);
	mysql_query($rs_update_project) or die("Error: " . mysql_error());
	$closewin = true;
}

# Select profits
$rs_project_profit_qry = sprintf("SELECT p.* FROM tbl_project_profit p JOIN tbl_project u ON u.Project_id=p.Project_id WHERE p.Project_id='%s' AND u.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_project_profit_result = mysql_query($rs_project_profit_qry);
$rs_project_profit_row = mysql_fetch_assoc($rs_project_profit_result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Uurtarief & Winstpercentages</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
<?php if($closewin){ ?>
<script type="text/javascript">
	window.close();
</script>
<?php } ?>
<div style="background-color:#FFF">
	<div style="margin: 0 auto; width: 519px;">
		<div class="entry">
			<?php if($success_message){ echo '<div class="success">'.$success_message.'</div>'; } ?>
			<?php if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; } ?>
			<?php if($error_message){ echo '<div class="error">'.$error_message.'</div>'; } ?>
			<div id="title">Uurtarief & Winstpercentages</div>
			<br />
					<table id="tbl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" width="100%" border="0">
						<tr>
							<td width="150"></td>
							<td width="24">&nbsp;</td>
							<td width="150" class="tbl-head">Calculatie</td>
							<td width="24">&nbsp;</td>
							<td width="150" class="tbl-head">Meerwerk</td>
						</tr>
						<tr>
							<td width="150" class="tbl-head"><div style="padding-left:2px;">Eigen uurtarief</div></td>
							<td width="24">&nbsp;</td>
							<td width="150" class="tbl-head">&nbsp;</td>
							<td width="24">&nbsp;</td>
							<td width="150" class="tbl-head">&nbsp;</td>
						</tr>
						<form name="frm_profits" id="frm_profits" action="" method="post">
						<tr>
							<td>Uurtarief excl. BTW</td>
							<td>&nbsp;</td>
							<td><input name="fld_salary" type="text" id="fld_salary" style="width: 99%;" value="<?php echo number_format($rs_project_profit_row['Hour_salary'], 2, ',', '.'); ?>" /></td>
							<td>&nbsp;</td>
							<td><input name="fld_salary_sec" type="text" id="fld_salary_sec" style="width: 99%;" value="<?php echo number_format($rs_project_profit_row['Hour_salary_sec'], 2, ',', '.'); ?>" /></td>
						</tr>
						<tr>
							<td class="tbl-head">Aanneming</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
							</tr>
						<tr>
							<td>Winst % materiaal</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit1_material" id="sl_profit1_material">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['1_Profit_material'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit1_material_sec" id="sl_profit1_material_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['1_Profit_material_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % materieel</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit1_physical" id="sl_profit1_physical">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['1_Profit_physical'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit1_physical_sec" id="sl_profit1_physical_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['1_Profit_physical_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % postprijs</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit1_other" id="sl_profit1_other">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['1_Profit_item'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="tbl-head">Onderaanneming</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
						</tr>
						<tr>
							<td>Winst % materiaal</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit2_material" id="sl_profit2_material">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['2_Profit_material'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit2_material_sec" id="sl_profit2_material_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['2_Profit_material_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % materieel</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit2_physical" id="sl_profit2_physical">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['2_Profit_physical'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit2_physical_sec" id="sl_profit2_physical_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['2_Profit_physical_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % postprijs</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit2_other" id="sl_profit2_other">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['2_Profit_item'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php if(false){ ?>
						<tr>
							<td class="tbl-head">Derden</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
						</tr>
						<tr>
							<td>Winst % materiaal</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit3_material" id="sl_profit3_material">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['3_Profit_material'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit3_material_sec" id="sl_profit3_material_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['3_Profit_material_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % materieel</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit3_physical" id="sl_profit3_physical">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['3_Profit_physical'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit3_physical_sec" id="sl_profit3_physical_sec">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['3_Profit_physical_sec'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Winst % postprijs</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit3_other" id="sl_profit3_other">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['3_Profit_item'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
                        <?php } ?>
						<tr>
							<td class="tbl-head">Stelpost</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tbl-head">&nbsp;</td>
						</tr>
						<tr>
							<td>Winst % materiaal</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit4_material" id="sl_profit4_material">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['4_Profit_material'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Winst % materieel</td>
							<td>&nbsp;</td>
							<td>
								<select style="width:99%" name="sl_profit4_physical" id="sl_profit4_physical">
								<?php for($i=0;$i<=100;$i++){ if($rs_project_profit_row['4_Profit_physical'] == $i){ echo '<option selected="selected" value="'.$i.'">'.$i.'%</option>'; }else{ echo '<option value="'.$i.'">'.$i.'%</option>'; } } ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right"><input name="btn_submit" type="submit" id="btn_submit" value="Opslaan" /></td>
						</tr>
						</form>
					</table>

		</div>
		<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
</div>
</div>
</div>
</body>
</html>