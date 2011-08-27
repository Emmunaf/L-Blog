<?php
include_once("functions.php");

$user = htmlspecialchars($_SESSION['user']); //Fix XSS By KinG-InFeT
$data = date ("d-m-Y");
$ora  = date ("H:i:s");

print "Benvenuto ".$user." oggi &egrave; ".$data." e sono le ore ".$ora."<br /><br />";

print "\n<a href='./insertpost.php'>New Post</a><br />"
    . "\n<a href='./userlist.php'>User list</a><br />"
    . "\n<a href='./deletearticle.php'>Delete Article</a><br />"
    . "\n<a href='./listcomment.php'>Edit Comment</a><br />"
    . "\n<a href='./listcomment.php'>Delete Comment</a><br />"
    . "\n<a href='./userlist.php'>User List</a><br />"
    . "\n<a href='./logout.php'>Esci/a><br />";
?>
