<?php
# Submited user data
$status_id = mysql_real_escape_string($_POST['sl_status']);

if($status_id){
	$ft_status_qry = sprintf("WHERE bl.Status='%s'", $status_id);
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_title'])){
	$title = mysql_real_escape_string($_POST['fld_title']);
	$message = mysql_real_escape_string($_POST['fld_message']);

	if($title && $message){
		$rs_new = sprintf("INSERT INTO tbl_tooltip (Create_date, Title, Message) VALUES (NOW(), '%s', '%s')", $title, $message);
		mysql_query($rs_new) or die("Error: " . mysql_error());
	}
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['tt_id'])){
	$tooltip_id = mysql_real_escape_string($_POST['tt_id']);
	$old_page_id = mysql_real_escape_string($_POST['pg_id']);
	$new_page_id = mysql_real_escape_string($_POST['slt_page']);
	$message = mysql_real_escape_string($_POST['txt_message']);

	if($old_page_id){
		$rs_update_null = sprintf("UPDATE tbl_switchboard SET Tooltip_id=NULL WHERE Switchboard_id='%s'", $old_page_id);
		mysql_query($rs_update_null) or die("Error: " . mysql_error());
	}

	if($tooltip_id && $new_page_id){
		$rs_update_new = sprintf("UPDATE tbl_switchboard SET Tooltip_id='%s' WHERE Switchboard_id='%s'", $tooltip_id, $new_page_id);
		mysql_query($rs_update_new) or die("Error: " . mysql_error());
	}
	
	if($message){
		$rs_update_tt = sprintf("UPDATE tbl_tooltip SET Message='%s' WHERE Tooltip_id='%s'", $message, $tooltip_id);
		mysql_query($rs_update_tt) or die("Error: " . mysql_error());
	}
}

# All tooltip query
$rs_tooltip_all_result = mysql_query("SELECT tt.*, sw.Page_title, sw.Tooltip_id AS Page_tooltip_id, sw.Switchboard_id FROM tbl_tooltip AS tt LEFT JOIN tbl_switchboard AS sw ON sw.Tooltip_id=tt.Tooltip_id") or die("Error: " . mysql_error());
$rs_tooltip_all_num = mysql_num_rows($rs_tooltip_all_result);
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<span>Tooltips</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<form action="" method="post" name="tooltip_add" id="tooltip_add">
					<table width="33%">
						<tr>
							<td colspan="2" class="tbl-head">Nieuwe tooltip</td>
						</tr>
						<tr>
							<td width="56%" class="tbl-subhead">Titel</td>
							<td width="44%" align="right"><input name="fld_title" type="text" id="fld_title" maxlength="50"></td>
						</tr>
						<tr>
							<td valign="top" class="tbl-subhead">Melding</td>
							<td align="right"><textarea name="fld_message" id="fld_message"></textarea></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left"><input type="submit" name="btn_submit" id="btn_submit" value="Invoeren"></td>
						</tr>
					</table>
				</form>
				<br>
				<table width="100%" border="0">
					<tr class="tbl-subhead">
						<td width="276">Titel</td>
						<td width="214">Pagina koppeling</td>
						<td width="600">Melding</td>
						<td width="100">&nbsp;</td>
					</tr>
					<?php $i=0;
					while($rs_tooltip_all_row = mysql_fetch_assoc($rs_tooltip_all_result)){ $i++;
					$rs_page_all_result = mysql_query("SELECT * FROM tbl_switchboard WHERE Tooltip_id IS NULL") or die("Error: " . mysql_error());
					$rs_page_all_num = mysql_num_rows($rs_page_all_result);
					?>
					<form name="frm_update_<?php echo $rs_tooltip_all_row['Tooltip_id']; ?>" id="frm_update_<?php echo $rs_tooltip_all_row['Tooltip_id']; ?>" method="post" action="">
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php echo $rs_tooltip_all_row['Title']; ?></td>
						<td valign="top">
							<input name="tt_id" id="tt_id" value="<?php echo $rs_tooltip_all_row['Tooltip_id']; ?>" type="hidden" />
							<?php
							if($rs_tooltip_all_row['Page_tooltip_id']){
								echo '<input name="pg_id" id="pg_id" value="'.$rs_tooltip_all_row['Switchboard_id'].'" type="hidden" />';
							}
							?>
							<select name="slt_page" id="slt_page">
								<option>Geen</option>
								<?php
								if($rs_tooltip_all_row['Page_tooltip_id']){
									echo '<option selected>'.$rs_tooltip_all_row['Switchboard_id'].': '.$rs_tooltip_all_row['Page_title'].'</option>';
								}
								while($rs_page_all_row = mysql_fetch_assoc($rs_page_all_result)){
									echo '<option value="'.$rs_page_all_row['Switchboard_id'].'">'.$rs_page_all_row['Switchboard_id'].': '.$rs_page_all_row['Page_title'].'</option>';
								}
								?>
							</select>
						</td>
						<td><textarea name="txt_message" rows="3" id="txt_message"><?php echo $rs_tooltip_all_row['Message']; ?></textarea></td>
						<td align="center" valign="top"><input type="submit" value="Opslaan" /></td>
						</form>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td colspan="9" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
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