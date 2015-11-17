<?php
# Submited user data
$status_id = mysql_real_escape_string($_POST['sl_status']);

# Username
$rs_username_qry = sprintf("SELECT * FROM tbl_user WHERE User_id='%s' LIMIT 1", $user_id);
$rs_username_result = mysql_query($rs_username_qry) or die("Error: " . mysql_error());
$rs_username_row = mysql_fetch_assoc($rs_username_result);

if($status_id){
	$ft_status_qry = sprintf("WHERE bl.Status='%s'", $status_id);
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_subject'])){
	$subject = mysql_real_escape_string($_POST['fld_subject']);
	$description = mysql_real_escape_string($_POST['fld_description']);

	if($subject && $description){
		$rs_add = sprintf("INSERT INTO tbl_backlog (Create_date, User_id, Subject, Description) VALUES (NOW(), '%s', '%s', '%s')", $user_id, $subject, $description);
		mysql_query($rs_add) or die("Error: " . mysql_error());
	}
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['bl_uid'])){
	$backlog_id = mysql_real_escape_string($_POST['bl_uid']);
	$status = mysql_real_escape_string($_POST['slt_update']);

	if($backlog_id && $status){
		$rs_update = sprintf("UPDATE tbl_backlog SET Status='%s' WHERE Backlog_id='%s'", $status, $backlog_id);
		mysql_query($rs_update) or die("Error: " . mysql_error());
	}
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['bl_tid'])){
	$backlog_id = mysql_real_escape_string($_POST['bl_tid']);
	$description = mysql_real_escape_string($_POST['txt_desc']);

	if($backlog_id && $description){
		$rs_update = sprintf("UPDATE tbl_backlog SET Description='%s' WHERE Backlog_id='%s'", $description, $backlog_id);
		mysql_query($rs_update) or die("Error: " . mysql_error());
	}
}

