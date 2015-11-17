<?php
# Project data id
$project_id = mysql_real_escape_string($_GET['r_id']);

# Project profit
$rs_profit_qry = sprintf("SELECT p.* FROM tbl_project_profit p JOIN tbl_project u ON u.Project_id=p.Project_id WHERE p.Project_id='%s' AND u.User_id='%s' LIMIT 1", $project_id, $user_id);
$rs_profit_row =  mysql_fetch_assoc(mysql_query($rs_profit_qry));

# New operation
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_operation'])){
	$operation = mysql_real_escape_string($_POST['fld_operation']);
	$description = mysql_real_escape_string($_POST['fld_description']);
	$chapter = mysql_real_escape_string($_POST['fld_chapter']);

	$rs_exist_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id WHERE c.Project_id='%s' AND o.Operation='%s' LIMIT 1", $project_id, $operation);
	$rs_exist_row = mysql_fetch_assoc(mysql_query($rs_exist_qry));
	
	if($rs_exist_row['TRUE']){
		$error_message = "Er bestaat al een werkzaamheid met deze naam in dit project";
	}else{
		$rs_prio_op_qry = sprintf("SELECT o.Priority FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Chapter_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $chapter, $user_id);
		$rs_prio_op_row = mysql_fetch_assoc(mysql_query($rs_prio_op_qry));
		
		# Check is this is the users chapter
		$rs_project_chap_check_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND c.Project_chapter_id='%s' LIMIT 1", $user_id, $chapter);
		$rs_project_chap_check_result = mysql_query($rs_project_chap_check_qry);
		if(mysql_num_rows($rs_project_chap_check_result)){
			$rs_add_op_qry = sprintf("INSERT INTO tbl_project_operation (Create_date, Chapter_id, Invoice_id, Operation, Description, Priority) VALUES (NOW(), '%s', '70', '%s', '%s', '%s')", $chapter, $operation, $description, ($rs_prio_op_row['Priority']+1));
			mysql_query($rs_add_op_qry) or die("Error: " . mysql_error());
		}
	}
}

# Move operation up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_op_id'])){
	$up_op_id = mysql_real_escape_string($_POST['fld_up_op_id']);

	$rs_up_this_op_qry = sprintf("SELECT o.* FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $up_op_id, $user_id);
	$rs_up_this_op_row = mysql_fetch_assoc(mysql_query($rs_up_this_op_qry));
	$chapter = $rs_up_this_op_row['Chapter_id'];

	$rs_up_op_qry = sprintf("SELECT opr.* FROM tbl_project_operation AS opr JOIN tbl_project_operation AS slct ON slct.Chapter_id=opr.Chapter_id WHERE slct.Project_operation_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $rs_up_this_op_row['Project_operation_id']);
	$rs_up_op_row =  mysql_fetch_assoc(mysql_query($rs_up_op_qry));
	if($rs_up_op_row){
		$rs_up_prev_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_up_this_op_row['Priority'], $rs_up_op_row['Project_operation_id']);
		mysql_query($rs_up_prev_op_qry);
	
		$rs_up_next_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_up_op_row['Priority'], $rs_up_this_op_row['Project_operation_id']);
		mysql_query($rs_up_next_op_qry);
	}
}

# Move operation down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_op_id'])){
	$down_op_id = mysql_real_escape_string($_POST['fld_down_op_id']);

	$rs_down_this_op_qry = sprintf("SELECT o.* FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $down_op_id, $user_id);
	$rs_down_this_op_row = mysql_fetch_assoc(mysql_query($rs_down_this_op_qry));
	$chapter = $rs_down_this_op_row['Chapter_id'];

	$rs_down_op_qry = sprintf("SELECT opr.* FROM tbl_project_operation AS opr JOIN tbl_project_operation AS slct ON slct.Chapter_id=opr.Chapter_id WHERE slct.Project_operation_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $rs_down_this_op_row['Project_operation_id']);
	$rs_down_op_row =  mysql_fetch_assoc(mysql_query($rs_down_op_qry));
	if($rs_down_op_row){
		$rs_down_prev_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_down_this_op_row['Priority'], $rs_down_op_row['Project_operation_id']);
		mysql_query($rs_down_prev_op_qry);
	
		$rs_down_next_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_down_op_row['Priority'], $rs_down_this_op_row['Project_operation_id']);
		mysql_query($rs_down_next_op_qry);
	}

}

# Delete operation
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_op_id'])){
	if(!$rs_project_module7_row){
		$del_op_id = mysql_real_escape_string($_POST['fld_del_op_id']);
		$rs_del_op_qry = sprintf("DELETE o FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s'", $del_op_id, $user_id);
		mysql_query($rs_del_op_qry);
		if(mysql_error()){
			if(mysql_errno() == 1451){
				$error_message = "Er zijn projectgegevens gekoppeld aan deze werkzaamheid";
			}else{
				die("Error: " . mysql_error());
			}
		}
	}
}

# New chapter
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_new_chapter'])){
	$chapter = mysql_real_escape_string($_POST['fld_new_chapter']);

	$rs_exist_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c WHERE c.Project_id='%s' AND c.Chapter='%s' LIMIT 1", $project_id, $chapter);
	$rs_exist_row = mysql_fetch_assoc(mysql_query($rs_exist_qry));
	
	if($rs_exist_row['TRUE']){
		$error_message = "Er bestaat al een hoofdstuk met deze naam in dit project";
	}else{
		//if(!$rs_project_module7_row){
			$rs_prio_chap_qry = sprintf("SELECT c.Priority FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
			$rs_prio_chap_row = mysql_fetch_assoc(mysql_query($rs_prio_chap_qry));
	
			$rs_add_chapter_qry = sprintf("INSERT INTO tbl_project_chapter (Create_date, Project_id, Chapter, Priority) VALUES (NOW(), '%s', '%s', '%s')", $project_id, $chapter, ($rs_prio_chap_row['Priority']+1));
			mysql_query($rs_add_chapter_qry) or die("Error: " . mysql_error());
		//}
	}
}

