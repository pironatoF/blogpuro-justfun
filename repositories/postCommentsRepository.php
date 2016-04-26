<?php

namespace Justfun\Repositories;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Entities\postCommentEntity as Entity;
use Justfun\Traits\dataPersistenceTrait as dataPersistenceTrait;

use Justfun\Services\paginatorService as Paginator;

/**
 * Description postCommentsRepository
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class postCommentsRepository{

    // qui assumiamo per comodità di utilizzare mysql
    const MAIN_TABLE = 'post_comments';

    protected $database, $tableName = 'post_comments';

    public function __construct($database) {
        $this->database = $database;
    }

    public function getAll() {
        return $this->database->getConnectionAdapter()->getAll(self::MAIN_TABLE, new Entity());
    }
    
    public function getAllByAuthor($author){
        $connection = $this->database->getConnectionAdapter()->getConnection();
        $sql = "SELECT * FROM " . self::MAIN_TABLE;
        $sql .= " WHERE user_id=".$author->getId();
        $sql.= " ORDER BY id DESC";
        $query = mysqli_query($connection, $sql);
        $resultsBag = array();
        while ($result = mysqli_fetch_assoc($query)) {
            $resultsBag[] = dataPersistenceTrait::hydrate($result, new Entity());
        }
        // manipolare result
        return $resultsBag;
    }
    
    public function getAllByPost($entity)
    {
        $connection = $this->database->getConnectionAdapter()->getConnection();
        $sql = "SELECT * FROM " . self::MAIN_TABLE;
        $sql .= " WHERE post_id=".$entity->getId();
        $sql.= " ORDER BY id DESC";
        try {
            $query = mysqli_query($connection, $sql);
            $resultsBag = array();
            while ($result = mysqli_fetch_assoc($query)) {
                //die(var_dump($result));
                $resultsBag[] = dataPersistenceTrait::hydrate($result, new Entity());
            }
        } catch (Exception $exc) {
            die($exc->getTraceAsString()); //@todo: gestire eccezione
        }
        return $resultsBag;
    }
    
    public function find($id) {
        return $this->database->getConnectionAdapter()->find($id, self::MAIN_TABLE, new Entity());
    }

    public function findBy($key,$value) {
        return $this->database->getConnectionAdapter()->findBy($key,$value,self::MAIN_TABLE, new Entity());
    }
    
    public function store(Entity $entity = null){
        if(!$entity){ // performe insert
            $entity= new Entity(); 
        }else{
            $entity->setMessage(addslashes($entity->getMessage())); 
        }
        return $this->database->getConnectionAdapter()->store($entity,self::MAIN_TABLE);
    }
    
    public function delete($id){
        return $this->database->getConnectionAdapter()->delete($id,self::MAIN_TABLE);
    }

    public function count(array $where){
        return $this->database->getConnectionAdapter()->count(self::MAIN_TABLE,$where);
    }
    
    public function search(array $filters = null,$order = 'DESC',Paginator $paginator) {
        return $this->database->getConnectionAdapter()->search($filters,$order,$paginator,self::MAIN_TABLE,new Entity());
    }

}
