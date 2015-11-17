<?php
# Project data id
$project_id = mysql_real_escape_string($_GET['r_id']);

# Project profit
$rs_profit_qry = sprintf("SELECT p.* FROM tbl_project_profit p JOIN tbl_project u ON u.Project_id=p.Project_id WHERE p.Project_id='%s' AND u.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_profit_row =  mysql_fetch_assoc(mysql_query($rs_profit_qry));

# Update calculate salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_sa_id'])){
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$salary_id = mysql_real_escape_string($_POST['fld_upd_sa_id']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$date = mysql_real_escape_string($_POST['fld_date']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0 || empty($amount)){
		$amount = 0;
	}

	if($price < 0 || empty($price)){
		$price = 0;
	}

	if(!$error_message){
		$rs_update_salary = sprintf("UPDATE tbl_project_calc_sec_salary SET Date=STR_TO_DATE('%s', '%%d-%%m-%%Y'), Price='%s', Amount='%s', Comment='%s' WHERE Project_calc_sec_salary_id='%s'", $date, $price, $amount, $comment, $salary_id);
		mysql_query($rs_update_salary) or die("Error: " . mysql_error());
		
		$rs_update_hour_qry = sprintf("SELECT Hour_id FROM tbl_project_calc_sec_salary WHERE project_id=%d AND Project_calc_sec_salary_id=%d", $project_id, $salary_id);
		$rs_update_hour_row = mysql_fetch_assoc(mysql_query($rs_update_hour_qry));
		
		$date2 = (strtotime(mysql_real_escape_string($date)) + 3600);
		
		$rs_update_hour = sprintf("UPDATE tbl_project_calc_hour SET Date=%d, Amount='%s', Comment='%s' WHERE Project_hour_id='%s'", $date2, $amount, $comment, $rs_update_hour_row['Hour_id']);
		mysql_query($rs_update_hour) or die("Error: " . mysql_error());
	}
}

# Update material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_ca_id'])){
	$material_id = mysql_real_escape_string($_POST['fld_upd_ca_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0 || empty($amount)){
		$amount = 0;
	}

	if($price < 0 || empty($price)){
		$price = 0;
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$unit){
		$error_message = "Vul een eenheid in";
	}	

	if(!$error_message){
		$rs_update_material = sprintf("UPDATE tbl_project_calc_material SET Materialtype='%s', Unit='%s', Price='%s', Amount='%s' WHERE Project_calc_material_id='%s'", $type, $unit, $price, $amount, $material_id);
		mysql_query($rs_update_material) or die("Error: " . mysql_error());
	}
}

# Update material sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_sec_ca_id'])){
	$material_id = mysql_real_escape_string($_POST['fld_upd_sec_ca_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 1){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 1){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$unit){
		$error_message = "Vul een eenheid in";
	}	

	if(!$error_message){
		$rs_update_material = sprintf("UPDATE tbl_project_calc_sec_material SET Materialtype='%s', Unit='%s', Price='%s', Amount='%s' WHERE Project_calc_sec_material_id='%s'", $type, $unit, $price, $amount, $material_id);
		mysql_query($rs_update_material) or die("Error: " . mysql_error());
	}
}

# Update physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_ce_id'])){
	$physical_id = mysql_real_escape_string($_POST['fld_upd_ce_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0 || empty($amount)){
		$amount = 0;
	}

	if($price < 0 || empty($price)){
		$price = 0;
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}

	if(!$unit){
		$error_message = "Vul een eenheid in";
	}

	if(!$error_message){
		$rs_update_physical = sprintf("UPDATE tbl_project_calc_physical SET Materialtype='%s', Unit='%s', Price='%s', Amount='%s' WHERE Project_calc_physical_id='%s'", $type, $unit, $price, $amount, $physical_id);
		mysql_query($rs_update_physical) or die("Error: " . mysql_error());
	}
}

# Update physical sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_sec_ce_id'])){
	$physical_id = mysql_real_escape_string($_POST['fld_upd_sec_ce_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 1){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 1){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}

	if(!$unit){
		$error_message = "Vul een eenheid in";
	}

	if(!$error_message){
		$rs_update_physical = sprintf("UPDATE tbl_project_calc_sec_physical SET Materialtype='%s', Unit='%s', Price='%s', Amount='%s' WHERE Project_calc_sec_physical_id='%s'", $type, $unit, $price, $amount, $physical_id);
		mysql_query($rs_update_physical) or die("Error: " . mysql_error());
	}
}

# Delete calculate salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_sa_id'])){
	$del_sa_id = mysql_real_escape_string($_POST['fld_del_sa_id']);

	$rs_sa_delete_qry = sprintf("SELECT * FROM tbl_project_calc_sec_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_sec_salary_id='%s' AND p.User_id='%s'", $del_sa_id, $user_id);
	$rs_sa_delete_res = mysql_query($rs_sa_delete_qry);
	$rs_sa_delete_row = mysql_fetch_assoc($rs_sa_delete_res);

	$rs_sa_all_qry = sprintf("SELECT COUNT(*) FROM tbl_project_calc_sec_salary WHERE Project_id=%d AND Salary_id IS NULL", $project_id);
	$rs_sa_all_res = mysql_query($rs_sa_all_qry);
	$rs_sa_all_row = mysql_fetch_array($rs_sa_all_res);

	$rs_del_sa_qry = sprintf("DELETE hr FROM tbl_project_calc_hour AS hr JOIN tbl_project AS p ON p.Project_id=hr.Project_id WHERE hr.Project_hour_id='%s' AND p.User_id='%s'", $rs_sa_delete_row['Hour_id'], $user_id);
	mysql_query($rs_del_sa_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}

	$rs_del_sa_qry = sprintf("DELETE sa FROM tbl_project_calc_sec_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_sec_salary_id='%s' AND p.User_id='%s'", $rs_sa_delete_row['Project_calc_sec_salary_id'], $user_id);
	mysql_query($rs_del_sa_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}
	
	if($rs_sa_all_row[0] == 1){
		$rs_sa_original_qry = sprintf("SELECT * FROM tbl_project_calc_salary WHERE Project_calc_salary_id = (SELECT Salary_id FROM tbl_project_calc_sec_salary WHERE Project_id=%d AND Salary_id IS NOT NULL AND Price IS NULL AND Amount=0)", $project_id);
		$rs_up_original_row = mysql_fetch_assoc(mysql_query($rs_sa_original_qry));

		$rs_add_salaryxxx = sprintf("UPDATE tbl_project_calc_sec_salary SET Price='%s', Amount='%s' WHERE Project_id=%d AND Salary_id IS NOT NULL", $rs_up_original_row['Price'], $rs_up_original_row['Amount'], $project_id);
		mysql_query($rs_add_salaryxxx) or die("Error: " . mysql_error());	
	}
}

# Delete calculate material sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_sec_ca_id'])){
	$del_ca_id = mysql_real_escape_string($_POST['fld_del_sec_ca_id']);

	$rs_del_ca_qry = sprintf("DELETE ca FROM tbl_project_calc_sec_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_material_id='%s' AND p.User_id='%s'", $del_ca_id, $user_id);
	mysql_query($rs_del_ca_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}
}