# Move chapter up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_chap_id'])){
	$up_chap_id = mysql_real_escape_string($_POST['fld_up_chap_id']);

	$rs_up_this_chap_qry = sprintf("SELECT * FROM tbl_project_chapter AS chp JOIN tbl_project AS p ON chp.Project_id=p.Project_id WHERE chp.Project_chapter_id='%s' AND p.User_id='%s' LIMIT 1", $up_chap_id, $user_id);
	$rs_up_this_chap_row =  mysql_fetch_assoc(mysql_query($rs_up_this_chap_qry));

	$rs_up_chap_qry = sprintf("SELECT chp.* FROM tbl_project_chapter AS chp JOIN tbl_project_chapter AS slct ON slct.Project_id=chp.Project_id WHERE slct.Project_chapter_id='%s' AND chp.Priority < slct.Priority ORDER BY chp.Priority DESC LIMIT 1", $rs_up_this_chap_row['Project_chapter_id']);
	$rs_up_chap_row =  mysql_fetch_assoc(mysql_query($rs_up_chap_qry));
	if($rs_up_chap_row){
		$rs_up_prev_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_up_this_chap_row['Priority'], $rs_up_chap_row['Project_chapter_id']);
		mysql_query($rs_up_prev_chap_qry);
	
		$rs_up_next_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_up_chap_row['Priority'], $rs_up_this_chap_row['Project_chapter_id']);
		mysql_query($rs_up_next_chap_qry);
	}

}

# Move chapter down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_chap_id'])){
	$down_chap_id = mysql_real_escape_string($_POST['fld_down_chap_id']);

	$rs_down_this_chap_qry = sprintf("SELECT * FROM tbl_project_chapter AS chp JOIN tbl_project AS p ON chp.Project_id=p.Project_id WHERE chp.Project_chapter_id='%s' AND p.User_id='%s' LIMIT 1", $down_chap_id, $user_id);
	$rs_down_this_chap_row =  mysql_fetch_assoc(mysql_query($rs_down_this_chap_qry));

	$rs_down_chap_qry = sprintf("SELECT chp.* FROM tbl_project_chapter AS chp JOIN tbl_project_chapter AS slct ON slct.Project_id=chp.Project_id WHERE slct.Project_chapter_id='%s' AND chp.Priority > slct.Priority ORDER BY chp.Priority ASC LIMIT 1", $rs_down_this_chap_row['Project_chapter_id']);
	$rs_down_chap_row =  mysql_fetch_assoc(mysql_query($rs_down_chap_qry));
	if($rs_down_chap_row){
		$rs_down_prev_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_down_this_chap_row['Priority'], $rs_down_chap_row['Project_chapter_id']);
		mysql_query($rs_down_prev_chap_qry);
	
		$rs_down_next_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_down_chap_row['Priority'], $rs_down_this_chap_row['Project_chapter_id']);
		mysql_query($rs_down_next_chap_qry);
	}

}

# Delete chapter
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_chap_id'])){
	$del_chap_id = mysql_real_escape_string($_POST['fld_del_chap_id']);
	
	//if(!$rs_project_module7_row){
		$rs_del_op_qry = sprintf("DELETE o FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Chapter_id='%s' AND p.User_id='%s'", $del_chap_id, $user_id);
		mysql_query($rs_del_op_qry);
		if(mysql_error()){
			if(mysql_errno() == 1451){
				$error_message = "Dit hoofdstuk is aan een projecteigenschap gekoppeld";
			}else{
				die("Error: " . mysql_error());
			}
		}
		
		$rs_del_chap_qry = sprintf("DELETE c FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_chapter_id='%s' AND p.User_id='%s'", $del_chap_id, $user_id);
		mysql_query($rs_del_chap_qry);
		if(mysql_error()){
			if(mysql_errno() == 1451){
				$error_message = "Er zijn projectgegevens gekoppeld aan dit hoofdstuk";
			}else{
				die("Error: " . mysql_error());
			}
		}
	//}
}

# Change operation
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_chg_operation'])){
	$operation = mysql_real_escape_string($_POST['fld_chg_operation']);
	$description = mysql_real_escape_string($_POST['fld_chg_description']);
	$operation_id = mysql_real_escape_string($_POST['fld_chg_op_id']);

	# Check is this is the users chapter
	$rs_project_op_check_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND o.Project_operation_id='%s' LIMIT 1", $user_id, $operation_id);
	$rs_project_op_check_result = mysql_query($rs_project_op_check_qry);
	if(mysql_num_rows($rs_project_op_check_result)){
		$rs_chg_op_qry = sprintf("UPDATE tbl_project_operation SET Operation='%s', Description='%s' WHERE Project_operation_id='%s'", $operation, $description, $operation_id);
		mysql_query($rs_chg_op_qry) or die("Error: " . mysql_error());
	}
}

