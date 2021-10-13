<?php
$config = parse_ini_file(".env", true);

$db = new PDO("pgsql:host={$config["db"]["server"]};dbname={$config["db"]["database"]}", $config["db"]["username"], $config["db"]["password"], [PDO::ATTR_PERSISTENT => true]);

if (PHP_OS == "WINNT") {
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$newUsernameRegex = "#^([A-Za-z0-9-_\[\]]{3,20}+)$#";
$encryptionKey = "}rb,h-fSkJ:r]d#bpQ5J!5vKXd(KkJ(XF\=#M6~)7N*-.s&?:v93K>^}`}2Zx+HK[VP5cz^aYv_v8(<TanpCRY_dgsQzvdG#]AjN";

$mailDomainsBlacklist = [
	"theroyalweb.club"
];

$mxBlacklist = [
	"smtp.yopmail.com",
	"mail.5-mail.info",
	"mail.businesssource.net",
	"mail.digital-work.net",
	"mail.easymail.top",
	"mail.fast-mail.one",
	"mail.first-email.net",
	"mail.mailsearch.net",
	"mail.red-mail.info",
	"mail.red-mail.top",
	"mail.the-first.email",
	"mail.desoz.com",
	"mail.bullstore.net",
	"mail.getnada.com",
	"mx1.mytemp.email",
	"mx2.mytemp.email",
	"mx3.mytemp.email",
	"generator.email",
	"mail.sharklasers.com",
	"mail.mailinator.com",
	"mail2.mailinator.com",
	"mail.mybx.site",
	"mx1.mailboxy.fun",
	"mx2.mailboxy.fun",
	"mx3.mailboxy.fun",
	"a.besttempmail.com",
	"dc-123eb55daced.ahem.email",
	"mail1.offsetmail.com",
	"mail2.offsetmail.com",
	"mx.supere.ml",
	"disbox.net",
	"sss.pp.ua",
	"mail.tempinbox.com",
	"flashmail.co",
	"smx1.web-hosting.com",
	"smx2.web-hosting.com",
	"smx3.web-hosting.com",
	"reject1.heluna.com",
	"mail.maildrop.cc",
	"reject2.heluna.com",
	"mail.shitmail.org",
	"mail.sellcow.net",
	"nb-96-126-99-158.fremont.nodebalancer.linode.com",
	"mail.crazymailing.com",
	"mintserver.mintemail.com",
	"mx.discard.email",
	"mail.sharklasers.com",
	"mail.20minutemail.it",
	"in.mailsac.com",
	"alt.mailsac.com",
	"mail.nowmymail.net",
	"wbdev.tech",
	"dtools.info",
	"mx10.mailspamprotection.com",
	"mx20.mailspamprotection.com",
	"mx30.mailspamprotection.com",
	"smi.ooo",
	"mail.yevme.com",
	"smtp.trashmail.com",
	"smtp2.trashmail.com",
	"mail.eyepaste.com",
	"mailnesia.com",
	"mail.dispostable.com"
];

$mxIpBlacklist = [
	"87.98.164.155", // yopmail
	"89.38.99.80", // temp-mail.org,
	"173.230.139.246", //tempail.com
	"107.191.99.34", // tempmailaddress
	"144.217.66.117", // getnada.com
	// mytemp.email
	"176.126.236.241",
	"2a02:59e0:0:9::3",
	"176.126.236.241",
	"2a02:59e0:0:9::3",
	"176.126.236.241",
	"2a02:59e0:0:9::3",
	
	"51.38.115.65", // emailfake.com,
	"167.114.101.158", // guerrillamail.com
	"23.239.11.30", // mailinator
	"45.33.83.75", // mailinator
	"163.172.34.235", // throwawaymail.com
	"165.227.245.168", // mohmal.com
	"47.88.63.72", // besttempmail.com
	"104.248.49.235", // ahem.email,
	"147.135.80.194", // offsetmail.com
	"159.69.193.248", // dropmail.me
	"69.162.74.142", // disbox.net
	"51.38.115.65", // generator.email
	"64.38.116.57", // tempinbox.com
	"54.37.203.27", // temp-mails.com
	"162.255.118.61", // owlymail.com
	"54.243.45.224", // maildrop
	"52.27.89.239",
	"52.23.67.158",
	"92.222.23.140", // shitmail.org
	"107.161.23.226", // minuteinbox
	"96.126.99.158", // 10 minute inbox
	"2600:3c01:1::607e:639e",
	"188.40.100.92", // crazymailing.com
	"24.212.168.12", // mintemail.com
	"37.120.169.172", // tempr.email
	"167.114.101.158", // sharklasers.com
	"80.211.128.184", // 20 minute mail
	"52.10.8.95", // mailsac
	"54.186.130.2",
	"158.106.190.195", // nowmymail
	"80.211.174.25", // dtools.info
	"107.6.129.66", // temporary-email.com
	"107.6.149.11",
	"107.6.149.12",
	"81.4.103.102", // tempmail.net
	"136.243.65.157", // trashmail.com
	"159.69.18.187",
	"104.131.57.140", // eyepaste.com
	"172.106.75.153", // mailnesia
	"188.166.49.116" // dispostable
];

// Fic "penséedéo"
$newsFicId = 2447;

// Statuts possibles des fics
$ficsStates = [
	1 => "En cours",
	2 => "En cours, sweet quotidienne",
	3 => "C'est compliqué",
	4 => "Terminée",
	5 => "Abandonnée"
];

// Types des fics
$ficsTypes = [
	1 => "Action",
	2 => "BD",
	3 => "Concours",
	4 => "Fantastique",
	5 => "Horreur",
	6 => "Moins de 15 ans",
	7 => "Nawak",
	8 => "No-Fake",
	9 => "Polar",
	10 => "Réaliste",
	11 => "Sayks",
	12 => "Science-Fiction",
	13 => "Sentimental",
	14 => "Inconnu"
];

// Smileys
$smilies = [
	":)" => "1.gif",
	":snif:" => "20.gif",
	":gba:" => "17.gif",
	":g)" => "3.gif",
	":-)" => "46.gif",
	":snif2:" => "13.gif",
	":bravo:" => "69.gif",
	":d)" => "4.gif",
	":hap:" => "18.gif",
	":ouch:" => "22.gif",
	":pacg:" => "9.gif",
	":cd:" => "5.gif",
	":-)))" => "23.gif",
	":ouch2:" => "57.gif",
	":pacd:" => "10.gif",
	":cute:" => "nyu.gif",
	":content:" => "24.gif",
	":p)" => "7.gif",
	":-p" => "31.gif",
	":noel:" => "11.gif",
	":oui:" => "37.gif",
	":(" => "45.gif",
	":peur:" => "47.gif",
	":question:" => "2.gif",
	":cool:" => "26.gif",
	":-(" => "14.gif",
	":coeur:" => "54.gif",
	":mort:" => "21.gif",
	":rire:" => "39.gif",
	":-((" => "15.gif",
	":fou:" => "50.gif",
	":sleep:" => "27.gif",
	":-D" => "40.gif",
	":nonnon:" => "25.gif",
	":fier:" => "53.gif",
	":honte:" => "30.gif",
	":rire2:" => "41.gif",
	":non2:" => "33.gif",
	":sarcastic:" => "43.gif",
	":monoeil:" => "34.gif",
	":o))" => "12.gif",
	":nah:" => "19.gif",
	":doute:" => "28.gif",
	":rouge:" => "55.gif",
	":ok:" => "36.gif",
	":non:" => "35.gif",
	":malade:" => "8.gif",
	":fete:" => "66.gif",
	":sournois:" => "67.gif",
	":hum:" => "68.gif",
	":ange:" => "60.gif",
	":diable:" => "61.gif",
	":gni:" => "62.gif",
	":play:" => "play.gif",
	":desole:" => "65.gif",
	":spoiler:" => "63.gif",
	":merci:" => "58.gif",
	":svp:" => "59.gif",
	":sors:" => "56.gif",
	":salut:" => "42.gif",
	":rechercher:" => "38.gif",
	":hello:" => "29.gif",
	":up:" => "44.gif",
	":bye:" => "48.gif",
	":gne:" => "51.gif",
	":lol:" => "32.gif",
	":dpdr:" => "49.gif",
	":dehors:" => "52.gif",
	":hs:" => "64.gif",
	":banzai:" => "70.gif",
	":bave:" => "71.gif",
	":pf:" => "pf.gif",
	":cimer:" => "cimer.gif",
	":ddb:" => "ddb.gif",
	":pave:" => "pave.gif",
	":objection:" => "objection.gif",
	":siffle:" => "siffle.gif"
];

uksort($smilies, function($a, $b) { return strlen($b) - strlen($a); });

ob_start();
register_shutdown_function("stop");

if (isset($_COOKIE["session"]) && is_string($_COOKIE["session"])) {
	$userSession = new Session($_COOKIE["session"]);
	if ($userSession->userId > 0) {
		$userSession->update();
		$loggedUser = new User($userSession->userId);
		$loggedUser->updateLastSeenTimestamp();
	} else {
		setcookie("session", null, -1, "/", $websiteHost, $_SERVER["SERVER_PORT"] == 443, true);
		header("Location: {$_SERVER["REQUEST_URI"]}");
		exit;
	}
}

$sessionToken = isset($userSession) ? sha1($_COOKIE["session"]) : sha1($_SERVER["REMOTE_ADDR"]);
$userLogged = isset($userSession->userId);