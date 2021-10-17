<?php
require "Core/Captcha.class.php";
require "Core/Mail.class.php";

$created = false;

if (isset($_POST["username"]) && is_string($_POST["username"])) {
	$messages = [];
	
	$userId = User::usernameToId($_POST["username"]);
	
	$user = new User($userId);
	if (!$user->exists) {
		$messages[] = "Le pseudo que vous avez spécifié n'existe pas.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$rememberHash = $user->generateRememberHash();
		
		Mail::send($user->email, "Réinitialisation de votre mot de passe", "Bonjour,\n\nafin de réinitialiser votre mot de passe Noelfic, veuillez cliquer sur le lien suivant : https://{$_SERVER["HTTP_HOST"]}/compte/mdp/reinitialiser/$userId-$rememberHash\n\nA bientôt sur Noelfic.fr. :)");
		
		$created = true;
		
		$messages[] = "Un mail de réinitialisation de votre mot de passe a été envoyé à l'adresse e-mail du pseudo spécifié.";
	}
}

require "Pages/Account_password_reset.php";