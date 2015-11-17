<?php
/**
 * Project hour registration
 * - Markup correction
 * - Code Safety
 *	 - Escape
 *	 - User based selection
 * - Freeing results
 * - Error handling
 */

# Submited user data
$chapter_id = mysql_real_escape_string($_POST['slt_chapter']);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

if($chapter_id){
	$ft_chap_qry = sprintf("AND o.Chapter_id='%s'", $chapter_id);
}

if(isset($_GET['del_id'])){
	$del_id = mysql_real_escape_string($_GET['del_id']);

	$rs_del_op_qry = sprintf("DELETE h.* FROM tbl_project_hour AS h JOIN tbl_project AS p ON p.Project_id=h.Project_id WHERE h.Project_hour_id='%s' AND p.User_id='%s'", $del_id, $user_id);
	mysql_query($rs_del_op_qry) or die("Error: " . mysql_error());
	
	$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
	mysql_query($rs_update_module) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_date'])){
	$date = (strtotime(mysql_real_escape_string($_POST['fld_date'])) + 3600);
	$hour = mysql_real_escape_string($_POST['fld_hour']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['slt_work']);
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$hour = str_replace(',', '.', $hour);
	
	if($date == 3600){
		$error_message = "Vul een geldige datum in";
	}
	
	if(!$error_message){	
		# Check if operation is part of this project
		$rs_project_op_check_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $operation_id, $user_id);
		$rs_project_op_check_result = mysql_query($rs_project_op_check_qry) or die("Error: " . mysql_error());
		if(mysql_num_rows($rs_project_op_check_result)){
			$rs_add_hour = sprintf("INSERT INTO tbl_project_hour (Create_date, Project_id, Operation_id, Tax_id, Date, Amount, Comment) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '%s')", $rs_project_perm_check_row['Project_id'], $operation_id, $tax_id, $date, $hour, $comment);
			mysql_query($rs_add_hour) or die("Error: " . mysql_error());
			
			$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
			mysql_query($rs_update_module) or die("Error: " . mysql_error());
		}else{
			$error_message = "Deze werkzaamheid is geen onderdeel van dit project";
		}
	}
}

# All hours query
$rs_hours_qry = sprintf("SELECT h.Project_hour_id, h.Date, h.Amount, h.Comment, o.Operation, c.Chapter, t.Tax FROM tbl_project_hour AS h JOIN tbl_tax AS t ON t.Tax_id=h.Tax_id JOIN tbl_project_operation AS o ON o.Project_operation_id=h.Operation_id JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=h.Project_id WHERE h.Project_id='%s' AND p.User_id='%s' %s ORDER BY Date DESC", $rs_project_perm_check_row['Project_id'], $user_id, $ft_chap_qry);
$rs_hours_result = mysql_query($rs_hours_qry) or die("Error: " . mysql_error());
$rs_hours_num = mysql_num_rows($rs_hours_result);

# All tax
$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());

