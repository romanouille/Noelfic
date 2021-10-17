<?php
$userId = $match[0];
$validationHash = $match[1];

$message = "";

$user = new User($userId);
if (!$user->exists) {
	$message = "Cet utilisateur n'existe pas.";
} elseif ($user->validated) {
	$message = "Votre compte est déjà validé.";
} elseif ($user->validationHash != $validationHash) {
	$message = "Le lien de validation est incorrect. En cas de problème, veuillez contacter un administrateur.";
} else {
	$user->validate();
	$message = "Votre compte a été validé, vous pouvez maintenant vous connecter.";
}

require "Pages/Account_validation.php";