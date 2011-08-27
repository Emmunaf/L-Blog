<?php
require_once("functions.php");
print '<body>';

$table = PREFIX."users";

if (!isset($_SESSION['user'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
	
	    if(strstr($_POST['username'],"'") === TRUE) {
	    	print "ERROR! Attento il tuo username contiene caratteri speciali come il singolo apice: '";}

    	$user = parse($_POST['username']);
		$pass = md5($_POST['password']);
    	$sql  = "SELECT * FROM $table WHERE username='$user' and password='$pass'";
    	$result = mysql_query($sql) or die(mysql_error());
    	$num_row = mysql_num_rows($result);
    	
    	if($num_row) {
    	
        	/*Prendo l'ip usato dall'utente e lo memorizzo*/
        	/*
        	$ip    = $_SERVER['REMOTE_ADDR'];
        	$id_p  = f_id($user,'1');
        	$query = "UPDATE ".$table." SET ip = '{$ip}' WHERE id = '$id_p';";
        	mysql_query($query) or die("SQL Error: ".mysql_error());
        	*/
        	/*Fine inserimento ip*/
        	
        	/*Controllo se l'utente non è bannato per user-id e se è vero memorizzo le sessioni*/
        	if(c_ban($user) == '0') {
            	$_SESSION['user']     = $user;
            	$_SESSION['password'] = $password;
            	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
            }elseif(c_ban($user) =='1') {
               /*Se è bannato faccio comparire un alert e reindirizzo alla index*/
                print "<script>alert('Utente bannato!')</script>";
                print "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
            }
	    }else {
	        print "<script>alert('Wrong Username or Password')</script>";
        	print "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
	    }
    }else{
	
        print '
        <div class="rounded_STYLE">
          <div class="tl"></div><div class="tr"></div>Login:
        <form method="POST" action="login.php">
        <input type="text" name="username" size ="9"><br />
        <input type="password" name="password" size="9"><br />
        <input type="submit" value="Log-in">
        </form><br/>
         <div class="bl"></div><div class="br"></div>
        </div><br/>
        <a class ="linkz" href="./register.php">Register</a><br />
        <a class ="linkz" href="./index.php">Home!</a><br />';	
	
    }
}else{
	$user = parse($_SESSION['user']);
	$id_p = f_id($user,'0');
	
	print "Benvenuto ".$user."!<br />
	<a class='logout' href='./logout.php'>Esci</a><br />
	<a class ='linkz' href='./profile.php?id=".$id_p."'>Profilo</a><br />
	<a class ='linkz' href='./index.php'>Home!</a><br />	";
	
	if (c_admin() == '1') {
    	print "<a class ='linkz' href='./insertpost.php'>Inser New Post</a><br />
	    <a class ='linkz' href='./userlist.php'>User list</a><br />
	    <a class ='linkz' href='./deletearticle.php'>Delete Article</a><br />
		<a href='./logout.php'>Admin Panel</a><br />";
	}
}
?>
