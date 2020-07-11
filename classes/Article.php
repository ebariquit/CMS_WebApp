<?php

	class Article 
	{
		
		public $id;
		public $publicationDate;
		public	$title;
		public $summary;
		public $content;
		
		// Constructor.
		// Takes optional array ($data) to populate class variables.
		public function __construct($data = array()) 
		{
			if ($data) {

				if (isset($data['id'])) {
					// Cast int because this field should always be an integer in the database.
					$this->id = (int) $data['id'];
				}

				if (isset($data['publicationDate']))
				{
					// Cast int because this field should always be an integer in the database.
					$this->publicationDate = (int) $data['publicationDate'];
				}	

				if (isset($data['title'])) {
					// Remove HTML markup.
					$this->title = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title']);
				}

				if (isset($data['summary'])) {
					// Remove HTML markup.
					$this->summary = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary']);
				}

				if (isset($data['content'])) {
					// Author of article may want to add their own HTML markup.
					$this->content = $data['content'];
				}

			}
			// No data was passed to the constructor.
			else {
				
				$this->id = null;
				$this->publicationDate = null;
				$this->title = null;
				$this->summary = null;
				$this->content = null;
			}
		}

		// Easily stores data submitted from the 'New Article' and 'Edit Article' forms.
		// i.e.: Converts dates in the format of YYYY-MM-DD into the UNIX timestamp format required for storage in database.
		public function storeFormValues($params) {

			// Store all parameters.
			$this->__construct($params);

			if (isset($params['publicationDate'])) {

				// Parse the date.
				$publicationDate = explode('-', $params['publicationDate']);

				// Split the date into separate values, using them to re-store the date in proper UNIX format.
				if (count($publicationDate) == 3) {
					list($year, $month, $day) = $publicationDate;
					$this->publicationDate = mktime(0, 0, 0, $month, $day, $year);
				}
			}
		}

		/*
		STATIC METHODS.
		These functions can be called without needing an instance of this class.
		*/

		public static function getById($id) : Article {

			// PDO: PHP Data Objects.
			// Built-in library for PHP scripts to talk to databases.
			// Note: we are using credentials defined in config.php.
			$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			
			// The query to run.
			// We avoid adding the id variable directly to the query string for security concerns.
			// We instead include a reference to it, which we define later.
			$sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) 
					AS publicationDate 
					FROM articles
					WHERE id = :id";

			$statement = $connection->prepare($sql);				
			$statement->bindValue(":id", $id, PDO::PARAM_INT);	// Last parameter of bindValue() is the datatype
			$statement->execute();

			$row = $statement->fetch();								

			// Note: good practice to close connections ASAP - frees memory on server. 
			$connection = null;										 

			if ($row)
				return new Article($row);

		}

		public static function getList($numRows = 1000000) {
			$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
					AS publicationDate
					FROM articles
					ORDER BY publicationDate DESC
					LIMIT :numRows";

			$statement = $connection->prepare($sql);
			$statement->bindValue(":numRows", $numRows, PDO::PARAM_INT);
			$statement->execute();

			$list= array();
			
			// Fetch and store all rows returned in the query.
			while ($row = $statement->fetch()) {
				$article = new Article($row);
				$list[] = $article;
			}

			// Get total number of articles returned.
			$sql = "SELECT FOUND_ROWS() AS totalRows";
			$totalRows = $connection->query($sql)->fetch();

			$connection = null;

			return array("results" => $list, "totalRows" => $totalRows[0]);

		}

		/*
		NON-STATIC METHODS.
		Must be called on an instance of this class.
		*/

		public function insert() {
			// Check if this Article object already has an ID. In this case, it should not.
			if (!is_null(($this->id)))
				trigger_error("Article::insert(): Attempt to insert Article object with an existing ID (ID: $this->id).", E_USER_ERROR);

			$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$sql = "INSERT INTO articles(publicationDate, title, summary, content)
					VALUES (FROM_UNIXTIME(:publicationDate), :title, :summary, :content";

			$statement = $connection->prepare($sql);
			$statement->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
			$statement->bindValue(":title", $this->title, PDO::PARAM_STR);
			$statement->bindValue(":summary", $this->summary, PDO::PARAM_STR);
			$statement->bindValue(":content", $this->content, PDO::PARAM_STR);
			$statement->execute();

			// We allow the database to handle ID assignment. However, we still want to store this value in the objet,
			// should we ever need to reference it in the future.
			$this->id = $connection->lastInsertId();
			
			$connection = null;

		}

		public function update() {
			// Check if this Article object already has an ID. In this case, it should.
			if (is_null(($this->id)))
				trigger_error("Article::update(): Attempt to update Article object without an existing ID.", E_USER_ERROR);

			$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$sql = "UPDATE articles
					SET publicationDate = FROM_UNIXTIME(:publicationDate), 
						title = :title,
						summary = :summary,
						content = :content
					WHERE id = :id";

			$statement = $connection->prepare($sql);
			$statement->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
			$statement->bindValue(":title", $this->title, PDO::PARAM_STR);
			$statement->bindValue(":summary", $this->summary, PDO::PARAM_STR);
			$statement->bindValue(":content", $this->content, PDO::PARAM_STR);
			$statement->execute();

			$connection = null;

		}

		public function delete() {
			// Check if this Article object already has an ID. In this case, it should.
			if (is_null(($this->id)))
				trigger_error("Article::delete(): Attempt to delete Article object without an existing ID.", E_USER_ERROR);

			$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$sql = "DELETE FROM articles
					WHERE id = :id
					LIMIT 1";

			$statement = $connection->prepare($sql);
			$statement->bindValue(":id", $this->id, PDO::PARAM_INT);
			$statement->execute();

			$connection = null;

		}

	}

?>	