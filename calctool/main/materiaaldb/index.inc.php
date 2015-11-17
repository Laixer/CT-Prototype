<?php
# For this page only
mysql_select_db($conn_db_material_name);

# Submited user data
$subgroup_id = mysql_real_escape_string($_POST['slt_subgroup']);
$search = mysql_real_escape_string($_POST['fld_search']);
$supplier = mysql_real_escape_string($_GET['s_id']);

if(($subgroup_id)||($search)){
	if($subgroup_id){
		$ft_subgroup_qry = sprintf("AND Subgroup_id='%s'", $subgroup_id);
	}
	if($search){
		if(strlen($search) > 2){
			$ft_search_qry = "AND Description LIKE '%".$search."%'";
		}else{
			$warn_message = "Zoekopdracht moet mimimaal 3 letters bevatten";
			$ft_search_qry = "AND FALSE";
		}
	}
}else{
	$ft_subgroup_qry = "AND FALSE";
}

if($supplier){
	$ft_suppier = $supplier;
}else{
	$ft_suppier = 2;
}
# All material query
$rs_material_all_qry = sprintf("SELECT * FROM tbl_material AS m JOIN tbl_material_unit AS u ON u.Material_unit_id=m.Unit_id JOIN tbl_material_unit_price AS p ON p.Material_unit_price_id=m.Unit_price_id WHERE 1=1 AND Supplier_id='%s' %s %s", $supplier, $ft_subgroup_qry, $ft_search_qry);
$rs_material_all_result = mysql_query($rs_material_all_qry) or die("Error: " . mysql_error());
$rs_material_all_num = mysql_num_rows($rs_material_all_result);

# All project status query
//$rs_project_status_result = mysql_query("SELECT * FROM tbl_project_status") or die("Error: " . mysql_error());

# All material subgroup query
$rs_material_subgroup_result = mysql_query("SELECT * FROM tbl_material_subgroup") or die("Error: " . mysql_error());

$rs_supplier_result = mysql_query("SELECT * FROM tbl_supplier") or die("Error: " . mysql_error());

//$warn_message = "Deze pagina is niet gekoppeld";

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<div style="float:right">
				<?php while($rs_supplier_row = mysql_fetch_assoc($rs_supplier_result)){ ?>
					<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=107&s_id=<?php echo $rs_supplier_row['Supplier_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="<?php echo $rs_supplier_row['Name']; ?>" />
				<?php } ?>
				</div>
				<span>Materialen Database</span>
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
						<td width="120">Filter subgroep</td>
						<td width="616">
							<form action="" method="post" name="frm_subgroup" id="frm_subgroup">
								<select onchange="document.frm_subgroup.submit()" name="slt_subgroup" id="slt_subgroup">
									<option value="">Geen</option>
									<?php while($rs_material_subgroup_row = mysql_fetch_assoc($rs_material_subgroup_result)){
										if($subgroup_id == $rs_material_subgroup_row['Material_subgroup_id']){
											echo '<option selected="selected" value="'.$rs_material_subgroup_row['Material_subgroup_id'].'">'.$rs_material_subgroup_row['Subgroup'].'</option>';
										}else{
											echo '<option value="'.$rs_material_subgroup_row['Material_subgroup_id'].'">'.$rs_material_subgroup_row['Subgroup'].'</option>';
										}
									} ?>
								</select>
							</form>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>Filter overeenkomst</td>
						<td>
							<form action="" method="post" name="frm_search" id="frm_search">
								<input name="fld_search" type="text" id="fld_search" size="50" />
								<input type="submit" name="btn_submit" id="btn_submit" value="Zoek" style="height:21px" />
							</form>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td colspan="2">Product <?php if($rs_material_all_num){ echo '('.$rs_material_all_num.' gevonden)'; } ?></td>
						<td width="112" align="center">Verpakkingslengte</td>
						<td width="110" align="center">Per prijseenheid</td>
						<td width="66" align="center">Prijs</td>
					</tr>
					<?php if($rs_material_all_num){ ?>
					<?php $i=0; while($rs_material_all_row = mysql_fetch_assoc($rs_material_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2"><a href="?p_id=23&r_id=<?php echo $rs_material_all_row['Material_id']; ?>"><?php echo $rs_material_all_row['Description']; ?></a></td>
						<td align="center"><?php if($rs_material_all_row['Packaging_length']){ echo $rs_material_all_row['Packaging_length']; }else{ echo "-"; } ?></td>
						<td align="center"><?php echo $rs_material_all_row['Unit_price']; ?></td>
						<td align="right">&euro;&nbsp;<?php echo number_format($rs_material_all_row['Price'], 2, ',', '.'); ?></td>
					</tr>
					<?php } ?>
					<?php }else{ ?>
					<tr>
						<td colspan="5" align="center">Selecteer een filter</td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td colspan="5" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php
mysql_free_result($rs_material_subgroup_result);
?>