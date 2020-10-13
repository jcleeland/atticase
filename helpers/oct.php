<?php
//Connect using MySQLi

//This class deals with all data transactions

class oct {
    var $dbpass;
    var $dbuser;
    var $dbhost;
    var $dbname;
    var $dbtype="mysql";
    var $dbprefix="oct";
    var $db;
    
    function connect() {
        $dsn=$this->dbtype.":host=".$this->dbhost.";dbname=".$this->dbname.";charset=UTF8";
        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];
        try {
            $this->db=new PDO($dsn, $this->dbuser, $this->dbpass, $options);
        } catch(\PDOException $e) {
            //Send the error message to the error popup ($e->getMessage())
            //throw new \PDOException($e->getMessage(), (int)$e->getCode());
            echo "Database connection failed: ".$e->getMessage();
        }
    }
}




?>
