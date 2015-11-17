<?php
# User inputted data
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$project_client = mysql_real_escape_string($_POST['sl_client']);
	$project_name = mysql_real_escape_string($_POST['fld_name']);
	$project_type = mysql_real_escape_string($_POST['sl_type']);
	$project_street = mysql_real_escape_string($_POST['fld_street']);
	$project_street_number = mysql_real_escape_string($_POST['fld_street_number']);
	$project_city = mysql_real_escape_string($_POST['fld_city']);
	$project_zipcode = mysql_real_escape_string($_POST['fld_zipcode']);
	$project_state = mysql_real_escape_string($_POST['sl_state']);
	$project_salary = mysql_real_escape_string($_POST['fld_loan']);
	$project_tax_salary = mysql_real_escape_string($_POST['sl_tax_loan']);
	$project_tax_other = mysql_real_escape_string($_POST['sl_tax_other']);
	$project_material_profit = mysql_real_escape_string($_POST['sl_profit_material']);
	$project_physical_profit = mysql_real_escape_string($_POST['sl_profit_physical']);
	$project_third_profit = mysql_real_escape_string($_POST['sl_profit_other']);
	$project_description = mysql_real_escape_string($_POST['txt_description']);
	$project_salary = str_replace(',', '.', $project_salary);

	if($project_type == 10){
		if($project_salary == NULL){
			$error_message = "Geen het uurloon op";
		}
	}

	if($project_type != 10){
		/*if(!$project_tax_salary){
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
		
		if(!$project_type){
			$error_message = "Geen een type op";
		}*/
	}

	if($project_client == NULL){
		$error_message = "Geen de opdrachtgever op";
	}

	if(!$error_message){	
		$rs_project_qry = sprintf("INSERT INTO tbl_project (Create_date, User_id, Client_relation_id, Project_type_id, Tax_salary_id, Tax_other_id, Name, Address, Address_number, Zipcode, City, State_id, Hour_salary, Profit_material, Profit_physical, Profit_third, Description) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', stf_project_ref('%s'), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user_id, $project_client, $project_type, 10, 10, $project_name, $project_street, $project_street_number, $project_zipcode, $project_city, $project_state, $project_salary, $project_material_profit, $project_physical_profit, $project_third_profit, $project_description);
		mysql_query($rs_project_qry) or die("Error: " . mysql_error());
		
		switch($project_type){
			case 10: # Regiewerk // Hebben deze nog effect?
				$rs_mod1_qry = sprintf("INSERT INTO `tbl_project_module` (`Project_id`, `Module_id`, `Module_start_date`) VALUES ((SELECT Project_id FROM tbl_project WHERE User_id='%s' ORDER BY Project_id DESC LIMIT 1), 5, NOW())", $user_id);
				$rs_mod2_qry = sprintf("INSERT INTO `tbl_project_module` (`Project_id`, `Module_id`, `Module_start_date`) VALUES ((SELECT Project_id FROM tbl_project WHERE User_id='%s' ORDER BY Project_id DESC LIMIT 1), 4, NOW())", $user_id);
				mysql_query($rs_mod1_qry) or die("Error: " . mysql_error());
				mysql_query($rs_mod2_qry) or die("Error: " . mysql_error());
				break;
			case 20: # Calculatie
				$rs_mod1_qry = sprintf("INSERT INTO `tbl_project_module` (`Project_id`, `Module_id`, `Module_start_date`) VALUES ((SELECT Project_id FROM tbl_project WHERE User_id='%s' ORDER BY Project_id DESC LIMIT 1), 1, NOW())", $user_id);
				$rs_profit_qry = sprintf("INSERT INTO `tbl_project_profit` (`Create_date`, `Project_id`) VALUES (NOW(), (SELECT Project_id FROM tbl_project WHERE User_id='%s' ORDER BY Project_id DESC LIMIT 1))", $user_id);
				mysql_query($rs_mod1_qry) or die("Error: " . mysql_error());
				mysql_query($rs_profit_qry) or die("Error: " . mysql_error());
				break;
			default:
				$error_message = "Project type niet geregistreerd";
				break;
		}
		# Get last inserted project
		$rs_new_project_qry = sprintf("SELECT Project_id FROM tbl_project WHERE User_id='%s' ORDER BY Project_id DESC LIMIT 1", $user_id, $ft_status_qry, $ft_type_qry);
		$rs_new_project_row = mysql_fetch_assoc(mysql_query($rs_new_project_qry)) or die("Error: " . mysql_error());
		if($project_type == 10){
			$success_message = "<a href='?p_id=104&r_id=".$rs_new_project_row['Project_id']."'>Project is aangemaakt, klik hier om naar het project te gaan</a>";
		}else{
			$success_message = "<a href='?p_id=111&r_id=".$rs_new_project_row['Project_id']."'>Project is aangemaakt, klik hier om naar het project te gaan</a>";
		}
	}
}

