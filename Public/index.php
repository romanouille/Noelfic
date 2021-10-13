<?php
if (PHP_OS == "WINNT") {
	error_reporting(-1);
	ini_set("display_errors", true);
} else {
	error_reporting(0);
	ini_set("display_errors", false);
}

setlocale(LC_ALL, "fr-fr");
set_include_path("../");
$phpErrors = [];

require "Core/Functions.php";
set_error_handler("errorHandler");

require "Core/Routes.php";
require "Core/User.class.php";
require "Core/Session.class.php";
require "Core/Init.php";

foreach ($routes as $route=>$handlerName) {
	if (preg_match($route, $_SERVER["REQUEST_URI"], $match)) {
		unset($match[0]);
		$match = array_values($match);
		$isApi = substr($_SERVER["REQUEST_URI"], 0, 4) == "/api";
		
		require "Handlers/$handlerName";
		exit;
	}
}

http_response_code(404);
require "Handlers/Error.php";