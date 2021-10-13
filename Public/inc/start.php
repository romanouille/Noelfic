<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Noelfic</title>
		<style>
#banner {
	background:url('img/banner_background.png') repeat-x;
	height:141px;
	/*min-width:900px;*/
	width:100%;
	margin-bottom:20px;
}

#logo {
	background:url('img/logo.png') no-repeat;
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
		</style>
	</head>
	
	<body class="grey lighten-4">
		<div id="banner"><div id="logo"></div></div>
		
		<div class="row">
			<div class="col s12 m12 l2 menu">
				<a class="waves-effect waves-light btn grey darken-3">Page d'accueil</a><br>
				<a class="waves-effect waves-light btn grey darken-3">Charte du site</a><br>
				<a class="waves-effect waves-light btn grey darken-3" disabled>S'inscrire</a>
				
				<br>
				
				<h5 class="center-align">Chapitres</h5>
				Aller au chapitre : <div class="input-field inline"><input type="text" name="chapter" placeholder="..."></div>
				<ul class="collection">
					<li class="collection-item">Chapitre 1</li>
					<li class="collection-item">Chapitre 1</li>
					<li class="collection-item">Chapitre 1</li>
					<li class="collection-item">Chapitre 1</li>
					<li class="collection-item">Chapitre 1</li>
				</ul>
			</div>
			
			<div class="col l8">