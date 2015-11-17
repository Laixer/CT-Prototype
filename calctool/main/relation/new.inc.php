<?php

# Check if type Zelf is already used
$rs_check_type_qry = sprintf("SELECT COUNT(*) AS Total FROM tbl_relation WHERE User_id='%s' LIMIT 1", $user_id);
$rs_check_type_result = mysql_query($rs_check_type_qry) or die("Error: " . mysql_error());
$rs_check_type_row = mysql_fetch_assoc($rs_check_type_result);

# User inputted data
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$relation_comp_name = mysql_real_escape_string($_POST['fld_company_name']);
	$relation_type = mysql_real_escape_string($_POST['slt_type']);
	$relation_business_type = mysql_real_escape_string($_POST['slt_business_type']);
	$relation_street = mysql_real_escape_string($_POST['fld_street']);
	$relation_street_number = mysql_real_escape_string($_POST['fld_street_number']);
	$relation_city = mysql_real_escape_string($_POST['fld_city']);
	$relation_zipcode = mysql_real_escape_string($_POST['fld_zipcode']);
	$relation_state = mysql_real_escape_string($_POST['sl_state']);
	$relation_kvk = mysql_real_escape_string($_POST['fld_kvk']);
	$relation_btw = mysql_real_escape_string($_POST['fld_btwnr']);
	$relation_iban = mysql_real_escape_string($_POST['fld_iban']);
	$relation_debnr = mysql_real_escape_string($_POST['fld_debnr']);
	$relation_first_name = mysql_real_escape_string($_POST['fld_first_name']);
	$relation_name = mysql_real_escape_string($_POST['fld_name']);
	$relation_phone1 = mysql_real_escape_string($_POST['fld_phone1']);
	$relation_phone2 = mysql_real_escape_string($_POST['fld_phone2']);
	$relation_email1 = mysql_real_escape_string($_POST['fld_email1']);
	$relation_comment = mysql_real_escape_string($_POST['txt_comment']);

	if($relation_business_type == 20){
		if(!$relation_comp_name){
			$error_message = "Geef een bedrijfsnaam op";
		}

		if(!$relation_type){
			if(!$rs_check_type_row['Total']){
				$relation_type = 1;
			}else{
				$error_message = "Geef een relatie type op";
			}
		}
	
		if(($rs_check_type_row['Total']) && ($relation_type == 1)){
			$error_message = "Relatie type 'Zelf' kan maar een keer worden gebruikt";
		}
	}
	
	if(!$relation_business_type){
		$error_message = "Geef een bedrijfs type op";
	}
	
	if((!$relation_first_name) || (!$relation_name)){
		$error_message = "Geef een contact persoon op";
	}
	
	if((!$relation_street) || (!$relation_street_number) || (!$relation_zipcode) || (!$relation_city) || (!$relation_state)){
		$error_message = "Vul alle adresgegevens in";
	}

	if(!$error_message){	
		$rs_relation_qry = sprintf("INSERT INTO `tbl_relation` (`User_id`, `Relation_type_id`, `Relation_business_type_id`, `Create_date`, `Company_name`, `Contact_name`, `Contact_first_name`, `Address`, `Address_number`, `Zipcode`, `City`, `State_id`, `KVK`, `BTW_number`, `IBAN`, `debit_number`, `Phone_1`, `Phone_2`, `Email_1`, `Comment`) VALUES ('%s', '%s', '%s', NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $_SESSION['SES_User_id'], $relation_type, $relation_business_type, $relation_comp_name, $relation_name, $relation_first_name, $relation_street, $relation_street_number, $relation_zipcode, $relation_city, $relation_state, $relation_kvk, $relation_btw, $relation_iban, $relation_debnr, $relation_phone1, $relation_phone2, $relation_email1, $relation_comment);
		mysql_query($rs_relation_qry) or die("Error: " . mysql_error());
		
		$success_message = "Relatie is aangemaakt";
	}
}