# Delete calculate physical sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_sec_ce_id'])){
	$del_ce_id = mysql_real_escape_string($_POST['fld_del_sec_ce_id']);

	$rs_del_ce_qry = sprintf("DELETE ce FROM tbl_project_calc_sec_physical AS ce JOIN tbl_project AS p ON p.Project_id=ce.Project_id WHERE ce.Project_calc_sec_physical_id='%s' AND p.User_id='%s'", $del_ce_id, $user_id);
	mysql_query($rs_del_ce_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}
}

# Move sa up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_sa_id'])){
	$up_sa_id = mysql_real_escape_string($_POST['fld_up_sa_id']);

	$rs_up_this_sa_qry = sprintf("SELECT ca.* FROM tbl_project_calc_sec_salary AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_salary_id='%s' AND p.User_id='%s' LIMIT 1", $up_sa_id, $user_id);
	$rs_up_this_sa_row = mysql_fetch_assoc(mysql_query($rs_up_this_sa_qry));
	$salary_id = $rs_up_this_sa_row['Project_calc_sec_salary_id'];
	
	$rs_up_sa_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_salary AS opr JOIN tbl_project_calc_sec_salary AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_salary_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $salary_id);
	$rs_up_sa_row = mysql_fetch_assoc(mysql_query($rs_up_sa_qry));
	
	if($rs_up_sa_row){
		$rs_up_prev_sa_qry = sprintf("UPDATE tbl_project_calc_sec_salary SET Priority='%s' WHERE Project_calc_sec_salary_id='%s'", $rs_up_this_sa_row['Priority'], $rs_up_sa_row['Project_calc_sec_salary_id']);
		mysql_query($rs_up_prev_sa_qry);
	
		$rs_up_next_sa_qry = sprintf("UPDATE tbl_project_calc_sec_salary SET Priority='%s' WHERE Project_calc_sec_salary_id='%s'", $rs_up_sa_row['Priority'], $rs_up_this_sa_row['Project_calc_sec_salary_id']);
		mysql_query($rs_up_next_sa_qry);
	}
}

# Move sa down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_sa_id'])){
	$down_sa_id = mysql_real_escape_string($_POST['fld_down_sa_id']);

	$rs_down_this_sa_qry = sprintf("SELECT sa.* FROM tbl_project_calc_sec_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_sec_salary_id='240' AND p.User_id='12003' LIMIT 1", $down_sa_id, $user_id);
	$rs_down_this_sa_row = mysql_fetch_assoc(mysql_query($rs_down_this_sa_qry));
	$salary_id = $rs_down_this_sa_row['Project_calc_sec_salary_id'];
	
	$rs_down_sa_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_salary AS opr JOIN tbl_project_calc_sec_salary AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_salary_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $salary_id);
	$rs_down_sa_row = mysql_fetch_assoc(mysql_query($rs_down_sa_qry));
	
	if($rs_down_sa_row){
		$rs_down_prev_sa_qry = sprintf("UPDATE tbl_project_calc_sec_salary SET Priority='%s' WHERE Project_calc_sec_salary_id='%s'", $rs_down_this_sa_row['Priority'], $rs_down_sa_row['Project_calc_sec_salary_id']);
		mysql_query($rs_down_prev_sa_qry);
	
		$rs_down_next_sa_qry = sprintf("UPDATE tbl_project_calc_sec_salary SET Priority='%s' WHERE Project_calc_sec_salary_id='%s'", $rs_down_sa_row['Priority'], $rs_down_this_sa_row['Project_calc_sec_salary_id']);
		mysql_query($rs_down_next_sa_qry);
	}
}

# Move ca up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_ca_id'])){
	$up_ca_id = mysql_real_escape_string($_POST['fld_up_ca_id']);

	$rs_up_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_material_id='%s' AND p.User_id='%s' LIMIT 1", $up_ca_id, $user_id);
	$rs_up_this_ca_row = mysql_fetch_assoc(mysql_query($rs_up_this_ca_qry));
	$material_id = $rs_up_this_ca_row['Project_calc_material_id'];
	
	$rs_up_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_material AS opr JOIN tbl_project_calc_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_material_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $material_id);
	$rs_up_ca_row = mysql_fetch_assoc(mysql_query($rs_up_ca_qry));

	if($rs_up_ca_row){
		$rs_up_prev_ca_qry = sprintf("UPDATE tbl_project_calc_material SET Priority='%s' WHERE Project_calc_material_id='%s'", $rs_up_this_ca_row['Priority'], $rs_up_ca_row['Project_calc_material_id']);
		mysql_query($rs_up_prev_ca_qry);
	
		$rs_up_next_ca_qry = sprintf("UPDATE tbl_project_calc_material SET Priority='%s' WHERE Project_calc_material_id='%s'", $rs_up_ca_row['Priority'], $rs_up_this_ca_row['Project_calc_material_id']);
		mysql_query($rs_up_next_ca_qry);
	}
}

