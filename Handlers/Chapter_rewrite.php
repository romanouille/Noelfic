<?php
require("Core/Fic.class.php");

if (strstr($_SERVER["REQUEST_URI"], "/integrale.php?titre=")) {
	$ficId = $match[0];
	$chapter = $match[1];
} else {
	$ficId = $match[1];
	$chapter = $match[0];
}

$ficId = Fic::getv2Id($ficId);
if ($ficId == 0) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$fic = new Fic($ficId);
if ($chapter > $fic->chapters) {
	http_response_code(404);
	require "Handlers/Error.php";
}

http_response_code(301);
header("Location: /fic/$ficId-".slug($fic->title)."/$chapter");