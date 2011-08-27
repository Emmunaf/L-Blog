<?php
/* Function.php;
 * Emp£Hack & System_infet & KinG-InFeT
 * Beta....For bug contact us to ema.muna95@hotmail.it or admin@netcoders.org or king-infet@autistici.org
 */
error_reporting(E_ALL);
session_start();
session_regenerate_id(TRUE);

include("firewall.php");
require_once("config.php");

/* Security function */
function parse($string) {
    return mysql_real_escape_string( htmlspecialchars( stripslashes( $string )));
}

/*!! Ritorna 0 se non si hanno i pemessi fi amministrazione, se si posseggono ritorna 1*/
function c_admin() {
    if (isset($_SESSION['user'])) {
    
    	$user     = parse($_SESSION['user']);
	    $table    = PREFIX."users";
	    $sqlquery = "SELECT level FROM {$table} where username = '{$user}';";
	    
	    $level = mysql_query($sqlquery); 
	
	    while ($row = mysql_fetch_row($level)) {
	    	if ($row['0'] == 'admin')
	    		return 1;	
	    	else
	    		return 0;
	    }
    }else
        return 0;
}

function sb_user($user){
    if(c_admin() == '1') {
        $table = PREFIX."ban_ip";
        $query = "DELETE FROM {$table} WHERE user_id = '{$user}';";
        mysql_query($query) or die("MySQL error: ".mysql_error());
        print "Rimosso il ban!";
        print "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
    }else
        die("Non hai i permessi necessari per eseguire l'azione!");
}

function f_id($username,$filtro) {
    //pessima idea :S
	if($filtro == '1')
	    $user = mysql_real_escape_string($username);
    else
    	$user = $username;

	$table    = PREFIX."users";
	$sqlquery = "SELECT id FROM {$table} WHERE username = '{$user}';";
	$result   = mysql_query($sqlquery); 
	
	while ($row = mysql_fetch_row($result)) {
		return $row['0'];
	}
}

function f_username($id) {
	
	$id       = (int) $id;
	$table    = PREFIX."users";
	$sqlquery = "SELECT username FROM {$table} WHERE id = '{$id}';";
	$result   = mysql_query($sqlquery); 
	
	while ($row = mysql_fetch_row($result)) {
		return $row['0'];
	}
}

/*Funzione per printare l'html fino alla chiusura del tag head, stampa il titolo*/
function p_html() {
	$table    = PREFIX."settings";
	$sqlquery = "SELECT title FROM $table";
	$b_name   = mysql_query($sqlquery); 
	
	while ($row = mysql_fetch_row($b_name)) {
		$filtro = htmlspecialchars($row[0]);
        print "\n<!DOCTYPE html>"
            . "\n<html>"
            . "\n<head>"
            . "\n<title>".$filtro."</title>"
            . "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\"/>"
            . "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">"
            . "\n<script type='text/javascript' src='jquery-1.6.2.js'></script>"
            . "\n</head>";
    }
}

/*Funzione  che mostra la prima parte (688 caratteri) degli articoli con titolo e contenuto*/
function p_news() {
	$host     = $_SERVER['HTTP_HOST'];
	$table    = PREFIX."blog";
	$sqlquery = mysql_query("SELECT id, author, title, data FROM {$table} ORDER BY id DESC");
	
    while ($row = mysql_fetch_assoc($sqlquery)) {
	    $id         = (int) $row['id'];
		$autore     = $row['author'];
		$titolo     = $row['title'];
		$contenuto  = $row['data'];  //htmlspecialchars
		$contenuto  = cut_string($contenuto, '688');
		$contenuto  = str_replace('#3',"'" , $contenuto);
		$c_num      = c_comment($id);
		
		print "\n<h2 class=\"title\"><a class=\"titoli\" href=\"./viewpost.php?id=".$id."\">".$titolo."</a><center><font size ='1'>    By ".$autore." (commenti:".$c_num.")</font></center></h2>";
		print $contenuto;
		
		if (c_admin() == '1') {
    		print "\n <br /><a class='mod' href=\"./deletearticle.php?action=delete&id=".$id."\">Cancella ".$titolo."</a><br />";
    	}
	    	print "\n<h2 class=\"separa\"></h2><br /><br />";
	}
  }
  
/*Funzione per inserire articoli, i parametri da passare sono i sottodetti*/
function i_article($section, $author, $title,$data, $replyof, $last, $time, $date) {
	$table = PREFIX."blog";
	$query = "INSERT INTO ".$table." (section, author, title, data, replyof, last, time, date) VALUES ('{$section}', '{$author}', '{$title}', '{$data}', '{$replyof}', '{$last}', '{$time}', '{$date}');";
	mysql_query($query) or die("SQL Error: ".mysql_error());
	print("Articolo inserito con successo!\n");	
}

