<?php
# Submited user data
$project_id = mysql_real_escape_string($_GET['r_id']);

# User inputted data
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){

# Check type and user
	$rs_project_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", $project_id, $user_id);
	$rs_project_check_row = mysql_fetch_assoc(mysql_query($rs_project_check_qry)) or die("Error: " . mysql_error());

	$project_street = mysql_real_escape_string($_POST['fld_street']);
	$project_street_number = mysql_real_escape_string($_POST['fld_street_number']);
	$project_city = mysql_real_escape_string($_POST['fld_city']);
	$project_zipcode = mysql_real_escape_string($_POST['fld_zipcode']);
	$project_state = mysql_real_escape_string($_POST['sl_state']);
	$project_tax_salary = mysql_real_escape_string($_POST['sl_tax_loan']);
	$project_tax_other = mysql_real_escape_string($_POST['sl_tax_other']);
	$project_material_profit = mysql_real_escape_string($_POST['sl_profit_material']);
	$project_physical_profit = mysql_real_escape_string($_POST['sl_profit_physical']);
	$project_third_profit = mysql_real_escape_string($_POST['sl_profit_other']);
	$project_salary = mysql_real_escape_string($_POST['fld_loan']);
	$project_description = mysql_real_escape_string($_POST['txt_description']);
	$project_salary = str_replace(',', '.', $project_salary);

	if($project_salary == NULL){
		$error_message = "Geen het uurloon op";
	}

	if($rs_project_check_row['Project_type_id'] != 10){
		if(!$project_tax_salary){
			$error_message = "Geef het BTW uurloon op";
		}

		if(!$project_tax_other){
			$error_message = "Geef het BTW overige op";
		}

		if(!$project_material_profit){
			$error_message = "Geef de materiaal winst op";
		}

		if(!$project_physical_profit){
			$error_message = "Geef de materieel winst op";
		}

		if(!$project_third_profit){
			$error_message = "Geef de overige winst op";
		}
	}else{
		$project_tax_salary = 10;
		$project_tax_other = 10;
		$project_material_profit = 0;
		$project_physical_profit = 0;
		$project_third_profit = 0;
	}

	if(!$error_message){
		$rs_project_qry = sprintf("UPDATE tbl_project SET Tax_salary_id='%s', Tax_other_id='%s', Address='%s', Address_number='%s', Zipcode='%s', City='%s', State_id='%s', Hour_salary='%s', Profit_material='%s', Profit_physical='%s', Profit_third='%s', Description='%s' WHERE Project_id='%s'", $project_tax_salary, $project_tax_other, $project_street, $project_street_number, $project_zipcode, $project_city, $project_state, $project_salary, $project_material_profit, $project_physical_profit, $project_third_profit, $project_description, $rs_project_check_row['Project_id']);
		mysql_query($rs_project_qry) or die("Error: " . mysql_error());
		
		$success_message = $relation_comp_name." is aangepast";
	}
}

# All project type query
$rs_relation_type_result = mysql_query("SELECT * FROM tbl_relation_type") or die("Error: " . mysql_error());

# All states query
$rs_state_result = mysql_query("SELECT * FROM tbl_state") or die("Error: " . mysql_error());

# All tax query
$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
$rs_tax2_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());

# All business types query
$rs_business_type_result = mysql_query("SELECT * FROM tbl_relation_business_type") or die("Error: " . mysql_error());

# All relations query
$rs_project_detail_qry = sprintf("SELECT p.*, r.Company_name, t.`Type`,s.State, x.Tax AS Tax_salary, y.Tax AS Tax_other FROM tbl_project AS p JOIN tbl_relation AS r ON r.Relation_id=p.Client_relation_id JOIN tbl_project_type AS t ON t.Project_type_id=p.Project_type_id JOIN tbl_state AS s ON s.State_id=p.State_id JOIN tbl_tax AS x ON x.Tax_id=p.Tax_salary_id JOIN tbl_tax AS y ON y.Tax_id=p.Tax_other_id WHERE p.Project_id='%s' AND p.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_project_detail_result = mysql_query($rs_project_detail_qry) or die("Error: " . mysql_error());
$rs_project_detail_row = mysql_fetch_assoc($rs_project_detail_result);
$rs_project_detail_num = mysql_num_rows($rs_project_detail_result);

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
<div id="title">
	<span><?php echo $rs_project_detail_row['Name']; ?></span>
	<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
	<a class="tooltip" href="javascript:void(0)">
		<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
	</a>
	<?php } ?>	
</div>
<form id="frm_new" name="frm_new" action="" method="post">
<div id="content-left">
	<div id="intern">
		<div class="details-head">Projectgegevens</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Opdrachtgever</div>
				<div class="details-ctr-right"><?php echo $rs_project_detail_row['Company_name']; ?></div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Projectnaam</div>
				<div class="details-ctr-right"><?php echo $rs_project_detail_row['Name']; ?></div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Type</div>
				<div class="details-ctr-right"><?php echo $rs_project_detail_row['Type']; ?></div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Project adresgegevens</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Straatnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street" id="fld_street" value="<?php echo $rs_project_detail_row['Address']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Huisnummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street_number" id="fld_street_number" value="<?php echo $rs_project_detail_row['Address_number']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Postcode</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_zipcode" id="fld_zipcode" value="<?php echo $rs_project_detail_row['Zipcode']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Plaats</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_city" id="fld_city" value="<?php echo $rs_project_detail_row['City']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Provincie</div>
				<div class="details-ctr-right">
					<select name="sl_state" id="sl_state">
						<?php while($rs_state_row = mysql_fetch_assoc($rs_state_result)){
							if($rs_project_detail_row['State_id'] == $rs_state_row['State_id']){
								echo '<option selected="selected" value="'.$rs_state_row['State_id'].'">'.$rs_state_row['State'].'</option>';
							}else{
								echo '<option value="'.$rs_state_row['State_id'].'">'.$rs_state_row['State'].'</option>';
							}
						} ?>
					</select>
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details">
			<input name="btn_submit" type="submit" id="btn_submit" value="Opslaan" />
		</div>
	</div>
</div>
<div id="content-left-sec">
	<div id="intern-">
		<div class="details-head">Financieel</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Uurloon excl. BTW</div>
				<div class="details-ctr-right">&euro;<input type="text" name="fld_loan" id="fld_loan" value="<?php echo number_format($rs_project_detail_row['Hour_salary'], 2, ',', '.'); ?>" /></div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Projectomschrijving</div>
		<div class="details">
			<textarea name="txt_description" rows="5" id="txt_description"><?php echo $rs_project_detail_row['Description']; ?></textarea>
		</div>
	</div>
</div>
<div style="clear: both; font-size:9px">&nbsp;</div>
</form>
</div>
<?php 
//mysql_free_result($rs_tax2_result);
//mysql_free_result($rs_tax_result);
//mysql_free_result($rs_state_result);
//mysql_free_result($rs_project_type_result);
//mysql_free_result($rs_clients_result);
?>