# All backlog query
$rs_backlog_all_result = mysql_query("SELECT bl.*, UNIX_TIMESTAMP(bl.Timestamp_date) AS t_date, u.Name FROM tbl_backlog AS bl JOIN tbl_user AS u ON u.User_id=bl.User_id ".$ft_status_qry." ORDER BY bl.Status ASC, bl.Create_date DESC") or die("Error: " . mysql_error());
$rs_backlog_all_num = mysql_num_rows($rs_backlog_all_result);
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<span>Backlog</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<form action="" method="post" name="backlog_add" id="backlog_add">
					<table width="50%">
						<tr>
							<td colspan="2" class="tbl-head">Nieuw backlog item</td>
						</tr>
						<tr>
							<td width="31%" class="tbl-subhead">Onderwerp</td>
							<td width="69%" align="left"><input name="fld_subject" type="text" id="fld_subject" maxlength="20"></td>
						</tr>
						<tr>
							<td valign="top" class="tbl-subhead">Omschrijving</td>
							<td align="left"><textarea name="fld_description" rows="5" id="fld_description"><?php echo "[".$rs_username_row['Name']." ".date("d-m-Y H:i")."]:\r\n"; ?></textarea></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left"><input type="submit" name="btn_submit" id="btn_submit" value="Invoeren"></td>
						</tr>
					</table>
				</form>
				<br>
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td>Filter status</td>
						<td colspan="4">
							<form action="" method="post" name="frm_status" id="frm_status">
								<select onchange="document.frm_status.submit()" name="sl_status" id="sl_status">
									<option value="">Geen</option>
									<option <?php if($status_id == 'Todo'){ echo "selected"; }?> value="Todo">Todo</option>
									<option <?php if($status_id == 'Now'){ echo "selected"; }?> value="Now">Now</option>
									<option <?php if($status_id == 'Test'){ echo "selected"; }?> value="Test">Test</option>
									<option <?php if($status_id == 'Done'){ echo "selected"; }?> value="Done">Done</option>
								</select>
							</form>
						</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="177">Onderwerp</td>
						<td width="152">Toegevoegd door</td>
						<td width="155">Datum</td>
						<td width="81">Status</td>
						<td width="601">Omschrijving</td>
					</tr>
					<?php $i=0; while($rs_backlog_all_row = mysql_fetch_assoc($rs_backlog_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>
						<?php
						if($rs_backlog_all_row['Status'] == 'Done'){
							echo '<font color="#006600" style="font-weight:bold;">'.$rs_backlog_all_row['Subject'].'</font>';
						}else if($rs_backlog_all_row['Status'] == 'Test'){
							echo '<font color="#FF00FF" style="font-weight:bold;">'.$rs_backlog_all_row['Subject'].'</font>';
						}else if($rs_backlog_all_row['Status'] == 'Todo'){
							echo '<font color="#FF0000" style="font-weight:bold;">'.$rs_backlog_all_row['Subject'].'</font>';
						}else if($rs_backlog_all_row['Status'] == 'Now'){
							echo '<font color="#0000FF" style="font-weight:bold;">'.$rs_backlog_all_row['Subject'].'</font>';
						}else{
							echo $rs_backlog_all_row['Subject'];
						} ?></td>
						<td><?php echo $rs_backlog_all_row['Name']; ?></td>
						<td><?php echo $rs_backlog_all_row['Create_date']; ?></td>
						<td valign="top">
							<form action="" method="post" name="frm_update_<?php echo $rs_backlog_all_row['Backlog_id']; ?>" id="frm_update_<?php echo $rs_backlog_all_row['Backlog_id']; ?>">
								<input type="hidden" name="bl_uid" id="bl_uid" value="<?php echo $rs_backlog_all_row['Backlog_id']; ?>" />
								<?php if($rs_backlog_all_row['Status'] != 'Done'){ ?>
								<select style="border:none; outline:none; background-color:<?php if($i%2){ echo "#bfbfbf"; }else{ echo "#FFF"; } ?>" onchange="document.frm_update_<?php echo $rs_backlog_all_row['Backlog_id']; ?>.submit()" name="slt_update" id="slt_update">
									<option <?php if($rs_backlog_all_row['Status'] == 'Todo'){ echo "selected"; }?> value="Todo">Todo</option>
									<option <?php if($rs_backlog_all_row['Status'] == 'Now'){ echo "selected"; }?> value="Now">Now</option>
									<option <?php if($rs_backlog_all_row['Status'] == 'Test'){ echo "selected"; }?> value="Test">Test</option>
									<option value="Done">Done</option>
								</select>
								<?php }else{ echo '<font color="#006600">Done</font>'; } ?>
							</form>
						</td>
						<td>
							<?php if($rs_backlog_all_row['Status'] != 'Done'){ ?>
							<form action="" method="post" name="frm_desc_<?php echo $rs_backlog_all_row['Backlog_id']; ?>" id="frm_desc_<?php echo $rs_backlog_all_row['Backlog_id']; ?>">
								<input type="hidden" name="bl_tid" id="bl_tid" value="<?php echo $rs_backlog_all_row['Backlog_id']; ?>" />
								<textarea name="txt_desc" id="txt_desc" onClick="$(this).height(200);" onBlur="$(this).height(36);" style="border:none;outline:none; width:100%; background-color:<?php if($i%2){ echo "#bfbfbf"; }else{ echo "#FFF"; } ?>;" ><?php echo "[".$rs_username_row['Name']." ".date("d-m-Y H:i")."]:\r\n".$rs_backlog_all_row['Description']; ?></textarea>
								<input type="submit" name="btn_submit" id="btn_submit" value="Opslaan" />
							</form>
							<?php }else{ echo nl2br($rs_backlog_all_row['Description']); } ?>
						</td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td colspan="6" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php
//mysql_free_result($rs_project_status_result);
//mysql_free_result($rs_project_type_result);
//mysql_free_result($rs_project_all_result);
?>