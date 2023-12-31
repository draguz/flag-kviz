<?php 
	class Database{
		
		private $host 	= 	DB_HOST;
		private $user 	= 	DB_USER;
		private $pass 	= 	DB_PASS;
		private $dbname = 	DB_NAME;
		private $stmt;
		private $dbh;
		

		
		public function __construct(){
		
	        // Create a new PDO instanace
	        try{
	            $this->dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME , DB_USER , DB_PASS, 
							array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
								  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
					);
	        }
	        // Catch any errors
			catch(PDOException $e){
				
				// $this->error = $e->getMessage();
				
					switch ($e->getCode()) {
						case 2002:
							echo 'MySQL server nije na danoj adresi';
							break;
						case 1049:
							echo 'Ime baze nije dobro';
							break;
						case 1045:
							echo 'Neispravna kombinacija korisnika i lozinke';
							break;
						default:
							echo $e->getCode();
							break;
					}
					exit;
				}
				
	
		}
		
		
		public function __destruct(){
			$this->dbh = null;
		}

		
		public function query($query){
			$this->stmt = $this->dbh->prepare($query);
		}	
		
		public function bind($param, $value, $type = null){
			if (is_null($type)) {
				switch (true) {
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;
				}
			}
			$this->stmt->bindValue($param, $value, $type);
		}
		
		
		public function execute(){
			return $this->stmt->execute();
		}
		
		
		public function resultset(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		}
		
		
		public function single(){
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
			// return $this->stmt->fetch(PDO::FETCH_ASSOC);  =  dohvaca array
		}
		
		
		public function rowCount(){
			return $this->stmt->rowCount();
		}
		
		
		public function lastInsertId(){
			return $this->dbh->lastInsertId();
		}
		
		
		public function beginTransaction(){
			return $this->dbh->beginTransaction();
		}
		
		
		public function endTransaction(){
			return $this->dbh->commit();
		}
		
		
		public function cancelTransaction(){
			return $this->dbh->rollBack();
		}
		
		
		
	}
	
	$database = new Database();

?>