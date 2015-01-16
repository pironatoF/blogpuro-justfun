<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;
use Justfun\Services\Factory as servicesFactory; 

use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Repositories\postsRepository as postsRepository;

use Justfun\Services\urlManagerService as urlManagerService; 

use Justfun\Traits\dataPersistenceTrait as dataPersistenceTrait;

use Justfun\Entities\postEntity as postEntity;

/**
 * Class postController
 *
 * @author Pironato Francesco
 * 
 */
class postController extends Controller{
       
    public function init(){
        parent::init();
        $this->response = CoreFactory::getResponse();
        // @TODO: se sono nella pagina dell'articolo singolo fare l'override con il titolo dell'articolo nel title
        $this->response->setData(array('title'=>'Article - BlogPuro'));
    }
    
    public function showAction(){
        $url = servicesFactory::getUrlManagerService($this->request->getRequestUri(),urlManagerService::URL_SHOW,'post')->getUrl();
        $postsRepository = RepositoriesFactory::getPostsRepository();
        
        // @TODO: cercare l'articolo dall'url (considerare %s' finale??)
        $post =  $postsRepository->findBy('url',$url);
        if($post){
            $data['post'] = $post;
        }else{
            $data = array();
            // !!!! non è stato trovato nessun articolo.. @TODO:gestire eccezione(fancy)
        }
        
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }
    
    public function editAction(){
        $this->response->addStylesheet('author'); 
        $this->response->addStylesheet('admin');
        $this->response->setLayout('author');
        $this->response->setActiveNav('post-edit');
        
        $postsRepository = RepositoriesFactory::getPostsRepository();
        $id = servicesFactory::getUrlManagerService($this->request->getRequestUri(),urlManagerService::URL_EDIT,'post')->getUrl();
        $formData = $this->request->getPostData();
        $post = $postsRepository->find($id);
        
        if($formData){
            // @TODO: implementare validatori
            $post = dataPersistenceTrait::save($formData,$post);
            $postsRepository->store($post);
            if($post->getPublished()) $this->response->redirect($post->getUrl()); 
            $this->response->redirect('/author/index/?op=ok');
        }
        
        if($post){
            $data['post'] = $post;
        }else{
            // non è stato trovato nessun articolo.. @TODO:gestire eccezione(fancy)
        }
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }
    
    
    
    //@TODO: edit url solamente per admin (gestire con acl)
    public function addAction(){
        $this->response->addStylesheet('author'); 
        $this->response->addStylesheet('admin');
        $this->response->setLayout('author');
        $this->response->setActiveNav('post-add');
        $data = array();
        $formData = $this->request->getPostData();
        $postsRepository = RepositoriesFactory::getPostsRepository();
        
        if( (!isset($formData['title']) || empty($formData['title']) ) || 
            (!isset($formData['message']) || empty($formData['message']) )
        ){
            if($formData){ $data['post'] = $formData;  }
        }else{
            // @TODO: implementare validatori
            
            $formData['url'] = servicesFactory::getUrlifyService($formData['title'])->getUrl();
            $formData['user_id'] = servicesFactory::getAuthService()->getUser()->getId();
            
            $post = dataPersistenceTrait::save($formData,new postEntity());
            $postsRepository->store($post);
            if($post->getPublished()) $this->response->redirect($post->getUrl()); 
            $this->response->redirect('/author/index/?op=ok');

        }
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }
    
    public function deleteAction(){
        if(!$this->response->getAuthService()->isLogged()) $this->response->redirect('/auth/login');
        $postsRepository = RepositoriesFactory::getPostsRepository();
        $id = servicesFactory::getUrlManagerService($this->request->getRequestUri(),urlManagerService::URL_DELETE,'post')->getUrl();
        /**
         *  @todo: dare la possibilità di settare il messaggio di errore da passare a response
         *  (creare un array come propietà e sfruttare il metodo gia esistente degli alert per
         *   il render)
         */
        $deleteOp = $postsRepository->delete($id);
        if(false === $deleteOp['status']){
            // qui fare il set dell'errore contenuto in $deleteOp['error']
            $this->response->redirect('/author/index/?op=ko');
        }
        $this->response->redirect('/author/index/?op=ok');
    }
    
}
