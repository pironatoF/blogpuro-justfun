<?php

namespace Justfun\Core;

use Justfun\Core\Request as Request;
use Justfun\Core\Response as Response;
use Justfun\Core\Application as Application;
use Justfun\Core\routingAdapter as routingAdapter;

/**
 * Description of Factory
 *
 * @author outsider
 */
class Factory {
   
    public static function getApplication(){
        return Application::getInstance();
    }

    public static function getRoutingAdapter(){
        return new routingAdapter();
    }
    
    public static function getRequest(){
        $service = new Request();
        return $service;
    } 
    
    public static function getResponse($type,$data){
        if($type == Response::RESPONSE_HTML || $type == Response::RESPONSE_JSON){
            $service = new Response($type,$data);
            return $service;
        }
        return new \Exception('type not accepted');
    }
}