# Move ca down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_ca_id'])){
	$down_ca_id = mysql_real_escape_string($_POST['fld_down_ca_id']);

	$rs_down_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_material_id='%s' AND p.User_id='%s' LIMIT 1", $down_ca_id, $user_id);
	$rs_down_this_ca_row = mysql_fetch_assoc(mysql_query($rs_down_this_ca_qry));
	$material_id = $rs_down_this_ca_row['Project_calc_material_id'];
	
	$rs_down_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_material AS opr JOIN tbl_project_calc_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_material_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $material_id);
	$rs_down_ca_row = mysql_fetch_assoc(mysql_query($rs_down_ca_qry));
	
	if($rs_down_ca_row){
		$rs_down_prev_ca_qry = sprintf("UPDATE tbl_project_calc_material SET Priority='%s' WHERE Project_calc_material_id='%s'", $rs_down_this_ca_row['Priority'], $rs_down_ca_row['Project_calc_material_id']);
		mysql_query($rs_down_prev_ca_qry);
	
		$rs_down_next_ca_qry = sprintf("UPDATE tbl_project_calc_material SET Priority='%s' WHERE Project_calc_material_id='%s'", $rs_down_ca_row['Priority'], $rs_down_this_ca_row['Project_calc_material_id']);
		mysql_query($rs_down_next_ca_qry);
	}
}

# Move ca up sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_sec_ca_id'])){
	$up_ca_id = mysql_real_escape_string($_POST['fld_up_sec_ca_id']);

	$rs_up_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_sec_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_material_id='%s' AND p.User_id='%s' LIMIT 1", $up_ca_id, $user_id);
	$rs_up_this_ca_row = mysql_fetch_assoc(mysql_query($rs_up_this_ca_qry));
	$material_id = $rs_up_this_ca_row['Project_calc_sec_material_id'];
	
	$rs_up_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_material AS opr JOIN tbl_project_calc_sec_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_material_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $material_id);
	$rs_up_ca_row = mysql_fetch_assoc(mysql_query($rs_up_ca_qry));

	if($rs_up_ca_row){
		$rs_up_prev_ca_qry = sprintf("UPDATE tbl_project_calc_sec_material SET Priority='%s' WHERE Project_calc_sec_material_id='%s'", $rs_up_this_ca_row['Priority'], $rs_up_ca_row['Project_calc_sec_material_id']);
		mysql_query($rs_up_prev_ca_qry);
	
		$rs_up_next_ca_qry = sprintf("UPDATE tbl_project_calc_sec_material SET Priority='%s' WHERE Project_calc_sec_material_id='%s'", $rs_up_ca_row['Priority'], $rs_up_this_ca_row['Project_calc_sec_material_id']);
		mysql_query($rs_up_next_ca_qry);
	}
}

# Move ca down sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_sec_ca_id'])){
	$down_ca_id = mysql_real_escape_string($_POST['fld_down_sec_ca_id']);

	$rs_down_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_sec_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_material_id='%s' AND p.User_id='%s' LIMIT 1", $down_ca_id, $user_id);
	$rs_down_this_ca_row = mysql_fetch_assoc(mysql_query($rs_down_this_ca_qry));
	$material_id = $rs_down_this_ca_row['Project_calc_sec_material_id'];
	
	$rs_down_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_material AS opr JOIN tbl_project_calc_sec_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_material_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $material_id);
	$rs_down_ca_row = mysql_fetch_assoc(mysql_query($rs_down_ca_qry));
	
	if($rs_down_ca_row){
		$rs_down_prev_ca_qry = sprintf("UPDATE tbl_project_calc_sec_material SET Priority='%s' WHERE Project_calc_sec_material_id='%s'", $rs_down_this_ca_row['Priority'], $rs_down_ca_row['Project_calc_sec_material_id']);
		mysql_query($rs_down_prev_ca_qry);
	
		$rs_down_next_ca_qry = sprintf("UPDATE tbl_project_calc_sec_material SET Priority='%s' WHERE Project_calc_sec_material_id='%s'", $rs_down_ca_row['Priority'], $rs_down_this_ca_row['Project_calc_sec_material_id']);
		mysql_query($rs_down_next_ca_qry);
	}
}

# Move ce up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_ce_id'])){
	$up_ce_id = mysql_real_escape_string($_POST['fld_up_ce_id']);
	
	$rs_up_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_physical_id='%s' AND p.User_id='%s' LIMIT 1", $up_ce_id, $user_id);
	$rs_up_this_ce_row = mysql_fetch_assoc(mysql_query($rs_up_this_ce_qry));
	$physical_id = $rs_up_this_ce_row['Project_calc_physical_id'];
	
	$rs_up_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_physical AS opr JOIN tbl_project_calc_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_physical_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $physical_id);
	$rs_up_ce_row = mysql_fetch_assoc(mysql_query($rs_up_ce_qry));
	
	if($rs_up_ce_row){
		$rs_up_prev_ce_qry = sprintf("UPDATE tbl_project_calc_physical SET Priority='%s' WHERE Project_calc_physical_id='%s'", $rs_up_this_ce_row['Priority'], $rs_up_ce_row['Project_calc_physical_id']);
		mysql_query($rs_up_prev_ce_qry);

		$rs_up_next_ce_qry = sprintf("UPDATE tbl_project_calc_physical SET Priority='%s' WHERE Project_calc_physical_id='%s'", $rs_up_ce_row['Priority'], $rs_up_this_ce_row['Project_calc_physical_id']);
		mysql_query($rs_up_next_ce_qry);
	}
}

# Move ce down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_ce_id'])){
	$down_ce_id = mysql_real_escape_string($_POST['fld_down_ce_id']);

	$rs_down_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_physical_id='%s' AND p.User_id='%s' LIMIT 1", $down_ce_id, $user_id);
	$rs_down_this_ce_row = mysql_fetch_assoc(mysql_query($rs_down_this_ce_qry));
	$physical_id = $rs_down_this_ce_row['Project_calc_physical_id'];
	
	$rs_down_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_physical AS opr JOIN tbl_project_calc_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_physical_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $physical_id);
	$rs_down_ce_row = mysql_fetch_assoc(mysql_query($rs_down_ce_qry));
	
	if($rs_down_ce_row){
		$rs_down_prev_ce_qry = sprintf("UPDATE tbl_project_calc_physical SET Priority='%s' WHERE Project_calc_physical_id='%s'", $rs_down_this_ce_row['Priority'], $rs_down_ce_row['Project_calc_physical_id']);
		mysql_query($rs_down_prev_ce_qry);
	
		$rs_down_next_ce_qry = sprintf("UPDATE tbl_project_calc_physical SET Priority='%s' WHERE Project_calc_physical_id='%s'", $rs_down_ce_row['Priority'], $rs_down_this_ce_row['Project_calc_physical_id']);
		mysql_query($rs_down_next_ce_qry);
	}
}

