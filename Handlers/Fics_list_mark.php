<?php
require "Core/Fic.class.php";

$listType = "note";
$listName = "note";

$list = Fic::getList(3, $match[0]);
if ($match[0] > $list["pages"]) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$data = [];
foreach ($list["data"] as $id) {
	$fic = new Fic($id);
	$data[$id] = $fic;
}


require "Pages/Fics_list.php";