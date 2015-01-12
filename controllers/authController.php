<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

use Justfun\Repositories\Factory as RepositoriesFactory;


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
    }
    
    public function loginAction(){
        $requestData = $this->request->getApplication()->getCore()->getPost();
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->addStylesheet('login');
        //$this->response->setData($data);
        $this->response->render();
    }
    
    /*public function indexAction(){
        $getData = $this->request->getApplication()->getCore()->getGet();
        $data = array('test'=> 'prova prova','key'=>'test test');
        
        $postsRepository = RepositoriesFactory::getPostsRepository();
        $data['posts'] = $postsRepository->getAll();
        
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }*/
    
    
    
    
}
