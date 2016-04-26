<?php

namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;


/**
 * Description of Database
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Database {
    
    
    // il tipo di database da collegare (mysql,mongodb....)
    protected $adapter;
    
    /**
     * Class name of the singleton  object.
     * @var string
     */
    private static $_databaseClassName = 'Database';

    private static $_database = null;

    public static function getInstance()
    {
        if (self::$_database === null) {
            self::init();
        }
        return self::$_database;
        
    }

    public static function setInstance(Database $database)
    {
        if (self::$_database !== null) {
            Exception('Database is already initialized');
        }
        self::setClassName(get_class($database));
        self::$_database = $database;
    }

    protected static function init()
    {
        $database = new self;
        self::setInstance($database);
        $database->setAdapter(CoreFactory::getMysqlAdapter());
    }
    
    public static function setClassName($databaseClassName = 'Database')
    {
        if (self::$_database !== null) {
            
            throw new Exception('Database is already initialized');
        }

        if (!is_string($databaseClassName)) {
            
            throw new Exception("Argument is not a class name");
        }

        self::$_databaseClassName = $databaseClassName;
    }
  
    // vedere se trasformare in protetta
    public function setAdapter($adapter){
        $this->adapter = $adapter;
    }
    
    public function getConnectionAdapter(){
        return $this->adapter;
    }
}
