<?php
require "Pages/Layout/Start.php";
?>
<div class="col s12 offset-m4 l10 offset-l1 xl6 offset-xl3">
	<div class="card">
		<div class="card-content">
			<h3 class="center-align"><?=$user->username?></h3>
		</div>
		<div class="card-tabs center-align">
			<ul class="tabs tabx-fixed-width">
				<li class="tab"><a href="#profile">Profil</a>
				<li class="tab"><a href="#chapters">Chapitres</a>
			</ul>
		</div>
		<div class="card-content">
			<div id="profile">
				<div class="row">
					<div class="col s12 m1">
						<img src="/img/avatars/<?=$user->avatarId > 0 ? md5(strtolower($user->username))."-{$user->avatarId}" : "defaut"?>.jpg" alt="" title="Avatar">
					</div>

					<div class="col s12 m6 offset-m4 l8 offset-l3 xl7 offset-xl4">
						<table>
							<tbody>
								<tr>
									<td>Grade :</td>
									<td><?=$user->rank == 1 ? "Administrateur" : "Membre"?></td>
								</tr>

								<tr>
									<td>Membre depuis :</td>
									<td><?=number_format($userDays, 0, ".", ".")?> jour<?=$userDays > 1 ? "s" : ""?></td>
								</tr>

								<tr>
									<td>Dernier passage :</td>
									<td><?=date("d/m/Y à H:i:s", $user->lastSeenTimestamp)?></td>
								</tr>
								
								<tr>
									<td>Chapitres postés :</td>
									<td><?=count($chapters)?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
<?php
if (!empty($user->description)) {
?>	
				<div class="container">
					<?=richText($user->description)?>
				</div>
<?php
}
?>
				
			</div>
			
			<div id="chapters">
<?php
if (!empty($chapters)) {
	foreach ($chapters as $id=>$value) {
		if ($id > 0) {
			echo "<br>";
		}
?>
				<a href="/fic/<?=$value["fic"]?>-<?=slug($value["ficTitle"])?>/<?=$value["chapter"]?>" title="<?=htmlspecialchars($value["ficTitle"])?> - Chapitre <?=$value["chapter"]?>" target="_blank"><?=htmlspecialchars($value["ficTitle"])?> - Chapitre <?=$value["chapter"]?></a>
<?php
	}
} else {
?>
				Aucun chapitre de publié.
<?php
}
?>
			</div>
		</div>
	</div>
</div>
<?php
require "Pages/Layout/End.php";