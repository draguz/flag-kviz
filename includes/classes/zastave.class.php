<?php

class Zastava extends Db_object{
    
    public $naziv;
    public $slika;
    public $kontinent;
    
    static $table = 'zastava';
    static $id = 'id';
    
    public $limit;
    
    private $connection;
    
    
    public function __construct(){
        global $database;
        $this->connection = $database;
    }
    
    public function __destruct(){
        $this->connection = null;
    }
   
    public function create(){
        $this->connection->query('insert into zastava (naziv,slika,kontinent) values(:naziv, :slika, :kontinent)');
        $this->connection->bind(':naziv', $this->naziv);
        $this->connection->bind(':slika', $this->slika);
        $this->connection->bind(':kontinent', $this->kontinent);
        $result = $this->connection->execute();
        return $result;
    }
    
    public function update(){
        $this->connection->query('update zastava set naziv=:slika, slika=:slika, kontinent=:kontinent '.
            ' where id=:id ');
        $this->connection->bind(':id', $this->id);
        $this->connection->bind(':slika', $this->slika);
        $this->connection->bind(':slika', $this->slika);
        $this->connection->bind(':kontinent', $this->kontinent);
        $result = $this->connection->execute();
        return $result;
    }
    
    
    public function flags(){
        $rez = self::find_by_query("SELECT * FROM zastava ORDER BY RAND() LIMIT ".self::getLimit()->broj);   
        return $rez;
    }
    

    public static function getLimit(){
        $rez = self::find_one('select broj from brojZastava order by id desc limit 1');
        return $rez;
    }
    
    public  function setLimit($x){
        $this->limit = $x;
        $this->connection->query('update brojZastava set broj=:broj ');
        $this->connection->bind(':broj', $this->limit);
        $result = $this->connection->execute();
        return $result;     
    }
    
    
    
        
}

?>