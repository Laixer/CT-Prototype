<?php
# Project data id
$project_id = mysql_real_escape_string($_GET['r_id']);

$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $project_id);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

# Delete calculate material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_ca_id'])){
	if(!$rs_project_module7_row){
		$del_ca_id = mysql_real_escape_string($_POST['fld_del_ca_id']);
	
		$rs_del_ca_qry = sprintf("DELETE ca FROM tbl_project_calc_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_material_id='%s' AND p.User_id='%s'", $del_ca_id, $user_id);
		mysql_query($rs_del_ca_qry);
		if(mysql_error()){
			if(mysql_errno() == 1451){
				$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
			}else{
				die("Error: " . mysql_error());
			}
		}
	}
}

# Delete calculate physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_ce_id'])){
	if(!$rs_project_module7_row){
		$del_ce_id = mysql_real_escape_string($_POST['fld_del_ce_id']);
	
		$rs_del_ce_qry = sprintf("DELETE ce FROM tbl_project_calc_physical AS ce JOIN tbl_project AS p ON p.Project_id=ce.Project_id WHERE ce.Project_calc_physical_id='%s' AND p.User_id='%s'", $del_ce_id, $user_id);
		mysql_query($rs_del_ce_qry);
		if(mysql_error()){
			if(mysql_errno() == 1451){
				$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
			}else{
				die("Error: " . mysql_error());
			}
		}
	}
}

# Add/update salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_cs_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$salary_id = mysql_real_escape_string($_POST['fld_cs_add_id']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_cs_id']);
	$amount = str_replace(',', '.', $amount);
	
	if(empty($amount)){
		$amount = 0;
	}

	if($amount < 0){
		$error_message = "Bedrag kan niet negatief zijn";
	}

	$rs_update_check_qry = sprintf("SELECT * FROM tbl_project_calc_salary WHERE Project_calc_salary_id=%s", $salary_id);
	$rs_update_check_row = mysql_fetch_assoc(mysql_query($rs_update_check_qry));
	if($amount > $rs_update_check_row['Amount']){
		$error_message = "Bedrag kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Bedrag is hetzelfde als gecalculeerd";
	}
	if(!$error_message){	
		$rs_update_salary = sprintf("UPDATE tbl_project_calc_frth_salary SET Amount='%s' WHERE Project_calc_salary_id=%s", $amount, $salary_id);
		mysql_query($rs_update_salary) or die("Error: " . mysql_error());
	}
}

# Add/update sum
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_cu_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$sum_id = mysql_real_escape_string($_POST['fld_cu_add_id']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_cu_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if(empty($amount)){
		$amount = 0;
	}
	
	if(empty($price)){
		$price = 0;
	}
	if($amount < 0){
		$error_message = "Bedrag kan niet negatief zijn";
	}
	if($price < 0){
		$error_message = "Prijs kan niet negatief zijn";
	}
	$rs_update_check_qry = sprintf("SELECT * FROM tbl_project_calc_sum WHERE Project_calc_sum_id=%s", $sum_id);
	$rs_update_check_row = mysql_fetch_assoc(mysql_query($rs_update_check_qry));
	if($amount > $rs_update_check_row['Amount']){
		$error_message = "Bedrag kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Bedrag is hetzelfde als gecalculeerd";
	}
	if($price > $rs_update_check_row['Price']){
		$error_message = "Prijs kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Prijs is hetzelfde als gecalculeerd";
	}
	if(!$error_message){
		$rs_update_salary = sprintf("UPDATE tbl_project_calc_frth_sum SET Amount='%s',Price='%s' WHERE Project_calc_sum_id=%s", $amount, $price, $sum_id);
		mysql_query($rs_update_salary) or die("Error: " . mysql_error());
	}
}

# Add/update material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ca_id'])){
	$material_id = mysql_real_escape_string($_POST['fld_ca_add_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ca_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if(empty($amount)){
		$amount = 0;
	}
	
	if(empty($price)){
		$price = 0;
	}
	if($amount < 0){
		$error_message = "Bedrag kan niet negatief zijn";
	}
	if($price < 0){
		$error_message = "Prijs kan niet negatief zijn";
	}
	$rs_update_check_qry = sprintf("SELECT * FROM tbl_project_calc_material WHERE Project_calc_material_id=%s", $material_id);
	$rs_update_check_row = mysql_fetch_assoc(mysql_query($rs_update_check_qry));
	if($amount > $rs_update_check_row['Amount']){
		$error_message = "Bedrag kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Bedrag is hetzelfde als gecalculeerd";
	}
	if($price > $rs_update_check_row['Price']){
		$error_message = "Prijs kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Prijs is hetzelfde als gecalculeerd";
	}
	if(!$error_message){
		$rs_update_material = sprintf("UPDATE tbl_project_calc_frth_material SET Amount='%s',Price='%s' WHERE Project_calc_material_id=%s", $amount, $price, $material_id);
		mysql_query($rs_update_material) or die("Error: " . mysql_error());
	}
}

