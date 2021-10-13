<?php
$username = $match[0];

if (strtolower($username) != $username) {
	header("Location: /profil/".strtolower($username));
	exit;
}

$userId = User::usernameToId($username);
if ($userId == 0) {
	http_response_code(404);
	require "Handlers/Error.php";
}

require "Core/Fic.class.php";

$user = new User($userId);
$userDays = floor((time()-$user->registrationTimestamp)/86400);
$chapters = $user->getChaptersList();

require "Pages/Profile.php";