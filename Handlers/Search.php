<?php
require "Core/Fic.class.php";

$searchText = urldecode($match[0]);
$searchType = $match[1];
$searchPage = $match[2];

if (empty(trim($searchText)) || $searchType != 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$list = Fic::search($searchType, $searchText, $searchPage);

if ($searchPage > $list["pages"] && $searchPage != 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if (!empty($list)) {
	$data = [];

	foreach ($list["data"] as $id) {
		$fic = new Fic($id);
		$data[$id] = $fic;
	}
}

if ($list["pages"] == 0) {
	http_response_code(404);
}

require("Pages/Search.php");