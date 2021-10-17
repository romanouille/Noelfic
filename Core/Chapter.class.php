<?php
class Chapter {
	/**
	 * int $id ID du chapitre
	 * int $author ID de l'auteur du chapitre
	 * string $title Titre du chapitre
	 * int $createdTimestamp Timestamp de la date de création du chapitre
	 * string $content Contenu du chapitre
	 */
	public $id, $author, $title, $createdTimestamp, $content;
	
	/**
	 * Constructeur
	 *
	 * @param int $id ID du chapitre
	 */
	public function __construct(int $id) {
		$this->id = $id;
		$this->load();
	}
	
	/**
	 * Charge le chapitre
	 */
	private function load() {
		global $db;
		
		$query = $db->prepare("SELECT id, author, title, created_timestamp, content FROM fics_chapters WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		$this->author = (int)$data["author"];
		$this->createdTimestamp = (int)$data["created_timestamp"];
		$this->title = (string)$data["title"];
		$this->content = (string)$data["content"];
	}
	
	/**
	 * Récupère les commentaires du chapitre
	 *
	 * @return array Commentaires
	 */
	public function getComments() : array {
		global $db;
		
		$ids = [];
		$result = [];
		$query = $db->prepare("SELECT id FROM fics_comments WHERE chapter = :chapter ORDER BY created_timestamp DESC");
		$query->bindValue(":chapter", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		
		foreach ($data as $value) {
			$ids[] = (int)$value["id"];
		}
		
		foreach ($ids as $commentId) {
			$comment = new Comment($commentId);
			$commentUser = new User($comment->author);
			
			$result[] = [
				"comment" => $comment,
				"commentUser" => $commentUser
			];
		}
		
		return $result;
	}
	
	/**
	 * Ajoute une vue au compteur de vue du chapitre
	 *
	 * @return bool Résultat
	 */
	public function addView() : bool {
		global $db;
		
		$query = $db->prepare("UPDATE fics_chapters SET views = views+1 WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		
		return true;
	}
}