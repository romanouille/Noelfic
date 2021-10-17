<?php
$userId = $match[0];
$rememberHash = $match[1];

$message = "";
$showForm = true;

$user = new User($userId);
if (!$user->exists) {
	$message = "Cet utilisateur n'existe pas.";
	$showForm = false;
} elseif (empty($user->rememberHash)) {
	$message = "Votre compte n'a subit aucune demande de réinitialisation de mot de passe.";
	$showForm = false;
} elseif ($user->rememberHash != $rememberHash) {
	$message = "Le lien de réinitialisation est incorrect. En cas de problème, veuillez réessayer ou contacter un administrateur.";
	$showForm = false;
} else {
	if (count($_POST) > 0) {
		if (!isset($_POST["password"]) || !is_string($_POST["password"])) {
			$message = "Vous devez saisir le nouveau mot de passe.";
		} elseif (strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 72) {
			$message = "Votre nouveau mot de passe doit se composer d'au minimum 8 caractères et d'au maximum 72 caractères.";
		} elseif (!isset($_POST["password2"]) || !is_string($_POST["password2"])) {
			$message = "Vous devez confirmer le nouveau mot de passe.";
		} elseif ($_POST["password"] != $_POST["password2"]) {
			$message = "Les mots de passe ne correspondent pas.";
		} else {
			$user->changePassword($_POST["password"]);
			$user->clearRememberHash();
			
			$message = "Votre mot de passe a été réinitialisé, vous pouvez désormais vous connecter.";
			$showForm = false;
		}
	} else {
		$message = "Veuillez saisir le nouveau mot de passe du compte.";
	}
}

require "Pages/Account_password_reset_form.php";