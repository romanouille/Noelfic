			</div>
			
			<div class="col s12 m12 l2 menu">
				<div class="row">
					<div class="col s6">
						<input type="text" name="search" placeholder="Rechercher..." style="margin-top:15px">
					</div>
					
					<div class="col s6 input-field">
						<select>
							<option value="1">Titre</option>
							<option value="2">Auteur</option>
						</select>
					</div>
				</div>
				
				<button class="waves-effect waves-light btn grey darken-3" type="button" disabled>Rechercher</button>
				<br>
				
				<h5 class="center-align">Fics</h5>
				<button class="waves-effect waves-light btn grey darken-3">Une fic au hasard</button>
				<button class="waves-effect waves-light btn grey darken-3">Fic du mois</button>
				<br>
				
				<h5 class="center-align">Classement</h5>
				<button class="waves-effect waves-light btn grey darken-3">Par genre</button>
				<button class="waves-effect waves-light btn grey darken-3">Par date</button>
				<button class="waves-effect waves-light btn grey darken-3">Par note</button>
				<button class="waves-effect waves-light btn grey darken-3">Par popularité</button>
			</div>
		</div>
		
		<footer class="page-footer grey darken-3">
			<div class="footer-copyright">
				<div class="container center-align">
					NoelFic.fr v1 2008-2018
				</div>
			</div>
		</footer>
		
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/js/materialize.min.js"></script>
		<script>
$(document).ready(function() {
	$("select").material_select();
});
</script>
	</body>
</html>