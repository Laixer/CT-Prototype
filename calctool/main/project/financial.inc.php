<?php
/**
 * Project details
 * - Markup correction
 * - Code Safety
 *	 - Escape
 *	 - User based selection
 * - Freeing results
 * - Error handling
 */

# Submited user data
$project_id = mysql_real_escape_string($_GET['r_id']);

# All relations query
$rs_project_detail_qry = sprintf("SELECT p.*, r.Company_name, t.Type, s.State, x.Tax AS Tax_salary, y.Tax AS Tax_other FROM tbl_project AS p JOIN tbl_relation AS r ON r.Relation_id=p.Client_relation_id JOIN tbl_project_type AS t ON t.Project_type_id=p.Project_type_id JOIN tbl_state AS s ON s.State_id=p.State_id JOIN tbl_tax AS x ON x.Tax_id=p.Tax_salary_id JOIN tbl_tax AS y ON y.Tax_id=p.Tax_other_id WHERE p.Project_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_project_detail_result = mysql_query($rs_project_detail_qry) or die("Error: " . mysql_error());
$rs_project_detail_row = mysql_fetch_assoc($rs_project_detail_result);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_offer_date'])){
	$date = (strtotime(mysql_real_escape_string($_POST['fld_offer_date'])) + 3600);
	
	$rs_project_module_check_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7 LIMIT 1", $rs_project_detail_row['Project_id']);
	$rs_project_module_check_result = mysql_query($rs_project_module_check_qry) or die("Error: " . mysql_error());
	$rs_project_module_check_row = mysql_fetch_assoc($rs_project_module_check_result);
	$rs_project_module_check_num = mysql_num_rows($rs_project_module_check_result);
	if($rs_project_module_check_num){
		$rs_update_mod = sprintf("UPDATE tbl_project_module SET Module_timestamp_date=NOW() WHERE Project_module_id='%s'", $rs_project_module_check_row['Project_module_id']);
		mysql_query($rs_update_mod) or die("Error: " . mysql_error());
	}else{
		$rs_insert_mod = sprintf("INSERT INTO tbl_project_module (Project_id, Module_id, Module_start_date) VALUES ('%s', '7', FROM_UNIXTIME('%s'))", $rs_project_detail_row['Project_id'], $date);
		mysql_query($rs_insert_mod) or die("Error: " . mysql_error());
	}

	# copy over calc less
	$rs_cpy_salary_less = sprintf("INSERT INTO tbl_project_calc_frth_salary SELECT * FROM tbl_project_calc_salary WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30)", $rs_project_detail_row['Project_id']);
	$rs_cpy_material_less = sprintf("INSERT INTO tbl_project_calc_frth_material SELECT * FROM tbl_project_calc_material WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30)", $rs_project_detail_row['Project_id']);
	$rs_cpy_physical_less = sprintf("INSERT INTO tbl_project_calc_frth_physical SELECT * FROM tbl_project_calc_physical WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30)", $rs_project_detail_row['Project_id']);
	$rs_cpy_sum_less = sprintf("INSERT INTO tbl_project_calc_frth_sum SELECT * FROM tbl_project_calc_sum WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30)", $rs_project_detail_row['Project_id']);
	mysql_query($rs_cpy_salary_less) or die("Error: " . mysql_error());
	mysql_query($rs_cpy_material_less) or die("Error: " . mysql_error());
	mysql_query($rs_cpy_physical_less) or die("Error: " . mysql_error());
	mysql_query($rs_cpy_sum_less) or die("Error: " . mysql_error());

	# copy over calc post
	$rs_cpy_material_post = sprintf("INSERT INTO tbl_project_calc_sec_material (Create_date,Timestamp_date,Project_id,Invoice_id,Operation_id,Tax_id,Material_id,Materialtype,Unit,Price,Amount,DB_chain,Priority) SELECT u.Create_date,u.Timestamp_date,u.Project_id,u.Invoice_id,u.Operation_id,u.Tax_id,u.Project_calc_material_id,u.Materialtype,u.Unit,u.Price,u.Amount,u.DB_chain,u.Priority FROM tbl_project_calc_material u WHERE Project_id='%s' AND Invoice_id=40", $rs_project_detail_row['Project_id']);
	$rs_cpy_physical_post = sprintf("INSERT INTO tbl_project_calc_sec_physical (Create_date,Timestamp_date,Project_id,Invoice_id,Operation_id,Tax_id,Physical_id,Materialtype,Unit,Price,Amount,DB_chain,Priority) SELECT u.Create_date,u.Timestamp_date,u.Project_id,u.Invoice_id,u.Operation_id,u.Tax_id,u.Project_calc_physical_id,u.Materialtype,u.Unit,u.Price,u.Amount,u.DB_chain,u.Priority FROM tbl_project_calc_physical u WHERE Project_id='%s' AND Invoice_id=40", $rs_project_detail_row['Project_id']);
	mysql_query($rs_cpy_material_post) or die("Error: " . mysql_error());
	mysql_query($rs_cpy_physical_post) or die("Error: " . mysql_error());
}

