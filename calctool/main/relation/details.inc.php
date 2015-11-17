<?php
#TODO safety

# This relation query
$rs_relation_detail_qry = sprintf("SELECT * FROM tbl_relation AS r JOIN tbl_relation_type AS t ON t.Relation_type_id=r.Relation_type_id JOIN tbl_relation_business_type AS b ON b.Relation_business_type_id=r.Relation_business_type_id JOIN tbl_state AS s ON s.State_id=r.State_id WHERE Relation_id='%s' AND r.User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_relation_detail_result = mysql_query($rs_relation_detail_qry) or die("Error: " . mysql_error());
$rs_relation_detail_row = mysql_fetch_assoc($rs_relation_detail_result);
$rs_relation_detail_num = mysql_num_rows($rs_relation_detail_result);
?>
<div id="page-bgtop">
<div id="title">
	<div style="float: right; font-size: 20px;">
		<input style="height: 24px; background: #FFF url('../images/up.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=110&r_id=<?php echo $rs_relation_detail_row['Relation_id']; ?>'" type="button" value="Logo wijzigen" />
		<input style="height: 24px; background: #FFF url('../images/change.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=108&r_id=<?php echo $rs_relation_detail_row['Relation_id']; ?>'" type="button" value="Relatie wijzigen" />
	</div>
	<span><?php if($rs_relation_detail_row['Relation_business_type_id'] == 20){ echo $rs_relation_detail_row['Company_name']; }else{ echo $rs_relation_detail_row['Contact_first_name'].' '.$rs_relation_detail_row['Contact_name']; } ?></span>
	<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
	<a class="tooltip" href="javascript:void(0)">
		<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
	</a>
	<?php } ?>
</div>
	<div id="content-left">
		<div id="intern">
			<div id="logo">
				<a href="#">
					<?php if($rs_relation_detail_row['Resource_id']) { ?>
					<img src="resource-embed/<?php echo $user_id; ?>-<?php echo $rs_relation_detail_row['Resource_id']; ?>.img" alt="Logo" />
					<?php }else{ ?>
					<img src="http://static-4.cdnhub.nl/nl/images/logos/van-dale-logo-big.gif" alt="Logo" width="200" height="100" />
					<?php } ?>
				</a>
			</div>
			<?php if($rs_relation_detail_row['Relation_business_type_id'] == 20){ ?>
			<div class="details-head">Relatiegegevens</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Bedrijfsnaam</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Company_name']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Relatietype</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Type']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Soort</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Business']; ?></div>
				</div>
			</div>
			<div class="details">&nbsp;</div>
			<?php } ?>
			<div class="details-head">Adresgegevens</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Straatnaam</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Address'].' '.$rs_relation_detail_row['Address_number']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Postcode</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Zipcode']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Plaats</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['City']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Provincie</div>
					<div class="details-ctr-right"><?php echo $rs_relation_detail_row['State']; ?></div>
				</div>
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
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['KVK']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">BTW nummer</div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['BTW_number']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">IBAN</div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['IBAN']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">Debiteurnummer</div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['debit_number']; ?></div>
						</div>
					</div>
					<div class="details">&nbsp;</div>
					<?php } ?>
					<div class="details-head">Contactgegevens</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">Naam </div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Contact_first_name'].' '.$rs_relation_detail_row['Contact_name']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">Mobiel</div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Phone_1']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">Vast</div>
							<div class="details-ctr-right"><?php echo $rs_relation_detail_row['Phone_2']; ?></div>
						</div>
					</div>
					<div class="details">
						<div class="details-container">
							<div class="details-ctr-left">Email</div>
							<div class="details-ctr-right"><a href="mailto:<?php echo $rs_relation_detail_row['Email_1']; ?>"><?php echo $rs_relation_detail_row['Email_1']; ?></a></div>
						</div>
					</div>
					<div class="details">&nbsp;</div>
					<div class="details-head">Opmerking</div>
					<div class="details"><?php echo $rs_relation_detail_row['Comment']; ?></div>
				</div>
			</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php
mysql_free_result($rs_relation_detail_result);
?>