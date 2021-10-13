<?php
http_response_code(503);
require "Core/Captcha.class.php";
require "Core/Mail.class.php";

$created = false;


$created = true;
$messages[] = "Le formulaire d'inscription revient ce week-end.";

if (count($_POST) > 0) {
	$messages = [];
	
	if (!isset($_POST["username"]) || !is_string($_POST["username"])) {
		$messages[] = "Vous devez spécifier votre pseudo.";
	} elseif (!preg_match($config["users"]["username_regex"], $_POST["username"])) {
		$messages[] = "Votre pseudo doit se composer de 3 à 20 caractères.";
	} else {
		$user = new User(User::usernameToId($_POST["username"]));
		if ($user->exists) {
			$messages[] = "Le pseudo spécifié existe déjà, veuillez en choisir un autre.";
		}
	}
	
	if (!isset($_POST["email"]) || !is_string($_POST["email"])) {
		$messages[] = "Vous devez spécifier votre adresse e-mail.";
	} else {
		$_POST["email"] = strtolower($_POST["email"]);
		
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$messages[] = "L'adresse e-mail que vous avez spécifié est incorrecte.";
		} elseif (User::emailToId($_POST["email"]) != 0) {
			$messages[] = "Un pseudo existe déjà avec cette adresse e-mail, veuillez en choisir une autre.";
		} else {
			$_POST["email"] = strtolower($_POST["email"]);
			
			$mailData = explode("@", $_POST["email"]);
			if (strstr($mailData[0], "+") && $mailData[1] == "gmail.com") {
				$mailData[0] = explode("+", $mailData[0]);
				$mailData[0] = $mailData[0][0];
				
				$_POST["email"] = implode("@", $mailData);
				$mailData = explode("@", $_POST["email"]);
			}
			$mailDomain = $mailData[1];
			
			$mx = getDomainMx($mailDomain);
			foreach ($mx as $server) {
				if (in_array($server, $mxBlacklist) || in_array(gethostbyname($server), $mxIpBlacklist) || in_array($mailDomain, $mailDomainsBlacklist)) {
					$messages[] = "Votre hébergeur d'adresse email n'est pas accepté sur ce site, veuillez spécifier une autre adresse.";
					break;
				}
			}
		}
	}

	if (!isset($_POST["password"]) || !is_string($_POST["password"])) {
		$messages[] = "Vous devez spécifier votre mot de passe.";
	} elseif (strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 72) {
		$messages[] = "Votre mot de passe doit se composer d'au minimum 8 caractères et d'au maximum 72 caractères.";
	}
	
	if (!isset($_POST["password2"]) || !is_string($_POST["password2"])) {
		$messages[] = "Vous devez confirmer votre mot de passe.";
	} elseif (strlen($_POST["password2"]) < 8 || strlen($_POST["password2"]) > 72) {
		$messages[] = "Votre mot de passe doit se composer d'au minimum 8 caractères et d'au maximum 72 caractères.";
	}
	
	if (!isset($_POST["rules"])) {
		$messages[] = "Vous devez accepter la charte.";
	}

	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$validationHash = randomHash();
		
		$user = new User;
		$user->username = (string)$_POST["username"];
		$user->password = $user->makePassword($_POST["password"]);
		$user->email = (string)$_POST["email"];
		$user->registrationTimestamp = time();
		$user->lastSeenTimestamp = time();
		$user->validationHash = $validationHash;
		$user->save();
		
		$created = true;
		
		if (Mail::send($_POST["email"], "Validation de votre compte", "Bonjour,\n\nafin de valider votre compte \"{$user->username}\", veuillez cliquer sur le lien suivant : https://{$_SERVER["HTTP_HOST"]}/compte/valider/{$user->id}-$validationHash\n\nA bientôt sur Noelfic. :)")) {
			$messages[] = "Votre compte a été créé, vous devez désormais cliquer sur le lien de vérification qui vous a été envoyé par mail.<br>Si vous ne recevez pas le mail, vérifiez qu'il ne soit pas dans vos spams, ou merci de contacter un <a href=\"/contact\" title=\"Contact\" target=\"_blank\">admin</a> pour signaler que vous n'avez pas reçu de mail";
		} else {
			$user->verified = true;
			$user->save();
			
			$messages[] = "Votre compte a été créé, vous pouvez maintenant vous connecter.";
		}
	}
}

if ($userLogged) {
	http_response_code(403);
	require "Handlers/Error.php";
}

//Mail::send("admin@n-mail.fr", "Validation de votre pseudo", "Bonjour,\n\nafin de valider votre pseudo sur Noelfic.fr, veuillez vous rendre sur le lien suivant : <lien>\n\nA bientôt sur Noelfic.fr. :)");


require "Pages/Register.php";