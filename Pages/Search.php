<?php
require "Pages/Layout/Start.php";
?>
<div class="center-align">
<?php
if ($list["pages"] > 0) {
?>
	<h4>Résultats pour la recherche de « <?=htmlspecialchars($searchText)?> » - Page <?=$searchPage?></h4>
	<p>
	<ul class="pagination">
		<li class="waves-effect<?=$match[2] < 2 ? " disabled" : ""?>"><a href="<?=$match[2] > 1 ? "/recherche?q={$match[0]}&t={$match[1]}&p=".($match[2]-1) : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

	$cond = $match[2] > 6 ? $match[2]-5 : 1;

	if ($cond > 1) {
?>
		<li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=1">1</a></li> ...
<?php
}

	for ($i = $cond; $i <= $match[2]+5; $i++) {
		if ($i > $list["pages"]) {
			break;
		}

		if ($i == $match[2]) {
?>
		<li class="active"><a href="#"><?=$i?></a></li>
<?php
		} else {
?>
		<li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=<?=$i?>"><?=$i?></a></li>
<?php
		}
	}

	if ($i <= $list["pages"]) {
?>
	... <li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=<?=$list["pages"]?>"><?=$list["pages"]?></a></li>
<?php
	}
?>
		<li class="waves-effect<?=$match[2] >= $list["pages"] ? " disabled" : ""?>"><a href="<?=$match[2] < $list["pages"] ? "/recherche?q={$match[0]}&t={$match[1]}&p=".($match[2]+1) : "#"?>"><i class="material-icons">chevron_right</i></a></li>
	</ul>

	<table class="bordered">
		<thead>
			<tr>
				<th>Titre</th>
				<th>Auteur</th>
				<th>Date</th>
				<th>Statut</th>
				<th>Note</th>
			</tr>
		</thead>

		<tbody>
<?php
	foreach ($data as $id=>$fic) {
?>
			<tr>
				<td><a href="/fic/<?=$id."-".slug($fic->title)."/1"?>" title="<?=htmlspecialchars($fic->title)?>"><?=htmlspecialchars($fic->title)?></a></td>
				<td><?php foreach ($fic->usernames as $id=>$username) { if ($id > 0) { echo ", "; } echo "<a href=\"/profil/".strtolower($username)."\" title=\"Profil de $username\" target=\"_blank\">$username</a>"; } ?></td>
				<td><?=date("d/m/Y H:i:s", $fic->createdTimestamp)?></td>
				<td><?=$ficsStates[$fic->status]?></td>
				<td><?php if ($fic->mark > 0) { for ($i = 1; $i <= $fic->mark; $i++) { if ($i > 1) { echo "&nbsp;"; } echo "<img src=\"/img/smileys/11.gif\" alt=\":noel:\" title=\":noel:\">"; } } else { echo "Non notée"; }?></td>
			</tr>
<?php } ?>
		</tbody>
	</table>

	<ul class="pagination">
		<li class="waves-effect<?=$match[2] < 2 ? " disabled" : ""?>"><a href="<?=$match[2] > 1 ? "/recherche?q={$match[0]}&t={$match[1]}&p=".($match[2]-1) : "#"?>"><i class="material-icons">chevron_left</i></a></li>
<?php

	$cond = $match[2] > 6 ? $match[2]-5 : 1;

	if ($cond > 1) {
?>
		<li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=1">1</a></li> ...
<?php
}

	for ($i = $cond; $i <= $match[2]+5; $i++) {
		if ($i > $list["pages"]) {
			break;
		}

		if ($i == $match[2]) {
?>
		<li class="active"><a href="#"><?=$i?></a></li>
<?php
		} else {
?>
		<li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=<?=$i?>"><?=$i?></a></li>
<?php
		}
	}

	if ($i <= $list["pages"]) {
?>
	... <li class="waves-effect"><a href="/recherche?q=<?=$match[0]?>&t=<?=$match[1]?>&p=<?=$list["pages"]?>"><?=$list["pages"]?></a></li>
<?php
	}
?>
		<li class="waves-effect<?=$match[2] >= $list["pages"] ? " disabled" : ""?>"><a href="<?=$match[2] < $list["pages"] ? "/recherche?q={$match[0]}&t={$match[1]}&p=".($match[2]+1) : "#"?>"><i class="material-icons">chevron_right</i></a></li>
	</ul>
<?php
} else {
?>
	<div class="card red">
		<div class="card-content white-text">
			Votre recherche n'a menée à aucun résultat pour "<b><?=htmlspecialchars($searchText)?></b>".
		</div>
	</div>
<?php
}
?>
</div>
<?php
require "Pages/Layout/End.php";