<?php
namespace Justfun\Entities;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * userEntity
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class userEntity {
    
    const ENTITY_NAME = 'user';
    
    protected $id,$username,$email,$password,$created,$description,$authorName;
    
    public function insertExceptions(){
        return array('created'=>'created');
    }
    
    public function getAuthorName() {
        return $this->authorName;
    }

    public function setAuthorName($authorName) {
        $this->authorName = $authorName;
        return $this;
    }

        public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId($id) {
        $this->id = (int)$id;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
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

}
