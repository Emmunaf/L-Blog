<?php
/*
 * Pagina schifosa e con uno schifo di controllo :S
 */

session_start();

if (!(isset($_SESSION['user']) && isset($_SESSION['password']))) {
    session_destroy();
    print "<meta http-equiv=\"refresh\" content=\"0;url=./index.php\">";
}else
    die("ERROR! Non sei loggato ritorna alla <a href=\"index.php\">Home Page</a>");

?>
