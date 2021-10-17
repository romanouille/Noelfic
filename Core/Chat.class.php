<?php
class Chat {
	/**
	 * int $id ID du salon
	 * bool $exists Indique si le salon existe ou non
	 */
	public $id = 0, $exists = false;
	
	/**
	 * Constructeur
	 *
	 * @param int $id ID du salon
	 */
	public function __construct(int $id) {
		$this->id = $id;
		$this->load();
	}
	
	/**
	 * Charge les données du salon
	 */
	private function load() {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM chat_rooms WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		$this->exists = $data["nb"] == 1;
	}
	
	/**
	 * Charge les messages du salon
	 *
	 * @param int $limit Limite de messages
	 *
	 * @return array Résultat
	 */
	public function getMessages(int $limit = 20) : array {
		global $db;
		
		$query = $db->prepare("SELECT id, author, (SELECT rank FROM users WHERE id = author) AS rank, created_timestamp, content FROM chat_messages WHERE room = :room ORDER BY created_timestamp DESC LIMIT :limit");
		$query->bindValue(":room", $this->id, PDO::PARAM_INT);
		$query->bindValue(":limit", $limit, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$value = array_map("trim", $value);
			
			$user = new User($value["author"]);
			
			$result[] = [
				"id" => (int)$value["id"],
				"author" => (int)$value["author"],
				"rank" => (int)$value["rank"],
				"username" => $user->username,
				"avatarId" => $user->avatarId,
				"createdTimestamp" => (int)$value["created_timestamp"],
				"richTextContent" => (string)richText($value["content"], false)
			];
		}
		
		return array_reverse($result);
	}
	
	/**
	 * Ajoute un message au salon
	 *
	 * @param int $userId ID de l'utilisateur
	 * @param string $content Contenu du message
	 *
	 * @return int ID du message créé
	 */
	public function addMessage(int $userId, string $content) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO chat_messages(author, source_ip, source_port, room, created_timestamp, content) VALUES(:author, :source_ip, :source_port, :room, :created_timestamp, :content)");
		$query->bindValue(":author", $userId, PDO::PARAM_INT);
		$query->bindValue(":source_ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
		$query->bindValue(":source_port", $_SERVER["REMOTE_PORT"], PDO::PARAM_INT);
		$query->bindValue(":room", $this->id, PDO::PARAM_INT);
		$query->bindValue(":created_timestamp", time(), PDO::PARAM_INT);
		$query->bindValue(":content", $content, PDO::PARAM_STR);
		$query->execute();
		
		return $db->lastInsertId();
	}
}