<?php
# Submited user data
$status_id = mysql_real_escape_string($_POST['sl_status']);
$type_id = mysql_real_escape_string($_POST['sl_type']);

if($status_id){
	$ft_status_qry = sprintf("AND p.Status_id='%s'", $status_id);
}

if($type_id){
	$ft_type_qry = sprintf("AND p.Project_type_id='%s'", $type_id);
}

# All relations query
//$rs_project_all_qry = sprintf("SELECT * FROM tbl_user", $user_id, $ft_status_qry, $ft_type_qry);
$rs_user_all_result = mysql_query("SELECT *, UNIX_TIMESTAMP(Timestamp_date) AS t_date FROM tbl_user ORDER BY Timestamp_date DESC") or die("Error: " . mysql_error());
$rs_user_all_num = mysql_num_rows($rs_user_all_result);

# All project status query
//$rs_project_status_result = mysql_query("SELECT * FROM tbl_project_status") or die("Error: " . mysql_error());

# All project type query
//$rs_project_type_result = mysql_query("SELECT * FROM tbl_project_type") or die("Error: " . mysql_error());
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<span>Gebruikers</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td>Filter status</td>
						<td>
							<form action="" method="post" name="frm_status" id="frm_status">
								<select onchange="document.frm_status.submit()" name="sl_status" id="sl_status">
									<option value="">Geen</option>
									<?php while($rs_project_status_row = mysql_fetch_assoc($rs_project_status_result)){
										if($status_id == $rs_project_status_row['Project_status_id']){
											echo '<option selected="selected" value="'.$rs_project_status_row['Project_status_id'].'">'.$rs_project_status_row['Status'].'</option>';
										}else{
											echo '<option value="'.$rs_project_status_row['Project_status_id'].'">'.$rs_project_status_row['Status'].'</option>';
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
					<tr class="tbl-head">
						<td>Filter type</td>
						<td>
							<form action="" method="post" name="frm_type" id="frm_type">
								<select onchange="document.frm_type.submit()" name="sl_type" id="sl_type">
									<option value="">Geen</option>
									<?php while($rs_project_type_row = mysql_fetch_assoc($rs_project_type_result)){
										if($type_id == $rs_project_type_row['Project_type_id']){
											echo '<option selected="selected" value="'.$rs_project_type_row['Project_type_id'].'">'.$rs_project_type_row['Type'].'</option>';
										}else{
											echo '<option value="'.$rs_project_type_row['Project_type_id'].'">'.$rs_project_type_row['Type'].'</option>';
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
						<td width="178">Naam</td>
						<td width="265">Aanmelddatum</td>
						<td width="269">Laatst gezien</td>
						<td width="269">IP</td>
						<td width="191">Bevestigd</td>
						<td width="120">Geblokkeerd</td>
					</tr>
					<?php $i=0; while($rs_user_all_row = mysql_fetch_assoc($rs_user_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php echo $rs_user_all_row['Name']; ?></td>
						<td><?php echo $rs_user_all_row['Create_date']; ?></td>
						<td><?php if(($rs_user_all_row['t_date']+60) >= time()){ echo "Online"; }else if($rs_user_all_row['Create_date'] == $rs_user_all_row['Timestamp_date']){ echo "Nooit"; }else{ echo $rs_user_all_row['Timestamp_date']; } ?></td>
						<td><?php echo $rs_user_all_row['IP']; ?></td>
						<td><?php if($rs_user_all_row['Confirmed'] == 'Y'){ echo "Ja"; }else{ echo "Nee"; } ?></td>
						<td><?php if($rs_user_all_row['Banned'] == 'Y'){ echo "Ja"; }else{ echo "Nee"; } ?></td>
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