# Move ce up sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_sec_ce_id'])){
	$up_ce_id = mysql_real_escape_string($_POST['fld_up_sec_ce_id']);
	
	$rs_up_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_sec_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_physical_id='%s' AND p.User_id='%s' LIMIT 1", $up_ce_id, $user_id);
	$rs_up_this_ce_row = mysql_fetch_assoc(mysql_query($rs_up_this_ce_qry));
	$physical_id = $rs_up_this_ce_row['Project_calc_sec_physical_id'];
	
	$rs_up_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_physical AS opr JOIN tbl_project_calc_sec_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_physical_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $physical_id);
	$rs_up_ce_row = mysql_fetch_assoc(mysql_query($rs_up_ce_qry));
	
	if($rs_up_ce_row){
		$rs_up_prev_ce_qry = sprintf("UPDATE tbl_project_calc_sec_physical SET Priority='%s' WHERE Project_calc_sec_physical_id='%s'", $rs_up_this_ce_row['Priority'], $rs_up_ce_row['Project_calc_sec_physical_id']);
		mysql_query($rs_up_prev_ce_qry);

		$rs_up_next_ce_qry = sprintf("UPDATE tbl_project_calc_sec_physical SET Priority='%s' WHERE Project_calc_sec_physical_id='%s'", $rs_up_ce_row['Priority'], $rs_up_this_ce_row['Project_calc_sec_physical_id']);
		mysql_query($rs_up_next_ce_qry);
	}
}

# Move ce down sec
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_sec_ce_id'])){
	$down_ce_id = mysql_real_escape_string($_POST['fld_down_sec_ce_id']);

	$rs_down_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_sec_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_sec_physical_id='%s' AND p.User_id='%s' LIMIT 1", $down_ce_id, $user_id);
	$rs_down_this_ce_row = mysql_fetch_assoc(mysql_query($rs_down_this_ce_qry));
	$physical_id = $rs_down_this_ce_row['Project_calc_sec_physical_id'];
	
	$rs_down_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_sec_physical AS opr JOIN tbl_project_calc_sec_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_sec_physical_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $physical_id);
	$rs_down_ce_row = mysql_fetch_assoc(mysql_query($rs_down_ce_qry));
	
	if($rs_down_ce_row){
		$rs_down_prev_ce_qry = sprintf("UPDATE tbl_project_calc_sec_physical SET Priority='%s' WHERE Project_calc_sec_physical_id='%s'", $rs_down_this_ce_row['Priority'], $rs_down_ce_row['Project_calc_sec_physical_id']);
		mysql_query($rs_down_prev_ce_qry);
	
		$rs_down_next_ce_qry = sprintf("UPDATE tbl_project_calc_sec_physical SET Priority='%s' WHERE Project_calc_sec_physical_id='%s'", $rs_down_ce_row['Priority'], $rs_down_this_ce_row['Project_calc_sec_physical_id']);
		mysql_query($rs_down_next_ce_qry);
	}
}

# Add salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_cs_id'])){
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$date = mysql_real_escape_string($_POST['fld_date']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_cs_id']);
	$amount = str_replace(',', '.', $amount);
	
	if($amount < 1){
		$error_message = "Vul een positieve hoeveelheid in";
	}

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_sec_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
		
		$rs_calc_sal_tax_qry = sprintf("SELECT tax_id FROM tbl_project_calc_salary WHERE project_id=%d AND operation_id=%d AND invoice_id=40 LIMIT 1", $project_id, $operation_id);
		$rs_calc_sal_tax_row =  mysql_fetch_array(mysql_query($rs_calc_sal_tax_qry));
		$tax_id = $rs_calc_sal_tax_row[0];

		$date_unix = (strtotime($date) + 3600);
		$rs_add_hour = sprintf("INSERT INTO tbl_project_calc_hour (Create_date, Project_id, Operation_id, Tax_id, Date, Amount, More_work, Comment) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '3', '%s')", $project_id, $operation_id, $tax_id, $date_unix, $amount, $comment);
		mysql_query($rs_add_hour) or die("Error: " . mysql_error());
		
		$rs_last_hour_qry = sprintf("SELECT Project_hour_id FROM tbl_project_calc_hour WHERE Project_id='%s' ORDER BY Project_hour_id DESC LIMIT 1", $project_id);
		$rs_last_hour_row =  mysql_fetch_assoc(mysql_query($rs_last_hour_qry));
		
		$rs_hour_qry = sprintf("SELECT cm.Price FROM tbl_project_calc_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND cm.Invoice_id=40 AND p.User_id='%s' AND cm.Operation_id='%s' LIMIT 1", $project_id, $user_id, $operation_id);
		$rs_hour_row =  mysql_fetch_assoc(mysql_query($rs_hour_qry));
		
		$rs_add_salary = sprintf("INSERT INTO tbl_project_calc_sec_salary (Create_date, Hour_id, Project_id, Invoice_id, Operation_id, Tax_id, Date, Price, Amount, Priority, Comment) VALUES (NOW(), '%s', '%s', '40', '%s', '%s', STR_TO_DATE('%s', '%%d-%%m-%%Y' ), '%s', '%s', '%s', '%s')", $rs_last_hour_row['Project_hour_id'], $project_id, $operation_id, $tax_id, $date, $rs_hour_row['Price'], $amount, ($rs_prio_cm_row['Priority']+1), $comment);
		mysql_query($rs_add_salary) or die("Error: " . mysql_error());
		
		$rs_add_salary = sprintf("UPDATE tbl_project_calc_sec_salary SET Price=NULL, Amount=0 WHERE Project_id=%d AND Salary_id IS NOT NULL", $project_id);
		mysql_query($rs_add_salary) or die("Error: " . mysql_error());
	}
}

