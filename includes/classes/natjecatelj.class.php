<?php

class Natjecatelj extends Db_object{
    
    public $ime;
    public $vrijeme;
    public $brojZastava;
    
    static $table = 'natjecatelj';
    static $id = 'id';

    
    private $connection;
    
    
    public function __construct(){
        global $database;
        $this->connection = $database;
    }
    
    public function __destruct(){
        $this->connection = null;
    }
   
    public function create(){
        $this->connection->query('insert into natjecatelj (ime,vrijeme,brojZastava) values(:ime, :vrijeme, :brojZastava)');
        $this->connection->bind(':ime', $this->ime);
        $this->connection->bind(':vrijeme', $this->vrijeme);
        $this->connection->bind(':brojZastava', $this->brojZastava);
        $result = $this->connection->execute();
        return $result;
    }
        

    
    
        
}

?>