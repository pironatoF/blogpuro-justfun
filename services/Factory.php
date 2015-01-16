<?php

namespace Justfun\Services;

use Justfun\Core\Database as Database;
use Justfun\Core\Factory as CoreFactory;

/**
 * Description of Factory
 *
 * @author Pironato Francesco
 */
class Factory {
    
    public static function getAuthService(){
        return new authService();
    }
    
    public static function getUrlManagerService($url = null,$urlType,$urlName){
        if(!$url) $url = CoreFactory::getApplication()->getCore()->getServer()['REQUEST_URI'];
        return new urlManagerService($url,$urlType,$urlName);
    }
    
    public static function getUrlifyService($stringToUrl){
        return new urlifyService($stringToUrl);
    }
    
    public static function getPaginatorService($currentPage, $itemsNumber, $itemsPerPage){
        return new paginatorService($currentPage, $itemsNumber, $itemsPerPage);
    }
}
