<?php
require "Pages/Layout/Start.php";
?>
<h4>Réinitialiser mon mot de passe</h4>

<div class="card blue">
	<div class="card-content white-text">
		<p><?=$message?></p>
	</div>
</div>

<?php
if ($showForm) {
?>

<form method="post">
	<input type="password" name="password" placeholder="Mot de passe" minlength="8" maxlength="72" value="<?=isset($_POST["password"]) && is_string($_POST["password"]) ? htmlspecialchars($_POST["password"]) : ""?>" required><br>
	<input type="password" name="password2" placeholder="Confirmez le nouveau mot de passe" minlength="8" maxlength="72" required><br><br>
	
	<button class="btn waves-effect waves-light" type="submit">Valider</button>
</form>
<?php
}

require "Pages/Layout/End.php";