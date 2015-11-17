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
	$relation_first_name = mysql_real_escape_string($_POST['fld_first_name']);
	$relation_name = mysql_real_escape_string($_POST['fld_name']);
	$relation_phone1 = mysql_real_escape_string($_POST['fld_phone1']);
	$relation_phone2 = mysql_real_escape_string($_POST['fld_phone2']);
	$relation_email1 = mysql_real_escape_string($_POST['fld_email1']);
	$relation_email2 = mysql_real_escape_string($_POST['fld_email2']);
	$relation_comment = mysql_real_escape_string($_POST['txt_comment']);

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
		$error_message = "Relatie type Zelf kan maar een keer worden gebruikt";
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
		$rs_relation_qry = sprintf("INSERT INTO `tbl_relation` (`User_id`, `Relation_type_id`, `Relation_business_type_id`, `Create_date`, `Timestamp_date`, `Company_name`, `Contact_name`, `Contact_first_name`, `Address`, `Address_number`, `Zipcode`, `City`, `Province_id`, `Phone_1`, `Phone_2`, `Email_1`, `Email_2`, `Comment`) VALUES ('%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $_SESSION['SES_User_id'], $relation_type, $relation_business_type, $relation_comp_name, $relation_name, $relation_first_name, $relation_street, $relation_street_number, $relation_zipcode, $relation_city, $relation_state, $relation_phone1, $relation_phone2, $relation_email1, $relation_email2, $relation_comment);
		mysql_query($rs_relation_qry) or die("Error: " . mysql_error());
		
		$success_message = $relation_comp_name." is aangemaakt";
	}
}

# All project type query
$rs_relation_type_result = mysql_query("SELECT * FROM tbl_relation_type") or die("Error: " . mysql_error());

# All states query
$rs_state_result = mysql_query("SELECT * FROM tbl_state") or die("Error: " . mysql_error());

# All business types query
$rs_business_type_result = mysql_query("SELECT * FROM tbl_relation_business_type") or die("Error: " . mysql_error());

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
<div id="title">
	<span>Relatielogo</span>
	<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
	<a class="tooltip" href="javascript:void(0)">
		<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
	</a>
	<?php } ?>
</div>
<form id="frm_new" name="fld_name" action="" method="post" enctype="multipart/form-data">
<div id="content-left">
	<div id="intern">
		<div id="logo"><a href="#"><img src="http://static-4.cdnhub.nl/nl/images/logos/van-dale-logo-big.gif" alt="Logo" width="200" height="100" /></a></div>
		<div class="details-head">Bestand</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Upload datum</div>
				<div class="details-ctr-right">~DATE~</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Laatste gewijzigd</div>
				<div class="details-ctr-right">~DATE~</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Grootte</div>
				<div class="details-ctr-right">~SIZE~</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Naam</div>
				<div class="details-ctr-right">
					<input type="text" name="fld_name" id="fld_name" />
				</div>
			</div>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details-head">Omschrijving</div>
		<div class="details">
			<textarea name="txt_comment" rows="6" id="txt_comment"></textarea>
		</div>
		<div class="details">&nbsp;</div>
		<div class="details">
			<input name="btn_submit" type="submit" id="btn_submit" value="Opslaan" />

			<input name="btn_submit" type="submit" id="btn_submit" value="Verwijderen" />
		</div>
	</div>
</div>
<div id="content-left-sec">
	<div id="intern-">
		<div class="details-head">Logo eisen</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Maximale resolutie</div>
				<div class="details-ctr-right">400 x 100</div>
			</div>
		</div>
		<div class="details">
			<div class="details-container">
				<div class="details-ctr-left">Maximale bestandsgrootte</div>
				<div class="details-ctr-right">1 Megabyte</div>
			</div>
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