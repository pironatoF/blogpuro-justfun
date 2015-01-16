<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Services\Factory as servicesFactory;


/**
 * Class authController
 *
 * @author Pironato Francesco
 * 
 */
class authController extends Controller{
       
    public function init(){
        parent::init();
        $this->response = CoreFactory::getResponse();
        $this->response->setData(array('title'=>'Login - BlogPuro'));
        $this->authService = servicesFactory::getAuthService();
    }
    
    public function logoutAction(){
         servicesFactory::getAuthService()->logout();
         $this->response->redirect('/index');
    }
    
    public function loginAction(){
        
        if($this->authService->isLogged()){
            // se l'utente è loggato lo rimando all'homepage
            $this->response->redirect('/index');
        }
        
        $requestData = $this->request->getApplication()->getCore()->getPost();
        
        if($requestData){
            // setto nuovamente l'email in caso l'utente abbia sbagliato pass
            $this->response->setData(array('email'=>$requestData['email']));
            $usersRepository = RepositoriesFactory::getUsersRepository();
            $user = $usersRepository->findByValue('email', $requestData['email']);
            if(!$user){
                // creare una classe generica che si occupi di gestire gli errori
            }else{
                if($user->getPassword() === md5($requestData['password'])){
                    
                    // loggare l'utente
                    servicesFactory::getAuthService()->login($user);
                    $this->response->redirect('/index');
                }else{
                    // password errata, gestire errore...
                }
            }
        }
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->addStylesheet('login');
        //$this->response->setData($data);
        $this->response->render();
    }
  
    
    
    
    
}
