<?php

namespace Justfun\Controllers;


use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of testController
 *
 * @author ita
 * 
 * @TODO: fare un'astrazione e restituire sempre un oggetto Response per ogni action
 */
class apiController extends Controller{
       
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        
        $getData = $this->request->getApplication()->getCore()->getGet();
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
