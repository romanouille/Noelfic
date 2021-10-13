<?php
require "Pages/Layout/Start.php";
?>
<div class="right-align">
	Note de la fic : <?php if ($fic->mark > 0) { for ($i = 1; $i <= $fic->mark; $i++) { if ($i > 1) { echo "&nbsp;"; } echo "<img src=\"/img/smileys/11.gif\" alt=\":noel:\" title=\":noel:\">"; } } else { echo "Non notée"; } ?>
</div>
<div class="center-align">
	<h4><?=htmlspecialchars($fic->title)?></h4>
	<hr>
	
	<p>
		Par : <?php foreach ($fic->usernames as $id=>$username) { if ($id > 0) { echo ", "; } echo "<a href=\"/profil/".strtolower($username)."\" style=\"color:#000\" title=\"Profil de $username\" target=\"_blank\"><b>$username</b></a>"; } ?><br>
		Genre : <?php foreach ($fic->types as $id=>$type) { if ($id > 0) { echo ", "; } echo "{$ficsTypes[$type]}"; } ?><br>
		Statut : <?=$ficsStates[$fic->status]?>
	</p>
	<hr>
	<br>
	
	<h4>Chapitre <?=$chapterNb?><?=!empty($chapter->title) ? " : ".htmlspecialchars($chapter->title) : ""?></h4>
	<br>
</div>

<p class="left-align">Publié le <?=date("d/m/Y à H:i:s", $chapter->createdTimestamp)?> par <a href="/profil/<?=strtolower($chapterUser->username)?>" style="color:<?=$chapterUser->rank == 1 ? "#C00" : "#000"?>" title="Profil de <?=$chapterUser->username?>" target="_blank"><b><?=$chapterUser->username?></b></a></p>
<div class="card grey lighten-2">
	<div class="card-content">
		<?=richText($chapter->content, true, true)?>
	</div>
</div>

<br>
<hr>

<h4>Commentaires</h4>

<?php
if (!empty($comments)) {
?>
<ul class="collection">
<?php
foreach ($comments as $data) {
	$comment = $data["comment"];
	$commentUser = $data["commentUser"];
?>
	<li class="collection-item avatar">
		<img src="/img/avatars/<?=$commentUser->avatarId > 0 ? md5(strtolower($commentUser->username))."-{$commentUser->avatarId}" : "defaut"?>.jpg" alt="Avatar" title="Avatar de <?=$commentUser->username?>" class="circle">
		<span class="title"><a style="color:#000" href="/profil/<?=strtolower($commentUser->username)?>" title="Profil de <?=$commentUser->username?>" target="_blank"><?=$commentUser->username?></a></span>
		<p class="chat-date"><?=date("d/m/Y à H:i:s", $comment->createdTimestamp)?></p>
		<p><?=richText($comment->content)?></p>
	</li>
<?php } ?>
</ul>

<?php
} else {
?>
Aucun commentaire pour ce chapitre.
<?php
}

require "Pages/Layout/End.php";