<?php

namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;


/**
 * Description of routingAdapter
 *
 * @author Pironato Francesco
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
       
        // fix per /?page=n 
        if( isset($data['controller']) && strstr($data['controller'], '?page')){
            $data['controller'] = 'index';
        }
        if( isset($data['action']) && strstr($data['action'], '?page')){
            $data['action'] = 'index';
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
        
        // fix camelCase controllers
        $toNormalize = strstr($data['controller'], '-');
        // normalize user_id as userId
        if ($toNormalize) {
            $data['controller'] = str_replace($toNormalize, '', $data['controller']);
            $data['controller'].= ucfirst(str_replace('-', '', $toNormalize));
        }
        
        
        
        
        $adapter = (object)array(
            'controller'=>$data['controller'],
            'action'=>$data['action'],
            'params'=>$params);
        return $adapter;
    }
    
}
