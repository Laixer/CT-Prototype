<?php
#cleanup todo

# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# For this page only
mysql_select_db($conn_db_material_name);

# Submited user data
$subgroup_id = mysql_real_escape_string($_POST['slt_subgroup']);
$search = mysql_real_escape_string($_POST['fld_search']);

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

# All material query
$rs_material_all_qry = sprintf("SELECT * FROM tbl_material AS m JOIN tbl_material_unit AS u ON u.Material_unit_id=m.Unit_id JOIN tbl_material_unit_price AS p ON p.Material_unit_price_id=m.Unit_price_id WHERE 1=1 %s %s", $ft_subgroup_qry, $ft_search_qry);
$rs_material_all_result = mysql_query($rs_material_all_qry) or die("Error: " . mysql_error());
$rs_material_all_num = mysql_num_rows($rs_material_all_result);

# All project status query
//$rs_project_status_result = mysql_query("SELECT * FROM tbl_project_status") or die("Error: " . mysql_error());

# All material subgroup query
$rs_material_subgroup_result = mysql_query("SELECT * FROM tbl_material_subgroup") or die("Error: " . mysql_error());

//$warn_message = "Deze pagina is niet gekoppeld";

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Materialen database</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<script type="text/javascript">
    function returnToCalc(title, punit, price){
        opener.setDatabaseResult(title, punit, price);
        window.close();
    }
</script>
<body>
<div style="background-color:#FFF">
	<div style="margin: 0 auto; width: 770px;">
		<div class="entry">
		<?php if($error_message){ echo '<div class="error">'.$error_message.'</div>'; } ?>
			<div id="title">Materialen database</div>
					<table width="100%" border="0">
					<tr class="tbl-head">
						<td width="120">Filter subgroep</td>
						<td width="616" colspan="4">
							<form action="" method="post" name="frm_subgroup" id="frm_subgroup">
								<select onchange="document.frm_subgroup.submit()" name="slt_subgroup" id="slt_subgroup">
									<option value="">Geen</option>
								</select>
							</form>
						</td>
					</tr>
					<tr class="tbl-head">
						<td>Filter overeenkomst</td>
						<td colspan="4">
							<form action="" method="post" name="frm_search" id="frm_search">
								<input name="fld_search" type="text" id="fld_search" size="50" />
								<input type="submit" name="btn_submit" id="btn_submit" value="Zoek" style="height:21px" />
							</form>
						</td>
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
						<td colspan="2"><a style="color: #333333;" href="#" onclick="returnToCalc('<?php echo $rs_material_all_row['Description']; ?>', '<?php echo $rs_material_all_row['Unit_price']; ?>', '<?php echo number_format($rs_material_all_row['Price'], 2, ',', ''); ?>');"><?php echo $rs_material_all_row['Description']; ?></a></td>
						<td align="center"><?php if($rs_material_all_row['Packaging_length']){ echo $rs_material_all_row['Packaging_length']; }else{ echo "-"; } ?></td>
						<td align="center"><?php echo $rs_material_all_row['Unit_price']; ?></td>
						<td align="right">&euro;<?php echo number_format($rs_material_all_row['Price'], 2, ',', '.'); ?></td>
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
		<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
</div>
</div>
</div>
</body>
</html>