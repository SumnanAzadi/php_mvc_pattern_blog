<?php
/*
 * PDO database class
 * Connect to Database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */

class Database{
    private $host=DB_HOST;
    private $user=DB_USER;
    private $pass =DB_PASS;
    private $dbname =DB_NAME;

    private $dbhandelling;
    private $stmt;
    private $error;


    public function __construct(){
        //set DSN
        $dsn ='mysql:host='.$this->host.';dbname='.$this->dbname;

        $option =array(
            PDO::ATTR_PERSISTENT =>true,
            PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION
        );

        //create PDO instance
        try{
            $this->dbhandelling =new PDO($dsn,$this->user,$this->pass,$option);
        }catch (PDOException $e){
            $this->error =$e->getMessage();
            echo $this->error;
        }
    }

    //Prepare statements with query
    public function query($sql){
        $this->stmt=$this->dbhandelling->prepare($sql);
    }

    //Bind Values
    /*
     * Example:::
    $STH = $DBH->prepare("SELECT * FROM table_name WHERE id = 1");
    $STH->execute();
    Same as.................
    $STH = $DBH->prepare("SELECT * FROM table_name WHERE id = :id");
    $STH->bindValue(':id', '1', PDO::PARAM_STR);
    $STH->execute();
    */
    public function bind($param, $value, $type=null){
        //if the type of the value is null then....
        if (is_null($type)){
            switch (true){
                case is_int($value):
                    $type=PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type=PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type=PDO::PARAM_NULL;
                    break;
                default:
                    $type=PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param,$value,$type);
    }

    //Execute the prepared statements
    public function execute(){
        return $this->stmt->execute();
    }

    //Get result set as array of objects
    public function resultSet(){
        $this->execute();//calling execute function
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //Get single record as object
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    //Get Row Count
    public function rowCount(){
        return $this->stmt->rowCount();
    }

}