<?php
require "Core/Fic.class.php";

$listType = "date";
$listName = "date";

$list = Fic::getList(2, $match[0]);
if ($match[0] > $list["pages"]) {
	$errorCode = 404;
	require "Handlers/Error.php";
}

$data = [];
foreach ($list["data"] as $id) {
	$fic = new Fic($id);
	$data[$id] = $fic;
}


require "Pages/Fics_list.php";