/*Taglia gli articoli lunghi per visualizzarne una anteprima*/
function cut_string($stringa, $max_char) {
    if(strlen($stringa)>$max_char) {
		$stringa_tagliata = substr($stringa, 0,$max_char);
		$last_space       = strrpos($stringa_tagliata," ");
		$stringa_ok       = substr($stringa_tagliata, 0,$last_space);
		return $stringa_ok."<br />[...]";
	}else
		return $stringa;
}

/*Funzione che conta il numero di commenti di un certo post, valore da passare è l'id del post*/
function c_comment($id) {
   	$id    = (int) $id;
	$table = PREFIX."comment";

	$conta = "SELECT COUNT(a_id) as conta FROM {$table} WHERE a_id = '{$id}'";
    $conto = @mysql_query ($conta);
    $tot   = @mysql_fetch_array ($conto);
    $c_num = $tot['conta'];
    return $c_num;
}

function p_comment($id) {
	$id = (int) $id;
	print "\n<br /><br />Commenti:<br /><br />";/*da continuare*/
	$table    = PREFIX."comment";
	$sqlquery = mysql_query("SELECT * FROM {$table} WHERE a_id = '{$id}'");
	
	while($row = mysql_fetch_assoc($sqlquery)) {
		$autore     = $row['author'];//html_s
		$titolo     = $row['title'];
		$contenuto  = $row['data'];
		$id2    = (int) $row['id'];
		$c_edit = "<a class='c_edit' href=\"editcomment.php?id=".$id2."\">Modifica Commento</a><br />";
		print "\n<span class='c_title'>Title: ".$titolo."</span><br />";/*Printo il titolo*/
		print "\n<span class='c_author'>Author: ".$autore."</span><br /><div class='accapo'><article style='overflow: auto;'>".$contenuto."</article><h2 class=\"separa\">";
		
		if (c_admin() == '1'){
			print $c_edit;/*stampo il link per editare commenti*/
		}
		
		print "\n</h2><br /></div>";/*con <h2 class=\"separa\"></h2><br/> creo spazio*/
		
	}
	show_i_comment($id);
}
	
/* Funzione per mostrare il tasto e il form inserisci commento*/
function show_i_comment($id) {
	print '<a onclick="show()" style="cursor: pointer;color : #185d99;">Inserisci commento</a>';
			//creo un div dove via ajax prelevo il conenuto di insertcomment.php
			//imposto status a 0 (0 = nascosto) poi controllo se è a 0 o no e a seconda del caso nascondo o mostro
			print '
			<div id="i_commenti" style="display: none;">
				<script type="text/javascript">
				$.get("insertcomment.php?id=$id",{id: '.$id.'},
				function(data){
					document.getElementById("i_commenti").innerHTML = data; 
				});
				var status = 0;
				
				function show(){
					if (status == 0) {
						$("#i_commenti").show(1500);
						status = 1;
					}
					else {
						$("#i_commenti").hide(1500);
						status = 0;
					}
				}
				</script>
			</div>';
	}
	
/*Funzione per inserire i commenti*/
function i_comment($a_id, $author, $title, $data, $time, $mail, $date) {
	$table = PREFIX."comment";
	$query = "INSERT INTO ".$table." (a_id, author, title, data, time, mail, date) VALUES ('{$a_id}', '{$author}', '{$title}', '{$data}', '{$time}', '{$mail}', '{$date}');";
	mysql_query($query) or die("SQL Error: ".mysql_error());
	
	print("Commento inserito con successo!\n");	
	print "\n<meta http-equiv=\"refresh\" content=\"0;url=viewpost.php?id=".$a_id."\">";
}

/*Funzione per verere i titoli degli articoli per poi eliminarli*/
function l_article() {
	p_html();
	pm_html();
	print "\nLista titoli dei possibili articoli da eliminare:<br/>";
	$host     = $_SERVER['HTTP_HOST'];
	$table    = PREFIX."blog";
	$sqlquery = mysql_query("SELECT id, author, title, data FROM {$table} ORDER BY id DESC");
	
	while ($row = mysql_fetch_assoc($sqlquery)) {
	    $id        = (int) $row['id'];
	    $autore    = $row['author'];//htmlspecial
	    $titolo    = $row['title'];
	    $contenuto = $row['data']; 
	    $c_num     = c_comment($id);
	    print "\n<a href=\"deletearticle.php?action=delete&id=".$id."\">Cancella ".$titolo."</a> (Commenti: ".$c_num.")<br/>";
	}
	print "\n</body></html>";
}

