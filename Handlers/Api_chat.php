<?php
require "Core/Chat.class.php";
$result = [];

$roomId = $match[0];
$room = new Chat($roomId);

if (!$room->exists) {
	http_response_code(404);
	$result["message"] = "Le salon demandé n'existe pas.";
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$result["messages"] = $room->getMessages();
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {
	if (!$userLogged) {
		http_response_code(401);
		$result["message"] = "Vous devez être connecté pour accéder à cette ressource.";
		exit;
	}
	parse_str(file_get_contents("php://input"), $_POST);
	
	if (!isset($_POST["sessionToken"]) || !is_string($_POST["sessionToken"])) {
		http_response_code(400);
		$result["message"] = "Vous devez spécifier votre token.";
		exit;
	}
	
	if ($_POST["sessionToken"] != $sessionToken) {
		http_response_code(400);
		$result["message"] = "Le token spécifié est incorrect.";
		exit;
	}
	
	if (!isset($_POST["message"]) || !is_string($_POST["message"])) {
		http_response_code(400);
		$result["message"] = "Vous devez spécifier le message à envoyer.";
		exit;
	}
	
	if (strlen($_POST["message"]) < 2 || strlen($_POST["message"]) > 300) {
		http_response_code(400);
		$result["message"] = "Votre message doit se composer d'au minimum 2 caractères et d'au maximum 300 caractères.";
		exit;
	}
	
	if (time()-$loggedUser->getLastMessageTimestampOnChat($roomId) < 5) {
		http_response_code(429);
		$result["message"] = "Vous avez déjà envoyé un message il y a moins de 3 secondes, veuillez réessayer dans quelques instants.";
		exit;
	}
	
	$messageId = $room->addMessage($loggedUser->id, $_POST["message"]);
	$result["messageId"] = $messageId;
} else {
	http_response_code(501);
	$result["message"] = "Méthode non implémentée.";
	exit;
}