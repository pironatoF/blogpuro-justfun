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
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Routing {
    
    const CALL_TIME_PRE = 'pre';
    
    const CALL_TIME_POST = 'post';
    
    protected $controller, $action, $params, $view;
    
    protected $preRunPlugins = array(),$postRunPlugins = array();
    
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

    
    /*
    public function preRouting($fn, $config = null) {
        if (is_callable($fn)) {
            return call_user_func($fn, $config);
        }
    }

    public function postRouting($fn, $config = null) {
        if (is_callable($fn)) {
            return call_user_func($fn, $config);
        }
    }*/

    // va a eseguire il plugin
    protected function pluginRun($fn, $config = null) {
        if(is_callable($fn) ){
            return call_user_func($fn,$config);
        }
    }

    // si occupa di pushare i plugin nelle giuste posizioni di esecuzione
    protected function pluginPush($callTime,$callback,$params){
        if($callTime == self::CALL_TIME_PRE){
            $this->preRunPlugins[] = array($callback,$params); 
        }elseif($callTime == self::CALL_TIME_POST){
            $this->postRunPlugins[] = array($callback,$params);
        }
    }
    
    // va a eseguire gli hook
    protected function pluginsRun($callTime){
        if($callTime == self::CALL_TIME_PRE){
            $plugins = $this->preRunPlugins;
        }elseif($callTime == self::CALL_TIME_POST){
            $plugins = $this->postRunPlugins;
        }
        foreach($plugins as $plugin){
            $this->pluginRun($plugin[0], $plugin[1]);
        }
        
    }
    
    // inizializza la gestione dei plugin
    protected function pluginsInit($plugins = null){
        if(!$plugins) return null;
        foreach($plugins as $plugin){
            $this->pluginPush($plugin[1]['callTime'],$plugin[0],$plugin[1]);
        }
    }
    
    public function run($plugins = null)
    {
        if($plugins) $this->pluginsInit($plugins);
        
        
        //$this->preRouting($preRoutingCallback, $preRoutingParams);
        if($plugins) $this->pluginsRun(self::CALL_TIME_PRE);   
        
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
        
        if($plugins) $this->pluginsRun(self::CALL_TIME_POST);
        //$this->postRouting($postRoutingCallback,$postRoutingParams);
    }
}