# All relations query
$rs_clients_qry = sprintf("SELECT * FROM tbl_relation WHERE User_id='%s'", $user_id, $ft_status_qry, $ft_type_qry);
$rs_clients_result = mysql_query($rs_clients_qry) or die("Error: " . mysql_error());
$rs_clients_num = mysql_num_rows($rs_clients_result);

# All project type query
$rs_project_type_result = mysql_query("SELECT * FROM tbl_project_type") or die("Error: " . mysql_error());

# All states query
$rs_state_result = mysql_query("SELECT * FROM tbl_state") or die("Error: " . mysql_error());

# All tax query
$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
$rs_tax2_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<script language="javascript"> 
function toggle(){
	$('.thide').toggle("blind");
} 
</script>
<div id="page-bgtop">
	<div id="title">
		<span>Nieuw project aanmaken</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic">
		<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
<form id="frm_new" name="fld_name" action="" method="post">
<div id="content-left">
	<div id="intern">
		<div class="details-head">Projectgegevens</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Opdrachtgever</div>
				<div class="details-ctr-right">
					<select name="sl_client" id="sl_client">
						<?php while($rs_clients_row = mysql_fetch_assoc($rs_clients_result)){
							echo '<option value="'.$rs_clients_row['Relation_id'].'">'.$rs_clients_row['Company_name'].'</option>';
						} ?>
					</select>
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Projectnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_name" id="fld_name" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Type</div>
				<div class="details-ctr-right">
					<select name="sl_type" id="sl_type" onchange="toggle();">
						<?php while($rs_project_type_row = mysql_fetch_assoc($rs_project_type_result)){
							echo '<option value="'.$rs_project_type_row['Project_type_id'].'">'.$rs_project_type_row['Type'].'</option>';
						} ?>
					</select>
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Project adregegevenss</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Straatnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street" id="fld_street" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Huisnummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street_number" id="fld_street_number" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Postcode</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_zipcode" id="fld_zipcode" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Plaats</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_city" id="fld_city" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Provincie</div>
				<div class="details-ctr-right">
					<select name="sl_state" id="sl_state">
						<?php while($rs_state_row = mysql_fetch_assoc($rs_state_result)){
							echo '<option value="'.$rs_state_row['State_id'].'">'.$rs_state_row['State'].'</option>';
						} ?>
					</select>
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details">
			<input name="btn_submit" type="submit" id="btn_submit" value="Project Opslaan" />
		</div>
	</div>
</div>
<div id="content-left-sec">
	<div id="intern-">
		<div class="details-head thide">Financieel</div>
		<div class="details thide">
			<div class="details-container">
				<div class="details-ctr-left">Uurloon excl. BTW</div>
				<div class="details-ctr-right">
					&euro; 
					<input type="text" name="fld_loan" id="fld_loan" />
				</div>
			</div>
		</div>
		<div class="details thide">&nbsp;</div>
		<div class="details-head">Projectomschrijving</div>
		<div class="details">
			<textarea name="txt_description" rows="5" id="txt_description"></textarea>
		</div>
	</div>
</div>
<div style="clear: both; font-size:9px">&nbsp;</div>
</form>
</div>
<?php 
mysql_free_result($rs_tax2_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_state_result);
mysql_free_result($rs_project_type_result);
mysql_free_result($rs_clients_result);
?>