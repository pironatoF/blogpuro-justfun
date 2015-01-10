<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Repositories\postsRepository as postsRepository;

/**
 * Description of indexController
 *
 * @author Pironato Francesco
 * 
 */
class indexController extends Controller{
       
    public function init(){
        parent::init();
        $this->response = CoreFactory::getResponse();
        $this->response->setData(array('title'=>'Homepage - BlogPuro'));
    }
    
    public function indexAction(){
        $getData = $this->request->getApplication()->getCore()->getGet();
        $data = array('test'=> 'prova prova','key'=>'test test');
        
        $postsRepository = RepositoriesFactory::getPostsRepository();
        $data['posts'] = $postsRepository->getAll();
        
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }
    
    
    
    
}
