<?php
require("Core/Fic.class.php");

foreach ($ficsTypes as $id=>$type) {
	if (slug($type) == $match[0]) {
		$listType = $id;
	}
}

if (!isset($listType)) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$list = Fic::getList(1, $match[1], $listType);
if (empty($list["data"])) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$data = [];
foreach ($list["data"] as $id) {
	$fic = new Fic($id);
	$data[$id] = $fic;
}

require "Pages/Fics_list_type.php";