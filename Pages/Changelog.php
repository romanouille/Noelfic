<?php
require "Pages/Layout/Start.php";
?>
<h4>Changelog</h4>

<p>
	<ul class="collection with-header">
		<li class="collection-header"><h6><b>18/03/2020</b></h6>
		<li class="collection-item">Le site est (enfin) remis en ligne - toujours en accès limité - sur un serveur dédié.
	</ul>
	
	<ul class="collection with-header">
		<li class="collection-header"><h6><b>02/04/2019</b></h6>
		<li class="collection-item">Mise en place d'une page "changelog" affichant les nouveautés du site
	</ul>
	
	<ul class="collection with-header">
		<li class="collection-header"><h6><b>01/04/2019</b></h6>
		<li class="collection-item">La gestion des utilisateurs et des sessions a été développée, les utilisateurs peuvent désormais se connecter et s'inscrire
		<li class="collection-item">Le chat a été développé et est en ligne
		<li class="collection-item">Mise en ligne du site sur un serveur temporaire, le site sera prochainement migré vers un serveur de production plus adapté, puis un système de cache sera mis en place pour optimiser la rapidité du site
	</ul>
	
	<ul class="collection with-header">
		<li class="collection-header"><h6><b>30/03/2019 au 31/03/2019</b></h6>
		<li class="collection-item">Réécriture intégrale du back-end du site pour un usage temporairement en lecture seule
		<li class="collection-item">Conversion du schéma de base de données de la v1/v1.5 vers un format plus adapté au nouveau back-end
	</ul>
</p>

<hr>

<h5>Todo</h5>
<ul class="collection">
	<li class="collection-item">Migrer le site vers un serveur de production dédié au site
	<li class="collection-item">Optimiser les requêtes vers la base de données (en mettant les résultats en cache)
	<li class="collection-item">Fonction "mot de passe oublié"
	<li class="collection-item">Pouvoir modifier les données publiques du compte (description, avatar, date de naissance...)
	<li class="collection-item">Pouvoir modifier son mot de passe, son adresse e-mail, exporter ses données perso (avec les IP/ports loggés des utilisateurs, les données perso...), supprimer le compte
	<li class="collection-item">Donner accès aux utilisateurs à la liste des sessions ouvertes sur leurs comptes, et pouvoir killer les sessions si besoin (en cas de hack par exemple)
	<li class="collection-item">2FA
	<li class="collection-item">Pouvoir poster des commentaires sur les fics
	<li class="collection-item">Pouvoir poster une fic et les chapitres associés
	<li class="collection-item">Outils de modération
	<li class="collection-item">Améliorer le chat
	<li class="collection-item">Mettre en place un cache aggressif pour éviter trop de requêtes vers le serveur de back-end et optimiser la rapidité du site
</ul>

<?=richText("Si vous avez une idée d'une fonction à ajouter au site, n'hésitez pas à en parler sur le chat. :)")?>
<?php
require "Pages/Layout/End.php";