<?php
/**
 * Fonction appelée à la fin du chargement d'une page
 */
function stop() {
	global $config, $phpErrors, $isApi, $result;
	
	$page = ob_get_contents();
	ob_end_clean();
	
	if ($isApi) {
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($result);
		exit;
	}
	
	if (!empty($phpErrors)) {
		if (PHP_OS != "WINNT") {
			http_response_code(500);
			$backtrace = openssl_encrypt(json_encode($phpErrors), "AES-256-CBC", $config["encryption"]["backtrace_key"]);
			echo <<<EOF
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>500 Internal Server Error</title>
		<meta charset="utf-8">
		<style>.backtrace { font-family:Courier; width:40%; word-wrap:break-word } p { margin-bottom:50px }</style>
	</head>
	
	<body>
		<h1>500 Internal Server Error</h1>
		<p>
			Cette page est temporairement indisponible, nous sommes désolé pour la gêne occasionée.<br>
			En attendant la résolution du problème, nous vous invitons à visiter le reste du site.
		</p>
		
		<h2>Backtrace</h2>
		<p class="backtrace">
			$backtrace
		</p>
	</body>
</html>
EOF;
			exit;
		}
	}
	
	$page = str_replace("	", "", str_replace("\r", "", str_replace("\n", "", $page)));
	$page = preg_replace("/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/", "", $page);
	echo $page;
}

/**
 * Retourne du texte enrichi sur le paramètre
 *
 * @param string $text Texte à enrichir
 *
 * @return string Résultat
 */
function richText(string $text, bool $allowReturns = true, bool $allowImg = false) : string {
	global $smilies;
	
	$bb1 = [
		"#\[b\](.+)\[\/b\]#iUs",
		"#\[i\](.+)\[\/i\]#iUs",
		"#\[s\](.+)\[\/s\]#iUs",
		"#\[u\](.+)\[\/u\]#iUs",
		"#https://([a-z]{0,3})\.?youtube\.com/embed/(.{11})#Ui",
		"#https://([a-z]{0,3})\.?youtube\.com/watch\?v=(.{11})#Ui"
	];

	$bb2 = [
		"<b>$1</b>",
		"<i>$1</i>",
		"<s>$1</s>",
		"<u>$1</u>",
		"<div class=\"embed-container\"><iframe src=\"https://www.youtube.com/embed/\\2\" frameborder=\"0\" allowfullscreen></iframe></div>",
		"<div class=\"embed-container\"><iframe src=\"https://www.youtube.com/embed/\\2\" frameborder=\"0\" allowfullscreen></iframe></div>"
	];

	$text = htmlspecialchars($text);
	
	
	
	if ($allowReturns) {
		$text = str_replace("<br />", "<br>", nl2br($text));
	}
	
	if ($allowImg) {
		$bb1[] = "#\[img\](.+)\[\/img\]#iUs";
		$bb2[] = "<img src=\"$1\" alt=\"\">";
	}

	foreach ($smilies as $smilie=>$file) {
		$text = str_replace($smilie, "<img src=\"/img/smileys/$file\" alt=\"$smilie\">", $text);
	}

	$text = preg_replace($bb1, $bb2, $text);
	
	$text = replace_links($text);

	return $text;
}

/**
 * Génère le slug d'une chaîne
 *
 * @param string $text Chaîne
 *
 * @return string Slug
 */
function slug(string $text) : string {
	$text = strtolower($text);
	
	$replace = [
		"é" => "e",
		"è" => "e",
		"ê" => "e",
		"à" => "a",
		"â" => "a",
		"ç" => "c",
		"î" => "i",
		"ô" => "o",
		"ù" => "u",
		"û" => "u"
	];
	
	foreach ($replace as $accent=>$letter) {
		$text = str_replace($accent, $letter, $text);
	}
	
	$chars = str_split("abcdefghijklmnopqrstuvwxyz0123456789-");
	$text = str_replace(" ", "-", strtolower($text));

	$text = str_split($text);
	
	foreach ($text as $id=>$char) {
		if (!in_array($char, $chars)) {
			unset($text[$id]);
		}
	}

	$text = implode("", $text);

	return $text;
}

/**
 * Gestionnaire des erreurs PHP
 *
 * @param int $errno Numéro de l'erreur
 * @param string $errstr Erreur
 * @param string $errfile Fichier source
 * @param int $errline Ligne source
 */
function errorHandler($errno, $errstr, $errfile, $errline) {
	global $phpErrors;
	
	$phpErrors[] = [
		"errno" => $errno,
		"errstr" => $errstr,
		"errfile" => $errfile,
		"errline" => $errline
	];
	
	return true;
}

/**
 * Remplace les URL par des liens cliquables, code tiré de Stackoverflow
 *
 * @param string $text Texte source
 *
 * @return string Résultat
 */
function replace_links( $text ) {    
	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);

	$ret = ' ' . $text;
	
	// Replace Links with http://
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);
	
	// Replace Links without http://
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);

	// Replace Email Addresses
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	
	return $ret;
}

/**
 * Récupère les données MX d'un domaine
 *
 * @param string $domain Domaine
 *
 * @return array Résultat
 */
function getDomainMx(string $domain) : array {
	getmxrr($domain, $result);
	
	return $result;
}

/**
 * Vérifie si un mot de passe est sécurisé grâce à l'API pwnedpasswords.com
 *
 * @param string $password Mot de passe à vérifier
 *
 * @return bool Résultat
 */
function isPasswordSecure(string $password) : bool {
	$hash = strtoupper(sha1($password));
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://api.pwnedpasswords.com/range/".substr($hash, 0, 5));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 3);
	curl_setopt($curl, CURLOPT_HTTPHEADER, ["X-Forwarded-For: {$_SERVER["REMOTE_ADDR"]}"]);
	curl_setopt($curl, CURLOPT_USERAGENT, "Noelfic Password Checker");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$page = curl_exec($curl);
	curl_close($curl);
	
	return !strstr($page, substr($hash, 5));
}

/**
 * Génère un hash aléatoire
 *
 * @return string Résultat
 */
function randomHash() {
	return md5(random_bytes(50).random_int(100000000, 999999999).microtime(1));
}