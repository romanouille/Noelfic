			</div>
			
			<div class="col s12 m12 l2 menu">
				<form method="get" action="/recherche">
					<input type="text" name="q" placeholder="Rechercher..." required>
					<input type="hidden" name="t" value="1">
					<input type="hidden" name="p" value="1">
					
					<button type="submit" class="waves-effect waves-light btn grey darken-3">Rechercher</button>
				</form>
				<br>
				
				<h5 class="center-align">Fics</h5>
				<a href="/fic/hasard" title="Une fic au hasard" class="waves-effect waves-light btn grey darken-3">Au hasard</a>
				<br>
				
				<h5 class="center-align">Classement</h5>
				<a href="/classement/genre/action/1" title="Classement par genre" class="waves-effect waves-light btn grey darken-3">Par genre</a>
				<a href="/classement/date/1" title="Classement par date" class="waves-effect waves-light btn grey darken-3">Par date</a>
				<a href="/classement/note/1" title="Classement par note" class="waves-effect waves-light btn grey darken-3">Par note</a>
				<a href="/classement/popularite/1" title="Classement par popularité" class="waves-effect waves-light btn grey darken-3">Par popularité</a>
			</div>
		</main>
		
		<footer class="page-footer grey darken-3">
			<div class="footer-copyright">
				<div class="container center-align">
					NoelFic.fr 2008-<?=date("Y")?><br>
					Certains éléments du site (principalement des images) appartiennent à <a href="http://www.jeuxvideo.com/" title="jeuxvideo.com" target="_blank">jeuxvideo.com</a>.<br>
					<a href="/contact" title="Contact">Contact</a>
				</div>
			</div>
		</footer>
		
<?php
if (PHP_OS == "WINNT") {
?>
		<div class="fixed-action-btn">
			<button data-target="debug" class="btn modal-trigger btn-floating btn-large red<?=!empty($phpErrors) ? " pulse" : ""?>"><i class="large material-icons">bug_report</i></button>
		</div>
		
		<div id="debug" class="modal">
			<div class="modal-content">
				<h2>Debug</h2>
				<p>
<?php
	if (!empty($phpErrors)) {
		foreach ($phpErrors as $id=>$phpError) {
			if ($id > 0) {
				echo "<br><br>";
			}
?>
					<h5>Erreur</h5>
					<b>Type</b> : <?=$phpError["errno"]?><br>
					<b>Description</b> : <?=$phpError["errstr"]?><br>
					<b>Fichier</b> : <?=$phpError["errfile"]?><br>
					<b>Ligne</b> : <?=$phpError["errline"]?>
<?php
		}
	} else {
		echo "Aucune erreur.";
	}
?>
				</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="modal-close waves-effect waves-green btn-flat">Close</a>
			</div>
		</div>
<?php
}
?>
		
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.min.js"></script>
		<script>
$(document).ready(function() {
	if ($("select").length > 0) {
		$("select").material_select();
	}
});

$(document).ready(function(){
	$(".tabs").tabs();
});

document.addEventListener("DOMContentLoaded", function() {
	var elems = document.querySelectorAll(".modal");
	var instances = M.Modal.init(elems);
});

<?php
if (isset($sessionToken)) {
?>
var sessionToken = "<?=$sessionToken?>";
<?php
}
?>

