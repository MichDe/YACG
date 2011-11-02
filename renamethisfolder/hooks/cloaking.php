<?php // IPCLOACK HOOK
if (!DEBUG) error_reporting(0);

if (CLOAKING_LEVEL != 4) {
	$timestamp = filemtime(FILE_BOTS);
	$lastupdated = date("Ymd",$timestamp);
	if ($lastupdated != date("Ymd")) {
		$lists = array(
		'http://labs.getyacg.com/spiders/google.txt',
		'http://labs.getyacg.com/spiders/inktomi.txt',
		'http://labs.getyacg.com/spiders/lycos.txt',
		'http://labs.getyacg.com/spiders/msn.txt',
		'http://labs.getyacg.com/spiders/altavista.txt',
		'http://labs.getyacg.com/spiders/askjeeves.txt',
		'http://labs.getyacg.com/spiders/wisenut.txt',
		);
		foreach($lists as $list) {
			$opt .= fetch($list);
		}
		$opt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $opt);
		$fp =  fopen(FILE_BOTS,"w");
		fwrite($fp,$opt);
		fclose($fp);
	}
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$host = strtolower(gethostbyaddr($ip));
	$file = implode(" ", file(FILE_BOTS));
	$exp = explode(".", $ip);
	$class = $exp[0].'.'.$exp[1].'.'.$exp[2].'.';
	$threshold = CLOAKING_LEVEL;
	$cloak = 0;
	if (stristr($host, "googlebot") && stristr($host, "inktomi") && stristr($host, "msn")) {
		$cloak++;
	}
	if (stristr($file, $class)) {
		$cloak++;
	}
	if (stristr($file, $agent)) {
		$cloak++;
	}
	if (strlen($ref) > 0) {
		$cloak = 0;
	}
	// PERFORM CLOAK DATA ANALYSIS
	if ($cloak >= $threshold) {
		$cloakdirective = 1;
	} 
	else {
		$cloakdirective = 0;
	}
}
?>