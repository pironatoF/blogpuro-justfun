<?php

namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;


/**
 * Description of routingAdapter
 *
 * @author outsider
 */
class routingAdapter {
    
    public function build()
    {
        // manage routing
        $mvcData = array('controller','action'); //add view, viewHelpers,actionHelpers..
        $application = CoreFactory::getApplication();
        $data = $application->getCore()->getGet();
        $params = null;
        foreach($data as $k=>$v){
            if(!in_array($k, $mvcData)){
                $params[$k] = $v;
            }
        }
        if((!isset($data['controller']))|| (!(isset($data['action']))) ){
            $request = $application->getCore()->getServer()['REQUEST_URI'];
            $request = explode('/', $request);
            if(isset($request[1])) $data['controller'] = $request[1];
            if(isset($request[2]))  $data['action'] = $request[2];
            if(isset($request[3])){
                unset($request[0]);
                unset($request[1]);
                unset($request[2]);
                foreach($request as $k => $v){
                    if(isset($request[$k+1]) && !empty($request[$k+1])){
                        if(strpos($request[$k+1], '?') !== false){
                            preg_match('@(.*)\?.*@i', $request[$k+1], $match);
                            $params[$v] = $match[1];
                        }else{
                            $params[$v] = $request[$k+1];
                        }
                    }
                }
                
            }
        }
        
        // homepage
        if( 
            ($data['controller'] == '' && !isset($data['action']) )         || 
            ($data['controller'] == 'index' && !isset($data['action']) )    ||
            ($data['controller'] == 'index' && $data['action'] == '' )    
        ){
            $data['controller'] = 'index'; 
            $data['action'] = 'index';
        }
        
        $adapter = (object)array(
            'controller'=>$data['controller'],
            'action'=>$data['action'],
            'params'=>$params);
        
        return $adapter;
    }
    
}