# All project module query
$rs_project_module1_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=1", $rs_project_detail_row['Project_id']);
$rs_project_module1_result = mysql_query($rs_project_module1_qry) or die("Error: " . mysql_error());
$rs_project_module1_row = mysql_fetch_assoc($rs_project_module1_result);
$rs_project_module2_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=2", $rs_project_detail_row['Project_id']);
$rs_project_module2_result = mysql_query($rs_project_module2_qry) or die("Error: " . mysql_error());
$rs_project_module2_row = mysql_fetch_assoc($rs_project_module2_result);
$rs_project_module6_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=6", $rs_project_detail_row['Project_id']);
$rs_project_module6_result = mysql_query($rs_project_module6_qry) or die("Error: " . mysql_error());
$rs_project_module6_row = mysql_fetch_assoc($rs_project_module6_result);
$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $rs_project_detail_row['Project_id']);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

# No projects have been found
if(!$rs_project_detail_row){
	$error_message = "Er is geen project gevonden";
	$hide_page = 1;
}

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<?php if(!$hide_page){ ?>
<script>
$(function(){
	$("#fld_offer_date").datepicker({ dateFormat: "dd-mm-yy", dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ], monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ] });
	$("#fld_close_date").datepicker({ dateFormat: "dd-mm-yy", dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ], monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ] });
});
</script>
<div id="page-bgtop">
	<div id="title">
		<span>Financieel</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
	<div id="content-left">
		<div id="intern">
			<div class="details-head">Projectfinanciële acties</div>
			<div class="details">&nbsp;</div>
			<div class="details">
				<div class="details-container">
					<input style="width:180px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=131&r_id=<?php echo $rs_project_detail_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" id="btn_submit" type="button" value="Offerte beheer" />
				</div>
			</div>
			<?php if($rs_project_module7_row){ ?>
			<div class="details">
				<div class="details-container">
					<input style="width:180px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" id="btn_submit" type="button" value="Termijnfactuur" />
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<input style="width:180px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" id="btn_submit" type="button" value="Eindfactuur" />
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<input style="width:180px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" id="btn_submit" type="button" value="Overzicht te factureren" />
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<input style="width:180px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=147&r_id=<?php echo $rs_project_detail_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" id="btn_submit" type="button" value="Begroot vs Besteed" />
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<style>
	#content-left-thrd {
		float: right;
		padding-top: 0px;
	}
	</style>
	<div id="content-left-sec">
		<div id="intern">
			<div class="details-head">Projectstatus</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left"><b>Offerte stadium</b></div>
					<div class="details-ctr-right"><b>Startdatum</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Calculatie</div>
					<div class="details-ctr-right"><?php echo $rs_project_module1_row['Module_start_date']; ?></div>
				</div>
			</div>
			<?php if($rs_project_detail_row['Project_type_id'] != 10) { ?>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Offerte verzonden</div>
					<div class="details-ctr-right"><?php echo $rs_project_module6_row['Module_start_date']; ?></div>
				</div>
			</div>
			<form action="" name="frm_offer_date" id="frm_offer_date" method="post">
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Opdracht ontvangen</div>
					<div style="float:right;">
						<?php if($rs_project_module7_row){ echo $rs_project_module7_row['Module_start_date']; }else{ ?>
						<input type="text" name="fld_offer_date" id="fld_offer_date" />
						<?php } ?>
					</div>
				</div>
			</div>
			</form>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">&nbsp;</div>
					<div style="float:right;">&nbsp;</div>
				</div>
			</div>
			<?php if($rs_project_module7_row){ ?>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">&nbsp;</div>
					<div class="details-ctr-right">&nbsp;</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left"><b>Opdracht stadium</b></div>
					<div class="details-ctr-right"><b>Startdatum</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Uitvoering</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Stelposten</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Minderwerk</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Meerwerk</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">&nbsp;</div>
					<div class="details-ctr-right">&nbsp;</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left"><b>Financiele handelingen</b></div>
					<div class="details-ctr-right"><b>Startdatum</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Termijnfactuur 1</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Eindfactuur</div>
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">&nbsp;</div>
					<div class="details-ctr-right">&nbsp;</div>
				</div>
			</div>
			<form action="" name="frm_close_date" id="frm_close_date" method="post">
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left"><b>Project gesloten</b></div>
					<div style="float:right;"><input name="fld_close_date" id="fld_close_date" type="text" /></div>
				</div>
			</div>
			</form>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">&nbsp;</div>
					<div style="float:right;">&nbsp;</div>
				</div>
			</div>
			<?php } } ?>
		</div>
	</div>
	<div id="content-left-thrd">
		<div id="intern">
			<div class="details-head">&nbsp;</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><b>Laatste wijziging</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo $rs_project_module1_row['Module_timestamp_date']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo $rs_project_module6_row['Module_timestamp_date']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">-</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">&nbsp;</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">
					<?php if(!$rs_project_module7_row){ ?>
					<input type="button" value="Opslaan" name="btn_offer_submit" id="btn_offer_submit" onClick="document.frm_offer_date.submit();" />
					<?php } ?>
					</div>
				</div>
			</div>
			<?php if($rs_project_module7_row){ ?>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><b>Laatste wijziging</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">&nbsp;</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-right"><b>Laatste wijziging</b></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;"><?php echo date("d-m-Y H:i"); ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">&nbsp;</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">-</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div style="float:right;">
					<input type="button" value="Opslaan" name="btn_close_submit" id="btn_close_submit" onClick="document.frm_close_date.submit();" />
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div style="clear: both; font-size:9px"></div>
	<!--<table width="100%" border="0">
		<tr class="tbl-head">
			<td>Projectstatus</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="tbl-subhead">
			<td width="206">Module</td>
			<td width="282">Startdatum</td>
			<td width="286">Laatste wijziging</td>
			<td width="237">Einddatum</td>
		</tr>
		<?php //$i=0; while($rs_project_module_row = mysql_fetch_assoc($rs_project_module_result)){ $i++; ?>
		<tr class="<?php //if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
			<td><a href="#p_id=20&r_id=<?php //echo $rs_project_all_row['Project_id']; ?>"><?php //echo $rs_project_module_row['Module']; ?></a></td>
			<td><?php //if($rs_project_module_row['Module_start_date']){ echo $rs_project_module_row['Module_start_date']; }else{ echo "-"; } ?></td>
			<td><?php //if($rs_project_module_row['Module_timestamp_date'] != $rs_project_module_row['Module_start_date']){ echo $rs_project_module_row['Module_timestamp_date']; }else{ echo "-"; } ?></td>
			<td><?php //if($rs_project_module_row['Module_finish_date']){ echo $rs_project_module_row['Module_finish_date']; }else{ echo "-"; } ?></td>
		</tr>
		<?php //} ?>
	</table>-->
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_module1_result);
mysql_free_result($rs_project_module2_result);
mysql_free_result($rs_project_detail_result);
?>