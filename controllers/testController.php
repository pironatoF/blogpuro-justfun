<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

/**
 * Description of testController
 *
 * @author ita
 * 
 * @TODO: fare un'astrazione e restituire sempre un oggetto Response per ogni action
 */
class testController extends Controller{
       
    public function init(){
        parent::init();
    }
    
    public function testAction(){
        $getData = $this->request->getApplication()->getCore()->getGet();
        $query = array();
        foreach($getData as $k=>$v){
            $query[$k] = $v;
        }
        $data = $query;
        
        $this->response = CoreFactory::getResponse();
        $this->response->setType(Response::RESPONSE_HTML)->setData($data);
        $this->response->render();
    }
    
    
    public function postAction(){
        $getData = $this->request->getApplication()->getCore()->getPost();
        $query = array();
        foreach($getData as $k=>$v){
            $query[$k] = $v;
        }
        $data = $query;
        
        if(!$data) $data = array('message'=>'ciao ciao');
        $this->response = CoreFactory::getResponse();
        $this->response->setType(Response::RESPONSE_JSON)->setData($data);
        $this->response->render();
    }
    
    //
    
    public function postjsonAction(){
        $getData =  json_decode(file_get_contents('php://input'), true);
        $query = array();
        foreach($getData as $k=>$v){
            $query[$k] = $v;
        }
        $data = $query;
        
        if(!$data) $data = array('message'=>'ciao ciao');
        $this->response = CoreFactory::getResponse();
        $this->response->setType(Response::RESPONSE_JSON)->setData($data);
        $this->response->render();
    }
    
    
}
