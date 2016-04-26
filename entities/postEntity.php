<?php
namespace Justfun\Entities;

use Justfun\Repositories\Factory as repositoriesFactory;

/**
 * Description of postEntity
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class postEntity {
    
    const ENTITY_NAME = 'post';
    const MESSAGE_PREVIEW_MAXLENGTH = 120;
        
    protected $id,$title,$message,$created,$url,$userId,$published;
    
    /**
     * @TODO: rifattorizzare il tutto in un service che gestisca queste eccezioni
     */
             
    public function insertExceptions(){
        return array('created'=>'created');
    }
    
    public function getPublished() {
        return $this->published;
    }

    public function setPublished($published) {
        $this->published = $published;
        return $this;
    }

        // lazy loading
    public function getUser(){
        $user = repositoriesFactory::getUsersRepository()->find((int)$this->userId);
        return $user;
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = (int)$userId;
        return $this;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getMessage() {
        return stripslashes($this->message);
    }
    
    public function getMessagePreview(){
        $message = substr(stripslashes($this->message), 0, self::MESSAGE_PREVIEW_MAXLENGTH);
        libxml_use_internal_errors(true); // evita errori dovuti a tag troncati
        $node = new \DOMDocument();
        $node->loadHTML($message);
        $node->normalizeDocument(); // normalizza il documento (chiude tag aperti)
        // rimuove doctype e tag html body
        $message =  preg_replace('/^<!DOCTYPE.+?>/',
                                 '',
                                str_replace( 
                                    array('<html>', '</html>', '<body>', '</body>'), 
                                    array('', '', '', ''), 
                                    $node->saveHTML()));
        return $message;
    }

    public function getCreated() {
        return date("g:i a F j, Y ", strtotime($this->created));
        //return $this->created;
    }

    public function setId($id) {
        $this->id = (int)$id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    // @TODO: generare l'url adatto
    public function getUrl() {
        return '/'.self::ENTITY_NAME.'/show/'.$this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }
    
    public function deHydrate(){
        $dataEntity = (array)get_object_vars ( $this );
        foreach($dataEntity as $k => $v){
            if(array_key_exists($k, $this->insertExceptions())){
                unset($dataEntity[$k]);
            }
        }
        return $dataEntity;
    }
    
    // lazy
    public function getComments(){
        return repositoriesFactory::getPostCommentsRepository()->getAllByPost($this);
    }
    
    // lazy
    public function getCommentsCount(){
        return (int)repositoriesFactory::getPostCommentsRepository()->count(array('post_id'=> (int)$this->getId()))['data']['count'];
    }
    
}
