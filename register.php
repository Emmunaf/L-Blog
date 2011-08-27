<?php
require_once("functions.php");
p_html();
pm_html();
?>

<center>
<h2>Registrati</h2>
<br>
<form method="post">
UserName: <input name="user"><br>
<br>
Password: <input name="pass" type="password"><br>
<br>
Password: <input name="cpass" type="password"><br>
<br>
Email: <input type="email" name="email"><br>
<br>
Sito Web: <input type="url" name="sito">
<br>
<br>
<img alt="" src="captcha.php"> <br>
<br>
Inserire Codice Captcha:<br>
<br>
<input name="cap"><br>
<br>
<input value="Registrati" type="submit"> </form>
</center></body>
</html>
<?php
$captcha = file_get_contents("cap.php"); //funziona solo con file_get_contents abilitato in php.ini

if(!empty($_POST['user'])               &&
   !empty($_POST['pass'])               && 
   !empty($_POST['cpass'])              && 
   !empty($_POST['email'])              && 
   ($_POST['pass'] === $_POST['cpass']) && 
   ($_POST['cap'] === $captcha)
  ) {
    $username = parse($_POST['user']);
    $password = md5($_POST['pass']);
    
    $cpass = md5($_POST['cpass']);
    $email = htmlspecialchars($_POST['email']);

    
    if(!isset($_POST['sito']))
    	$sito = "";
    else
        $sito = mysql_real_escape_string($_POST['sito']);
        
    register($username, $password, $email, $sito);
}else{
    print "Riempire tutti i campi<br>";
}
