<?php
class Fic {
	/**
	 * int $id ID de la fic
	 * bool $exists Indique si la fic existe ou non
	 * string $title Titre de la fic
	 * int $status Statut de la fic (en cours, abandonnée, ...)
	 * int $chapters Nombre de chapitres
	 * int $views Nombre de vues de la fic
	 * float $mark Note moyenne de la fic
	 * int $createdTimestamp Timestamp de création de la fic
	 * string jvc URL de la fic vers jeuxvideo.com
	 * array $authors ID des auteurs de la fic
	 * array $usernames Pseudos des auteurs de la fic
	 * array $types Types de la fic (action, BD, ...)
	 */
	public $id, $exists, $title, $status, $chapters = 0, $views, $mark, $createdTimestamp, $jvc, $authors = [], $usernames = [], $types = [];
	
	/**
	 * Constructeur
	 *
	 * @param int $id ID de la fic
	 */
	public function __construct(int $id) {
		$this->id = $id;
		$this->load();
	}
	
	/**
	 * Charge les données de la fic
	 */
	private function load() {
		global $db;
		
		// Vérifie si la fic existe
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		if ($data["nb"] == 0) {
			$this->exists = false;
		}
		
		$this->exists = true;
		
		
		// Données essentielles de la fic
		$query = $db->prepare("SELECT title, status, views, mark, created_timestamp, jvc, deleted FROM fics WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		$this->title = (string)$data["title"];
		$this->status = (int)$data["status"];
		$this->views = (int)$data["views"];
		$this->mark = (float)$data["mark"];
		$this->createdTimestamp = (int)$data["created_timestamp"];
		$this->jvc = (string)$data["jvc"];
		$this->deleted = (bool)$data["deleted"];
		
		
		// Nombre de chapitres
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics_chapters WHERE fic = :fic");
		$query->bindValue(":fic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		$this->chapters = (int)$data["nb"];
		
		
		// Auteurs de la fic
		$query = $db->prepare("SELECT DISTINCT author FROM fics_chapters WHERE fic = :fic");
		$query->bindValue(":fic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		foreach ($data as $value) {
			$this->authors[] = (int)$value["author"];
			$this->usernames[] = (new User($value["author"]))->username;
		}
		
		
		// Types de la fic
		$query = $db->prepare("SELECT type FROM fics_types WHERE fic = :fic");
		$query->bindValue(":fic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		foreach ($data as $value) {
			$this->types[] = (int)$value["type"];
		}
	}
	
	/**
	 * Récupère les récents chapitres postés sur le site
	 *
	 * @return array Résultat
	 */
	public static function getRecentsChapters() : array {
		global $db;

		$query = $db->query("SELECT fic, chapter, created_timestamp FROM fics_chapters ORDER BY created_timestamp DESC LIMIT 10");
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$value = array_map("trim", $value);
			$fic = new Fic($value["fic"]);
			
			$result[] = [
				"fic" => (int)$value["fic"],
				"chapter" => (int)$value["chapter"],
				"createdTimestamp" => (int)$value["created_timestamp"],
				"ficTitle" => $fic->title
			];
		}

		return $result;
	}
	
	/**
	 * Récupère l'ID d'un chapitre en fonction de son numéro par rapport à une fic
	 *
	 * @param int $chapterNb Numéro du chapitre
	 *
	 * @return int ID du chapitre
	 */
	public function getChapterId(int $chapterNb) : int {
		global $db;
		
		$query = $db->prepare("SELECT id FROM fics_chapters WHERE fic = :fic AND chapter = :chapter");
		$query->bindValue(":fic", $this->id, PDO::PARAM_INT);
		$query->bindValue(":chapter", $chapterNb, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["id"];
	}
	
	/**
	 * Ajoute une vue au compteur de vues de la fic
	 *
	 * @return bool Résultat
	 */
	public function addView() : bool {
		global $db;
		
		$query = $db->prepare("UPDATE fics SET views = views+1 WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();

		return true;
	}
	
	/**
	 * Récupère l'ID d'une fic v1 sur la v2
	 *
	 * @param int $v1id ID de la fic sur la v1
	 *
	 * @return int ID de la fic sur la v2
	 */
	public static function getv2Id(int $v1id) : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics_migrated_id WHERE v1 = :v1");
		$query->bindValue(":v1", $v1id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		if ($data["nb"] == 0) {
			return 0;
		}
		
		$query = $db->prepare("SELECT v2 FROM fics_migrated_id WHERE v1 = :v1");
		$query->bindValue(":v1", $v1id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return (int)$data["v2"];
	}
	
	/**
	 * Récupère la liste des fics dans un contexte spécifique
	 *
	 * @param int $mode Mode de récupération, 1 : par genre, 2 : par date, 3 : par note, 4 : par popularité
	 * @param int $page Page de la liste
	 * @param int $type Type de fic (uniquement utilisé avec $mode = 1)
	 *
	 * @return array Liste
	 */
	public static function getList(int $mode, int $page, int $type = 0) : array {
		global $db, $newsFicId;
		
		$result = [
			"pages" => 0,
			"data" => []
		];

		if ($mode == 1) {
			// Par genre
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics_types WHERE type = :type AND fic != :fic");
			$query->bindValue(":type", $type, PDO::PARAM_INT);
			$query->bindValue(":fic", $newsFicId, PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetch();
			
			if ($data["nb"] == 0) {
				return $result;
			}
			
			$result["pages"] = ceil($data["nb"]/20);
			
			$query = $db->prepare("SELECT fic FROM fics_types WHERE type = :type AND fic != :fic ORDER BY id DESC OFFSET :offset LIMIT 20");
			$query->bindValue(":type", $type, PDO::PARAM_INT);
			$query->bindValue(":fic", $newsFicId, PDO::PARAM_INT);
			$query->bindvalue(":offset", (($page-1)*20), PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetchAll();
			
			foreach ($data as $value) {
				$result["data"][] = (int)$value["fic"];
			}

			return $result;
		} elseif ($mode == 2) {
			// Par date
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics WHERE id != :id AND deleted = 0");
			$query->bindValue(":id", $newsFicId, PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetch();
			
			if ($data["nb"] == 0) {
				return $result;
			}
			
			$result["pages"] = ceil($data["nb"]/20);
			
			$query = $db->prepare("SELECT id FROM fics WHERE id != :fic AND deleted = 0 ORDER BY created_timestamp DESC OFFSET :offset LIMIT 20");
			$query->bindValue(":fic", $newsFicId, PDO::PARAM_INT);
			$query->bindValue(":offset", (($page-1)*20), PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetchAll();
			
			foreach ($data as $value) {
				$result["data"][] = (int)$value["id"];
			}
			
			return $result;
		} elseif ($mode == 3) {
			// Par note
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics WHERE id != :id AND deleted = 0");
			$query->bindValue(":id", $newsFicId, PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetch();
			
			if ($data["nb"] == 0) {
				return $result;
			}
			
			$result["pages"] = ceil($data["nb"]/20);
			
			$query = $db->prepare("SELECT id FROM fics WHERE id != :fic AND deleted = 0 ORDER BY mark DESC OFFSET :offset LIMIT 20");
			$query->bindValue(":fic", $newsFicId, PDO::PARAM_INT);
			$query->bindValue(":offset", (($page-1)*20), PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetchAll();
			
			foreach ($data as $value) {
				$result["data"][] = (int)$value["id"];
			}
			
			return $result;
		} elseif ($mode == 4) {
			// Par popularité
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics WHERE id != :id AND deleted = 0");
			$query->bindValue(":id", $newsFicId, PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetch();
			
			if ($data["nb"] == 0) {
				return $result;
			}
			
			$result["pages"] = ceil($data["nb"]/20);
			
			$query = $db->prepare("SELECT id FROM fics WHERE id != :fic AND deleted = 0 ORDER BY views DESC OFFSET :offset LIMIT 20");
			$query->bindValue(":fic", $newsFicId, PDO::PARAM_INT);
			$query->bindValue(":offset", (($page-1)*20), PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetchAll();
			
			foreach ($data as $value) {
				$result["data"][] = (int)$value["id"];
			}
			
			return $result;
		}
	}
	
	/**
	 * Recherche une fic
	 *
	 * @param int $mode Mode de recherche (inutilisé pour le moment)
	 * @param string $text Texte à rechercher
	 * @param int $page Page
	 *
	 * @return array Résultat
	 */
	public static function search(int $mode, string $text, int $page = 1) : array {
		global $db, $newsFicId;
		
		$result = [
			"pages" => 0,
			"data" => []
		];		

		$text = str_replace("%", "", $text);
		if (empty($text)) {
			return ["pages" => 0, "data" => []];
		}
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM fics WHERE title ILIKE :title AND id != :id AND deleted = 0");
		$query->bindValue(":title", "%$text%", PDO::PARAM_STR);
		$query->bindValue(":id", $newsFicId, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		

		if ($data["nb"] == 0) {
			return $result;
		}
		
		$result["pages"] = ceil($data["nb"]/20);
		
		$query = $db->prepare("SELECT id FROM fics WHERE title ILIKE :title AND id != :id AND deleted = 0 ORDER BY created_timestamp DESC OFFSET :offset LIMIT 20");
		$query->bindValue(":title", "%$text%", PDO::PARAM_STR);
		$query->bindValue(":id", $newsFicId, PDO::PARAM_INT);
		$query->bindValue(":offset", (($page-1)*20), PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		
		foreach ($data as $value) {
			$result["data"][] = (int)$value["id"];
		}
		
		return $result;
	}
	
	/**
	 * Récupère le nombre total de fics postées sur le site
	 *
	 * @return int Nombre total de fics
	 */
	public static function getTotalNb() : int {
		global $db;
		
		$query = $db->query("SELECT COUNT(*) AS nb FROM fics WHERE deleted = 0");
		$data = $query->fetch();
		
		return $data["nb"];
	}
}