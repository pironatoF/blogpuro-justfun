<?php

namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;


/**
 * Class mysqlAdapter
 *
 * @author Pironato Francesco
 */
class mysqlAdapter {
    
    protected $connection;
    
    
    /**
     * Class name of the singleton  object.
     * @var string
     */
    private static $_adapterClassName = 'mysqlAdapter';

    private static $_adapter = null;

    public static function getInstance()
    {
        if (self::$_adapter === null) {
            self::init();
        }
        return self::$_adapter;
        
    }

    public static function setInstance(mysqlAdapter $adapter)
    {
        if (self::$_adapter !== null) {
            Exception('mysqlAdapter is already initialized');
        }
        self::setClassName(get_class($adapter));
        self::$_adapter = $adapter;
    }

    protected static function init()
    {
        $adapter = new self;
        
        self::setInstance($adapter);
        $adapter->setConnection();
        
    }
    
    public static function setClassName($adapterClassName = 'mysqlAdapter')
    {
        if (self::$_adapter !== null) {
            
            throw new Exception('mysqlAdapter is already initialized');
        }

        if (!is_string($adapterClassName)) {
            
            throw new Exception("Argument is not a class name");
        }

        self::$_adapterClassName = $adapterClassName;
    }
   
    protected function setConnection()
    {
        // @TODO: spostare i dati hardcoded in un file di configurazione!
        $this->connection = mysqli_connect('localhost', 'root', 'itathesmoker', 'blogpuro')or die('connection error');
        
        return $this;
    }
    
    public function getConnection(){
        return $this->connection;
    }
    
    public function find($id,$table,$entityPrototype){
        if(!is_int($id)) die('error'); // gestire eccezione
        $sql = "SELECT * FROM ".$table." WHERE id=".$id;
        $query = mysqli_query($this->connection, $sql);
        $result = mysqli_fetch_row($query);
        // manipolare e idratare result
        return self::hydrate($result, $entityPrototype);
    }
    
    public function getAll($table,$entityPrototype){
        $sql = "SELECT * FROM ".$table;
        $query = mysqli_query($this->connection, $sql);
        $resultsBag = array();
        while($result = mysqli_fetch_assoc($query)){
            //$resultsBag[] = $result;
            $resultsBag[] = self::hydrate($result, $entityPrototype);
        }
        // manipolare result
        return $resultsBag;
    }
    
    public function search(){
        // implementare
    }
    
    // vedere se tenere o eliminare
    public function __destruct() {
        mysqli_close($this->connection);
    }
    
    /**
     * 
     * @param type $result
     * @param type $entityPrototype
     * @return type
     */
    protected static function hydrate($result,$entityPrototype){
        $entity = clone $entityPrototype;
        foreach($result as $k => $v){
            $methodName = 'set'.ucfirst($k);
            $entity->$methodName($v);
        }
        return $entity;
    }
}