/*Funzione per eliminare gli articoli*/
function d_article($getid) {
	$getid  = (int) $getid;
	$table  = PREFIX."blog";
	$query  = "DELETE FROM ".$table." WHERE id = '".$getid."';";
	$result = mysql_query($query) or die(mysql_error());
	print "\n<script>alert('Articolo cancellato con successo')</script>";
	print "\n<meta http-equiv=\"refresh\" content=\"0;url=deletearticle.php\">";	
}
	
function l_comment() {
	$table  = PREFIX."comment";
	$query  = "SELECT * FROM ".$table.";";
	$result = mysql_query($query) or die(mysql_error());
	
	while ($row = mysql_fetch_array($result)) {
	    $id     = (int) $row['id'];
	    $autore = htmlspecialchars($row['author']);
	    $testo  = htmlspecialchars($row['data']); 
	    print "\nAutore: ".$autore."<br/>\n";
	    print "\nTesto: ".$testo."<br/>\n";
	    print "\n<a href=\"editcomment.php?id=".$id."&autore=".$autore."&testo=".$testo."\">Modifica Commento</a><br/>\n";
    }	
}
/*Funzione per modificare commenti!*/
function e_comment($id, $autore, $testo){
	$id = (int) $id;
	$testo = mysql_real_escape_string($testo);
	$table  = PREFIX."comment";
	$query  = "UPDATE ".$table." SET author = '{$autore}', data = '{$testo}' WHERE id = '{$id}';";
	$result = mysql_query($query) or die(mysql_error());
	print "\nCommento Modificato con Successo!";
	print "\n<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
}

/*Funzione per modificare il proprio profilo*/
function e_profile($id, $email, $password, $website) {
		$id       = (int) $id;
		$table    = PREFIX."users";
		$password = md5($password);
		$query  = "UPDATE ".$table." SET email = '{$email}', password = '{$password}', web_site = '{$website}' WHERE id = '{$id}';";
		$result = mysql_query($query) or die(mysql_error());
		print "\nDati cambiati con successo!";
}

/*Funzione per mostrare il profile degli utenti*/
function p_profile($id) {

	$id       = (int) $id;
	$table    = PREFIX."users";
	$sqlquery = mysql_query("SELECT * FROM {$table} WHERE id = '{$id}'");
	
	while($row = mysql_fetch_assoc($sqlquery)) {		
    	$user   = htmlspecialchars($row['username']);
    	$email  = htmlspecialchars($row['email']);
    	$level  = htmlspecialchars($row['level']);
    	print "\nUsername: {$user}<br />";
    	print "\nEmail: {$email}<br />";
    	print "\nLevel: {$level}<br />";
		if (c_admin() == '1' && $level != "admin") {
		print "\nChange Level: <select onchange=\"OnSelectionChange(this)\">
    	<option value =\"admin\">Admin
    	<option value =\"mod\">Mod
    	<option value =\"user\">User
    	</select><br />";
	}
   }
}
	
/*Funzione per stampare la userlist!*/	
function userlist() {
	$table  = PREFIX."users";
	$query  = "SELECT * FROM ".$table.";";
	$result = mysql_query($query) or die(mysql_error());
	print "\n<center><table style=\"text-align: left;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\"> <tbody>";

	while ($row = mysql_fetch_array($result)) {
    	$id      = (int) $row['id'];
	    $user    = htmlspecialchars($row['username']);
	    $level   = htmlspecialchars($row['level']); 
	    $email   = $row['email']; 
	    $website = $row['web_site']; 
        print "\n<tr>"
            . "\n<td width='23%' style=\"vertical-align: top;\">UserName: <a class='linkz' href='profile.php?id=".$row['id']."'>{$user}</a><br />"
            . "\n</td>"
            . "\n<td width='12%' style=\"vertical-align: top;\">Level: {$level}<br />"
            . "\n</td>"
            . "\n<td width='23%' style=\"vertical-align: top;\">Email: {$email}<br />"
            . "\n</td>"
            . "\n<td width='20%' style=\"vertical-align: top;\">Web Site: {$website}<br />"
            . "\n</td>"
            . "\n</tr>";
	}
    print "\n</tbody>"
        . "\n</table></center><br />";
}

