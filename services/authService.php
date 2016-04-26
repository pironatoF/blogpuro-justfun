<?php

namespace Justfun\Services;

use Justfun\Repositories\Factory as repositoriesFactory;

use Justfun\Traits\sessionsTrait  as sessionsTrait; 

/**
 * Clss authService
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
Class authService {
     
    protected $user;
    
    /**
     * isLogged
     * @return boolean
     */
    public function isLogged(){
        return sessionsTrait::sessionDataExist('user');
    }
    
    public function logout(){
        sessionsTrait::sessionDataPop('user');
    }
    
    public function login($user){
        if(!$this->isLogged()){
            sessionsTrait::sessionDataPush('user',$user->getId());
        }
    }
    
    public function getUser(){
        if(!$this->isLogged()) return null;
        $userId = (int)sessionsTrait::sessionDataGet('user');
        $usersRepository = repositoriesFactory::getUsersRepository();
        $this->user = $usersRepository->find($userId);
        if(!$this->user) die('user doesnt exist'); // gestire meglio l'errore (eccezioni..)
        return $this->user;
    }
    
}
