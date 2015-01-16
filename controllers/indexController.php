<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Repositories\postsRepository as postsRepository;
use Justfun\Services\Factory as servicesFactory;

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
        $getData = $this->request->getGetData();
        $postsRepository = RepositoriesFactory::getPostsRepository();
        if(!isset($getData['page']))$getData['page'] = 1; // forzo la paginazione
        if(isset($getData['page'])){
            $page = $getData['page'];
            $postsNumberPerPage = 4;  // determino il numero di post da visualizzare per pagina
            $postsCount = (int)$postsRepository->count(array())['data']['count'];
            $paginator = servicesFactory::getPaginatorService($page, $postsCount,$postsNumberPerPage);
            $paginator->setBaseUrl('/');
            $filters = array(
                
            );
            $data['posts'] = $postsRepository->search($filters,'DESC',$paginator);
            $data['paginator'] = $paginator;
        }else{
            $data['posts'] = $postsRepository->getAll();
        }
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }
    
    
    
    
}
