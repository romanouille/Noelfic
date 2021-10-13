<?php
class Comment {
	/**
	 * int $id ID du commentaire
	 * int $author ID de l'auteur du commentaire
	 * int $createdTimestamp Timestamp de la date de création du commentaire
	 * string $content Contenu du commentaire
	 */
	public $id, $author, $createdTimestamp, $content;
	
	/**
	 * Constructeur
	 *
	 * @param int $id ID du commentaire
	 */
	public function __construct(int $id) {
		$this->id = $id;
		$this->load();
	}
	
	/**
	 * Charge le commentaire
	 */
	private function load() {
		global $db;
		
		$query = $db->prepare("SELECT author, created_timestamp, content FROM fics_comments WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		$this->author = (int)$data["author"];
		$this->createdTimestamp = (int)$data["created_timestamp"];
		$this->content = (string)$data["content"];
	}
}