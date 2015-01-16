<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Services\Factory as servicesFactory;


/**
 * Class authorController
 *
 * @author Pironato Francesco
 * 
 */
class authorController extends Controller{
       
    public function init(){
        parent::init();
        $this->response = CoreFactory::getResponse();
        $this->response->setData(array('title'=>'Author Area - BlogPuro'));
        $this->response->addStylesheet('author'); 
        $this->response->addStylesheet('admin');
        /**
         *  @TODO: gestire ACL con una classe apposita
         */
        // acl temporaneo che rimanda l'utente al login se non è loggato
        if(!$this->response->getAuthService()->isLogged()) $this->response->redirect('/auth/login');
    }
    
    /**
     *  @TODO: utilizzare un nuovo layout con sidebar contente menu,
     *         estendere response per injettare controller(anche json stlye) 
     */
    public function sidebarMenuAction(){
        
    }
    
    // dashboard
    public function indexAction(){
        $getData = $this->request->getGetData();
        $postsRepository = RepositoriesFactory::getPostsRepository();
        $currentUser = servicesFactory::getAuthService()->getUser();
        
        if(!isset($getData['page']))$getData['page'] = 1; // forzo la paginazione
        
        if(isset($getData['page'])){
            $page = $getData['page'];
            $postsNumberPerPage = 5;  // determino il numero di post da visualizzare per pagina
            $postsCount = (int)$postsRepository->count(array('user_id'=>$currentUser->getId()))['data']['count'];
            $paginator = servicesFactory::getPaginatorService($page, $postsCount,$postsNumberPerPage);
            $paginator->setBaseUrl('/author/index/');
            $filters = array(
                'user_id' => $currentUser->getId()
            );
            $data['posts'] = $postsRepository->search($filters,'DESC',$paginator);
            $data['paginator'] = $paginator;
        }else{
            $data['posts'] = $postsRepository->getAllByAuthor($currentUser);
        }
        
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->setActiveNav('index');
        $this->response->render();
    }
    
    
    
    
    
    /*public function loginAction(){
        
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
    }*/
  
    
    
    
    
}
