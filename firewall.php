<?php
/*Piccolo firewall di protezione con sistema di log e di avvertimento via mail.
 * 
 * by
 * Emp£Hack
 * Il seguente script è ancora in BETA! 
 * Per bug:
 * ema.muna95@hotmail.it
 * or
 * admin@netcoders.org
 */

//CONFIG
/* Indirizzo Email dell'admin*/
define('ADMINMAIL', 'admin@netcoders.org');
 
$bloccati = array();
$tempo = NULL;

/*Non conviene vengon bloccati coloro che arrivan attraverso google in una tua pagina*/
$protezione_referrer = FALSE;
$protezione_cookie   = FALSE;

/*Attivare!*/
$protezione_url      = TRUE;

/**utilizzare require_once **/
/*Creo una funzione per prendere l'ip anche se proxato*/
function get_ip() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	elseif ( isset($_SERVER['HTTP_CLIENT_IP']))
		$ip = getenv('HTTP_CLIENT_IP');
	else
		$ip = getenv('REMOTE_ADDR');

    return $ip;
}

/*Creo una funzione per prelevare il referrer (se compare)*/
function get_referrer() {
	if (isset($_SERVER['HTTP_REFERRER']))
		$referrer = getenv('HTTP_REFERRER');
	else
		$referrer = FALSE;

	return $referrer;
}

/*Creo una funzione per ottenere l'user agent */
function get_user_agent() {
	$http_agent = getenv('HTTP_USER_AGENT');
	
    if (isset($http_agent))
    	$user_agent = getenv('HTTP_USER_AGENT');
    else
	    $user_agent = FALSE;
	    
    return $user_agent;
}

/*vedo la query inviata (se presente!)*/
function get_query_string() {
	$query_string = str_replace('%09', '%20', getenv('QUERY_STRING'));
	return $query_string;
}

/* vedo che tipo di richiesta è stata effettuata*/
function get_request_method() {
	if(isset($_SERVER['REQUEST_METHOD']))
		$method = getenv('REQUEST_METHOD');
	else
        $method = FALSE;
}

/*Apro il file, metto dentro il log completo, poi mando alla funzione send_mail il contenuto*/
function logger($attacco) {
	$ip = get_ip();
	$user_agent = get_user_agent();
	$richiesta_url = "(http://".$_SERVER['HTTP_HOST'].")".$_SERVER['REQUEST_URI'];
	$referrer = get_referrer();
	
	if (!file_exists('logs.txt')) {
    	$apostrofo = date("j");
    	
    	if ($apostrofo == "1" || $apostrofo == "8" || $apostrofo == "11")
	    	$l = "l'";
	    else
    		$l = "il";

    	$logfile = fopen('logs.txt','a+');
    	fwrite($logfile,"File di log creato ".$l." ".date("j F")." del ".date("Y")." alle ore ".date("g:i a")."\n"."\n");
    	fclose($logfile);
    	usleep(100);
	}
	
	$logfile = fopen('logs.txt', 'a+');
	$contents = date("F j, Y, g:i a")." ...! ".$attacco." !... IP: ".$ip." | Agent: ".$user_agent." | URL: ".$richiesta_url." | Referer: ".$referrer."\n\n";   
	fwrite($logfile, $contents);
	fclose($logfile);
	send_email($contents);
}

/*funzione per inviare mail al'admin con i log*/
function send_email($messaggio) {
	
	//if (isset($adminmail)) {
		$to = ADMINMAIL;
		$subject = 'Attacco';
		$headers = 'From: admin@netcoders.org'."\r\n".'Reply-To: admin@netcoders.org' . "\r\n".'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $messaggio, $headers);
    //}
}

/*Se la funzione di protezione cookie è abilitata controllo che nei cookie non siano presenti determinate parole*/
if ($protezione_cookie === TRUE) {
	$denyck = Array('base', 'bgsound', 'blink', 'embed', 'expression', 'frame', 'javascript', 'layer', 'link', 'meta', 'object', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onreadystatechange', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload', 'script', 'style', 'title', 'vbscript');
    foreach($_COOKIE as $value) {
		$check = str_replace($denyck, '*', $value);
		if( $value != $check ) {
			logger( 'Modifica cookie!' );
			unset( $value );
		}
	}
}

/*Usando query_string controllo non ci siano caratteri e parole non consentite*/
if ($protezione_url === TRUE) {	
	$denyurl = array( 'alert(', 'alert%20', 'and', 'chmod', 'config.php', '/*', 'db_mysql.inc', 'union', 'document.cookie', 'drop%20','drop', 'file_get_contents', 'file_put_contents', 'fopen', 'fputs', 'fwrite', 'http:', '.htpasswd', 'char','--','*/','/**/','#', 'img src', 'img%20src', '.inc.php', 'insert%20into', 'select', '.jsp', '.js', 'mv%20', 'new_password', 'outfile', 'passwd%20', 'phpadmin', 'perl%20', 'hex', '%20rm', 'require', 'rmdir%20', 'rmdir(', 'phpinfo()', '<?php', 'reboot%20', '%20and%20', 'select%20', 'select from', 'select%20from', 'server', 'system', '<script>', '</script', 'union%20', 'union(', 'union=', 'wget%20', 'wget(', '$_request', '$_get', '$request', '$get',  '&aim', '/etc/password', '/etc/gshadow', 'mysql_query', '/etc/passwd', '?>', '>', '<', 'font', 'char');
	$query_string = get_query_string();
	if (!$query_string === FALSE){
		$check = str_replace($denyurl, '*', $query_string );
		if( $query_string != $check ) {
			logger( 'URL attaccata' );
			die("<center><font size=6>Presumibile attacco bloccato!</center></font>");
		}
	}
}
/*Protezione del referrer*/
if ($protezione_referrer === TRUE) {
	$denyref = Array('and','/*','--','union','/**/');
	if (isset($_SERVER['HTTP_REFERER'])) {
	    if ( !stripos( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], 0 ) ) {
			logger( 'Referrer non coincidente con il server, richiesta esterna' );
			$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			die("Stai visitando una pagina con un referrer diverso da quello del server! Probabilmente sei stato portato quì da google :D
			<a href='".$url."'>Clicca qua' per visitare la pagina che stai cercando! :D</a>");
		}
		$referrer_string = getenv('HTTP_REFERRER');
		$check = str_replace($denyref, '*', $referrer_string);
		
		if ($referrer_string != $check) {
		    logger('Referrer Attaccato!');
		    die("<center><font size=6>Presumibile attacco bloccato!</center></font>");
		}
	}
}

//QUESTA FUNZIONE NON CREDO FUNZIONI PROPRIO BENE :S
if (get_user_agent() == "Acunetix Web Vulnerability Scanner") {
	  logger('Acunetix ha scansionato il sito!');
      die("Acunetix, VIAAA!");
}
?>
