<?php
require_once("functions.php");
//p_html();
//pm_html();
$a_id=$_GET['id'];
?>
<form method="post" action="insertcomment.php?id=<?php echo $a_id ?>">
<div style="text-align: center;"><big><span style="font-weight: bold;">Inserisci
Commento</span></big><br>
<br>
eMail: <input type="email" name="email"><br>
<br>
Autore: <input name="autore"><br>
<br>

Titolo: <input name="titolo"><br>
<br>
Testo:<br>
&nbsp;<textarea cols="30" rows="15" name="testo"></textarea><br>
<br>
<img alt="" src="captcha.php"> <br>
<br>
Inserire Codice Captcha:<br>
<br>
<input name="cap"><br>
<br>
<input value="Inserisci Commento" type="submit"> </div>
<br>
<br>
</form>
</body>
</html>
<?php
$captcha = file_get_contents("cap.php");//funzionante solo con PHP >= 5

if(!empty($_GET['id'])      && 
   !empty($_POST['email'])  && 
   !empty($_POST['autore']) && 
   !empty($_POST['titolo']) && 
   !empty($_POST['testo'])  && 
   ($_POST['cap'] === $captcha)
  ) {
	$a_id = (int) $_GET['id'];
	$mail = parse($_POST['email']);
	
	$author = parse($_POST['autore']);
	$title  = parse($_POST['titolo']);
	$data   = parse($_POST['testo']);
	//$data   = str_replace("'", '"', $data);
	$replyof = "-1";
	
	$last = "0";
	$time = (date("G:i:s"));
	$date = (date("d-m-y"));

    i_comment($a_id, $author, $title, $data, $time, $mail, $date);
}
?>