# Change chapter
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_chg_chap'])){
	$chapter = mysql_real_escape_string($_POST['fld_chg_chap']);
	$chapter_id = mysql_real_escape_string($_POST['fld_chg_chap_id']);

	# Check is this is the users chapter
	$rs_project_chap_check_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND c.Project_chapter_id='%s' LIMIT 1", $user_id, $chapter_id);
	$rs_project_chap_check_result = mysql_query($rs_project_chap_check_qry);
	if(mysql_num_rows($rs_project_chap_check_result)){
		$rs_chg_op_qry = sprintf("UPDATE tbl_project_chapter SET Chapter='%s'WHERE Project_chapter_id='%s'", $chapter, $chapter_id);
		mysql_query($rs_chg_op_qry) or die("Error: " . mysql_error());
	}
}

# Update calculate salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_op_id'])){
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$salary_id = mysql_real_escape_string($_POST['fld_upd_sa_id']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$date = mysql_real_escape_string($_POST['fld_date']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_upd_op_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	if(!$error_message){
		$rs_check_qry = sprintf("SELECT project_calc_third_salary_id FROM tbl_project_calc_third_salary WHERE Project_id=%d AND invoice_id=70 AND Operation_id=%d LIMIT 1", $project_id, $operation_id);
		$rs_check_row = mysql_fetch_array(mysql_query($rs_check_qry));
		if(!empty($rs_check_row[0])){
			$rs_update_salary = sprintf("UPDATE tbl_project_calc_third_salary SET Tax_id='%s', Date=STR_TO_DATE('%s', '%%d-%%m-%%Y'), Amount='%s', Price='%s', Comment='%s' WHERE Project_id=%d AND invoice_id=70 AND Operation_id=%d", $tax_id, $date, $amount, $price, $comment, $project_id, $operation_id);
			mysql_query($rs_update_salary) or die("Error: " . mysql_error());
		}else{
			$rs_add_salary = sprintf("INSERT INTO tbl_project_calc_third_salary (Create_date, Hour_id, Project_id, Invoice_id, Operation_id, Tax_id, Date, Price, Amount, Priority, Comment) VALUES (NOW(), NULL, '%s', '70', '%s', '%s', STR_TO_DATE('%s', '%%d-%%m-%%Y' ), '%s', '%s', 1, '%s')", $project_id, $operation_id, $tax_id, $date, $price, $amount, $comment);
			mysql_query($rs_add_salary) or die("Error: " . mysql_error());
		}
	}
}

# Update material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_upd_ca_id'])){
	$material_id = mysql_real_escape_string($_POST['fld_upd_ca_id']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$unit){
		$error_message = "Vul een eenheid in";
	}	

	if(!$error_message){
		$rs_update_material = sprintf("UPDATE tbl_project_calc_third_material SET Materialtype='%s', Unit='%s', Tax_id='%s', Price='%s', Amount='%s' WHERE Project_calc_third_material_id='%s'", $type, $unit, $tax_id, $price, $amount, $material_id);
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
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}

	if(!$unit){
		$error_message = "Vul een eenheid in";
	}

	if(!$error_message){
		$rs_update_physical = sprintf("UPDATE tbl_project_calc_third_physical SET Materialtype='%s', Unit='%s', Tax_id='%s', Price='%s', Amount='%s' WHERE Project_calc_third_physical_id='%s'", $type, $unit, $tax_id, $price, $amount, $physical_id);
		mysql_query($rs_update_physical) or die("Error: " . mysql_error());
	}
}

# Delete calculate salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_sa_id'])){
	$del_sa_id = mysql_real_escape_string($_POST['fld_del_sa_id']);

	$rs_sa_delete_qry = sprintf("SELECT * FROM tbl_project_calc_third_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_third_salary_id='%s' AND p.User_id='%s'", $del_sa_id, $user_id);
	$rs_sa_delete_row = mysql_fetch_assoc(mysql_query($rs_sa_delete_qry));

	$rs_del_sa_qry = sprintf("DELETE sa FROM tbl_project_calc_third_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_third_salary_id='%s' AND p.User_id='%s'", $rs_sa_delete_row['Project_calc_third_salary_id'], $user_id);
	mysql_query($rs_del_sa_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}
}

# Delete calculate material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_ca_id'])){
	$del_ca_id = mysql_real_escape_string($_POST['fld_del_ca_id']);

	$rs_del_ca_qry = sprintf("DELETE ca FROM tbl_project_calc_third_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_material_id='%s' AND p.User_id='%s'", $del_ca_id, $user_id);
	mysql_query($rs_del_ca_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze regel";
		}else{
			die("Error: " . mysql_error());
		}
	}
}

# Delete calculate physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_del_ce_id'])){
	$del_ce_id = mysql_real_escape_string($_POST['fld_del_ce_id']);

	$rs_del_ce_qry = sprintf("DELETE ce FROM tbl_project_calc_third_physical AS ce JOIN tbl_project AS p ON p.Project_id=ce.Project_id WHERE ce.Project_calc_third_physical_id='%s' AND p.User_id='%s'", $del_ce_id, $user_id);
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

	$rs_up_this_sa_qry = sprintf("SELECT ca.* FROM tbl_project_calc_third_salary AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_salary_id='%s' AND p.User_id='%s' LIMIT 1", $up_sa_id, $user_id);
	$rs_up_this_sa_row = mysql_fetch_assoc(mysql_query($rs_up_this_sa_qry));
	$salary_id = $rs_up_this_sa_row['Project_calc_third_salary_id'];
	
	$rs_up_sa_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_salary AS opr JOIN tbl_project_calc_third_salary AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_salary_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $salary_id);
	$rs_up_sa_row = mysql_fetch_assoc(mysql_query($rs_up_sa_qry));
	
	if($rs_up_sa_row){
		$rs_up_prev_sa_qry = sprintf("UPDATE tbl_project_calc_third_salary SET Priority='%s' WHERE Project_calc_third_salary_id='%s'", $rs_up_this_sa_row['Priority'], $rs_up_sa_row['Project_calc_third_salary_id']);
		mysql_query($rs_up_prev_sa_qry);
	
		$rs_up_next_sa_qry = sprintf("UPDATE tbl_project_calc_third_salary SET Priority='%s' WHERE Project_calc_third_salary_id='%s'", $rs_up_sa_row['Priority'], $rs_up_this_sa_row['Project_calc_third_salary_id']);
		mysql_query($rs_up_next_sa_qry);
	}
}

