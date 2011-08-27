<?php
/* Profile.php;
 * Emp£Hack & System_infet & KinG-InFeT
 * Beta....For bug contact us to ema.muna95@hotmail.it or admin@netcoders.org or king-infet@autistici.org
 */
require_once("functions.php");

if(!isset($_GET['action'])) {	
    p_html();
    pm_html();
  
    if(isset($_GET['id'])) {
    	$id = (int) $_GET['id'];
    	/*INIZIO SOLUZIONE PROVVISORIA*/
    	$current_folder = explode('/',$_SERVER["PHP_SELF"]);
    	$folder = $current_folder['1'];
    	/*FINE SOLUZIONE PROVVISORIA*/
    	print "<script type='text/Javascript'>
		function OnSelectionChange (select) {
		var selectedOption = select.options[select.selectedIndex];
		document.getElementById('save').style.display='block';
		$.get('/$folder/save.php?id=$id&level='+selectedOption.value,
		function (data) {
		alert('Permessi utente cambiati con successo!');
			}); 
		}
		</script>";
    	p_profile($id);
    	$user = f_username($id);
    	if(c_admin() == '1') {
			print "\n<br /><div id='save' style='display: none;'>
			
			</div>
			";
    		if(c_ban($user) == '0') {
    			print "\n<br /><a href='profile.php?action=ban&user=".$user."'>Banna utente!</a>";
    	    }else{
    		    print "\n<br /><a href='profile.php?action=sban&user=".$user."'>Rimuovi Ban!</a>";
    	    }
        }
        print "</html>";
    }
}else{
  	$azione = $_GET['action'];
   	if($azione =='ban') {
   		$current_user = mysql_real_escape_string($_SESSION['user']);
   		$id = f_id($current_user);
   		if(isset($_GET['user'])) {
   		$user = parse($_GET['user']);
   		if(c_permission($id,$user) == '1')
   		    b_user($user);
   	    else
   	        error3();
   	    }
   	
   	}
   	
   	if($azione == 'sban') {
   		if(isset($_GET['user'])) {
   		    $user = $_GET['user'];
   		    sb_user($user);
   		}
   	}
}
?>
