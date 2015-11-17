<?php
# Submited user data
$type_id = mysql_real_escape_string($_POST['sl_type']);

if($type_id){
	$ft_type_qry = sprintf("AND r.Relation_type_id='%s'", $type_id);
}

# All relations query
$rs_relation_all_qry = sprintf("SELECT r.Relation_id, r.Company_name, r.Contact_name, r.Contact_first_name, r.Address, r.Address_number, r.Phone_1, r.Phone_2,  r.Email_1, r.City, r.Zipcode, r.Relation_business_type_id, rt.`Type` FROM tbl_relation r JOIN tbl_relation_type rt ON rt.Relation_type_id = r.Relation_type_id WHERE r.User_id='%s' AND r.Relation_id > 0 %s", $user_id, $ft_type_qry);
$rs_relation_all_result = mysql_query($rs_relation_all_qry) or die("Error: " . mysql_error());
$rs_relation_all_num = mysql_num_rows($rs_relation_all_result);

# All relation types query
$rs_relation_type_all_result = mysql_query("SELECT * FROM tbl_relation_type") or die("Error: " . mysql_error());
?>
<div id="page-bgtop">
<div id="title">
	<div style="float: right; font-size: 20px;">
		<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=106'" type="button" value="Nieuwe relatie" />
	</div>
	<span>Relaties</span>
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
					<tr>
						<td class="tbl-head">Type</td>
						<td>
							<form action="" method="post" name="frm_type" id="frm_type">
								<select onchange="document.frm_type.submit()" name="sl_type" id="sl_type">
									<option value="">Geen</option>
									<?php while($rs_relation_type_all_row = mysql_fetch_assoc($rs_relation_type_all_result)){
										if($type_id == $rs_relation_type_all_row['Relation_type_id']){
											echo '<option selected="selected" value="'.$rs_relation_type_all_row['Relation_type_id'].'">'.$rs_relation_type_all_row['Type'].'</option>';
										}else{
											echo '<option value="'.$rs_relation_type_all_row['Relation_type_id'].'">'.$rs_relation_type_all_row['Type'].'</option>';
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
					<tr>
						<td class="tbl-head">Provincie</td>
						<td>
							<form action="" method="post" name="frm_state" id="frm_state">
								<select name="sl_state" id="sl_state">
									<option value="">Geen</option>
									<?php while($rs_relation_type_all_row = mysql_fetch_assoc($rs_relation_type_all_result)){
										if($type_id == $rs_relation_type_all_row['Relation_type_id']){
											echo '<option selected="selected" value="'.$rs_relation_type_all_row['Relation_type_id'].'">'.$rs_relation_type_all_row['Type'].'</option>';
										}else{
											echo '<option value="'.$rs_relation_type_all_row['Relation_type_id'].'">'.$rs_relation_type_all_row['Type'].'</option>';
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
						<td width="178">(Bedrijfs)naam</td>
						<td width="265">Relatietype</td>
						<td width="265">Contactpersoon</td>
						<td width="269">Telefoon</td>
						<td width="191">Email</td>
						<td width="120">Plaats</td>
					</tr>
					<?php if($rs_relation_all_num){ ?>
					<?php $i=0; while($rs_relation_all_row = mysql_fetch_assoc($rs_relation_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><a href="?p_id=102&r_id=<?php echo $rs_relation_all_row['Relation_id']; ?>"><?php if($rs_relation_all_row['Relation_business_type_id'] == 20){ echo $rs_relation_all_row['Company_name']; }else{ echo $rs_relation_all_row['Contact_first_name'].' '.$rs_relation_all_row['Contact_name']; } ?></a></td>
						<td><?php if($rs_relation_all_row['Relation_business_type_id'] == 20){ echo $rs_relation_all_row['Type']; }else{ echo 'Particulier'; } ?></td>
						<td><?php echo $rs_relation_all_row['Contact_first_name'].' '.$rs_relation_all_row['Contact_name']; ?></td>
						<td><?php if($rs_relation_all_row['Phone_1']){ echo $rs_relation_all_row['Phone_1']; }else{ echo $rs_relation_all_row['Phone_2']; } ?></td>
						<td><a href="mailto:<?php echo $rs_relation_all_row['Email_1']; ?>"><?php echo $rs_relation_all_row['Email_1']; ?></a></td>
						<td><?php echo $rs_relation_all_row['City']; ?></td>
					</tr>
					<?php } ?>
					<?php }else{ ?>
					<tr>
						<td colspan="6" align="center">Geen relaties gevonden</td>
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
mysql_free_result($rs_relation_type_all_result);
mysql_free_result($rs_relation_all_result);
?>