# Move sa down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_sa_id'])){
	$down_sa_id = mysql_real_escape_string($_POST['fld_down_sa_id']);

	$rs_down_this_sa_qry = sprintf("SELECT sa.* FROM tbl_project_calc_third_salary AS sa JOIN tbl_project AS p ON p.Project_id=sa.Project_id WHERE sa.Project_calc_third_salary_id='240' AND p.User_id='12003' LIMIT 1", $down_sa_id, $user_id);
	$rs_down_this_sa_row = mysql_fetch_assoc(mysql_query($rs_down_this_sa_qry));
	$salary_id = $rs_down_this_sa_row['Project_calc_third_salary_id'];
	
	$rs_down_sa_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_salary AS opr JOIN tbl_project_calc_third_salary AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_salary_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $salary_id);
	$rs_down_sa_row = mysql_fetch_assoc(mysql_query($rs_down_sa_qry));
	
	if($rs_down_sa_row){
		$rs_down_prev_sa_qry = sprintf("UPDATE tbl_project_calc_third_salary SET Priority='%s' WHERE Project_calc_third_salary_id='%s'", $rs_down_this_sa_row['Priority'], $rs_down_sa_row['Project_calc_third_salary_id']);
		mysql_query($rs_down_prev_sa_qry);
	
		$rs_down_next_sa_qry = sprintf("UPDATE tbl_project_calc_third_salary SET Priority='%s' WHERE Project_calc_third_salary_id='%s'", $rs_down_sa_row['Priority'], $rs_down_this_sa_row['Project_calc_third_salary_id']);
		mysql_query($rs_down_next_sa_qry);
	}
}

# Move ca up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_ca_id'])){
	$up_ca_id = mysql_real_escape_string($_POST['fld_up_ca_id']);

	$rs_up_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_third_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_material_id='%s' AND p.User_id='%s' LIMIT 1", $up_ca_id, $user_id);
	$rs_up_this_ca_row = mysql_fetch_assoc(mysql_query($rs_up_this_ca_qry));
	$material_id = $rs_up_this_ca_row['Project_calc_third_material_id'];
	
	$rs_up_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_material AS opr JOIN tbl_project_calc_third_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_material_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $material_id);
	$rs_up_ca_row = mysql_fetch_assoc(mysql_query($rs_up_ca_qry));

	if($rs_up_ca_row){
		$rs_up_prev_ca_qry = sprintf("UPDATE tbl_project_calc_third_material SET Priority='%s' WHERE Project_calc_third_material_id='%s'", $rs_up_this_ca_row['Priority'], $rs_up_ca_row['Project_calc_third_material_id']);
		mysql_query($rs_up_prev_ca_qry);
	
		$rs_up_next_ca_qry = sprintf("UPDATE tbl_project_calc_third_material SET Priority='%s' WHERE Project_calc_third_material_id='%s'", $rs_up_ca_row['Priority'], $rs_up_this_ca_row['Project_calc_third_material_id']);
		mysql_query($rs_up_next_ca_qry);
	}
}

# Move ca down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_ca_id'])){
	$down_ca_id = mysql_real_escape_string($_POST['fld_down_ca_id']);

	$rs_down_this_ca_qry = sprintf("SELECT ca.* FROM tbl_project_calc_third_material AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_material_id='%s' AND p.User_id='%s' LIMIT 1", $down_ca_id, $user_id);
	$rs_down_this_ca_row = mysql_fetch_assoc(mysql_query($rs_down_this_ca_qry));
	$material_id = $rs_down_this_ca_row['Project_calc_third_material_id'];
	
	$rs_down_ca_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_material AS opr JOIN tbl_project_calc_third_material AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_material_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $material_id);
	$rs_down_ca_row = mysql_fetch_assoc(mysql_query($rs_down_ca_qry));
	
	if($rs_down_ca_row){
		$rs_down_prev_ca_qry = sprintf("UPDATE tbl_project_calc_third_material SET Priority='%s' WHERE Project_calc_third_material_id='%s'", $rs_down_this_ca_row['Priority'], $rs_down_ca_row['Project_calc_third_material_id']);
		mysql_query($rs_down_prev_ca_qry);
	
		$rs_down_next_ca_qry = sprintf("UPDATE tbl_project_calc_third_material SET Priority='%s' WHERE Project_calc_third_material_id='%s'", $rs_down_ca_row['Priority'], $rs_down_this_ca_row['Project_calc_third_material_id']);
		mysql_query($rs_down_next_ca_qry);
	}
}