# Add material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ca_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ca_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 1){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 1){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$unit){
		$error_message = "Vul een eenheid in";
	}	

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_sec_material AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
	
		$rs_add_material = sprintf("INSERT INTO tbl_project_calc_sec_material (Create_date, Materialtype, Unit, Project_id, Invoice_id, Operation_id, Tax_id, Price, Amount, DB_chain, Priority) VALUES (NOW(), '%s', '%s', '%s', '40', '%s', '%s', '%s', '%s', 'N', '%s')", $type, $unit, $project_id, $operation_id, $tax_id, $price, $amount, ($rs_prio_cm_row['Priority']+1));
		mysql_query($rs_add_material) or die("Error: " . mysql_error());
	}
}

# Add physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ce_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ce_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 1){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 1){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}

	if(!$unit){
		$error_message = "Vul een eenheid in";
	}

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_sec_physical AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
	
		$rs_add_physical = sprintf("INSERT INTO tbl_project_calc_sec_physical (Create_date, Materialtype, Unit, Project_id, Invoice_id, Operation_id, Tax_id, Price, Amount, DB_chain, Priority) VALUES (NOW(), '%s', '%s', '%s', '40', '%s', '%s', '%s', '%s', 'N', '%s')", $type, $unit, $project_id, $operation_id, $tax_id, $price, $amount, ($rs_prio_cm_row['Priority']+1));
		mysql_query($rs_add_physical) or die("Error: " . mysql_error());
	}
}

# Submited user data
$ft_chapter_id = mysql_real_escape_string($_POST['slt_chapter']);

if($ft_chapter_id){
	$ft_chapter_qry = sprintf("AND c.Project_chapter_id='%s'", $ft_chapter_id);
}

# Select all chapters for this project
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project AS p JOIN tbl_project_chapter AS c ON c.Project_id=p.Project_id WHERE p.User_id='%s' AND c.Project_id='%s' %s ORDER BY Priority ASC", $user_id, $project_id, $ft_chapter_qry);
$rs_project_work_result = mysql_query($rs_project_work_qry);
$rs_project_work_num = mysql_num_rows($rs_project_work_result);

