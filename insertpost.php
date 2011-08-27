<?php
include("functions.php");

if (isset($_SESSION['user'])) {	
    if (c_admin() == '1') {
        p_html();
        pm_html();
?>
<form method="post">
<div style="text-align: center;"><big><span style="font-weight: bold;">Insert
Article</span></big><br>
<br>
Section: <input name="sezione"><br>
<br>
Author: <input name="autore"><br>
<br>
Title: <input name="titolo"><br>
<br>
Text:<br>
&nbsp;<textarea name="testo" cols="140" rows="20" style="width: 70%; height: 311px;"></textarea><br>
<br>
<input value="Insert Article" type="submit">
</div>
<br>
<br>
</form>
</body>
</html>
<?php
        if(!empty($_POST['sezione']) && 
           !empty($_POST['autore'])  && 
           !empty($_POST['titolo'])  && 
           !empty($_POST['testo'])
          ) {
    	    $section = parse($_POST['sezione']);
    	    $author  = parse($_POST['autore']);
    	    $title   = parse($_POST['titolo']);
    	    $data    = nl2br( parse($_POST['testo']));//aggiunta di nl2br()
    	    //$data    = str_replace("'", '#3', $data);
    	    $replyof = "-1";
    	    
    	    $last = "0";
    	    $time = (date("G:i:s"));
    	    $date = (date("d-m-y"));
	    
            i_article($section, $author, $title,$data, $replyof, $last, $time, $date);
        }
    }else
        error2();
}else
	error1();
?>
