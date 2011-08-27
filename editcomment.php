<?php
require_once("functions.php");

if (c_admin() == 1) {
	$id = (int) $_GET['id'];
	$table = PREFIX."comment";
    $query = mysql_query("SELECT * FROM {$table} WHERE id = '{$id}'");
    while($row = mysql_fetch_assoc($query)) {
		$autorec = $row['author'];
		$testoc = $row['data'];
		}
	p_html();
?>
<form method = "POST" >
Modifica Commento<br/>
<br>
Autore:<br>
<input name="autore" value="<?php print $autorec; ?>"><br/>
<br>
Testo:<br>
<textarea cols="30" rows="15" name="testo"><?php print $testoc; ?></textarea><br/>
<br>
<input value="Modifica Commento" type="submit">
</form>
<?php
    if(!empty($_GET['id']) &&
        !empty($_POST['autore']) &&
        !empty($_POST['testo'])
      ) {
        $id     = (int) $_GET['id'];
        $autore = htmlspecialchars($_POST['autore']);
        $testo  = htmlspecialchars($_POST['testo']);
        
        //modifico
        e_comment($id, $autore, $testo);
    }else
        print "Riempire tutti i campi<br />";
}else{
	error2();
}

?>