# Add/update physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ce_id'])){
	$physical_id = mysql_real_escape_string($_POST['fld_ce_add_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ce_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if(empty($amount)){
		$amount = 0;
	}
	
	if(empty($price)){
		$price = 0;
	}
	if($amount < 0){
		$error_message = "Bedrag kan niet negatief zijn";
	}
	if($price < 0){
		$error_message = "Prijs kan niet negatief zijn";
	}
	$rs_update_check_qry = sprintf("SELECT * FROM tbl_project_calc_physical WHERE Project_calc_physical_id=%s", $physical_id);
	$rs_update_check_row = mysql_fetch_assoc(mysql_query($rs_update_check_qry));
	if($amount > $rs_update_check_row['Amount']){
		$error_message = "Bedrag kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Bedrag is hetzelfde als gecalculeerd";
	}
	if($price > $rs_update_check_row['Price']){
		$error_message = "Prijs kan niet meer zijn dan gecalucleerd";
	}else if($amount == $rs_update_check_row['Amount']){
		$warn_message = "Prijs is hetzelfde als gecalculeerd";
	}
	if(!$error_message){
		$rs_update_material = sprintf("UPDATE tbl_project_calc_frth_physical SET Amount='%s',Price='%s' WHERE Project_calc_physical_id=%s", $amount, $price, $physical_id);
		mysql_query($rs_update_material) or die("Error: " . mysql_error());
	}
}

# Add total
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_total_less'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$amount = str_replace(',', '.', $amount);

	if($tax_id == 40){
		$rs_check_result_qry = sprintf("SELECT (IFNULL(Salary_1_notax_40,0)+IFNULL(Material_1_notax_40,0)+IFNULL(Physical_1_notax_40,0)+IFNULL(Sum_1_notax_40,0)) AS Total_1_notax FROM tvw_result_less WHERE Project_id=%s", $project_id);
		$rs_check_result_row = mysql_fetch_assoc(mysql_query($rs_check_result_qry));
	}else if($tax_id == 20){
		$rs_check_result_qry = sprintf("SELECT (IFNULL(Salary_1_notax_20,0)+IFNULL(Material_1_notax_20,0)+IFNULL(Physical_1_notax_20,0)+IFNULL(Sum_1_notax_20,0)) AS Total_1_notax FROM tvw_result_less WHERE Project_id=%s", $project_id);
		$rs_check_result_row = mysql_fetch_assoc(mysql_query($rs_check_result_qry));
	}else{
		$rs_check_result_qry = sprintf("SELECT (IFNULL(Salary_1_notax_10,0)+IFNULL(Material_1_notax_10,0)+IFNULL(Physical_1_notax_10,0)+IFNULL(Sum_1_notax_10,0)) AS Total_1_notax FROM tvw_result_less WHERE Project_id=%s", $project_id);
		$rs_check_result_row = mysql_fetch_assoc(mysql_query($rs_check_result_qry));
	}
	$rs_check_total_qry = sprintf("SELECT SUM(Amount) AS Total_less FROM tbl_less_total WHERE Project_id=%s AND Tax_id=%s", $project_id, $tax_id);
	$rs_check_total_row = mysql_fetch_assoc(mysql_query($rs_check_total_qry));
	$total_check = ($rs_check_result_row['Total_1_notax']-$rs_check_total_row['Total_less']);

	if($amount > 0){
		$error_message = "Bedrag kan niet positief zijn";
	}
	if($amount < $total_check){
		$error_message = "Het maximaal (nog) in mindering te brengen bedrag bedraagt ".number_format($total_check, 2, ',', '.');
	}
	if(!$error_message){
		$rs_total_less_qry = sprintf("INSERT INTO tbl_less_total (Create_date, Project_id, Invoice_id, Amount, Tax_id, `Comment`) VALUES (NOW(), '%s', '10', '%s', '%s', '%s')", $project_id, $amount, $tax_id, $comment);
		mysql_query($rs_total_less_qry) or die("Error: " . mysql_error());
	}
}

# Delete total
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_total_less_del'])){
	$total_less_id = mysql_real_escape_string($_POST['fld_total_less_del']);

	$rs_total_less_qry = sprintf("DELETE FROM tbl_less_total WHERE Less_total_id='%d'", $total_less_id);
	mysql_query($rs_total_less_qry) or die("Error: " . mysql_error());
}

# Submited user data
$ft_chapter_id = mysql_real_escape_string($_POST['slt_chapter']);

if($ft_chapter_id){
	$ft_chapter_qry = sprintf("AND c.Project_chapter_id='%s'", $ft_chapter_id);
}

# Select all chapters for this project
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project AS p JOIN tbl_project_chapter AS c ON c.Project_id=p.Project_id WHERE p.User_id='%s' AND c.Project_id='%s' %s ORDER BY Priority ASC", $user_id, $project_id, $ft_chapter_qry);
$rs_project_work_result = mysql_query($rs_project_work_qry);
$rs_project_work_num = mysql_num_rows($rs_project_work_result);

# All chapters for filter
$rs_filter_chapter_result = mysql_query(sprintf("SELECT c.* FROM tbl_project AS p JOIN tbl_project_chapter AS c ON c.Project_id=p.Project_id WHERE p.User_id='%s' AND c.Project_id='%s' ORDER BY Priority ASC", $user_id, $project_id));

