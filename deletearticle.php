<?php
require_once("functions.php");

if(isset($_SESSION['user'])) {
    if(c_admin() == 1) {
        if(@$_GET['action'] == 'delete'){
            $getid = (int) $_GET['id'];
            if(isset($getid))
                d_article($getid);
        }
        l_article();
    } else {
		error2();
	}
}else {
	error1();
}
?>
