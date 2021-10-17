<?php
require "Pages/Layout/Start.php";
?>
<h4>Réinitialiser mon mot de passe</h4>

<?php
if (isset($messages)) {
?>
<div class="card green">
	<div class="card-content white-text">
		<p><?php foreach ($messages as $message) { echo "$message<br>"; } ?></p>
	</div>
</div>
<?php
}

if (!$created) {
?>
<form method="post">
	<input type="text" name="username" placeholder="Pseudo" maxlength="15" value="<?=isset($_POST["username"]) && is_string($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ""?>" required>
	
	<p>
		<?=Captcha::generate()?>
	</p>
	
	<p>
		<button type="submit" class="waves-effect waves-light btn green">Valider</button>
	</p>
</form>
<?php
}

require "Pages/Layout/End.php";