<?php
if ($handlerName == "Chapter.php") {
?>
let totalChapters = <?=$fic->chapters?>;
function handleChapterKey(event) {
	if (event.keyCode == 13) {
		if (totalChapters >= document.getElementById("chapter").value) {
			var url = document.URL.split("/");
			url[5] = document.getElementById("chapter").value;
			window.location.href = url.join("/");
		} else {
			alert("Ce chapitre n'existe pas.");
		}
	}
}
<?php
} elseif ($handlerName == "Home.php") {
?>
class Chat {
	/**
	 * Constructeur
	 *
	 * @param int id ID du salon
	 */
	constructor(id) {
		this.id = id;
	}
	
	/**
	 * Évènement déclenchant l'envoi d'un message lorsque le bouton "entrée" est appuyé"
	 */
	keyup(e) {
		if (e.keyCode == 13) {
			chat.sendMessage();
		}
	}
	
	/**
	 * Envoie un message à partir du champ de texte
	 */
	sendMessage() {
		document.getElementById("chatSend").disabled = true;
		document.getElementById("chatMessage").disabled = true;
		let xhr = new XMLHttpRequest();
		xhr.onreadystatechange = chat.sendMessageCallback;
		xhr.open("PUT", "/api/chat/"+chat.id, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("sessionToken="+sessionToken+"&message="+encodeURIComponent(document.getElementById("chatMessage").value));
	}
	
	/**
	 * Callback pour la fonction sendMessage()
	 */
	sendMessageCallback() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				chat.loadMessages();
				document.getElementById("chatMessage").value = "";
			} else {
				alert(JSON.parse(this.responseText)["message"]);
			}
			
			document.getElementById("chatSend").disabled = false;
			document.getElementById("chatMessage").disabled = false;
			document.getElementById("chatMessage").focus();
		}
	}
	
	/**
	 * Charge les messages du salon
	 */
	loadMessages() {
		let xhr = new XMLHttpRequest();
		xhr.onreadystatechange = this.loadMessagesCallback;
		xhr.open("GET", "/api/chat/"+this.id, true);
		xhr.send();
	}
	
	/**
	 * Callback de loadMessages(). Génère un code HTML et l'affiche au client.
	 */
	loadMessagesCallback() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				let messages = JSON.parse(this.responseText)["messages"], html = "", i = 0;
				
				for (i = 0; i < messages.length; i++) {
					let date = new Date(messages[i].createdTimestamp*1000);
					
					let message = document.createElement("li");
					message.className = "collection-item avatar";
					
					let avatar = document.createElement("img");
					avatar.src = "/img/avatars/"+(messages[i].avatarId > 0 ? md5(messages[i].username.toLowerCase())+"-"+messages[i].avatarId : "defaut")+".jpg";
					avatar.title = "Avatar de "+messages[i].username;
					avatar.className = "circle";
					
					let profileSpan = document.createElement("span");
					profileSpan.className = "title";
					
					let username = document.createElement("a");
					username.href="/profil/"+messages[i].username.toLowerCase();
					username.title = "Profil de "+messages[i].username;
					username.target = "_blank";
					if (messages[i].rank == 1) {
						username.style.color = "#C00";
					} else {
						username.style.color = "#000";
					}
					username.innerHTML = messages[i].username;
					
					profileSpan.appendChild(username);
					
					let messageDate = document.createElement("p");
					messageDate.className = "chat-date";
					messageDate.innerHTML = ("0"+date.getDate()).substr(-2)+"/"+("0"+(date.getMonth()+1)).substr(-2)+"/"+date.getFullYear()+" à "+("0"+date.getHours()).substr(-2)+":"+("0"+date.getMinutes()).substr(-2)+":"+("0"+date.getSeconds()).substr(-2);
					
					let messageContent = document.createElement("p");
					messageContent.innerHTML = messages[i].richTextContent;
					
					message.appendChild(avatar);
					message.appendChild(profileSpan);
					message.appendChild(messageDate);
					message.appendChild(messageContent);
					
					html += message.outerHTML;
				}
				
				document.getElementById("chat").innerHTML = html;
			}
		}
	}
}

let chat = new Chat(1);
setInterval(function() {
	chat.loadMessages();
}, 5000);

<?php
	if ($userLogged) {
?>
document.getElementById("chatSend").addEventListener("click", chat.sendMessage);
document.getElementById("chatMessage").addEventListener("keyup", chat.keyup);
<?php
	}
}
?>
		</script>
	</body>
</html>