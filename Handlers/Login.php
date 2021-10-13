<?php
if ($userLogged) {
	http_response_code(403);
	require "Handlers/Error.php";
}

require "Core/Captcha.class.php";
$messages = [];

if (count($_POST) > 0) {
	if (!isset($_POST["username"]) || !is_string($_POST["username"])) {
		$messages[] = "Vous devez spécifier votre pseudo.";
	} elseif (strlen($_POST["username"]) < 3 || strlen($_POST["username"]) > 20) {
		$messages[] = "Votre pseudo doit se composer de 3 à 20 caractères.";
	} else {
		$user = new User(User::usernameToId($_POST["username"]));
		if (!$user->exists) {
			$messages[] = "Le pseudo spécifié n'existe pas.";
		} else {
			if (!$user->validated) {
				$messages[] = "Votre compte n'a pas encore été validé. Vous devez cliquer sur le lien de confirmation qui vous a été envoyé par mail.";
			}
		}
	}

	if (!isset($_POST["password"]) || !is_string($_POST["password"])) {
		$messages[] = "Vous devez spécifier votre mot de passe.";
	} elseif (strlen($_POST["password"]) > 72) {
		$messages[] = "Votre mot de passe doit se composer d'au maximum 72 caractères.";
	}

	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}

	if (empty($messages)) {
		if ($user->checkPassword($_POST["password"])) {
			if ($user->username != $_POST["username"]) {
				$user->updateUsername($_POST["username"]);
			}
			
			$user->updateLastSeenTimestamp();
			
			$session = Session::create($user->id);
			setcookie("session", $session, time()+31536000, "/", $_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"] == 443, true);
			header("Location: /");
			exit;
		} else {
			$messages[] = "Le mot de passe spécifié est incorrect.";
		}
	}
}

if (empty($messages)) {
	$messages[] = "Vous tentez d'accéder à un contenu qui nécessite que vous soyez connecté.";
}

require("Pages/Login.php");