function register($username, $password, $email, $sito) {
	$table = PREFIX."users";
	$sqlquery  = "SELECT username FROM  ".$table." WHERE username = '{$username}';";
	$sqlquery2 = "SELECT username FROM  ".$table." WHERE email = '{$email}';";
	$result  = mysql_query($sqlquery);
	$result2 = mysql_query($sqlquery2);
	$num  = mysql_num_rows ($result);
	$num2 = mysql_num_rows ($result2);
	
	if($num == '0') { 
		if($num2 == '0') {
        	$query = "INSERT INTO ".$table." (username, password, level, text, background, email, web_site) VALUES ('{$username}', '{$password}', 'user', '#FFFFFF', '#000000', '{$email}', '{$sito}');";
        	mysql_query($query) or die("SQL Error: ".mysql_error());
        	print "\n<script>alert('Utente registrato!')</script>";
        	print "\n<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
        }else{
	        print "\n<script>alert('Email già registrata!')</script>";
        	print "\n<meta http-equiv=\"refresh\" content=\"0;url=register.php\">";
        }
	}else{
    	print "\n<script>alert('Nome utente già registrato!')</script>";
    	print "\n<meta http-equiv=\"refresh\" content=\"0;url=register.php\">";
	}
}

/*funzioni per fine html e per menu html*/
function pe_html(){
	print '<p><a href="http://jigsaw.w3.org/css-validator/check/referer">'
	    . '<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="CSS Valido!" /></a></p>';
}

function pm_html() {
	print '<body>';
	print'<div id="main">
	<aside id="left_menu">
	<center>Menu</center><br/>';
	include("login.php");
	print'<br/><br/>...</aside>';
	print '<aside id="center_blog">';
}

/*Fine funzioni html*/
function change_password($nuovapass) {
    $username = parse($_SESSION['user']);
    $table = PREFIX."users";
    $query = "UPDATE ".$table." SET password = '{$nuovapass}' WHERE username = '{$username}';";
    mysql_query($query) or die("MySQL error: ".mysql_error());
    print "\n<script>alert('Password cambiata!')</script>";
    print "\n<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
}

function c_permission($id,$user2){/*id = id del utente corrente del quale si vuole sapere se il level (i privilegi) sono maggiori o no del secondo utente(del quale si passerà l'username*/
    $id      = (int) $id;
    $id2     = (int) f_id($user2);
    $prefixx = PREFIX;
    $table   = $prefixx."users";
    $query1  = mysql_query("SELECT * FROM {$table} WHERE id = '{$id}'");
    
    while($row = mysql_fetch_assoc($query1)) {
        $level1 = htmlspecialchars($row['level']);
    }
    
    /*converto in valori numerici i livelli della var livel1*/
    $level1 = str_replace('admin','3',$level1);
    $level1 = str_replace('mod','2',$level1);
    $level1 = str_replace('user','1',$level1);
    
    $query2 = mysql_query("SELECT * FROM {$table} WHERE id = '{$id2}'");
    while($row = mysql_fetch_assoc($query2)) {
        $level2 = htmlspecialchars($row['level']);
    }
    
    $level2 = str_replace('admin','3',$level2);
    $level2 = str_replace('mod','2',$level2);
    $level2 = str_replace('user','1',$level2);
    
    if($level1 > $level2)
        return 1;
    else
        return 0;
}

function b_user($user){
	if(c_admin() == '1') {
        $table = PREFIX."ban_ip";
		$query = "INSERT INTO {$table} (user_id) VALUES ('{$user}');";
		mysql_query($query) or die("MySQL error: ".mysql_error());
		print "\nUtente Bannato!";
		print "\n<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
	}else{
		error3();
		}
}
	
function c_ban($user){
    $table  = PREFIX."ban_ip";
    $user   = mysql_real_escape_string($user);
	$query  = "SELECT * FROM {$table} WHERE user_id='{$user}';";
    $result = mysql_query($query);
    
	if(mysql_num_rows($result)) 
		return 1;
	else
		return 0;
}

/*Funzioni per errori _!!Da tenere alla fine del file !!_*/

function error1() {
    print "<meta http-equiv=\"refresh\" content=\"2;url=index.php\">";
    die("Logga prima di accedere a questa pagina!");
}

function error2() {
    print "<meta http-equiv=\"refresh\" content=\"2;url=index.php\">";
    die("Non hai i permessi per accedere a questa pagina!");
}

function error3() {
    print "<meta http-equiv=\"refresh\" content=\"2;url=index.php\">";
    die("Non hai i permessi per eseguire quest'azione");
}
/*Fine funzioni per errori*/
?>