# Project profit
$rs_profit_qry = sprintf("SELECT p.Hour_salary, p.1_Profit_material, p.1_Profit_physical, p.1_Profit_item FROM tbl_project_profit p JOIN tbl_project u ON u.Project_id=p.Project_id WHERE p.Project_id='%s' AND u.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_profit_row =  mysql_fetch_assoc(mysql_query($rs_profit_qry));

# Less total
$rs_less_total_qry = sprintf("SELECT lt.*, t.Tax FROM tbl_less_total AS lt JOIN tbl_tax AS t ON t.Tax_id=lt.Tax_id WHERE lt.Project_id='%s' AND Invoice_id=10 ORDER BY lt.Create_date DESC", $project_id);
$rs_less_total_rs = mysql_query($rs_less_total_qry);
$rs_less_total_num = mysql_num_rows($rs_less_total_rs);

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<?php if(!$hide_page){ ?>
<script type="text/javascript">
	var returnTo;

    function databaseWindow(returnForm){
        var w = window.open('/maintoolv2/material-mgr/','','width=800,height=600,scrollbars=yes,toolbar=no,location=no');
		returnTo = returnForm;
        w.focus();
    }
 
    function setDatabaseResult(RSType, RSUnit, RSPrice){
        returnTo.fld_type.value = RSType;
		returnTo.fld_unit.value = RSUnit;
		returnTo.fld_price.value = RSPrice;
        window.focus();
    }
	function checkToggle(repl, toggle){
		if ($(repl).css('display') == 'none'){
			$(repl).show("slow");
			$(toggle).text('[-]');
		}else{
			$(repl).hide("slow");
			$(toggle).text('[+]');
		}
	}
</script>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=139&amp;r_id=<?php echo $_GET['r_id']; ?>'" value="Aanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=140&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Onderaanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=143&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Uittrekstaat" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=145&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Eindresultaat" />
		</div>
		<span>Minderwerk Aanneming</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic">
		<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div><?php if(0){ if(!$rs_less_total_num){ ?>
					<table width="278">
						<tr>
							<td width="242"><strong>Vast bedrag in mindering brengen:</strong></td>
							<td width="24" align="left"><input type="checkbox" name="cb_total" id="cb_total" onChange="$('#tbl_sepcified').toggle('blind');$('#tbl_total').toggle('blind');"></td>
						</tr>
					</table>
					<?php } } ?>
					<?php if(!$rs_less_total_num){ ?>
				<div id="tbl_total" style="display:none">
				<?php }else{ ?>
				<div><br>
				<?php } ?>
					<table width="606">
						<tr>
							<td width="17" class="tbl-head">&nbsp;</td>
							<td width="144" class="tbl-head">Bedrag</td>
							<td width="64" class="tbl-head" align="center">BTW</td>
							<td width="340" class="tbl-head">Omschrijving voor op de factuur</td>
							<td width="17" class="tbl-head">&nbsp;</td>
						</tr>
						<?php
						while($rs_less_total_row = mysql_fetch_assoc($rs_less_total_rs)){
						?>
							<tr>
								<form action="" method="post" name="frm_total_less_del_<?php echo $rs_less_total_row['Less_total_id']; ?>">
									<input type="hidden" name="fld_total_less_del" id="fld_total_less_del" value="<?php echo $rs_less_total_row['Less_total_id']; ?>">
								</form>
								<!--<form action="" method="post" name="frm_total_less_<?php //echo $rs_less_total_row['Less_total_id']; ?>">-->
								<td>
									<!--<a href="javascript:void(0);" onclick="frm_total_less_<?php //echo $rs_less_total_row['Less_total_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a>-->
									<!--<input type="hidden" name="fld_total_less_chg" id="fld_total_less_chg" value="<?php //echo $rs_less_total_row['Less_total_id']; ?>">-->
								</td>
								<td align="right"><!--<input type="text" name="fld_amount" id="fld_amount" value="<?php //echo number_format($rs_less_total_row['Amount'], 2, ',', '.'); ?>">--><?php echo "&euro;&nbsp;".number_format($rs_less_total_row['Amount'], 2, ',', '.'); ?></td>
								<td align="center">
									<!--<select name="slt_tax" id="slt_tax">
										<option <?php //if($rs_less_total_row['Tax_id'] == 10){ echo "selected"; } ?> value="10">0%</option>
										<option <?php //if($rs_less_total_row['Tax_id'] == 20){ echo "selected"; } ?> value="20">6%</option>
										<option <?php //if($rs_less_total_row['Tax_id'] == 40){ echo "selected"; } ?> value="40">21%</option>
									</select>-->
									 <?php echo $rs_less_total_row['Tax']."%"; ?>
								</td>
								<td><!--<input type="text" style="width:99%" name="fld_comment" id="fld_comment" value="<?php //echo $rs_less_total_row['Comment']; ?>">--><?php echo $rs_less_total_row['Comment']; ?></td>
								<td><a href="javascript:void(0);" onclick="document.frm_total_less_del_<?php echo $rs_less_total_row['Less_total_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
								<!--</form>-->
							</tr>
						<?php } ?>
						<form action="" method="post" name="frm_totall_less_add">
						<input type="hidden" id="fld_total_less" name="fld_total_less" value="1" />
						<tr>
							<td><a href="javascript:void(0);" onclick="document.frm_totall_less_add.submit()"><img src="../../images/add.png" width="16" height="16" title="Opslaan" /></a></td>
							<td><input type="text" name="fld_amount" id="fld_amount"></td>
							<td><select name="slt_tax" id="slt_tax">
								<option value="10">0%</option>
								<option value="20">6%</option>
								<option value="40">21%</option>
							</select></td>
							<td><input type="text" style="width:99%" name="fld_comment" id="fld_comment"></td>
							<td>&nbsp;</td>
						</tr>
						</form>
					</table>
				</div>
				<?php if(!$rs_less_total_num){ ?>
				<div id="tbl_sepcified">
					<table>
						<tr>
							<td class="tbl-head">Filter hoofdstuk</td>
							<td class="tbl-head">
								<form action="" method="post" name="frm_ft_chapter">
									<select onchange="document.frm_ft_chapter.submit()" name="slt_chapter" id="slt_chapter">
										<option value="">Geen</option>
										<?php while($rs_filter_chapter_row = mysql_fetch_assoc($rs_filter_chapter_result)){
											if($ft_chapter_id == $rs_filter_chapter_row['Project_chapter_id']){
												echo '<option selected="selected" value="'.$rs_project_status_row['Project_status_id'].'">'.$rs_filter_chapter_row['Chapter'].'</option>';
											}else{
												echo '<option value="'.$rs_filter_chapter_row['Project_chapter_id'].'">'.$rs_filter_chapter_row['Chapter'].'</option>';
											}
										} ?>
									</select>
								</form>
							</td>
						</tr>
					</table>
					<?php if($rs_project_work_num){ ?>
					<?php $i=0; while($rs_project_work_row = mysql_fetch_assoc($rs_project_work_result)){ $i++;
						# Select all operations for this project
						$rs_project_work_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=10 ORDER BY Priority ASC", $rs_project_work_row['Project_chapter_id']);
						$rs_project_work_op_result = mysql_query($rs_project_work_op_qry);
						$rs_project_work_op_num = mysql_num_rows($rs_project_work_op_result);
					?>
					<table id="tbl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" width="100%" border="0">
						<tr class="tbl-head">
							<td width="19">&nbsp;</td>
							<td width="16">
							</td>
							<td colspan="9">
								<?php echo $rs_project_work_row['Chapter']; ?>
							</td>
							<td width="18"></td>
						</tr>
						<tr class="tbl-subhead">
							<td width="19">&nbsp;</td>
							<td width="16">&nbsp;</td>
							<td colspan="3">Uit te voeren werkzaamheden</td>
							<td colspan="6">Omschrijving werkzaamheden voor op de factuur</td>
							<td width="18">&nbsp;</td>
						</tr>
						<?php if($rs_project_work_op_num){ ?>
						<?php while($rs_project_work_op_row = mysql_fetch_assoc($rs_project_work_op_result)){
							$rs_calc_salary_qry = sprintf("SELECT cs.Project_calc_salary_id, cs.Amount, cs.Tax_id, t.Tax FROM tbl_project_calc_salary AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id JOIN tbl_tax AS t ON t.Tax_id=cs.Tax_id WHERE cs.Project_id='%s' AND cs.Invoice_id='10' AND cs.Operation_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_salary_result = mysql_query($rs_calc_salary_qry) or die("Error: " . mysql_error());
							$rs_calc_salary_row = mysql_fetch_assoc($rs_calc_salary_result);

							$rs_calc_salary2_qry = sprintf("SELECT cs.Project_calc_salary_id, cs.Amount, cs.Tax_id, t.Tax FROM tbl_project_calc_frth_salary AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id JOIN tbl_tax AS t ON t.Tax_id=cs.Tax_id WHERE cs.Project_id='%s' AND cs.Invoice_id='10' AND cs.Operation_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_salary2_result = mysql_query($rs_calc_salary2_qry) or die("Error: " . mysql_error());
							$rs_calc_salary2_row = mysql_fetch_assoc($rs_calc_salary2_result);
							
							$rs_calc_material_qry = sprintf("SELECT cm.Project_calc_material_id, cm.Materialtype, cm.Unit, cm.Price, cm.Amount, t.Tax FROM tbl_project_calc_material AS cm JOIN tbl_tax AS t ON t.Tax_id=cm.Tax_id JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE cm.Project_id='%s' AND cm.Invoice_id='10' AND cm.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_material_result = mysql_query($rs_calc_material_qry) or die("Error: " . mysql_error());
							$rs_calc_material_num = mysql_num_rows($rs_calc_material_result);

							$rs_calc_physical_qry = sprintf("SELECT cp.Project_calc_physical_id, cp.Materialtype, cp.Unit, cp.Price, cp.Amount, t.Tax FROM tbl_project_calc_physical AS cp JOIN tbl_tax AS t ON t.Tax_id=cp.Tax_id JOIN tbl_project AS p ON p.Project_id=cp.Project_id WHERE cp.Project_id='%s' AND cp.Invoice_id='10' AND cp.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_physical_result = mysql_query($rs_calc_physical_qry) or die("Error: " . mysql_error());
							$rs_calc_physical_num = mysql_num_rows($rs_calc_physical_result);
							
							$rs_calc_sum_qry = sprintf("SELECT cs.Project_calc_sum_id, cs.Unit, cs.Price, cs.Amount, cs.Tax_id, t.Tax FROM tbl_project_calc_sum AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id JOIN tbl_tax AS t ON t.Tax_id=cs.Tax_id WHERE cs.Project_id='%s' AND cs.Invoice_id='10' AND cs.Operation_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_sum_result = mysql_query($rs_calc_sum_qry) or die("Error: " . mysql_error());
							$rs_calc_sum_row = mysql_fetch_assoc($rs_calc_sum_result);
							
							$rs_calc_sum2_qry = sprintf("SELECT cs.Project_calc_sum_id, cs.Unit, cs.Price, cs.Amount, cs.Tax_id, t.Tax FROM tbl_project_calc_frth_sum AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id JOIN tbl_tax AS t ON t.Tax_id=cs.Tax_id WHERE cs.Project_id='%s' AND cs.Invoice_id='10' AND cs.Operation_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_sum2_result = mysql_query($rs_calc_sum2_qry) or die("Error: " . mysql_error());
							$rs_calc_sum2_row = mysql_fetch_assoc($rs_calc_sum2_result);
							
							$rs_total_qry = sprintf("SELECT * FROM tvw_total_mod_2 WHERE Project_id='%s' AND Project_operation_id='%s' AND User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_total_result = mysql_query($rs_total_qry) or die("Error: " . mysql_error());
							$rs_total_row = mysql_fetch_assoc($rs_total_result);
							$total1 = 0;
							$_total1 = 0;
							$total2 = 0;
							$_total2 = 0;
						?>
						<tr class="tbl-operation">
							<td width="19"><a href="javascript:void(0);" onClick="checkToggle('.t_total_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a></td>
							<td width="16">
							</td>
							<form name="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
								<td colspan="3">
								<?php echo $rs_project_work_op_row['Operation']; ?>
								</td>
								<td colspan="6">
								<?php echo $rs_project_work_op_row['Description']; ?>
								</td>
							</form>
							<td width="16">
							</td>
						</tr>
						<tr style="display:" class="t_total_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
							<td colspan="2"></td>
							<td colspan="10">
								<table width="100%">
								<?php if(($rs_calc_salary_row || $rs_calc_material_num || $rs_calc_physical_num) || !$rs_calc_sum_row){ ?>
									<tr>
										<td width="8%"><a href="javascript:void(0);" onClick="checkToggle('.t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Arbeid</b></td>
										<td width="4%">&nbsp;</td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Tarief</b></td>
										<td width="6%" bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td width="28%" bgcolor="#CCCCCC" align="center"><b>Nieuwe uren</b></td>
										<td width="12%" bgcolor="#CCCCCC" align="center"><b>Gecalculeerd</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Minderwerk</b></td>
										<td width="8%">&nbsp;</td>
										<td width="2%">&nbsp;</td>
									</tr>
									<form name="frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td width="7%">
											<input type="hidden" name="fld_cs_add_id" id="fld_cs_add_id" value="<?php echo $rs_calc_salary_row['Project_calc_salary_id']; ?>" />
											<input type="hidden" name="fld_op_cs_id" id="fld_op_cs_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
										</td>
										<td width="7%">
											<a href="javascript:void(0);" onclick="document.frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a>
										</td>
										<td width="16%" align="center" style="color: #999999">&euro;&nbsp;<?php echo number_format($rs_profit_row['Hour_salary'], 2, ',', '.'); ?></td>
										<td width="6%" align="center" style="color: #999999">per uur</td>
										<td width="28%" align="right"><input style="width:99%; text-align:center" type="text" name="fld_amount" id="fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_salary_row['Project_calc_salary_id']; ?>" value="<?php echo str_replace('.', ',', $rs_calc_salary2_row['Amount']); ?>"></td>
										<td width="12%" align="center" style="color: #999999">&euro;&nbsp;<?php echo number_format($rs_profit_row['Hour_salary']*$rs_calc_salary_row['Amount'], 2, ',', '.'); ?></td>
										<td width="16%" align="center" style="color:#F00">&euro;&nbsp;-<?php echo number_format(($rs_profit_row['Hour_salary']*$rs_calc_salary_row['Amount'])-($rs_profit_row['Hour_salary']*$rs_calc_salary2_row['Amount']), 2, ',', '.');?></td>
										<td width="8%" align="center">&nbsp;</td>
										<td width="2%"><a href="javascript:void(0);" onClick="$('#fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_salary_row['Project_calc_salary_id']; ?>').val(<?php echo $rs_calc_salary_row['Amount']; ?>);$('#frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>').submit();"><img src="../../images/change.png" width="16" height="16" alt="Reset" title="Reset" /></a></td>
									</tr>
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="9">&nbsp;</td>
									</tr>
									</form>
									<tr>
										<td width="8%"><a href="javascript:void(0);" onClick="checkToggle('.t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Materiaal</b></td>
										<td width="4%">&nbsp;</td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Materiaalsoort</b></td>
										<td width="6%" bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td width="28%" bgcolor="#CCCCCC" align="center"><b>Prijs / eenheid</b></td>
										<td width="12%" bgcolor="#CCCCCC" align="center"><b>Nieuwe hoeveelheid</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Gecalculeerd incl. winst</b></td>
										<td width="8%" bgcolor="#CCCCCC" align="center"><b>Minderwerk</b></td>
										<td width="2%">&nbsp;</td>
									</tr>
									<?php
									while($rs_calc_material_row = mysql_fetch_assoc($rs_calc_material_result)){
										$rs_calc_material2_qry = sprintf("SELECT cm.Project_calc_material_id, cm.Materialtype, cm.Unit, cm.Price, cm.Amount, t.Tax FROM tbl_project_calc_frth_material AS cm JOIN tbl_tax AS t ON t.Tax_id=cm.Tax_id JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE cm.Project_calc_material_id='%s' ORDER BY Priority DESC", $rs_calc_material_row['Project_calc_material_id']);
										$rs_calc_material2_result = mysql_query($rs_calc_material2_qry) or die("Error: " . mysql_error());
										$rs_calc_material2_row = mysql_fetch_assoc($rs_calc_material2_result);
									?>
									<form name="frm_ca_add_<?php echo $rs_calc_material2_row['Project_calc_material_id']; ?>" id="frm_ca_add_<?php echo $rs_calc_material2_row['Project_calc_material_id']; ?>" action="" method="post">
									<tr style="display:" class="t_material_<?php echo $rs_calc_material2_row['Project_calc_material_id']; ?>">
										<td width="7%">
											<input type="hidden" name="fld_ca_add_id" id="fld_ca_add_id" value="<?php echo $rs_calc_material_row['Project_calc_material_id']; ?>" />
											<input type="hidden" name="fld_op_ca_id" id="fld_op_ca_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
										</td>
										<td width="7%">
											<a href="javascript:void(0);" onclick="document.frm_ca_add_<?php echo $rs_calc_material2_row['Project_calc_material_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a>
										</td>
										<td width="16%" style="color: #999999"><?php echo $rs_calc_material_row['Materialtype']; ?></td>
										<td width="6%" align="center" style="color: #999999"><?php echo $rs_calc_material_row['Unit']; ?></td>
										<td width="28%" align="right"><input type="text" style="text-align:center" name="fld_price" id="fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_material_row['Project_calc_material_id']; ?>" value="<?php echo str_replace('.', ',', $rs_calc_material2_row['Price']); ?>"></td>
										<td width="12%" align="right"><input type="text" style="text-align:center" name="fld_amount" id="fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_material_row['Project_calc_material_id']; ?>" value="<?php echo str_replace('.', ',', $rs_calc_material2_row['Amount']); ?>"></td>
										<td width="16%" align="right" style="color: #999999">&euro;&nbsp;<?php echo number_format($rs_calc_material_row['Amount']*($rs_calc_material_row['Price']+(($rs_calc_material_row['Price']/100)*$rs_profit_row['1_Profit_material'])), 2, ',', '.'); ?></td>
										<td width="8%" align="center" style="color:#F00">&euro;&nbsp;-
										<?php
										if($rs_calc_material_row['Price'] == $rs_calc_material2_row['Price']){
											$_total1 = ($rs_calc_material_row['Amount']*($rs_calc_material_row['Price']+(($rs_calc_material_row['Price']/100)*$rs_profit_row['1_Profit_material'])))-($rs_calc_material2_row['Price'] + (($rs_calc_material2_row['Price']/100)*$rs_profit_row['1_Profit_material'])) * $rs_calc_material2_row['Amount'];
											echo number_format($_total1, 2, ',', '.');
											$total1 += $_total1;
										}else{
											$_total1 = ($rs_calc_material_row['Amount']*($rs_calc_material_row['Price']+(($rs_calc_material_row['Price']/100)*$rs_profit_row['1_Profit_material'])))-$rs_calc_material2_row['Price'] * $rs_calc_material2_row['Amount'];
											echo number_format($_total1, 2, ',', '.');
											$total1 += $_total1;
										}
										?></td>
										<td width="2%">
											<a href="javascript:void(0);" onClick="$('#fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_material_row['Project_calc_material_id']; ?>').val(<?php echo $rs_calc_material_row['Price']; ?>);$('#fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_material_row['Project_calc_material_id']; ?>').val(<?php echo $rs_calc_material_row['Amount']; ?>);$('#frm_ca_add_<?php echo $rs_calc_material2_row['Project_calc_material_id']; ?>').submit();"><img src="../../images/change.png" width="16" height="16" alt="Reset" title="Reset" /></a>
										</td>
									</tr>
									</form>
									<?php } ?>
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right" colspan="2"><font color="#313131"><b><i>TOTALEN</i></b></font></td>
										<td width="16%">&nbsp;</td>
										<td width="6%">&nbsp;</td>
										<td width="28%">&nbsp;</td>
										<td width="12%" align="right"><font color="#313131"><b><i>&nbsp;</i></b></font></td>
										<td width="16%" align="right">&nbsp;</td>
										<td width="8%" align="center"><font color="#F00"><b><i>&euro;&nbsp;-&nbsp;<?php echo number_format($total1, 2, ',', '.'); ?></i></b></font></td>
										<td width="2%">&nbsp;</td>
									</tr>
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="9">&nbsp;</td>
									</tr>
									<tr>
										<td width="8%"><a href="javascript:void(0);" onClick="checkToggle('.t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Materieel</b></td>
										<td width="4%">&nbsp;</td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Materieelsoort</b></td>
										<td width="6%" bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td width="28%" bgcolor="#CCCCCC" align="center"><b>Prijs / eenheid</b></td>
										<td width="12%" bgcolor="#CCCCCC" align="center"><b>Nieuwe hoeveelheid</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Gecalculeerd incl. winst</b></td>
										<td width="8%" bgcolor="#CCCCCC" align="center"><b>Minderwerk</b></td>
										<td width="2%">&nbsp;</td>
									</tr>
									<?php
									while($rs_calc_physical_row = mysql_fetch_assoc($rs_calc_physical_result)){
										$rs_calc_physical2_qry = sprintf("SELECT cp.Project_calc_physical_id, cp.Materialtype, cp.Unit, cp.Price, cp.Amount, t.Tax FROM tbl_project_calc_frth_physical AS cp JOIN tbl_tax AS t ON t.Tax_id=cp.Tax_id JOIN tbl_project AS p ON p.Project_id=cp.Project_id WHERE cp.Project_calc_physical_id='%s' ORDER BY Priority DESC", $rs_calc_physical_row['Project_calc_physical_id']);
										$rs_calc_physical2_result = mysql_query($rs_calc_physical2_qry) or die("Error: " . mysql_error());
										$rs_calc_physical2_row = mysql_fetch_assoc($rs_calc_physical2_result);
									?>
									<form name="frm_ce_add_<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>" id="frm_ce_add_<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>" action="" method="post">
									<tr style="display:" class="t_physical_<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>">
										<td width="7%">
											<input type="hidden" name="fld_ce_add_id" id="fld_ce_add_id" value="<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>" />
											<input type="hidden" name="fld_op_ce_id" id="fld_op_ce_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
										</td>
										<td width="7%">
											<a href="javascript:void(0);" onclick="document.frm_ce_add_<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a>
										</td>
										<td width="16%" style="color: #999999"><?php echo $rs_calc_physical_row['Materialtype']; ?></td>
										<td width="6%" align="center" style="color: #999999"><?php echo $rs_calc_physical_row['Unit']; ?></td>
										<td width="28%" align="right"><input type="text" style="text-align:center" name="fld_price" id="fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_physical_row['Project_calc_physical_id']; ?>" value="<?php echo str_replace('.', ',', $rs_calc_physical2_row['Price']); ?>"></td>
										<td width="12%" align="right"><input type="text" style="text-align:center" name="fld_amount" id="fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_physical_row['Project_calc_physical_id']; ?>" value="<?php echo str_replace('.', ',', $rs_calc_physical2_row['Amount']); ?>"></td>
										<td width="16%" align="right" style="color: #999999">&euro;&nbsp;<?php echo number_format($rs_calc_physical_row['Amount']*($rs_calc_physical_row['Price']+(($rs_calc_physical_row['Price']/100)*$rs_profit_row['1_Profit_physical'])), 2, ',', '.'); ?></td>
										<td width="8%" align="center" style="color:#F00">&euro;&nbsp;-
										<?php
										if($rs_calc_physical_row['Price'] == $rs_calc_physical2_row['Price']){
											$_total2 = ($rs_calc_physical_row['Amount']*($rs_calc_physical_row['Price']+(($rs_calc_physical_row['Price']/100)*$rs_profit_row['1_Profit_physical'])))-($rs_calc_physical2_row['Price'] + (($rs_calc_physical2_row['Price']/100)*$rs_profit_row['1_Profit_physical'])) * $rs_calc_physical2_row['Amount'];
											echo number_format($_total2, 2, ',', '.');
											$total2 += $_total2;
										}else{
											$_total2 = ($rs_calc_physical_row['Amount']*($rs_calc_physical_row['Price']+(($rs_calc_physical_row['Price']/100)*$rs_profit_row['1_Profit_physical'])))-$rs_calc_physical2_row['Price'] * $rs_calc_physical2_row['Amount'];
											echo number_format($_total2, 2, ',', '.');
											$total2 += $_total2;
										} ?>
										</td>
										<td width="2%">
											<a href="javascript:void(0);" onClick="$('#fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_physical_row['Project_calc_physical_id']; ?>').val(<?php echo $rs_calc_physical_row['Price']; ?>);$('#fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_physical_row['Project_calc_physical_id']; ?>').val(<?php echo $rs_calc_physical_row['Amount']; ?>);$('#frm_ce_add_<?php echo $rs_calc_physical_row['Project_calc_physical_id']; ?>').submit();"><img src="../../images/change.png" width="16" height="16" alt="Reset" title="Reset" /></a>
										</td>
									</tr>
									</form>
									<?php } ?>
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right" colspan="2"><font color="#313131"><b><i>TOTALEN</i></b></font></td>
										<td width="16%">&nbsp;</td>
										<td width="6%">&nbsp;</td>
										<td width="28%">&nbsp;</td>
										<td width="12%" align="right">&nbsp;</td>
										<td width="16%" align="right"><font color="#313131"><b><i>&nbsp;</i></b></font></td>
										<td width="8%" align="center"><font color="#F00"><b><i>&euro;&nbsp;-&nbsp;<?php echo number_format($total2, 2, ',', '.'); ?></i></b></font></td>
										<td width="2%">&nbsp;</td>
									</tr>
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="9">&nbsp;</td>
									</tr>
									<?php } ?>
									<?php if($rs_calc_sum_row || (!$rs_calc_salary_row && !$rs_calc_material_num && !$rs_calc_physical_num && !$rs_calc_sum_row)){ ?>
									<tr>
										<td width="8%"><a href="javascript:void(0);" onClick="checkToggle('.t_sum_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Totaalpost</b></td>
										<td width="4%">&nbsp;</td>
										<td width="16%">&nbsp;</td>
										<td width="6%" bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td width="28%" bgcolor="#CCCCCC" align="center"><b>Postprijs incl. winst</b></td>
										<td width="12%" bgcolor="#CCCCCC" align="center"><b>Nieuwe hoeveelheid</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Gecalculeerd incl. winst</b></td>
										<td width="8%" bgcolor="#CCCCCC" align="center"><b>Minderwerk</b></td>
										<td width="2%">&nbsp;</td>
									</tr>
									<form name="frm_cu_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_cu_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
									<tr style="display:" class="t_sum_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td width="8%">
											<input type="hidden" name="fld_cu_add_id" id="fld_cu_add_id" value="<?php echo $rs_calc_sum_row['Project_calc_sum_id']; ?>" />
											<input type="hidden" name="fld_op_cu_id" id="fld_op_cu_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
										</td>
										<td width="4%">
											<a href="#" onClick="document.frm_cu_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a>
										</td>
										<td width="16%">&nbsp;</td>
										<td width="6%" style="color: #999999"><?php echo $rs_calc_sum_row['Unit']; ?></td>
										<td width="28%" align="center"><input name="fld_price" id="fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_sum_row['Project_calc_sum_id']; ?>" style="width:99%;text-align:center" type="text" value="<?php echo str_replace('.', ',', $rs_calc_sum2_row['Price']); ?>" /></td>
										<td width="12%" align="right"><input name="fld_amount" id="fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_sum_row['Project_calc_sum_id']; ?>" style="width:99%;text-align:center" type="text" value="<?php echo str_replace('.', ',', $rs_calc_sum2_row['Amount']); ?>" /></td>
										<td width="16%" align="right" style="color: #999999">&euro;&nbsp;<?php echo number_format($rs_calc_sum_row['Amount']*($rs_calc_sum_row['Price']+(($rs_calc_sum_row['Price']/100)*$rs_profit_row['1_Profit_item'])), 2, ',', '.'); ?></td>
										<td width="8%" align="center" style="color:#F00">&euro;&nbsp;-
										<?php
										if($rs_calc_sum_row['Price'] == $rs_calc_sum2_row['Price']){
											echo number_format(($rs_calc_sum_row['Amount']*($rs_calc_sum_row['Price']+(($rs_calc_sum_row['Price']/100)*$rs_profit_row['1_Profit_item'])))-($rs_calc_sum2_row['Price'] + (($rs_calc_sum2_row['Price']/100)*$rs_profit_row['1_Profit_item'])) * $rs_calc_sum2_row['Amount'], 2, ',', '.');
										}else{
											echo number_format(($rs_calc_sum_row['Amount']*($rs_calc_sum_row['Price']+(($rs_calc_sum_row['Price']/100)*$rs_profit_row['1_Profit_item'])))-$rs_calc_sum2_row['Price'] * $rs_calc_sum2_row['Amount'], 2, ',', '.');
										} ?>
										</td>
										<td width="2%"><a href="javascript:void(0);" onClick="$('#fld_price_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_sum_row['Project_calc_sum_id']; ?>').val(<?php echo $rs_calc_sum_row['Price']; ?>);$('#fld_amount_<?php echo $rs_project_work_op_row['Project_operation_id'].$rs_calc_sum_row['Project_calc_sum_id']; ?>').val(<?php echo $rs_calc_sum_row['Amount']; ?>);$('#frm_cu_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>').submit();"><img src="../../images/change.png" width="16" height="16" alt="Reset" title="Reset" /></a></td>
									</tr>
									</form>
									<tr style="display:" class="t_sum_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="9">&nbsp;</td>
									</tr>
									<?php } ?>
								</table>
							</td>
						</tr>
						<?php } ?>
						<?php } ?>
					</table>
					<?php } ?>
	<br />
					<?php } ?>
					<?php }?>
</div>
		<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
<?php } ?>
<?php /*
mysql_free_result($rs_project_chap2_result);
mysql_free_result($rs_project_chap_result);
mysql_free_result($rs_project_relations_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_invoice2_result);
mysql_free_result($rs_invoice_result);
mysql_free_result($rs_invoices_total_result);
mysql_free_result($rs_invoices_result); */
?>