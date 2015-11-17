<?php
# Submited user data
$relation_id = mysql_real_escape_string($_GET['r_id']);

# Check if type Zelf is already used
$rs_check_type_qry = sprintf("SELECT COUNT(*) AS Total FROM tbl_relation WHERE User_id='%s' LIMIT 1", $user_id);
$rs_check_type_result = mysql_query($rs_check_type_qry) or die("Error: " . mysql_error());
$rs_check_type_row = mysql_fetch_assoc($rs_check_type_result);

# This relation query
$rs_relation_detail_chk_qry = sprintf("SELECT Relation_business_type_id FROM tbl_relation WHERE Relation_id='%s' LIMIT 1", $relation_id);
$rs_relation_detail_chk_row = mysql_fetch_assoc(mysql_query($rs_relation_detail_chk_qry));

# User inputted data
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$relation_comp_name = mysql_real_escape_string($_POST['fld_company_name']);
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

	if($rs_relation_detail_chk_row['Relation_business_type_id'] == 20){
		if(!$relation_comp_name){
			$error_message = "Geef een bedrijfsnaam op";
		}
	}

	if((!$relation_first_name) || (!$relation_name)){
		$error_message = "Geef een contact persoon op";
	}
	
	if((!$relation_street) || (!$relation_street_number) || (!$relation_zipcode) || (!$relation_city) || (!$relation_state)){
		$error_message = "Vul alle adresgegevens in";
	}

	if(!$error_message){
		$rs_relation_qry = sprintf("UPDATE tbl_relation SET Company_name='%s', KVK='%s', BTW_number='%s', IBAN='%s', debit_number='%s', Contact_name='%s', Contact_first_name='%s', Address='%s', Address_number='%s', Zipcode='%s', City='%s', State_id='%s', Phone_1='%s', Phone_2='%s', Email_1='%s', Comment='%s' WHERE Relation_id='%s'", $relation_comp_name, $relation_kvk, $relation_btw, $relation_iban, $relation_debnr, $relation_name, $relation_first_name, $relation_street, $relation_street_number, $relation_zipcode, $relation_city, $relation_state, $relation_phone1, $relation_phone2, $relation_email1, $relation_comment, $relation_id);
		mysql_query($rs_relation_qry) or die("Error: " . mysql_error());
		
		$success_message = "Relatie is aangepast";
	}
}

# All project type query
$rs_relation_type_result = mysql_query("SELECT * FROM tbl_relation_type") or die("Error: " . mysql_error());

# All states query
$rs_state_result = mysql_query("SELECT * FROM tbl_state") or die("Error: " . mysql_error());

# All business types query
$rs_business_type_result = mysql_query("SELECT * FROM tbl_relation_business_type") or die("Error: " . mysql_error());

# This relation query
$rs_relation_detail_qry = sprintf("SELECT * FROM tbl_relation AS r JOIN tbl_relation_type AS t ON t.Relation_type_id=r.Relation_type_id JOIN tbl_relation_business_type AS b ON b.Relation_business_type_id=r.Relation_business_type_id WHERE Relation_id='%s' LIMIT 1", $relation_id);
$rs_relation_detail_result = mysql_query($rs_relation_detail_qry) or die("Error: " . mysql_error());
$rs_relation_detail_row = mysql_fetch_assoc($rs_relation_detail_result);
$rs_relation_detail_num = mysql_num_rows($rs_relation_detail_result);

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
<div id="title">
	<span><?php echo $rs_relation_detail_row['Company_name']; ?></span>
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
		<?php if($rs_relation_detail_row['Relation_business_type_id'] == 20){ ?>
		<div class="details-head">Algemeen</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Bedrijfsnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_company_name" id="fld_company_name" value="<?php echo $rs_relation_detail_row['Company_name']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Relatietype</div>
				<div class="details-ctr-right" style="padding: 4px 0;"><?php echo $rs_relation_detail_row['Type']; ?></div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Soort</div>
				<div class="details-ctr-right" style="padding: 4px 0;"><?php echo $rs_relation_detail_row['Business']; ?></div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<?php } ?>
		<div class="details-head">Adres</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Straatnaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street" id="fld_street" value="<?php echo $rs_relation_detail_row['Address']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Huisnummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_street_number" id="fld_street_number" value="<?php echo $rs_relation_detail_row['Address_number']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Postcode</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_zipcode" id="fld_zipcode" value="<?php echo $rs_relation_detail_row['Zipcode']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Plaats</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_city" id="fld_city" value="<?php echo $rs_relation_detail_row['City']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Provincie</div>
				<div class="details-ctr-right">
					<select name="sl_state" id="sl_state">
						<?php while($rs_state_row = mysql_fetch_assoc($rs_state_result)){
							if($rs_relation_detail_row['State_id'] == $rs_state_row['State_id']){
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
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">&nbsp;</div>
		<div class="details">
			<input name="btn_submit" type="submit" id="btn_submit" value="Opslaan" />
		</div>
	</div>
</div>
<div id="content-left-sec">
	<div id="intern-">
		<?php if($rs_relation_detail_row['Relation_business_type_id'] == 20){ ?>
		<div class="details-head">Bedrijfsgegevens</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">KVK</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_kvk" id="fld_kvk" value="<?php echo $rs_relation_detail_row['KVK']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="tax-salary-container">
			<div class="details-container">
				<div class="details-ctr-left">BTW nummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_btwnr" id="fld_btwnr" value="<?php echo $rs_relation_detail_row['BTW_number']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="tax-other-container">
			<div class="details-container">
				<div class="details-ctr-left">IBAN</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_iban" id="fld_iban" value="<?php echo $rs_relation_detail_row['IBAN']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="profit-material-container">
			<div class="details-container">
				<div class="details-ctr-left">Debiteurnummer</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_debnr" id="fld_debnr" value="<?php echo $rs_relation_detail_row['debit_number']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<?php } ?>
		<div class="details-head">Contactgegevens</div>
		<div class="details" id="tax-salary-container">
			<div class="details-container">
				<div class="details-ctr-left">Naam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_name" id="fld_name" value="<?php echo $rs_relation_detail_row['Contact_name']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Voornaam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_first_name" id="fld_first_name" value="<?php echo $rs_relation_detail_row['Contact_first_name']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="tax-other-container">
			<div class="details-container">
				<div class="details-ctr-left">Mobiel</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_phone1" id="fld_phone1" value="<?php echo $rs_relation_detail_row['Phone_1']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="profit-material-container">
			<div class="details-container">
				<div class="details-ctr-left">Vast</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_phone2" id="fld_phone2" value="<?php echo $rs_relation_detail_row['Phone_2']; ?>" />
				</div>
			</div>
		</div>
		<div class="details" id="profit-physical-container">
			<div class="details-container">
				<div class="details-ctr-left">Email</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_email1" id="fld_email1" value="<?php echo $rs_relation_detail_row['Email_1']; ?>" />
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Opmerking</div>
		<div class="details">
			<textarea name="txt_comment" rows="6" id="txt_comment"><?php echo $rs_relation_detail_row['Comment']; ?></textarea>
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