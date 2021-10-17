<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"  media="screen,projection">
		<link rel="canonical" href="https://<?=$_SERVER["HTTP_HOST"]?>/">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<?php

if ($handlerName == "Home.php") {
	$pageTitle = "Page d'accueil";
	$pageDescription = "Noelfic a pour vocation de regrouper toutes les histoires (et pas uniquement les fictions) des auteurs prolifiques des forums de jeuxvideo.com.";
} elseif ($handlerName == "Chapter.php") {
	$pageTitle = htmlspecialchars($fic->title)." - ".(!empty($chapter->title) ? htmlspecialchars($chapter->title) : "Chapitre $chapterNb")."";
	$pageDescription = substr(str_replace("  ", " ", str_replace("\r", "", str_replace("\n", " ", htmlspecialchars($chapter->content)))), 0, 157);
	if (strlen($chapter->content) > 157) {
		$pageDescription .= "..."; 
	} 
} elseif ($handlerName == "Fics_list_date.php") {
	$pageTitle = "Classement des fics par date";
	$pageDescription = "Classement des fics de Noelfic selon leurs dates de publication";
} elseif ($handlerName == "Fics_list_mark.php") {
	$pageTitle = "Classement des fics par note";
	$pageDescription = "Classement des fics de Noelfic selon leurs notes";
} elseif ($handlerName == "Fics_list_type.php") {
	$pageTitle = "{$ficsTypes[$listType]} - Classement des fics par genre";
	$pageDescription = "Classement des fics de Noelfic selon le genre {$ficsTypes[$listType]}";
} elseif ($handlerName == "Fics_list_views.php") {
	$pageTitle = "Classement des fics par popularité";
	$pageDescription = "Classement des fics de Noelfic selon leurs popularité";
} elseif ($handlerName == "Profile.php") {
	$pageTitle = "Profil de {$user->username}";
	$pageDescription = "Profil de $user->username sur Noelfic";
} elseif ($handlerName == "Search.php") {
	$pageTitle = "Recherche";
	$pageDescription = "Résultat de votre recherche sur Noelfic";
} elseif ($handlerName == "Error.php") {
	$pageTitle = "Erreur $errorCode";
	$pageDescription = "Erreur $errorCode";
} elseif ($handlerName == "Rules.php") {
	$pageTitle = "Charte";
	$pageDescription = "Bonjour jeune auteur, avec ou sans talent ! Parce qu'un bon site tel que Noelfic se doit d'obéir à certaines règles, voici donc la charte de Noelfic";
} elseif ($handlerName == "Register.php") {
	$pageTitle = "Inscription";
	$pageDescription = "Créer un compte sur Noelfic";
} elseif ($handlerName == "Login.php") {
	$pageTitle = "Connexion";
	$pageDescription = "Se connecter sur Noelfic";
} elseif ($handlerName == "Contact.php") {
	$pageTitle = "Contact";
	$pageDescription = "Contacter un membre du staff Noelfic";
} elseif ($handlerName == "Account_validation.php") {
	$pageTitle = "Valider mon compte";
	$pageDescription = "Valider mon compte sur Noelfic";
} elseif ($handlerName == "Changelog.php") {
	$pageTitle = "Changelog";
	$pageDescription = "Liste des modifications apportées à Noelfic";
} elseif ($handlerName == "Account_password_reset.php" || $handlerName == "Account_password_reset_form.php") {
	$pageTitle = "Réinitialiser mon mot de passe";
	$pageDescription = "Formulaire de réinitialisation de mot de passe de compte Noelfic";
}


if (!isset($pageTitle) || !isset($pageDescription)) {
	$pageTitle = "Noelfic";
	$pageDescription = "Noelfic";
} else {
	$pageTitle .= " - Noelfic";
}
?>
		<meta property="og:title" content="<?=$pageTitle?>">
		<meta property="og:type" content="article">
		<meta property="og:url" content="https://<?=$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>">
		<meta property="og:image" content="https://<?=$_SERVER["HTTP_HOST"]?>/img/noel.png">
		<meta property="og:locale" content="fr_FR">
		<meta property="og:description" content="<?=$pageDescription?>">
		<meta name="description" content="<?=$pageDescription?>">
		<title><?=$pageTitle?></title>
		<style>
