<?php
require "Pages/Layout/Start.php";
?>
<h4>Inscription</h4>

<?php
if (isset($messages)) {
?>
<div class="card blue">
	<div class="card-content white-text">
		<p><?php foreach ($messages as $message) { echo "$message<br>"; } ?></p>
	</div>
</div>
<?php
}

if (!$created) {
?>
<form method="post">
	<div class="row">
		<div class="col s12 m6">
			<input type="text" name="username" placeholder="Pseudo" maxlength="15" value="<?=isset($_POST["username"]) && is_string($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ""?>" required>
		</div>
		
		<div class="col s12 m6">
			<input type="email" name="email" placeholder="Adresse e-mail" maxlength="255" value="<?=isset($_POST["email"]) && is_string($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""?>" required>
		</div>
	</div>
	
	<div class="row">
		<div class="col s12 m6">
			<input type="password" name="password" placeholder="Mot de passe" minlength="8" maxlength="72" value="<?=isset($_POST["password"]) && is_string($_POST["password"]) ? htmlspecialchars($_POST["password"]) : ""?>" required>
		</div>
		
		<div class="col s12 m6">
			<input type="password" name="password2" placeholder="Confirmez le mot de passe" minlength="8" maxlength="72" value="<?=isset($_POST["password2"]) && is_string($_POST["password2"]) ? htmlspecialchars($_POST["password2"]) : ""?>" required>
		</div>
	</div>
	
	<p>
		<label>
			<input type="checkbox" class="filled-in" name="rules"<?=isset($_POST["rules"]) ? " checked" : ""?> required>
			<span>J'ai lu et accepté la <a href="/charte" title="Charte" target="_blank">charte</a></span>
		</label>
	</p>
	
	<p>
		<?=Captcha::generate()?>
	</p>
	
	<p>
		<button type="submit" class="waves-effect waves-light btn green">Valider</button>
	</p>
	
	<br>
	
	<p>
		<i class="tiny material-icons red-text">warning</i> Un mail de confirmation vous sera envoyé, veillez donc à spécifier une adresse e-mail valide.
	</p>
</form>
<?php
}

require "Pages/Layout/End.php";