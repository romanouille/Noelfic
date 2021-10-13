<?php
class Session {
	public $name, $userId = 0;
	
	public function __construct(string $name) {
		$this->name = $name;
		
		$this->load();
	}

	private function load() {
		global $db;

		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users_sessions WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();

		if ($data["nb"] == 1) {
			$query = $db->prepare("SELECT user_id FROM users_sessions WHERE name = :name");
			$query->bindValue(":name", $this->name, PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetch();

			$this->userId = $data["user_id"];
		}
	}

	public function update() : bool {
		global $db;

		$query = $db->prepare("SELECT last_ip FROM users_sessions WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();

		if ($data["last_ip"] != $_SERVER["REMOTE_ADDR"]) {
			$query = $db->prepare("UPDATE users_sessions SET last_ip = :last_ip, last_seen = :last_seen WHERE name = :name");
			$query->bindValue(":last_ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
			$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
			$query->bindValue(":name", $this->name, PDO::PARAM_STR);
			$query->execute();

			$query = $db->prepare("SELECT COUNT(*) AS nb FROM users_ip WHERE user_id = :user_id AND ip = :ip");
			$query->bindValue(":user_id", $this->userId, PDO::PARAM_INT);
			$query->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetch();

			if ($data["nb"] == 1) {
				$query = $db->prepare("UPDATE users_ip SET last_seen = :last_seen WHERE user_id = :user_id AND ip = :ip");
				$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
				$query->bindValue(":user_id", $this->userId, PDO::PARAM_INT);
				$query->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
				return $query->execute();
			} else {
				$query = $db->prepare("INSERT INTO users_ip(user_id, ip, first_seen, last_seen) VALUES(:user_id, :ip, :first_seen, :last_seen)");
				$query->bindValue(":user_id", $this->userId, PDO::PARAM_INT);
				$query->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
				$query->bindValue(":first_seen", time(), PDO::PARAM_INT);
				$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
				return $query->execute();
			}
		} else {
			$query = $db->prepare("UPDATE users_sessions SET last_seen = :last_seen WHERE name = :name");
			$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
			$query->bindValue(":name", $this->name, PDO::PARAM_STR);
			$query->execute();

			$query = $db->prepare("UPDATE users_ip SET last_seen = :last_seen WHERE user_id = :user_id AND ip = :ip");
			$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
			$query->bindValue(":user_id", $this->userId, PDO::PARAM_INT);
			$query->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
			return $query->execute();
		}
	}

	public static function create(int $userId) : string {
		global $db;

		$sessionName = md5(uniqid().microtime(1).random_int(1000000000, 9999999999).$userId);

		$query = $db->prepare("INSERT INTO users_sessions(name, user_id, first_seen, last_seen, expiration) VALUES(:name, :user_id, :first_seen, :last_seen, :expiration)");
		$query->bindValue(":name", $sessionName, PDO::PARAM_STR);
		$query->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$query->bindValue(":first_seen", time(), PDO::PARAM_INT);
		$query->bindValue(":last_seen", time(), PDO::PARAM_INT);
		$query->bindValue(":expiration", time()+31536000, PDO::PARAM_INT);
		
		if ($query->execute()) {
			$userSession = new Session($sessionName);
			$userSession->update();

			return $sessionName;
		} else {
			return "";
		}
	}
}