html, body {
	height:100%;
	margin:0
}

main {
	min-height:100%
}

#banner {
	background:url('/img/banner_background.png') repeat-x;
	height:141px;
	/*min-width:900px;*/
	width:100%;
	margin-bottom:20px;
}

#logo {
	background:url('/img/logo.png') no-repeat;
	height:100%;
	width:100%;
	float:left;
}

.menu .btn {
	margin-bottom:10px;
	width:100%;
}

.home-list-title {
	width:20%;
}

.home-list-chapter {
	width:60%;
	text-align:center;
}

.home-list-date {
	width:20%;
	text-align:center;
}

.collection .collection-item.avatar .title {
	font-weight:bold;
}

.chat-date {
	font-size:10px;
}

.embed-container {
	position:relative;
	padding-bottom:56.25%;
	height:0;
	overflow:hidden;
	max-width:100%; 
}

.embed-container iframe, .embed-container object, .embed-container embed {
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height:100%;
}

.captcha-center {
	margin:0 auto;
	width:304px;
}

input::placeholder {
	color:#737C93
}
		</style>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	</head>
	
	<body class="grey lighten-4">
		<div id="banner"><div id="logo"></div></div>
		
		<main class="row">
			<div class="col s12 m12 l2 menu">
				<a href="/" title="Page d'accueil" class="waves-effect waves-light btn grey darken-3">Accueil</a><br>
				<a href="/charte" title="Charte" class="waves-effect waves-light btn grey darken-3">Charte</a><br>
<?php
if (!$userLogged) {
?>
				<a href="/compte/connexion" title="Connexion" class="waves-effect waves-light btn grey darken-3">Connexion</a>
<?php
}

if ($userLogged) {
?>
Connecté en tant que <a href="/profil/<?=strtolower($loggedUser->username)?>" title="Profil de <?=$loggedUser->username?>" target="_blank"><?=$loggedUser->username?></a>
<?php
}

if ($handlerName == "Chapter.php" && http_response_code() == 200) {
?>
				<br>
				
				<h5 class="center-align">Chapitres</h5>
				Aller au chapitre : <div class="input-field inline"><input type="text" id="chapter" placeholder="..." onkeypress="handleChapterKey(event)"></div>
				<ul class="collection">
<?php
	$nb = 1;
	$chapterCond = $chapterNb > 6 ? $chapterNb-5 : 1;
	if ($chapterCond > 1) {
?>
					<a href="/fic/<?=$ficId?>-<?=$ficSlug?>/1" title="Chapitre 1>"><li class="collection-item">Chapitre 1</li></a>
<?php
	}

	for ($i = $chapterCond; $i <= $fic->chapters; $i++) {
		if ($nb > 10) {
			break;
		}
?>
					<a href="/fic/<?=$ficId?>-<?=$ficSlug?>/<?=$i?>" title="Chapitre <?=$i?>"><li class="collection-item">Chapitre <?=$i?></li></a>
<?php
		$nb++;
	}

	if ($i < $fic->chapters) {
?>
					<a href="/fic/<?=$ficId?>-<?=$ficSlug?>/<?=$fic->chapters?>" title="Chapitre <?=$fic->chapters?>"><li class="collection-item">Chapitre <?=$fic->chapters?></li></a>
				</ul>
<?php
	}
}
?>
			</div>
			
			<div class="col l8">
				<!--<div class="card orange">
					<div class="card-content white-text">
						Noelfic est temporairement en accès limité.<br>
						<a href="/changelog" title="Changelog" target="_blank">Changelog</a>
					</div>
				</div>-->