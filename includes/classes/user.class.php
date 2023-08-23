<?php 


	class User extends Db_object{
	
		public $id_user;
		public $username;
		public $password;
		public $employee_id;
		public $admin;
		
		private $connection;
		
		static $table = 'user';
		static $id = 'id_user';

        public function __construct(){
            global $database;
            $this->connection = $database;
        }

        public function __destruct()
        {
            global $database;
            $this->connection = null;
        }
		

		public function verifyUser(){
			$this->connection->query('select * from user where username=:username and password=:password limit 1');	
			$this->connection->bind(':username', $this->username );
			$this->connection->bind(':password', md5($this->password) );	
			$this->connection->execute();
			$user = $this->connection->single();
			$this->connection = null;
			return $user;		
		}
		
		
		public function getUsername(){
			return $this->username;
		}
		
		
	}

	
?>


