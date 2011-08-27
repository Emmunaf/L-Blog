<?php

/* Save.php;
 * Emp£Hack & System_infet & KinG-InFeT
 * Beta....For bug contact us to ema.muna95@hotmail.it or admin@netcoders.org or king-infet@autistici.org
 */
include("functions.php");
if(isset($_GET['id'])){
	if (c_admin() == '1') {
		$level = parse($_GET['level']);
		$id = (int) $_GET['id'];
		if($level == 'admin' || $level == 'user' || $level == 'mod'){
			$table    = PREFIX."users";
			mysql_query("UPDATE {$table} SET level = '{$level}' WHERE id = '{$id}'");
		}
	}
	else{
		error3();
	}
}
?>