# All project type query
$rs_relation_type_result = mysql_query("SELECT * FROM tbl_relation_type ORDER BY Type ASC") or die("Error: " . mysql_error());

# All states query
$rs_state_result = mysql_query("SELECT * FROM tbl_state") or die("Error: " . mysql_error());

# All business types query
$rs_business_type_result = mysql_query("SELECT * FROM tbl_relation_business_type") or die("Error: " . mysql_error());

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
	<span>Relatiegegevens</span>
	<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
	<a class="tooltip" href="javascript:void(0)">
		<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
	</a>
	<?php } ?>
</div>
<form id="frm_new" name="fld_name" action="" method="post">
<div id="content-left">
	<div id="intern">
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Soort</div>
				<div class="details-ctr-right">
					<select name="slt_business_type" id="slt_business_type" onchange="toggle();">
						<option selected value="20">Zakelijk</option>
						<option value="10">Particulier</option>
					</select>
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head thide">Algemeen</div>
		<div class="details thide">
			<div class="details-container">
				<div class="details-ctr-left">Bedrijfsnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_company_name" id="fld_company_name" />
				</div>
			</div>
		</div>
		<div class="details thide">
			<div class="details-container">
				<div class="details-ctr-left">Relatietype</div>
				<div class="details-ctr-right">
				<?php if($rs_check_type_row['Total']){ ?>
					<select name="slt_type" id="slt_type"">
						<?php while($rs_relation_type_row = mysql_fetch_assoc($rs_relation_type_result)){
							if($rs_relation_type_row['Relation_type_id'] != 1){
								echo '<option value="'.$rs_relation_type_row['Relation_type_id'].'">'.$rs_relation_type_row['Type'].'</option>';
							}
						} ?>
					</select>
				<?php }else{ echo "Zelf"; } ?>
				</div>
			</div>
		</div>
		<div class="details thide">&nbsp;</div>
		<div class="details-head">Adres</div>
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
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">
			<input name="btn_submit" type="submit" id="btn_submit" value="Relatie Opslaan" />
		</div>
	</div>
</div>
<div id="content-left-sec">
	<div id="intern-">
		<div class="details-head thide">Bedrijfsgegevens</div>
		<div class="details thide">
			<div class="details-container">
				<div class="details-ctr-left">KVK</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_kvk" id="fld_kvk" />
				</div>
			</div>
		</div>
		<div class="details thide" id="tax-other-container">
			<div class="details-container">
				<div class="details-ctr-left">BTW nummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_btwnr" id="fld_btwnr" />
				</div>
			</div>
		</div>
		<div class="details thide" id="tax-salary-container">
			<div class="details-container">
				<div class="details-ctr-left">IBAN</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_iban" id="fld_iban" />
				</div>
			</div>
		</div>
		<div class="details thide" id="tax-salary-container">
			<div class="details-container">
				<div class="details-ctr-left">Debiteurnummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_debnr" id="fld_debnr" />
				</div>
			</div>
		</div>
		<div class="details thide">&nbsp;</div>
		<div class="details-head">Contactgegevens</div>
		<div class="details" id="tax-salary-container">
			<div class="details-container">
				<div class="details-ctr-left">Naam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_name" id="fld_name" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Voornaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_first_name" id="fld_first_name" />
				</div>
			</div>
		</div>
		<div class="details" id="tax-other-container">
			<div class="details-container">
				<div class="details-ctr-left">Mobiel</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_phone1" id="fld_phone1" />
				</div>
			</div>
		</div>
		<div class="details" id="profit-material-container">
			<div class="details-container">
				<div class="details-ctr-left">Vast</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_phone2" id="fld_phone2" />
				</div>
			</div>
		</div>
		<div class="details" id="profit-physical-container">
			<div class="details-container">
				<div class="details-ctr-left">Email</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_email1" id="fld_email1" />
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Opmerking</div>
		<div class="details">
			<textarea name="txt_comment" rows="6" id="txt_comment"></textarea>
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