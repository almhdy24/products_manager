<?php 
namespace App;

class SQLiteConnection 
{
    private $db;

    /**
     * @return \PDO
     */
    public function connect()
     {
        if ($this->db == null) {

            $this->db = new \PDO("sqlite:".Config::DB_PATH);

        }
        
        return $this->db;
     }
     
}