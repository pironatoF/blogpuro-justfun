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
 * @author Pironato Francesco
 */
class userEntity {
    
    const ENTITY_NAME = 'user';
    
    protected $id,$username,$email,$password,$created,$description;
    
    
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
        $this->id = $id;
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



}