# Move ce up
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_up_ce_id'])){
	$up_ce_id = mysql_real_escape_string($_POST['fld_up_ce_id']);
	
	$rs_up_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_third_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_physical_id='%s' AND p.User_id='%s' LIMIT 1", $up_ce_id, $user_id);
	$rs_up_this_ce_row = mysql_fetch_assoc(mysql_query($rs_up_this_ce_qry));
	$physical_id = $rs_up_this_ce_row['Project_calc_third_physical_id'];
	
	$rs_up_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_physical AS opr JOIN tbl_project_calc_third_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_physical_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $physical_id);
	$rs_up_ce_row = mysql_fetch_assoc(mysql_query($rs_up_ce_qry));
	
	if($rs_up_ce_row){
		$rs_up_prev_ce_qry = sprintf("UPDATE tbl_project_calc_third_physical SET Priority='%s' WHERE Project_calc_third_physical_id='%s'", $rs_up_this_ce_row['Priority'], $rs_up_ce_row['Project_calc_third_physical_id']);
		mysql_query($rs_up_prev_ce_qry);

		$rs_up_next_ce_qry = sprintf("UPDATE tbl_project_calc_third_physical SET Priority='%s' WHERE Project_calc_third_physical_id='%s'", $rs_up_ce_row['Priority'], $rs_up_this_ce_row['Project_calc_third_physical_id']);
		mysql_query($rs_up_next_ce_qry);
	}
}

# Move ce down
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_down_ce_id'])){
	$down_ce_id = mysql_real_escape_string($_POST['fld_down_ce_id']);

	$rs_down_this_ce_qry = sprintf("SELECT ca.* FROM tbl_project_calc_third_physical AS ca JOIN tbl_project AS p ON p.Project_id=ca.Project_id WHERE ca.Project_calc_third_physical_id='%s' AND p.User_id='%s' LIMIT 1", $down_ce_id, $user_id);
	$rs_down_this_ce_row = mysql_fetch_assoc(mysql_query($rs_down_this_ce_qry));
	$physical_id = $rs_down_this_ce_row['Project_calc_third_physical_id'];
	
	$rs_down_ce_qry = sprintf("SELECT opr.* FROM tbl_project_calc_third_physical AS opr JOIN tbl_project_calc_third_physical AS slct ON slct.Operation_id=opr.Operation_id WHERE slct.Project_calc_third_physical_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $physical_id);
	$rs_down_ce_row = mysql_fetch_assoc(mysql_query($rs_down_ce_qry));
	
	if($rs_down_ce_row){
		$rs_down_prev_ce_qry = sprintf("UPDATE tbl_project_calc_third_physical SET Priority='%s' WHERE Project_calc_third_physical_id='%s'", $rs_down_this_ce_row['Priority'], $rs_down_ce_row['Project_calc_third_physical_id']);
		mysql_query($rs_down_prev_ce_qry);
	
		$rs_down_next_ce_qry = sprintf("UPDATE tbl_project_calc_third_physical SET Priority='%s' WHERE Project_calc_third_physical_id='%s'", $rs_down_ce_row['Priority'], $rs_down_this_ce_row['Project_calc_third_physical_id']);
		mysql_query($rs_down_next_ce_qry);
	}
}
/*
# Add/update salary
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_cs_id'])){
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$date = mysql_real_escape_string($_POST['fld_date']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_cs_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);
	
	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_third_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));

		$rs_add_salary = sprintf("INSERT INTO tbl_project_calc_third_salary (Create_date, Hour_id, Project_id, Invoice_id, Operation_id, Tax_id, Date, Price, Amount, Priority, Comment) VALUES (NOW(), NULL, '%s', '70', '%s', '%s', STR_TO_DATE('%s', '%%d-%%m-%%Y' ), '%s', '%s', '%s', '%s')", $project_id, $operation_id, $tax_id, $date, $price, $amount, ($rs_prio_cm_row['Priority']+1), $comment);
		mysql_query($rs_add_salary) or die("Error: " . mysql_error());
	}
}
*/
# Add/update material
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ca_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ca_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$unit){
		$error_message = "Vul een eenheid in";
	}	

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_third_material AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
	
		$rs_add_material = sprintf("INSERT INTO tbl_project_calc_third_material (Create_date, Materialtype, Unit, Project_id, Invoice_id, Operation_id, Tax_id, Price, Amount, DB_chain, Priority) VALUES (NOW(), '%s', '%s', '%s', '70', '%s', '%s', '%s', '%s', 'N', '%s')", $type, $unit, $project_id, $operation_id, $tax_id, $price, $amount, ($rs_prio_cm_row['Priority']+1));
		mysql_query($rs_add_material) or die("Error: " . mysql_error());
	}
}

