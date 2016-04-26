<?php

namespace Justfun\Repositories;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Entities\postEntity as Entity;
use Justfun\Traits\dataPersistenceTrait as dataPersistenceTrait;

use Justfun\Services\paginatorService as Paginator;

/**
 * Description of postsRepository
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class postsRepository {

    // qui assumiamo per comodità di utilizzare mysql :D
    const MAIN_TABLE = 'posts';

    protected $database, $tableName = 'posts';

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
            //$resultsBag[] = $result;
            $resultsBag[] = dataPersistenceTrait::hydrate($result, new Entity());
        }
        // manipolare result
        return $resultsBag;
        
    }
    
    public function find($id) {
        return $this->database->getConnectionAdapter()->find($id, self::MAIN_TABLE, new Entity());
    }

    public function findByTitle($title) {
        $connection = $this->database->getConnectionAdapter()->getConnection();
        $sql = 'SELECT * FROM ' . self::MAIN_TABLE . ' WHERE title="' . $title . '"';
        $query = mysqli_query($connection, $sql);
        $result = mysqli_fetch_assoc($query);
        return dataPersistenceTrait::hydrate($result, new Entity());
        /** manipolare dati (hydrate)... forse conviene farlo
         *  in tutti i repo usando un traits da portare orizzontalmente!
         */
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
        $entity = $this->find($id);
        $this->database->getConnectionAdapter()->delete($id,self::MAIN_TABLE);

        $postCommentsRepository = RepositoriesFactory::getPostCommentsRepository();
        $comments = $postCommentsRepository->getAllByPost($entity);
        if(count($comments) > 0 ){
            foreach($comments as $comment){
                $postCommentsRepository->delete($comment->getId());
            }
        }
        return;
    }

    public function count(array $where){
        return $this->database->getConnectionAdapter()->count(self::MAIN_TABLE,$where);
    }
    
    public function search(array $filters = null,$order = 'DESC',Paginator $paginator) {
        return $this->database->getConnectionAdapter()->search($filters,$order,$paginator,self::MAIN_TABLE,new Entity());
    }

}
