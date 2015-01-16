<?php

namespace Justfun\Core;

use Justfun\Core\Factory as CoreFactory;
use Justfun\Traits\dataPersistenceTrait as dataPersistenceTrait;

use Justfun\Services\paginatorService as Paginator;

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

    public static function getInstance() {
        if (self::$_adapter === null) {
            self::init();
        }
        return self::$_adapter;
    }

    public static function setInstance(mysqlAdapter $adapter) {
        if (self::$_adapter !== null) {
            Exception('mysqlAdapter is already initialized');
        }
        self::setClassName(get_class($adapter));
        self::$_adapter = $adapter;
    }

    protected static function init() {
        $adapter = new self;

        self::setInstance($adapter);
        $adapter->setConnection();
    }

    public static function setClassName($adapterClassName = 'mysqlAdapter') {
        if (self::$_adapter !== null) {

            throw new Exception('mysqlAdapter is already initialized');
        }

        if (!is_string($adapterClassName)) {

            throw new Exception("Argument is not a class name");
        }

        self::$_adapterClassName = $adapterClassName;
    }

    protected function setConnection() {
        // @TODO: spostare i dati hardcoded in un file di configurazione!
        $this->connection = mysqli_connect('localhost', 'root', 'itathesmoker', 'blogpuro')or die('connection error');

        return $this;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function find($id, $table, $entityPrototype) {
        if (!is_int($id))
            die('error'); // gestire eccezione
        $sql = "SELECT * FROM " . $table . " WHERE id=" . $id;
        $query = mysqli_query($this->connection, $sql);
        $result = mysqli_fetch_assoc($query);
        return dataPersistenceTrait::hydrate($result, $entityPrototype);
    }

    // @TODO: ordinare al contrario!! (nuovi => vecchi)
    public function getAll($table, $entityPrototype) {
        $sql = "SELECT * FROM " . $table." ORDER BY id DESC";
        $query = mysqli_query($this->connection, $sql);
        $resultsBag = array();
        while ($result = mysqli_fetch_assoc($query)) {
            $resultsBag[] = dataPersistenceTrait::hydrate($result, $entityPrototype);
        }
        // manipolare result
        return $resultsBag;
    }

    public function findBy($key, $value, $table, $entityPrototype) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $key . '="' . $value . '"';
        $query = mysqli_query($this->connection, $sql);
        $result = mysqli_fetch_assoc($query);
        if (!$result)
            return null;
        return dataPersistenceTrait::hydrate($result, $entityPrototype);
    }

    // vedere se tenere o eliminare
    public function __destruct() {
        mysqli_close($this->connection);
    }

    public function insert($dataEntity, $table) {


        $sql = "INSERT INTO " . $table . "(";
        $keys = count($dataEntity);
        $values = $keys;
        foreach ($dataEntity as $k => $v) {
            $sql.= dataPersistenceTrait::camelToMysql($k);
            if ($keys > 1)
                $sql.= " , ";
            $keys--;
        }
        $sql.= ") VALUES (";
        foreach ($dataEntity as $k => $v) {
            $sql.= "'" . $v . "'";
            if ($values > 1)
                $sql.= ', ';
            $values--;
        }
        $sql.=")";

        //die($sql); // controllare e togliere il die

        if (mysqli_query($this->connection, $sql)) {
            //echo "New record created successfully";
        } else {
            die(mysqli_error($this->connection));
        }
    }

    public function delete($id,$table){
        $id = (int)$id;
        $sql = 'DELETE FROM '.$table;
        $sql.= ' WHERE id='.$id;
        if (mysqli_query($this->connection, $sql)) {
            return array('status'=>true);
        } else {
            return array('status'=>false,'error'=>mysqli_error($this->connection));
        }
    }
    
    public function update($dataEntity, $table) {
        $id = (int) $dataEntity['id'];
        unset($dataEntity['id']);

        $sql = 'UPDATE ' . $table . ' SET ';
        $keys = count($dataEntity);
        foreach ($dataEntity as $k => $v) {
            $sql.= dataPersistenceTrait::camelToMysql($k) . ' = "' . $v . '" ';
            if ($keys > 1)
                $sql.= ' , ';
            $keys--;
        }
        $sql .= ' WHERE id=' . $id;

        if (mysqli_query($this->connection, $sql)) {
            //return $id;
        } else {
            die(mysqli_error($this->connection));
        }
    }

    public function store($entity, $table) {
        try {
            if ($entity->getId()) {
                // update
                $this->update($entity->deHydrate(), $table);
            } else {
                // insert
                /**
                 * @TODO: escludere le date come created, altrimenti non le crea mysql in insert,
                 * ad esempio in ogni entity fare un metodo insert exception che torni una lista
                 * di propietà su cui fare l'unset
                 */
                $this->insert($entity->deHydrate(), $table);
            }
            return $entity;
        } catch (Exception $exc) {
            die($exc->getTraceAsString());
        }
    }
    
    public function count($table,array $where){
        $sql = 'SELECT COUNT(*) AS count FROM '.$table;
        if($where){
            $sql.= ' WHERE ';
            $keys = count($where);
            foreach($where as $k => $v){
                $sql.= $k.' = "'.$v.'"';
                if($keys > 1) $sql.=' , ';
                $keys--;
            }
        }
        
        $query =mysqli_query($this->connection, $sql);
        if ($query) {
            return array('status' => true,'data'=> mysqli_fetch_assoc($query));
        } else {
            return array('status' => false,'error'=> mysqli_error($this->connection));
        }
    }
    
    public function search(array $filters = null,$order = 'DESC',Paginator $paginator,$table, $entityPrototype) {
        // inizializzare la query con la tabella
        $sql = "SELECT * FROM " . $table;
        // gli devo passare i filtri (where)
        if($filters){
            $sql.= ' WHERE ';
            $keys = count($filters);
            foreach($filters as $k => $v){
                $sql.= $k.' = "'.$v.'"';
                if($keys > 1) $sql.=' , ';
                $keys--;
            }
        }
        // gli devo passare l'order (default DESC)
        $sql.=" ORDER BY id ".$order;
        // gli devo settare offset e limit
        $sql.= " LIMIT ".$paginator->getOffset().",".$paginator->getLimit();
        //die($sql);
        $query = mysqli_query($this->connection, $sql);
        $resultsBag = array();
        while ($result = mysqli_fetch_assoc($query)) {
            $resultsBag[] = dataPersistenceTrait::hydrate($result, $entityPrototype);
        }
        // manipolare result
        return $resultsBag;
    }

}
