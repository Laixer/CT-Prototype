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
$rs_project_all_qry = sprintf("SELECT p.Project_id, p.Name, p.Address, p.Address_number, p.City, p.Zipcode, p.Project_type_id, r.Company_name, t.`Type` FROM tbl_project AS p JOIN tbl_relation AS r ON r.Relation_id=p.Client_relation_id JOIN tbl_project_type AS t ON t.Project_type_id=p.Project_type_id WHERE p.User_id='%s' AND p.Project_id > 0 %s %s ORDER BY p.Timestamp_date DESC", $user_id, $ft_status_qry, $ft_type_qry);
$rs_project_all_result = mysql_query($rs_project_all_qry) or die("Error: " . mysql_error());
$rs_project_all_num = mysql_num_rows($rs_project_all_result);

# All project status query
$rs_project_status_result = mysql_query("SELECT * FROM tbl_project_status") or die("Error: " . mysql_error());

# All project type query
$rs_project_type_result = mysql_query("SELECT * FROM tbl_project_type") or die("Error: " . mysql_error());
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<div style="float: right; font-size: 20px;">
					<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=105'" type="button" value="Nieuw project" />
				</div>
				<span>Projecten</span>
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
						<td width="178">Projectnaam</td>
						<td width="265">Opdrachtgever</td>
						<td width="269">Type</td>
						<td width="269">Adres</td>
						<td width="191">Plaats</td>
						<td width="120">Postcode</td>
					</tr>
					<?php if($rs_project_all_num){ ?>
					<?php $i=0; while($rs_project_all_row = mysql_fetch_assoc($rs_project_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<?php if($rs_project_all_row['Project_type_id'] == 20){ ?>
						<td><a href="?p_id=111&r_id=<?php echo $rs_project_all_row['Project_id']; ?>"><?php echo $rs_project_all_row['Name']; ?></a></td>
						<?php }else if($rs_project_all_row['Project_type_id'] == 10){ ?>
						<td><a href="?p_id=104&r_id=<?php echo $rs_project_all_row['Project_id']; ?>"><?php echo $rs_project_all_row['Name']; ?></a></td>
						<?php } ?>
						<td><?php echo $rs_project_all_row['Company_name']; ?></td>
						<td><?php echo $rs_project_all_row['Type']; ?></td>
						<td><?php echo $rs_project_all_row['Address'].' '.$rs_project_all_row['Address_number']; ?></td>
						<td><?php echo $rs_project_all_row['City']; ?></td>
						<td><?php echo $rs_project_all_row['Zipcode']; ?></td>
					</tr>
					<?php } ?>
					<?php }else{ ?>
					<tr>
						<td colspan="6" align="center">Geen projecten gevonden</td>
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
mysql_free_result($rs_project_status_result);
mysql_free_result($rs_project_type_result);
mysql_free_result($rs_project_all_result);
?>