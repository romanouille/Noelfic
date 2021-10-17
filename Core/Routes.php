<?php
$routes = [
	"#^\/$#" => "Home.php",
	"#^\/charte$#" => "Rules.php",
	"#^\/fic\/([0-9]+)-([a-z0-9-]+)\/([0-9]+)$#" => "Chapter.php",
	"#^\/integrale\.php\?titre=([0-9]+)&chap=([0-9]+)$#" => "Chapter_rewrite.php",
	"#^\/integrale\.php\?chap=([0-9]+)&titre=([0-9]+)$#" => "Chapter_rewrite.php",
	"#^\/classement\/genre/([a-z0-9-]+)\/([0-9]+)$#" => "Fics_list_type.php",
	"#^\/classement\/date\/([0-9]+)$#" => "Fics_list_date.php",
	"#^\/classement\/note\/([0-9]+)$#" => "Fics_list_mark.php",
	"#^\/classement/popularite\/([0-9]+)$# "=> "Fics_list_views.php",
	"#^\/recherche\?q=(.*)&t=([1-1])&p=([0-9]+)$#" => "Search.php",
	"#^\/profil\/([A-Za-z0-9-_.\[\]]+)#" => "Profile.php",
	"#^\/fic\/hasard$#" => "Fic_random.php",
	"#^\/api\/chat\/([0-9]+)$#" => "Api_chat.php",
	"#^\/compte\/inscription$#" => "Register.php",
	"#^\/compte\/connexion$#" => "Login.php",
	"#^\/compte\/valider\/([0-9]+)-([a-f0-9]+)$#" => "Account_validation.php",
	"#^\/compte\/mdp\/reinitialiser\/([0-9]+)-([a-f0-9]+)$#" => "Account_password_reset_form.php",
	"#^\/compte\/mdp/reinitialiser$#" => "Account_password_reset.php",
	"#^\/contact$#" => "Contact.php"
];