# All chapters for filter
$rs_filter_chapter_result = mysql_query(sprintf("SELECT c.* FROM tbl_project AS p JOIN tbl_project_chapter AS c ON c.Project_id=p.Project_id WHERE p.User_id='%s' AND c.Project_id='%s' ORDER BY Priority ASC", $user_id, $project_id));

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<?php if(!$hide_page){ ?>
<script type="text/javascript">
	var returnTo;

    function databaseWindow(returnForm){
        var w = window.open('/maintoolv2/material-mgr/','','width=800,height=600,scrollbars=yes,toolbar=no,location=no');
		returnTo = returnForm;
        w.focus();
    }
 
    function setDatabaseResult(RSType, RSUnit, RSPrice){
        returnTo.fld_type.value = RSType;
		returnTo.fld_unit.value = RSUnit;
		returnTo.fld_price.value = RSPrice;
        window.focus();
    }
	function checkToggle(repl, toggle)
	{
		if ($(repl).css('display') == 'none') {
			$(repl).show("slow");
			$(toggle).text('[-]');
		}else{
			$(repl).hide("slow");
			$(toggle).text('[+]');
		}
	}
</script>
<script>
$(function(){
	$(".date").datepicker({ dateFormat: "dd-mm-yy", dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ], monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ] });
});
</script>
		<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=142&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Uittrekstaat" />
		</div>
		<span> Stelposten stellen</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic">
		<div class="tt-title"><?php echo $__tooltip['Title']; ?></div>
		</span></a><a class="tooltip" href="javascript:void(0)"><span class="classic">		<?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div><br>
					<table>
						<tr>
							<td class="tbl-head">Filter hoofdstuk</td>
							<td class="tbl-head">
								<form action="" method="post" name="frm_ft_chapter">
									<select onchange="document.frm_ft_chapter.submit()" name="slt_chapter" id="slt_chapter">
										<option value="">Geen</option>
										<?php while($rs_filter_chapter_row = mysql_fetch_assoc($rs_filter_chapter_result)){
											if($ft_chapter_id == $rs_filter_chapter_row['Project_chapter_id']){
												echo '<option selected="selected" value="'.$rs_project_status_row['Project_status_id'].'">'.$rs_filter_chapter_row['Chapter'].'</option>';
											}else{
												echo '<option value="'.$rs_filter_chapter_row['Project_chapter_id'].'">'.$rs_filter_chapter_row['Chapter'].'</option>';
											}
										} ?>
									</select>
								</form>
							</td>
						</tr>
					</table>
					<?php if($rs_project_work_num){ ?>
					<?php $i=0; while($rs_project_work_row = mysql_fetch_assoc($rs_project_work_result)){ $i++;
						# Select all operations for this project
						$rs_project_work_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=40 ORDER BY Priority ASC", $rs_project_work_row['Project_chapter_id']);
						$rs_project_work_op_result = mysql_query($rs_project_work_op_qry);
						$rs_project_work_op_num = mysql_num_rows($rs_project_work_op_result);
					?>
					<table id="tbl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" width="100%" border="0">
						<tr class="tbl-head">
							<td width="16">&nbsp;</td>
							<td width="16">&nbsp;</td>
							<td><div style="padding-left:2px;"><?php echo $rs_project_work_row['Chapter']; ?></div>
							</td>
							<td>&nbsp;</td>
							<td width="50">&nbsp;</td>
						</tr>
						<tr class="tbl-subhead">
							<td width="16">&nbsp;</td>
							<td width="16">&nbsp;</td>
							<td>Uit te voeren werkzaamheden</td>
							<td>Omschrijving werkzaamheden voor op de offerte</td>
							<td width="50">Bewerken</td>
						</tr>
						<?php if($rs_project_work_op_num){ ?>
						<?php while($rs_project_work_op_row = mysql_fetch_assoc($rs_project_work_op_result)){
							$rs_calc_salary_qry = sprintf("SELECT cs.Project_calc_sec_salary_id, DATE_FORMAT(cs.Date, '%%d-%%m-%%Y') AS Date, cs.Price, cs.Amount, cs.Comment, cs.Tax_id, cs.Salary_id FROM tbl_project_calc_sec_salary AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id WHERE cs.Project_id='%s' AND cs.Invoice_id='40' AND cs.Operation_id='%s' AND p.User_id='%s' AND Price IS NOT NULL ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_salary_result = mysql_query($rs_calc_salary_qry) or die("Error: " . mysql_error());
							$rs_calc_salary_num = mysql_num_rows($rs_calc_salary_result);
							
							$rs_calc_salary_new_qry = sprintf("SELECT COUNT(*) as total FROM tbl_project_calc_sec_salary AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id WHERE cs.Project_id=%d AND cs.Invoice_id='40' AND cs.Operation_id='%s' AND p.User_id='%s' AND Salary_id IS NULL", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_salary_new_row = mysql_fetch_assoc(mysql_query($rs_calc_salary_new_qry));
							
							$rs_hour_qry = sprintf("SELECT cm.Price FROM tbl_project_calc_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND cm.Invoice_id=40 AND p.User_id='%s' AND cm.Operation_id='%s' LIMIT 1", $project_id, $user_id, $rs_project_work_op_row['Project_operation_id']);
							$rs_hour_row =  mysql_fetch_assoc(mysql_query($rs_hour_qry));

							$rs_calc_material_sec_qry = sprintf("SELECT cm.Project_calc_sec_material_id, cm.Materialtype, cm.Unit, cm.Price, cm.Amount, cm.Tax_id, cm.Material_id, t.Tax FROM tbl_project_calc_sec_material AS cm JOIN tbl_tax AS t ON t.Tax_id=cm.Tax_id JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE cm.Project_id='%s' AND cm.Invoice_id='40' AND cm.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_material_sec_result = mysql_query($rs_calc_material_sec_qry) or die("Error: " . mysql_error());
							$rs_calc_material_sec_num = mysql_num_rows($rs_calc_material_sec_result);
							
							$rs_calc_physical_sec_qry = sprintf("SELECT cp.Project_calc_sec_physical_id, cp.Materialtype, cp.Unit, cp.Price, cp.Amount, cp.Tax_id, cp.Physical_id, t.Tax FROM tbl_project_calc_sec_physical AS cp JOIN tbl_tax AS t ON t.Tax_id=cp.Tax_id JOIN tbl_project AS p ON p.Project_id=cp.Project_id WHERE cp.Project_id='%s' AND cp.Invoice_id='40' AND cp.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_physical_sec_result = mysql_query($rs_calc_physical_sec_qry) or die("Error: " . mysql_error());
							$rs_calc_physical_sec_num = mysql_num_rows($rs_calc_physical_sec_result);
							
							$rs_total_qry = sprintf("SELECT * FROM tvw_total_mod_7 WHERE Project_id='%s' AND Project_operation_id='%s' AND User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_total_result = mysql_query($rs_total_qry) or die("Error: " . mysql_error());
							$rs_total_row = mysql_fetch_assoc($rs_total_result);
						?>
						<tr class="tbl-operation">
							<td><a style="display:" href="javascript:void(0);" id="tgl_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" onClick="checkToggle('.t_total_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a></td>
							<td>&nbsp;</td>
							<form name="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
								<td><?php echo $rs_project_work_op_row['Operation']; ?></td>
								<td><?php echo $rs_project_work_op_row['Description']; ?>
								</td>
							</form>
							<td align="center">&nbsp;</td>
						</tr>
						<tr style="display:" class="t_total_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
							<td colspan="2"></td>
							<td colspan="3">
								<table width="100%">
									<tr>
										<td width="9%"><a href="javascript:void(0);" onClick="checkToggle('.t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Arbeid</b></td>
										<td width="13%" bgcolor="#CCCCCC" align="center"><b>Datum</b></td>
										<td width="10%" bgcolor="#CCCCCC" align="center"><b>Tarief</b></td>
										<td width="13%" bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td width="10%" bgcolor="#CCCCCC" align="center"><b>Arbeidsuren</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Arbeidskosten</b></td>
										<td width="16%" bgcolor="#CCCCCC" align="center"><b>Opmerking</b></td>
										<td width="6%" bgcolor="#CCCCCC" align="center"><b>BTW</b></td>
										<td width="4%">&nbsp;</td>
										<td width="3%">&nbsp;</td>
									</tr>
									<?php
									while($rs_calc_salary_row = mysql_fetch_assoc($rs_calc_salary_result)){
										if((!empty($rs_calc_salary_row['Salary_id']))&&$rs_calc_salary_new_row['total']){
											//continue;
										}
									?>
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_sa_upd_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_sa_upd_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" id="frm_sa_upd_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" action="" method="post">
										<td>
										<?php if(empty($rs_calc_salary_row['Salary_id'])){ ?>
											<input autocomplete="off" style="width:99%" class="date" type="text" name="fld_date" id="fld_date_<?php echo $rs_project_work_op_row['Project_operation_id'].'_'.$rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" value="<?php echo $rs_calc_salary_row['Date']; ?>" />
										<?php }else{ echo 'Gecalculeerd'; } ?>
										</td>
										<td align="center">&euro;&nbsp;<?php echo number_format($rs_calc_salary_row['Price'], 2, ',', '.'); ?>
										<input type="hidden" name="fld_price" id="fld_price" value="<?php echo $rs_calc_salary_row['Price']; ?>" />
										</td>
										<td align="center">per uur</td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount" value="<?php echo str_replace('.', ',', $rs_calc_salary_row['Amount']); ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_salary_row['Price']*$rs_calc_salary_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_sa_id" id="fld_upd_sa_id" value="<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" /></td>
										<td align="right">
										<?php if(empty($rs_calc_salary_row['Salary_id'])){ ?>
											<input style="width:99%" type="text" name="fld_comment" id="fld_comment" value="<?php echo $rs_calc_salary_row['Comment']; ?>" />
										<?php }else{ echo '-'; } ?>
										</td>
										<td align="center">
											<!--<select name="slt_tax" id="slt_tax" style="width: 99%;">-->
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == $rs_calc_salary_row['Tax_id']){
													echo $rs_tax_row['Tax'].'%';
												}
											}
											?>
											<!--</select>-->
										</td>
										</form>
										<td align="right">
											<form action="" method="post" name="frm_sa_up_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" id="frm_sa_up_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>">
												<input type="hidden" name="fld_up_sa_id" id="fld_up_sa_id" value="<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" />
											</form>
											<form action="" method="post" name="frm_sa_down_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" id="frm_sa_down_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>">
												<input type="hidden" name="fld_down_sa_id" id="fld_down_sa_id" value="<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_sa_up_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Regel omhoog" title="Regel omhoog" /></a>
											<a href="#" onClick="document.frm_sa_down_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Regel omlaag" title="Regel omlaag" /></a>
										</td>
										<td align="right">
										<?php if(empty($rs_calc_salary_row['Salary_id'])){ ?>
											<form action="" method="post" name="frm_sa_del_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" id="frm_sa_del_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>">
												<input type="hidden" name="fld_del_sa_id" id="fld_del_sa_id" value="<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_sa_del_<?php echo $rs_calc_salary_row['Project_calc_sec_salary_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
										<?php } ?>
										</td>
									</tr>
									<?php } ?>
									<form name="frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="#" onclick="document.frm_cs_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/add.png" width="16" height="16" /></a></td>
										<td><input name="fld_date" class="date" id="fld_date_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" style="width:99%" type="text" /></td>
										<td align="center">&euro;<?php echo number_format($rs_hour_row['Price'], 2, ',', '.'); ?></td>
										<td>&nbsp;</td>
										<td><input name="fld_amount" id="fld_amount" style="width:99%" type="text" /></td>
										<td><input type="hidden" name="fld_op_cs_id" id="fld_op_cs_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" /></td>
										<td><input style="width:99%" type="text" name="fld_comment" id="fld_comment" /></td>
										<td>
											<!--<select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php /*
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == 40){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											} */
											?>
											</select>-->
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									</form>
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><font color="#313131"><b><i>TOTALEN</i></b></font></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Salary_noprofit'], 2, ',', '.'); ?></i></b></font></td>
										<td align="right">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="10">&nbsp;</td>
									</tr>
									<tr>
										<td><a href="javascript:void(0);" onClick="checkToggle('.t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Materiaal</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Materiaalsoort</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Prijs / eenheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Hoeveelheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Totaalprijs excl. winst</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Totaalprijs incl. winst</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>BTW</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<?php
									while($rs_calc_material_sec_row = mysql_fetch_assoc($rs_calc_material_sec_result)){
										if(!empty($rs_calc_material_sec_row['Material_id'])){
											$rs_calc_material_org_qry = sprintf("SELECT Materialtype,Unit,Price,Amount FROM tbl_project_calc_material WHERE project_calc_material_id=%d LIMIT 1", $rs_calc_material_sec_row['Material_id']);
											$rs_calc_material_org_result = mysql_query($rs_calc_material_org_qry) or die("Error: " . mysql_error());
											$rs_calc_material_org_row = mysql_fetch_assoc($rs_calc_material_org_result);
										}
									?>
									<tr style="display:" class="t_material_<?php echo $rs_calc_material_sec_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_ca_upd_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_ca_upd_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" id="frm_ca_upd_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" action="" method="post">
										<td><input style="width:99%" type="text" name="fld_type" id="fld_type_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" value="<?php echo $rs_calc_material_sec_row['Materialtype']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_unit" id="fld_unit_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" value="<?php echo $rs_calc_material_sec_row['Unit']; ?>" /></td>
										<td align="right"><input style="width:99%;text-align:center;" type="text" name="fld_price" id="fld_price_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" value="<?php echo number_format($rs_calc_material_sec_row['Price'], 2, ',', '.'); ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" value="<?php echo $rs_calc_material_sec_row['Amount']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_material_sec_row['Price']*$rs_calc_material_sec_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_sec_ca_id" id="fld_upd_sec_ca_id" value="<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_material_sec_row['Amount']*($rs_calc_material_sec_row['Price']+(($rs_calc_material_sec_row['Price']/100)*$rs_profit_row['4_Profit_material'])), 2, ',', '.'); ?></td>
										<td align="center">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == $rs_calc_material_sec_row['Tax_id']){
													echo $rs_tax_row['Tax'].'%';
												}
											}
											?>
										</td>
										</form>
										<td align="right">
											<form action="" method="post" name="frm_ca_up_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" id="frm_ca_up_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>">
												<input type="hidden" name="fld_up_sec_ca_id" id="fld_up_sec_ca_id" value="<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" />
											</form>
											<form action="" method="post" name="frm_ca_down_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" id="frm_ca_down_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>">
												<input type="hidden" name="fld_down_sec_ca_id" id="fld_down_ca_id" value="<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ca_up_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Regel omhoog" title="Regel omhoog" /></a>
											<a href="#" onClick="document.frm_ca_down_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Regel omlaag" title="Regel omlaag" /></a>
										</td>
										<td align="right">
										<?php if(empty($rs_calc_material_sec_row['Material_id'])){ ?>
											<form action="" method="post" name="frm_ca_del_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" id="frm_ca_del_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>">
												<input type="hidden" name="fld_del_sec_ca_id" id="fld_del_sec_ca_id" value="<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ca_del_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
										<?php }else{ ?>
											<a href="javascript:void(0);" onClick="$('#fld_type_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>').val('<?php echo $rs_calc_material_org_row['Materialtype']; ?>');$('#fld_unit_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>').val('<?php echo $rs_calc_material_org_row['Unit']; ?>');$('#fld_price_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>').val('<?php echo $rs_calc_material_org_row['Price']; ?>');$('#fld_amount_ma_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>').val('<?php echo $rs_calc_material_org_row['Amount']; ?>');document.frm_ca_upd_sec_<?php echo $rs_calc_material_sec_row['Project_calc_sec_material_id']; ?>.submit();"><img src="../../images/change.png" width="16" height="16" alt="Verwijderen" title="Reset" /></a>
										<?php } ?>
										</td>
									</tr>
									<?php } ?>
									<form name="frm_ca_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_ca_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right">
											<a href="#" onclick="document.frm_ca_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/add.png" width="16" height="16" /></a>
											<a href="#" onClick="databaseWindow(document.frm_ca_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>)"><img src="../../images/head_dbase.png" width="18" height="18" /></a>										</td>
										<td><input name="fld_type" id="fld_type" style="width:99%" type="text" /></td>
										<td><input name="fld_unit" id="fld_unit" style="width:99%" type="text" /></td>
										<td><input name="fld_price" id="fld_price" style="width:99%" type="text" /></td>
										<td><input name="fld_amount" id="fld_amount" style="width:99%" type="text" /></td>
										<td><input type="hidden" name="fld_op_ca_id" id="fld_op_ca_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" /></td>
										<td>&nbsp;</td>
										<td>
											<select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == 20){
													continue;
												}
												if($rs_tax_row['Tax_id'] == 40){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											}
											?>
											</select>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									</form>
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><font color="#313131"><b><i>TOTALEN</i></b></font></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Material_noprofit'], 2, ',', '.'); ?></i></b></font></td>
										<td align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Material_profit'], 2, ',', '.'); ?></i></b></font></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="10">&nbsp;</td>
									</tr>
									<tr>
										<td><a href="javascript:void(0);" onClick="checkToggle('.t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a> <b>Materieel</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Materieelsoort</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Eenheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Prijs / eenheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Hoeveelheid</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Totaalprijs excl. winst</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>Totaalprijs incl. winst</b></td>
										<td bgcolor="#CCCCCC" align="center"><b>BTW</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<?php
										while($rs_calc_physical_sec_row = mysql_fetch_assoc($rs_calc_physical_sec_result)){
										if(!empty($rs_calc_physical_sec_row['Physical_id'])){
											$rs_calc_physical_org_qry = sprintf("SELECT Materialtype,Unit,Price,Amount FROM tbl_project_calc_physical WHERE project_calc_physical_id=%d LIMIT 1", $rs_calc_physical_sec_row['Physical_id']);
											$rs_calc_physical_org_result = mysql_query($rs_calc_physical_org_qry) or die("Error: " . mysql_error());
											$rs_calc_physical_org_row = mysql_fetch_assoc($rs_calc_physical_org_result);
										}
									?>
									<tr style="display:" class="t_physical_<?php echo $rs_calc_physical_sec_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_ce_upd_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_ce_upd_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" id="frm_ce_upd_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" action="" method="post">
										<td><input style="width:99%" type="text" name="fld_type" id="fld_type_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" value="<?php echo $rs_calc_physical_sec_row['Materialtype']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_unit" id="fld_unit_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" value="<?php echo $rs_calc_physical_sec_row['Unit']; ?>" /></td>
										<td align="right"><input style="width:99%;text-align:center;" type="text" name="fld_price" id="fld_price_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" value="<?php echo $rs_calc_physical_sec_row['Price']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" value="<?php echo $rs_calc_physical_sec_row['Amount']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_physical_sec_row['Price']*$rs_calc_physical_sec_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_sec_ce_id" id="fld_upd_sec_ce_id" value="<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_physical_sec_row['Amount']*($rs_calc_physical_sec_row['Price']+(($rs_calc_physical_sec_row['Price']/100)*$rs_profit_row['4_Profit_physical'])), 2, ',', '.'); ?></td>
										<td align="center">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == $rs_calc_physical_sec_row['Tax_id']){
													echo $rs_tax_row['Tax'].'%';
												}
											}
											?>
										</td>
										</form>
										<td align="right">
											<form action="" method="post" name="frm_ce_up_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" id="frm_ce_up_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>">
												<input type="hidden" name="fld_up_sec_ce_id" id="fld_up_sec_ce_id" value="<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" />
											</form>
											<form action="" method="post" name="frm_ce_down_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" id="frm_ce_down_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>">
												<input type="hidden" name="fld_down_sec_ce_id" id="fld_down_sec_ce_id" value="<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ce_up_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Regel omhoog" title="Regel omhoog" /></a>
											<a href="#" onClick="document.frm_ce_down_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Regel omlaag" title="Regel omlaag" /></a>
										</td>
										<td align="right">
										<?php if(empty($rs_calc_physical_sec_row['Physical_id'])){ ?>
											<form action="" method="post" name="frm_ce_del_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" id="frm_ce_del_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>">
												<input type="hidden" name="fld_del_sec_ce_id" id="fld_del_sec_ce_id" value="<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ce_del_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
										<?php }else{ ?>
											<a href="javascript:void(0);" onClick="$('#fld_type_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>').val('<?php echo $rs_calc_physical_org_row['Materialtype']; ?>');$('#fld_unit_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>').val('<?php echo $rs_calc_physical_org_row['Unit']; ?>');$('#fld_price_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>').val('<?php echo $rs_calc_physical_org_row['Price']; ?>');$('#fld_amount_ph_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>').val('<?php echo $rs_calc_physical_org_row['Amount']; ?>');document.frm_ce_upd_sec_<?php echo $rs_calc_physical_sec_row['Project_calc_sec_physical_id']; ?>.submit();"><img src="../../images/change.png" width="16" height="16" alt="Verwijderen" title="Reset" /></a>
										<?php } ?>
										</td>
									</tr>
<!---->								<?php } ?>
									<form name="frm_ce_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_ce_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="#" onclick="document.frm_ce_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/add.png" width="16" height="16" /></a><a href="#" onClick="databaseWindow(document.frm_ce_add_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>)"><img src="../../images/head_dbase.png" width="18" height="18" /></a></td>
										<td><input name="fld_type" id="fld_type" style="width:99%" type="text" /></td>
										<td><input name="fld_unit" id="fld_unit" style="width:99%" type="text" /></td>
										<td><input name="fld_price" id="fld_price" style="width:99%" type="text" /></td>
										<td><input name="fld_amount" id="fld_amount" style="width:99%" type="text" /></td>
										<td><input type="hidden" name="fld_op_ce_id" id="fld_op_ce_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" /></td>
										<td>&nbsp;</td>
										<td>
											<select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == 20){
													continue;
												}
												if($rs_tax_row['Tax_id'] == 40){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											}
											?>
											</select>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									</form>
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><font color="#313131"><b><i>TOTALEN</i></b></font></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="16%" align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Physical_noprofit'], 2, ',', '.'); ?></i></b></font></td>
										<td width="12%" align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Physical_profit'], 2, ',', '.'); ?></i></b></font></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="10">&nbsp;</td>
									</tr>
									<tr style="display:" class="t_sum_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td colspan="10">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php } ?>
						<?php } ?>
						<form name="frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" action="" method="post">
						</form>
					</table>
					<br />
					<?php } ?>
					<?php }else{ ?>
					<div style="text-align:center">Dit project bevat nog geen werkzaamheden</div>
					<?php } ?>
</div>
		<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
<?php } ?>