# Hour totals
$rs_hours_total_qry = sprintf("SELECT SUM(h.Amount) AS Total FROM tbl_project_hour AS h JOIN tbl_project AS p ON p.Project_id=h.Project_id WHERE h.Project_id='%s' AND p.User_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_hours_total_result = mysql_query($rs_hours_total_qry) or die("Error: " . mysql_error());
$rs_hours_total_row = mysql_fetch_assoc($rs_hours_total_result);

# All chapters for this project
$rs_project_chap_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_chap_result = mysql_query($rs_project_chap_qry) or die("Error: " . mysql_error());
$rs_project_chap2_result = mysql_query($rs_project_chap_qry) or die("Error: " . mysql_error());

# No projects have been found
if(!$rs_project_perm_check_row){
	$error_message = "Er zijn geen gegevens gevonden";
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
	$( "#fld_date" ).datepicker({ dateFormat: "dd-mm-yy", dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ], monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ] });
});
</script>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/chapter-mgr/?r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>','Werkzaamheden','width=800,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Werkzaamheden toevoegen" />
		</div>
		<span>Urenregistratie</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
	<div id="content-main">
		<div id="intern">
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td colspan="2">Uren toevoegen</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="189">Datum</td>
						<td width="144">Uren</td>
						<td width="69">BTW</td>
						<td width="231">Werkzaamheden</td>
						<td width="370">Opmerking</td>
					</tr>
					<form action="" method="post" name="frm_hour_add" id="frm_hour_add">
						<tr class="tbl-even">
							<td><a href="#" onclick="document.frm_hour_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
							<td><input style="width: 99%;" type="text" name="fld_date" id="fld_date" /></td>												
							<td><input style="width: 99%;" type="text" name="fld_hour" id="fld_hour" /></td>
							<td>
								<select name="slt_tax" id="slt_tax" style="width: 99%;">
								<?php
								while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
									if($rs_tax_row['Tax_id'] == 40){
										echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
									}else{
										echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
									}
								}
								?>
								</select>
							</td>
							<td>
								<select name="slt_work" id="slt_work" style="width: 99%;">
								<?php
								while($rs_project_chap2_row = mysql_fetch_assoc($rs_project_chap2_result)){
									$rs_project_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' ORDER BY Priority ASC", $rs_project_chap2_row['Project_chapter_id']);
									$rs_project_op_result = mysql_query($rs_project_op_qry) or die("Error: " . mysql_error());
									echo '<optgroup label="'.$rs_project_chap2_row['Chapter'].'">';
									while($rs_project_op_row = mysql_fetch_assoc($rs_project_op_result)){
										echo '<option value="' . $rs_project_op_row['Project_operation_id'] . '">&raquo;&nbsp;' . $rs_project_op_row['Operation'] . '</option>';
									}
									echo '</optgroup>';
								}
								?>
								</select>
							</td>
							<td><input style="width: 99%;" type="text" name="fld_comment" id="fld_comment" /></td>
						</tr>
					</form>
				</table>
			</div>
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td colspan="2">Filter hoofdstuk</td>
						<td>
							<form action="" method="post" name="frm_chapter" id="frm_chapter">
								<select onchange="document.frm_chapter.submit()" name="slt_chapter" id="slt_chapter">
									<option value="">Geen</option>
									<?php while($rs_project_chap_row = mysql_fetch_assoc($rs_project_chap_result)){
										if($chapter_id == $rs_project_chap_row['Project_chapter_id']){
											echo '<option selected="selected" value="'.$rs_project_chap_row['Project_chapter_id'].'">'.$rs_project_chap_row['Chapter'].'</option>';
										}else{
											echo '<option value="'.$rs_project_chap_row['Project_chapter_id'].'">'.$rs_project_chap_row['Chapter'].'</option>';
										}
									} ?>
								</select>
							</form>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="142">Datum</td>
						<td width="94">Uren</td>
						<td width="73">BTW</td>
						<td width="232">Hoofdstuk</td>
						<td width="192">Werkzaamheden</td>
						<td width="266">Opmerking</td>
					</tr>
					<?php $i=0; while($rs_hours_row = mysql_fetch_assoc($rs_hours_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><a href="?p_id=<?php echo $_GET['p_id']; ?>&r_id=<?php echo $_GET['r_id']; ?>&del_id=<?php echo $rs_hours_row['Project_hour_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						<td><?php echo date("d-m-Y", $rs_hours_row['Date']); ?></td>
						<td><?php echo $rs_hours_row['Amount']; ?></td>
						<td><?php echo $rs_hours_row['Tax']; ?>%</td>
						<td><?php echo $rs_hours_row['Chapter']; ?></td>
						<td><?php if($rs_hours_row['Placeholder'] == 'Y'){ echo "Alle bijbehorende werkzaamheden"; }else{ echo $rs_hours_row['Operation']; } ?></td>
						<td><?php echo $rs_hours_row['Comment']; ?></td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="142">Totaal</td>
						<td width="94"><?php echo $rs_hours_total_row['Total']; ?></td>
						<td width="73">&nbsp;</td>
						<td width="232">&nbsp;</td>
						<td width="192">&nbsp;</td>
						<td width="266">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="7" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php  
mysql_free_result($rs_project_chap2_result);
mysql_free_result($rs_project_chap_result);
mysql_free_result($rs_hours_total_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_hours_result);
?>