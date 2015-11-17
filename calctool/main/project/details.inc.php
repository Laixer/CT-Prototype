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

# All project module query
$rs_project_module_qry = sprintf("SELECT p.*, m.Module FROM tbl_project_module AS p JOIN tbl_modules AS m ON m.Module_id=p.Module_id WHERE p.Project_id='%s'", $rs_project_detail_row['Project_id']);
$rs_project_module_result = mysql_query($rs_project_module_qry) or die("Error: " . mysql_error());

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
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/chapter-mgr/?r_id=<?php echo $rs_project_detail_row['Project_id']; ?>','Werkzaamheden','width=800,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Werkzaamheden toevoegen" />
			<input style="height: 24px; background: #FFF url('../images/change.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=109&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Wijzigen" />
		</div>
		<span><?php echo $rs_project_detail_row['Name']; ?></span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
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
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Address'].' '.$rs_project_detail_row['Address_number']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Postcode</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Zipcode']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Plaats</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['City']; ?></div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Provincie</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['State']; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div id="content-left-sec">
		<div id="intern">
			<div class="details-head">Financieel</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Uurloon</div>
					<div class="details-ctr-right">&euro;<?php echo number_format($rs_project_detail_row['Hour_salary'], 2, ',', '.'); ?></div>
				</div>
			</div>
			<?php if($rs_project_detail_row['Project_type_id'] != 10) { ?>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">BTW uurloon</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Tax_salary']; ?>%</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">BTW overige kosten</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Tax_other']; ?>%</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Winst materiaal)</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Profit_material']; ?>%</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Winst materieel</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Profit_physical']; ?>%</div>
				</div>
			</div>
			<div class="details">
				<div class="details-container">
					<div class="details-ctr-left">Winst overig</div>
					<div class="details-ctr-right"><?php echo $rs_project_detail_row['Profit_third']; ?>%</div>
				</div>
			</div>
			<?php } ?>
			<div class="details">&nbsp;</div>
			<div class="details-head">Projectomschrijving</div>
			<div class="details">
				<div class="details-ctr-left"><?php echo $rs_project_detail_row['Description']; ?></div>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px"></div>
	<table width="100%" border="0">
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
		<?php $i=0; while($rs_project_module_row = mysql_fetch_assoc($rs_project_module_result)){ $i++; ?>
		<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
			<td><a href="#p_id=20&r_id=<?php echo $rs_project_all_row['Project_id']; ?>"><?php echo $rs_project_module_row['Module']; ?></a></td>
			<td><?php if($rs_project_module_row['Module_start_date']){ echo $rs_project_module_row['Module_start_date']; }else{ echo "-"; } ?></td>
			<td><?php if($rs_project_module_row['Module_timestamp_date'] != $rs_project_module_row['Module_start_date']){ echo $rs_project_module_row['Module_timestamp_date']; }else{ echo "-"; } ?></td>
			<td><?php if($rs_project_module_row['Module_finish_date']){ echo $rs_project_module_row['Module_finish_date']; }else{ echo "-"; } ?></td>
		</tr>
		<?php } ?>
	</table>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_module_result);
mysql_free_result($rs_project_detail_result);
?>