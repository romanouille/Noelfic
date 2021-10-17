<?php
require "Pages/Layout/Start.php";
?>
<h4>Connexion</h4>

<div class="row">
	<div class="col s12 m6">
		<div class="card blue">
			<div class="card-content white-text">
				<p><?php foreach ($messages as $message) { echo "$message<br>"; } ?></p>
			</div>
		</div>

		<form method="post">
			<div class="input-field">
				<input type="text" name="username" placeholder="Pseudo" maxlength="15" value="<?=isset($_POST["username"]) && is_string($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ""?>" required>
				<input type="password" name="password" placeholder="Mot de passe" maxlength="72" value="<?=isset($_POST["password"]) && is_string($_POST["password"]) ? htmlspecialchars($_POST["password"]) : ""?>" required>
			</div>

			<div class="center-align">
				<p>
					<?=Captcha::generate(true)?>
				</p>
				
				<p>
					<button class="waves-effect waves-light btn green">Valider</button>
				</p>
				
				<br>
				
				<p>
					<a href="/compte/mdp/reinitialiser" title="Réinitialiser mon mot de passe" class="waves-effect waves-light btn">Mot de passe perdu</a>
				</p>
			</div>
		</form>

		<br>
	</div>

	<div class="col s12 m6 center-align">
		<p>Posséder un compte Noelfic permet de noter, commenter et créer des fics sur le site.</p>
		<a href="/compte/inscription" title="Créer un compte" class="waves-effect waves-light btn grey darken-3">Créer un compte</a>
	</div>
</div>
<?php
require "Pages/Layout/End.php";