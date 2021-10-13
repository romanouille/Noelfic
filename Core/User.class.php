<?php
class User {	
	/**
	 * int $id ID de l'utilisateur
	 * bool $exists Indique si l'utilisateur existe
	 * string $username Nom d'utilisateur
	 * string $passwordv1 Mot de passe V1
	 * string $password Mot de passe V2
	 * string $email Adresse e-mail
	 * string $avatarId ID de l'avatar
	 * string $description Description indiquée par l'utilisateur
	 * int $birth Timestamp de la date de naissance de l'utilisateur
	 * int $registrationTimestamp Timestamp de la date de création de l'utilisateur
	 * int $lastSeenTimestamp Timestamp de la date de dernier passage de l'utilisateur
	 * string $validationHash Hash de validation à utiliser pour valider le compte
	 * bool $validated Indique si le compte a été validé
	 */
	 
	public $id, $exists = false, $username, $passwordv1, $password, $email, $avatarId = 0, $description, $birth = 0, $registrationTimestamp = 0, $lastSeenTimestamp = 0, $validationHash, $validated = false, $rank = 0;
	
	/**
	 * Constructeur
	 *
	 * @param int $id ID de l'utilisateur
	 */
	public function __construct(int $id = 0) {
		$this->id = $id;		
		$this->load();
	}
	
	/**
	 * Charge l'utilisateur
	 */
	private function load() {
		global $db;
		
		if ($this->id == 0) {
			return;
		}
		
		$this->exists = true;
		
		$query = $db->prepare("SELECT username, password_v1, password, email, avatar_id, description, birth, registration_timestamp, last_seen_timestamp, validation_hash, validated, rank FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		$this->username = (string)$data["username"];
		$this->passwordv1 = (string)$data["password_v1"];
		$this->password = (string)$data["password"];
		$this->email = (string)$data["email"];
		$this->avatarId = (int)$data["avatar_id"];
		$this->description = (string)$data["description"];
		$this->birth = (int)$data["birth"];
		$this->registrationTimestamp = (int)$data["registration_timestamp"];
		$this->lastSeenTimestamp = (int)$data["last_seen_timestamp"];
		$this->validationHash = (string)$data["validation_hash"];
		$this->validated = (bool)$data["validated"];
		$this->rank = (int)$data["rank"];
	}
	
	/**
	 * Récupère l'ID d'un utilisateur à partir de son pseudo
	 *
	 * @param string $username Nom d'utilisateur
	 *
	 * @return int ID de l'utilisateur
	 */
	public static function usernameToId(string $username) : int {
		global $db;
		
		$username = strtolower($username);
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users WHERE LOWER(username) = :username");
		$query->bindValue(":username", $username, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		if ($data["nb"] == 0) {
			return 0;
		}
		
		$query = $db->prepare("SELECT id FROM users WHERE LOWER(username) = :username");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return (int)$data["id"];
	}
	
	public function checkPassword(string $password) : bool {
		global $db;
		
		if (!empty($this->passwordv1)) {
			if (md5(htmlspecialchars($password)) == $this->passwordv1) {
				$query = $db->prepare("UPDATE users SET password_v1 = '', password = :password WHERE id = :id");
				$query->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
				$query->bindValue(":id", $this->id, PDO::PARAM_INT);
				$query->execute();
				
				return true;
			} else {
				return false;
			}
		}

		return password_verify($password, $this->password);
	}
	
	public static function emailToId(string $email) : int {
		global $db;
		
		$email = strtolower($email);
		
		$query = $db->prepare("SELECT id FROM users WHERE lower(email) = :email");
		$query->bindValue(":email", $email, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return isset($data["id"]) ? (int)$data["id"] : 0;
	}
	
	public function getLastMessageTimestampOnChat(int $room) : int {
		global $db;
		
		$query = $db->prepare("SELECT created_timestamp FROM chat_messages WHERE author = :author AND room = :room ORDER BY created_timestamp DESC");
		$query->bindValue(":author", $this->id, PDO::PARAM_INT);
		$query->bindValue(":room", $room, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return isset($data["created_timestamp"]) ? (int)$data["created_timestamp"] : 0;
	}
	
	public function getChaptersList() : array {
		global $db;
		
		$result = [];
		
		$query = $db->prepare("SELECT fic, chapter, (SELECT title FROM fics WHERE id = fic) AS fic_title FROM fics_chapters WHERE author = :author AND (SELECT deleted FROM fics WHERE id = fic) = 0 ORDER BY created_timestamp DESC");
		$query->bindValue(":author", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		
		foreach ($data as $value) {
			$result[] = [
				"fic" => (int)$value["fic"],
				"chapter" => (int)$value["chapter"],
				"ficTitle" => (string)trim($value["fic_title"])
			];
		}
		
		return $result;
	}
	
	public function updateUsername(string $username) : bool {
		global $db;
		
		$query = $db->prepare("UPDATE users SET username = :username WHERE id = :id");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		return $query->execute();
	}
	
	public function updateLastSeenTimestamp() : bool {
		global $db;
		
		$query = $db->prepare("UPDATE users SET last_seen_timestamp = :timestamp WHERE id = :id");
		$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		return $query->execute();
	}
}