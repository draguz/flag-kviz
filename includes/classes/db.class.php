<?php


class Db_object {
	
	

	public static function find_all() {
		return static::find_by_query("SELECT * FROM " . static::$table . " ");	
	}
	
	public static function find_by_id($id) {
		return static::find_one("SELECT * FROM " . static::$table . " where ".static::$id. " = ".$id);
	}

	public static function count() {
		return static::find_one("SELECT count(*) as count FROM " . static::$table . " ");
	}
	
	public static function delete_row($id){
		global $database;
		$database->query("delete from " . static::$table . " where ".static::$id. " = ".$id);
		$database->bind(':id', $id);
		$result = $database->execute();
		return $result;
	}
	
	
	
		public static function find_by_query($sql) {
			global $database;
			$database->query($sql);
			$database->execute();
			$result = $database->resultset();
			return $result;	
		}
		
		public static function find_one($sql) {
			global $database;
			$database->query($sql);
			$database->execute();
			$result = $database->single();
			return $result;
		}
	
		
		
}