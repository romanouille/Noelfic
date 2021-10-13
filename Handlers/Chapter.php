<?php
require "Core/Chapter.class.php";
require "Core/Comment.class.php";
require "Core/Fic.class.php";

$ficId = $match[0];
$ficSlug = $match[1];
$chapterNb = $match[2];

// On vérifie si la fic existe et si le chapitre existe
$fic = new Fic($ficId);
if (!$fic->exists || $chapterNb == 0 || $chapterNb > $fic->chapters) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if ($fic->deleted) {
	http_response_code(410);
	require "Handlers/Error.php";
}

// Si le slug envoyé par le client est incorrect, on lui renvoie le bon
if ($ficSlug != slug($fic->title)) {
	header("Location: /fic/$ficId-".slug($fic->title)."/$chapterNb");
	exit;
}

$chapter = new Chapter($fic->getChapterId($chapterNb));
$chapterUser = new User($chapter->author);
$comments = $chapter->getComments();
$fic->addView();
$chapter->addView();

require "Pages/Chapter.php";