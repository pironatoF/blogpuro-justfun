<?php

namespace Justfun\Core;

use Justfun\Core\Request as Request;
use Justfun\Core\Response as Response;
use Justfun\Core\Application as Application;
use Justfun\Core\routingAdapter as routingAdapter;

use Justfun\Core\Database as Database;
use Justfun\Core\mysqlAdapter as mysqlAdapter;
use Justfun\Core\Plugin as PluginManager;

/**
 * Description of Factory
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Factory {
   
    public static function getDatabase(){
        $database = Database::getInstance();
        return $database;
    }
    
    public static function getMysqlAdapter(){
        $adapter = mysqlAdapter::getInstance();
        return $adapter;
    }
    
    public static function getApplication(){
        return Application::getInstance();
    }

    public static function getPluginManager(){
        return PluginManager::getInstance();
    }
    
    public static function getRoutingAdapter(){
        return new routingAdapter();
    }
    
    public static function getRequest(){
        $service = new Request();
        return $service;
    } 
    
    public static function getResponse(){
        $service = new Response();
        return $service;
    }
}
