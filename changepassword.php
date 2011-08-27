<?php
require_once("functions.php");
p_html();
pm_html();
?>
<form method = "POST">
<h2>Modifica Password</h2>
<br>
*Nuova Password: <input name="nuovapass" type='password'><br>
<br>
*Nuova Password: <input name="cnuovapass" type='password'><br>
<input value="Cambia Password" type="submit"><br/>
</form>
</body>
</html>
<?php

$pass  = mysql_real_escape_string($_POST['nuovapass']);
$passc = mysql_real_escape_string($_POST['cnuovapass']);
$nuovapass = md5($pass);

if(isset($pass) && ($passc) && ($pass === $passc)) {
    change_password($nuovapass);
else
    print "*Riempire tutti i campi<br />";
?>