# Add/update physical
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_op_ce_id'])){
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$price = mysql_real_escape_string($_POST['fld_price']);
	$type = mysql_real_escape_string($_POST['fld_type']);
	$unit = mysql_real_escape_string($_POST['fld_unit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['fld_op_ce_id']);
	$price = str_replace(',', '.', $price);
	$amount = str_replace(',', '.', $amount);

	if($amount < 0){
		$error_message = "Vul een positieve hoeveelheid in";
	}
	
	if($price < 0){
		$error_message = "Vul een positieve prijs/eenheid in";
	}
	
	if(!$type){
		$error_message = "Vul een factuurbedrag in";
	}

	if(!$unit){
		$error_message = "Vul een eenheid in";
	}

	if(!$error_message){
		$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_third_physical AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
	
		$rs_add_physical = sprintf("INSERT INTO tbl_project_calc_third_physical (Create_date, Materialtype, Unit, Project_id, Invoice_id, Operation_id, Tax_id, Price, Amount, DB_chain, Priority) VALUES (NOW(), '%s', '%s', '%s', '70', '%s', '%s', '%s', '%s', 'N', '%s')", $type, $unit, $project_id, $operation_id, $tax_id, $price, $amount, ($rs_prio_cm_row['Priority']+1));
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
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=136&amp;r_id=<?php echo $_GET['r_id']; ?>'" value="Aanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=137&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Onderaanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=144&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Uittrekstaat" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=146&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Eindresultaat" />
		</div>
		<span>Meerwerk Onderaanneming</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
		<span class="classic">
		<div class="tt-title"><?php echo $__tooltip['Title']; ?></div>
		</span></a><a class="tooltip" href="javascript:void(0)"><span class="classic">		<?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div><br>
  <table width="100%" border="0">
			<tr class="tbl-subhead">
				<td colspan="2" class="tbl-head">Nieuw hoofdstuk toevoegen</td>
			</tr>
						<form name="frm_chap_add" id="frm_chap_add" action="" method="post">
						<tr>
							<td width="16"><a href="#" onclick="document.frm_chap_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
							<td width="1002"><input name="fld_new_chapter" type="text" id="fld_new_chapter" style="width: 100%;" maxlength="50" /></td>
						</tr>
						</form>
					</table><br><!--
    	<table width="100%" border="0">
    	  <tr class="tbl-subhead">
    	    <td colspan="2" class="tbl-head">Bestaand hoofdstuk toevoegen</td>
  	    </tr>
    	  <form name="frm_chap_add" id="frm_chap_add2" action="" method="post">
    	    <tr>
    	      <td width="16"><a href="#" onclick="document.frm_chap_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
    	      <td width="1002">
				<select style="width: 100%;">
                <?php //while($rs_filter_chapter_row = mysql_fetch_assoc($rs_filter_chapter_result)){
					//echo '<option value="'.$rs_filter_chapter_row['Project_chapter_id'].'">'.$rs_filter_chapter_row['Chapter'].'</option>';
				//} ?>
				</select>
			</td>
  	      </tr>
  	    </form>
  	  </table>--><br>
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
						$rs_project_work_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=70 ORDER BY Priority ASC", $rs_project_work_row['Project_chapter_id']);
						$rs_project_work_op_result = mysql_query($rs_project_work_op_qry);
						$rs_project_work_op_num = mysql_num_rows($rs_project_work_op_result);
					?>
					<table id="tbl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" width="100%" border="0">
						<tr class="tbl-head">
							<td width="16">&nbsp;</td>
							<td width="16"><a href="#" onclick="document.frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" alt="Opslaan" title="Opslaan" /></a></td>
							<td colspan="2">
								<div style="float:right">
									<form action="" method="post" name="frm_chap_up_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_chap_up_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">
										<input type="hidden" name="fld_up_chap_id" id="fld_up_chap_id" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" />
									</form>
									<form action="" method="post" name="frm_chap_down_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_chap_down_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">
										<input type="hidden" name="fld_down_chap_id" id="fld_down_chap_id" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" />
									</form>
									<a href="#" onClick="document.frm_chap_up_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Hoofdstuk omhoog" title="Hoofdstuk omhoog" /></a>
									<a href="#" onClick="document.frm_chap_down_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Hoofdstuk omlaag" title="Hoofdstuk omlaag" /></a>
								</div>
								<div style="padding-left:2px;">
									<form action="" method="post" name="frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">
										<input name="fld_chg_chap" type="text" id="fld_chg_chap" style="width: 96%; height:15px; border: none; background-color:transparent; color:#FFF" value="<?php echo $rs_project_work_row['Chapter']; ?>" maxlength="50" />
										<input type="hidden" name="fld_chg_chap_id" id="fld_chg_chap_id" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" />
									</form>
								</div>
							</td>
							<td width="16">
								<form action="" method="post" name="frm_chap_del_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_chap_del_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">
									<input type="hidden" name="fld_del_chap_id" id="fld_del_chap_id" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" />
								</form>
								<a href="#" onclick="document.frm_chap_del_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
							</td>
						</tr>
						<tr class="tbl-subhead">
							<td width="16">&nbsp;</td>
							<td width="16">&nbsp;</td>
							<td>Uit te voeren werkzaamheden</td>
							<td>Omschrijving werkzaamheden voor op de factuur</td>
							<td width="16">&nbsp;</td>
						</tr>
						<?php if($rs_project_work_op_num){ ?>
						<?php while($rs_project_work_op_row = mysql_fetch_assoc($rs_project_work_op_result)){
							$rs_calc_salary_qry = sprintf("SELECT cs.Project_calc_third_salary_id, DATE_FORMAT(cs.Date, '%%d-%%m-%%Y') AS Date, cs.Price, cs.Amount, cs.Comment, cs.Tax_id FROM tbl_project_calc_third_salary AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id WHERE cs.Project_id='%s' AND cs.Invoice_id='70' AND cs.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_salary_result = mysql_query($rs_calc_salary_qry) or die("Error: " . mysql_error());
							$rs_calc_salary_num = mysql_num_rows($rs_calc_salary_result);
							
							$rs_calc_material_qry = sprintf("SELECT cm.Project_calc_third_material_id, cm.Materialtype, cm.Unit, cm.Price, cm.Amount, cm.Tax_id, t.Tax FROM tbl_project_calc_third_material AS cm JOIN tbl_tax AS t ON t.Tax_id=cm.Tax_id JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE cm.Project_id='%s' AND cm.Invoice_id='70' AND cm.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_material_result = mysql_query($rs_calc_material_qry) or die("Error: " . mysql_error());
							$rs_calc_material_num = mysql_num_rows($rs_calc_material_result);
							
							$rs_calc_physical_qry = sprintf("SELECT cp.Project_calc_third_physical_id, cp.Materialtype, cp.Unit, cp.Price, cp.Amount, cp.Tax_id, t.Tax FROM tbl_project_calc_third_physical AS cp JOIN tbl_tax AS t ON t.Tax_id=cp.Tax_id JOIN tbl_project AS p ON p.Project_id=cp.Project_id WHERE cp.Project_id='%s' AND cp.Invoice_id='70' AND cp.Operation_id='%s' AND p.User_id='%s' ORDER BY Priority DESC", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_calc_physical_result = mysql_query($rs_calc_physical_qry) or die("Error: " . mysql_error());
							$rs_calc_physical_num = mysql_num_rows($rs_calc_physical_result);
							
							$rs_total_qry = sprintf("SELECT * FROM tvw_total_more WHERE Project_id='%s' AND Project_operation_id='%s' AND User_id='%s' LIMIT 1", $project_id, $rs_project_work_op_row['Project_operation_id'], $user_id);
							$rs_total_result = mysql_query($rs_total_qry) or die("Error: " . mysql_error());
							$rs_total_row = mysql_fetch_assoc($rs_total_result);
						?>
						<tr class="tbl-operation">
							<td><a style="display:" href="javascript:void(0);" id="tgl_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" onClick="checkToggle('.t_total_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>', this);">[+]</a></td>
							<td><a href="#" onclick="document.frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" alt="Opslaan" title="Opslaan" /></a></td>
							<form name="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
								<td>
									<input name="fld_chg_operation" type="text" id="fld_chg_operation" style="width: 99%; height:15px; background: #FFE796;" maxlength="50" value="<?php echo $rs_project_work_op_row['Operation']; ?>" />
									<input type="hidden" name="fld_chg_op_id" id="fld_chg_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
                                </td>
								<td>
									<div style="float:right">
										<a href="#" onClick="document.frm_op_up_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Werkzaamheid omhoog" title="Werkzaamheid omhoog" /></a>
										<a href="#" onClick="document.frm_op_down_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Werkzaamheid omlaag" title="Werkzaamheid omlaag" /></a>
									</div>
									<input name="fld_chg_description" type="text" id="fld_chg_description" style="width: 90%; height:15px; background: #FFE796;" maxlength="100" value="<?php echo $rs_project_work_op_row['Description']; ?>" />
								</td>
							</form>
							<td>
								<form action="" method="post" name="frm_op_up_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_up_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
									<input type="hidden" name="fld_up_op_id" id="fld_up_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
								</form>
								<form action="" method="post" name="frm_op_down_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_down_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
									<input type="hidden" name="fld_down_op_id" id="fld_down_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
								</form>
								<form action="" method="post" name="frm_op_del_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_del_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
									<input type="hidden" name="fld_del_op_id" id="fld_del_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" />
								</form>
								<a href="#" onclick="document.frm_op_del_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
							</td>
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
									<?php $rs_calc_salary_row = mysql_fetch_assoc($rs_calc_salary_result); ?>
									<tr style="display:" class="t_salary_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_sa_upd_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_sa_upd_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_sa_upd_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
										<td><input style="width:99%" class="date" type="text" name="fld_date" id="fld_date" value="<?php echo $rs_calc_salary_row['Date']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_price" id="fld_price" value="<?php echo number_format($rs_calc_salary_row['Price'], 2, ',', '.'); ?>" /></td>
										<td align="center">per uur</td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount" value="<?php echo str_replace('.', ',', $rs_calc_salary_row['Amount']); ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_salary_row['Price']*$rs_calc_salary_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_op_id" id="fld_upd_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" /></td>
										<td align="right"><input style="width:99%" type="text" name="fld_comment" id="fld_comment" value="<?php echo $rs_calc_salary_row['Comment']; ?>" /></td>
										<td align="center">
											<select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == $rs_calc_salary_row['Tax_id']){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											}
											?>
											</select>
										</td>
										</form>
										<td align="right"></td>
										<td align="right"></td>
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
									<?php while($rs_calc_material_row = mysql_fetch_assoc($rs_calc_material_result)){ ?>
									<tr style="display:" class="t_material_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_ca_upd_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_ca_upd_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" id="frm_ca_upd_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" action="" method="post">
										<td><input style="width:99%" type="text" name="fld_type" id="fld_type" value="<?php echo $rs_calc_material_row['Materialtype']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_unit" id="fld_unit" value="<?php echo $rs_calc_material_row['Unit']; ?>" /></td>
										<td align="right"><input style="width:99%;text-align:center;" type="text" name="fld_price" id="fld_price" value="<?php echo number_format($rs_calc_material_row['Price'], 2, ',', '.'); ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount" value="<?php echo number_format($rs_calc_material_row['Amount'], 2, ',', '.'); ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_material_row['Price']*$rs_calc_material_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_ca_id" id="fld_upd_ca_id" value="<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_material_row['Amount']*($rs_calc_material_row['Price']+(($rs_calc_material_row['Price']/100)*$rs_profit_row['2_Profit_material_sec'])), 2, ',', '.'); ?></td>
										<td align="center">
											<select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == 20){
													continue;
												}
												if($rs_tax_row['Tax_id'] == $rs_calc_material_row['Tax_id']){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											}
											?>
											</select>
										</td>
										</form>
										<td align="right">
											<form action="" method="post" name="frm_ca_up_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" id="frm_ca_up_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>">
												<input type="hidden" name="fld_up_ca_id" id="fld_up_ca_id" value="<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" />
											</form>
											<form action="" method="post" name="frm_ca_down_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" id="frm_ca_down_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>">
												<input type="hidden" name="fld_down_ca_id" id="fld_down_ca_id" value="<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ca_up_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Regel omhoog" title="Regel omhoog" /></a>
											<a href="#" onClick="document.frm_ca_down_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Regel omlaag" title="Regel omlaag" /></a>
										</td>
										<td align="right">
											<form action="" method="post" name="frm_ca_del_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" id="frm_ca_del_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>">
												<input type="hidden" name="fld_del_ca_id" id="fld_del_ca_id" value="<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ca_del_<?php echo $rs_calc_material_row['Project_calc_third_material_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
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
										<td align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Material_noprofit_70'], 2, ',', '.'); ?></i></b></font></td>
										<td align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Material_profit_70'], 2, ',', '.'); ?></i></b></font></td>
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
									<?php while($rs_calc_physical_row = mysql_fetch_assoc($rs_calc_physical_result)){ ?>
									<tr style="display:" class="t_physical_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>">
										<td align="right"><a href="javascript:void(0);" onclick="document.frm_ce_upd_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" title="Opslaan" /></a></td>
										<form name="frm_ce_upd_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" id="frm_ce_upd_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" action="" method="post">
										<td><input style="width:99%" type="text" name="fld_type" id="fld_type" value="<?php echo $rs_calc_physical_row['Materialtype']; ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_unit" id="fld_unit" value="<?php echo $rs_calc_physical_row['Unit']; ?>" /></td>
										<td align="right"><input style="width:99%;text-align:center;" type="text" name="fld_price" id="fld_price" value="<?php echo number_format($rs_calc_physical_row['Price'], 2, ',', '.'); ?>" /></td>
										<td align="center"><input style="width:99%;text-align:center;" type="text" name="fld_amount" id="fld_amount" value="<?php echo number_format($rs_calc_physical_row['Amount'], 2, ',', '.'); ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_physical_row['Price']*$rs_calc_physical_row['Amount'], 2, ',', '.'); ?><input type="hidden" name="fld_upd_ce_id" id="fld_upd_ce_id" value="<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" /></td>
										<td align="right">&euro;&nbsp;<?php echo number_format($rs_calc_physical_row['Amount']*($rs_calc_physical_row['Price']+(($rs_calc_physical_row['Price']/100)*$rs_profit_row['2_Profit_physical_sec'])), 2, ',', '.'); ?></td>
										<td align="center"><select name="slt_tax" id="slt_tax" style="width: 99%;">
											<?php
											$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());
											while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
												if($rs_tax_row['Tax_id'] == 20){
													continue;
												}
												if($rs_tax_row['Tax_id'] == $rs_calc_physical_row['Tax_id']){
													echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}else{
													echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
												}
											}
											?>
											</select>
										</td>
										</form>
										<td align="right">
											<form action="" method="post" name="frm_ce_up_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" id="frm_ce_up_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>">
												<input type="hidden" name="fld_up_ce_id" id="fld_up_ce_id" value="<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" />
											</form>
											<form action="" method="post" name="frm_ce_down_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" id="frm_ce_down_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>">
												<input type="hidden" name="fld_down_ce_id" id="fld_down_ce_id" value="<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ce_up_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>.submit()"><img src="../../images/up.png" width="16" height="16" alt="Regel omhoog" title="Regel omhoog" /></a>
											<a href="#" onClick="document.frm_ce_down_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>.submit()"><img src="../../images/down.png" width="16" height="16" alt="Regel omlaag" title="Regel omlaag" /></a>
										</td>
										<td align="right">
											<form action="" method="post" name="frm_ce_del_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" id="frm_ce_del_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>">
												<input type="hidden" name="fld_del_ce_id" id="fld_del_ce_id" value="<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>" />
											</form>
											<a href="#" onClick="document.frm_ce_del_<?php echo $rs_calc_physical_row['Project_calc_third_physical_id']; ?>.submit()"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a>
										</td>
									</tr>
									<?php } ?>
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
										<td width="16%" align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Physical_noprofit_70'], 2, ',', '.'); ?></i></b></font></td>
										<td width="16%" align="right"><font color="#313131"><b><i>&euro;&nbsp;<?php echo number_format($rs_total_row['Physical_profit_70'], 2, ',', '.'); ?></i></b></font></td>
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
						<tr>
	                        <td>&nbsp;</td>
						  <td><a href="#" onclick="document.frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
						  <td><input name="fld_operation" type="text" id="fld_operation" style="width: 99%;" maxlength="50" /><input type="hidden" name="fld_chapter" id="fld_chapter" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" /></td>
						  <td colspan="2"><input name="fld_description" type="text" id="fld_description" style="width: 100%;" maxlength="100" /></td>
						</tr>
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
<?php /*
mysql_free_result($rs_project_chap2_result);
mysql_free_result($rs_project_chap_result);
mysql_free_result($rs_project_relations_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_invoice2_result);
mysql_free_result($rs_invoice_result);
mysql_free_result($rs_invoices_total_result);
mysql_free_result($rs_invoices_result); */
?>
