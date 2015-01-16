<?php
namespace Justfun\Entities;

use Justfun\Repositories\Factory as repositoriesFactory;
use Justfun\Entities\userEntity as userEntity;
/**
 * Description of postCommentEntity
 *
 * @author Pironato Francesco
 */
class postCommentEntity {
    
    const ENTITY_NAME = 'post_comments';
    const MESSAGE_PREVIEW_MAXLENGTH = 120;
        
    protected $id,$message,$created,$postId,$userId,$isGuest;
    
    /**
     * @TODO: rifattorizzare il tutto in un service che gestisca queste eccezioni
     */
             
    public function insertExceptions(){
        return array('created'=>'created');
    }
    
    // lazy loading
    public function getUser(){
        // gestire guest
        $isGuest =  (int)$this->isGuest;
        if( 0 === $isGuest ){
            try {
                return repositoriesFactory::getUsersRepository()->find((int)$this->userId);
            } catch (Exception $exc) {
                die($exc->getTraceAsString()); // @todo: gestire meglio eccezione
            }
        }else{
            //fake
            $user = new userEntity();
            $user->setAuthorName('Anonymus')
                 ->setDescription('A guest user')
                 ->setEmail('anonymus@user.guest');
            return $user;
        }
    }
    
    public function getPost(){
        try {
            return repositoriesFactory::getPostsRepository()->find((int)$this->postId);
        } catch (Exception $exc) {
            die($exc->getTraceAsString()); // @todo: gestire meglio eccezione
        }
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
    

    public function getMessage() {
        return stripslashes($this->message);
    }
    
    public function getMessagePreview($previewSize = null){
        if(!$previewSize) $previewSize = self::MESSAGE_PREVIEW_MAXLENGTH;
        $message = substr(stripslashes($this->message), 0, $previewSize);
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

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
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
    
    public function getPostId() {
        return $this->postId;
    }

    public function getIsGuest() {
        return $this->isGuest;
    }

    public function setPostId($postId) {
        $this->postId = $postId;
        return $this;
    }

    public function setIsGuest($isGuest) {
        $this->isGuest = $isGuest;
        return $this;
    }


}
