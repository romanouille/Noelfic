<?php
require "Core/Chapter.class.php";
require "Core/Chat.class.php";
require "Core/Fic.class.php";

$recentsChapters = Fic::getRecentsChapters();
$fic = new Fic($newsFicId);
$penseedeo = new Chapter($fic->getChapterId($fic->chapters));
$penseedeoUser = new User($penseedeo->author);

$chat = new Chat(1);
$chatMessages = $chat->getMessages();

require "Pages/Home.php";