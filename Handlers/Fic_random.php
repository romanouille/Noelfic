<?php
require "Core/Fic.class.php";

$ficId = rand(1, Fic::getTotalNb());
$fic = new Fic($ficId);

header("Location: /fic/$ficId-".slug($fic->title)."/1");