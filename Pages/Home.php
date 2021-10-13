<?php
require "Pages/Layout/Start.php";

?>
<h5>Bienvenue</h5>
<hr>
<p>
	Bienvenue sur NoelFic.fr. Ce site a pour vocation de regrouper toutes les histoires (et pas uniquement les fictions) des auteurs prolifiques des forums de jeuxvideo.com.<br>
	Le fonctionnement du site est simple : un auteur crée un compte, puis une histoire, et poste ses chapitres.<br><br>
	
	Évidemment, nous nous réservons le droit de bannir quiconque outrepasse ses libertés, et d'effacer partie ou totalité du contenu qu'il aurait apporté au site.<br>
	Ceci étant dit, un grand merci à tous les auteurs pour leur participation.
</p>
<br>

<h5>Derniers chapitres ajoutés</h5>
<hr>
<table class="striped">
	<tbody>
<?php
foreach ($recentsChapters as $recentChapter) {
?>
		<tr>
			<td class="home-list-title"><a href="/fic/<?=$recentChapter["fic"]?>-<?=slug($recentChapter["ficTitle"])?>/<?=$recentChapter["chapter"]?>" title="<?=htmlspecialchars($recentChapter["ficTitle"])." - Chapitre {$recentChapter["chapter"]}"?>"><?=htmlspecialchars($recentChapter["ficTitle"])?></a></td>
			<td class="home-list-chapter">Chapitre <?=$recentChapter["chapter"]?></td>
			<td class="home-list-date"><?=date("d/m/Y à H:i:s", $recentChapter["createdTimestamp"])?></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<br>

<h5>La penséedeo du jour</h5>
<hr>
<p><b>[<?=date("d/m/Y", $penseedeo->createdTimestamp)?>] par <a href="/profil/<?=strtolower($penseedeoUser->username)?>" title="Profil de <?=$penseedeoUser->username?>" style="color:<?=$penseedeoUser->rank == 1 ? "#C00" : "#000"?>" target="_blank"><?=$penseedeoUser->username?></a> - <a href="/fic/2447-la-penseedeo-du-jour/<?=$fic->chapters?>" title="Toutes les penséedeos">Toutes les penséedeos</a></b></p>

<div class="card grey lighten-2">
	<div class="card-content">
		<?=richText($penseedeo->content)?>
	</div>
</div>
<br>

<h5>Minichat</h5>
<hr>

<ul class="collection" id="chat">
<?php
foreach ($chatMessages as $chatMessage) {
?>
	<li class="collection-item avatar">
		<img src="/img/avatars/<?=$chatMessage["avatarId"] > 0 ? md5(strtolower($chatMessage["username"]))."-{$chatMessage["avatarId"]}" : "defaut"?>.jpg" alt="Avatar" title="Avatar de <?=$chatMessage["username"]?>" class="circle">
		<span class="title"><a style="color:<?=$chatMessage["rank"] == 1 ? "#C00" : "#000"?>" href="/profil/<?=strtolower($chatMessage["username"])?>" title="Profil de <?=$chatMessage["username"]?>" target="_blank"><?=$chatMessage["username"]?></a><!-- <a class="btn-floating btn-small red"><i class="material-icons" onclick="alert('Fonction non implémentée')">delete</i></a>--></span>
		<p class="chat-date"><?=date("d/m/Y à H:i:s", $chatMessage["createdTimestamp"])?></p>
		<p><?=$chatMessage["richTextContent"]?></p>
	</li>
<?php } ?>
</ul>

<?php
if ($userLogged) {
?>
<div id="chat">
	<div class="row">
		<div class="col s10">
			<input type="text" id="chatMessage" placeholder="Votre message" maxlength="300">
		</div>
		
		<div class="col s2">
			<button class="waves-effect waves-light btn green btn-large" id="chatSend">Envoyer</button>
		</div>
	</div>
</div>

<p>Le chat est encore en beta et est pour l'instant très minimaliste.</p>
<?php
} else {
?>
<p>Vous devez être connecté pour participer au chat.
<?php
}

require "Pages/Layout/End.php";