<?php
namespace Justfun\Controllers;
use Justfun\Core\Factory as CoreFactory;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Controller {
    
    protected $request;
    
    
    public function __construct() {
        $this->init();
    }
    
    public function init(){
        $this->request = CoreFactory::getRequest();
    }
    
}
