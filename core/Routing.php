<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Justfun\Core;

/**
 * Description of Routing
 *
 * @author outsider
 */
class Routing {

    protected $controller, $action, $params, $view;
    
    public function __construct($adapter) {
        // view hardcoded
        $this->setController($adapter->controller)
             ->setAction($adapter->action)
             ->setParams($adapter->params)
             ->setView('index');
    }

    public function getController() {
        return $this->controller;
    }
    public function getParams() {
        return $this->params;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

        public function getAction() {
        return $this->action;
    }

    public function getView() {
        return $this->view;
    }

    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setView($view) {
        $this->view = $view;
        return $this;
    }

    

    public function preRouting($fn, $config = null) {
        if (is_callable($fn)) {
            return call_user_func($fn, $config);
        }
    }

    public function postRouting($fn, $config = null) {
        if (is_callable($fn)) {
            return call_user_func($fn, $config);
        }
    }

    
    public function run($preRoutingCallback=null,$preRoutingParams=null,$postRoutingCallback=null,$postRoutingParams=null)
    {
        $this->preRouting($preRoutingCallback, $preRoutingParams);
                
        // @TODO: rifattorizzare le varie chiamate, instanziando le classi tramite un factory che gestisca le dipendenze
        $className = '\\Justfun\\Controllers\\'.$this->getController().'Controller';
        if(!class_exists($className)) die('bad controller provided'); // gestire l'eccezione
        $controller =  new $className();
        // run action
        $actionName = $this->getAction().'Action';
        if(method_exists($controller, $actionName)){
            $controller->$actionName();
        }elseif(method_exists($controller, 'indexAction')){
            $controller->{'indexAction'}();
        }else{
            die('bad action provided'); // gestire l'eccezione
        }
        
        $this->postRouting($postRoutingCallback,$postRoutingParams);
    }
}
