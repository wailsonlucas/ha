<?php
class Database{
    public $isConnected;
    protected $database;
    public function __construct($host = "localhost",$user="root",$password = "",$database = "social")
    {
        $this->isConnected = true;
        try{
            $this->database = new PDO("mysql:host={$host};dbname={$database}",$user,$password);

            $this->database->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

        }catch(PDOException $e){

            throw new exception($e->getMessage());
        }
    }


    public function Disconnect(){
        $this->isConnected = false;
        $this->database = null;
        
    }


    public function GetRows($query,$params = []){
        try
        {

            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }
        catch(PDOException $e)
        {
            throw new exception($e->getMessage());

        }
    }


    public function GetRow($query,$params = []){
        try
        {

            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        }
        catch(PDOException $e)
        {
            throw new exception($e->getMessage());

        }
    }



    public function Insert($query,$params = []){
        try
        {

            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return true;
        }
        catch(PDOException $e)
        {
            throw new exception($e->getMessage());

        }
    }

 

    public function getCount($query,$params = [])
    {
        try{
            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
    
           return  $stmt->rowCount();

        }
        catch(PDOException $e)
        {
            throw new Exception($e->getMessage());

        }

    }



     
      public function Update($query,$params = [])
      {
         $this->Insert($query,$params);
      }
 
 
    
      public function Delete($query,$params = [])
      {
         $this->Insert($query,$params);
      }



}