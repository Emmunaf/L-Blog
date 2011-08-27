<?php
/*viewpost.php
 * Emp£HacK & System-infet
 * Beta...for bug contact me to ema.muna95@hotmail.it
 * File che consente la lettura dei post
 */
require_once("functions.php");

p_html();
pm_html();

if (isset($_GET['id'])) {
	$table = PREFIX."blog";
	$id = (int) $_GET['id'];
	$sqlquery = mysql_query("SELECT * FROM {$table} WHERE id = '{$id}'");
	
	while($row = mysql_fetch_assoc($sqlquery)) {
		
		$autore    = $row['author'];
		$titolo    = $row['title'];
		
		$contenuto = $row['data'];
		$contenuto = str_replace('\r\n',"<br />",$contenuto);
		print "<h2 class=\"title\">".$titolo."</h2><article style='overflow: auto;'>
		".$contenuto."
		</article><div class='p_author'>By ".$autore."</div>";
		
    	/*controllo il numero di commenti e se è diverso da zero scrivo i commenti con la funzione p_comment*/
		$c_num = c_comment($id);
		
		if(!$c_num == 0)
		    p_comment($id);
		else
			show_i_comment($id);
		print "</aside></div>";
	}
}
print '</body></html>';
?>
