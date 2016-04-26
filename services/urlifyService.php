<?php

namespace Justfun\Services;

/**
 * Class urlifyService
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
Class urlifyService {
    
    protected $url;
    
    public function __construct($stringToUrl) {
        $this->run($stringToUrl);
    }
    
    public function getUrl() {
        return $this->url;
    }

    private function run($stringToUrl){
        $url = str_replace( 
                        array('@','!','\'','"','_','?',
                            '^','#','$','€','=','(',')',
                            '[',']','{','}','+','-','*',
                            ':','.',';',',','/','§','ç',
                            '&','%','°','>','<','|'
                            )
                        ,'', $stringToUrl);
        
        $url = str_replace('à', 'a', $url);
        $url = str_replace('è', 'e', $url);
        $url = str_replace('é', 'e', $url);
        $url = str_replace('ì', 'i', $url);
        $url = str_replace('ò', 'o', $url);
        $url = str_replace('ù', 'u', $url);
        
        $url = stripslashes($url);
        $url = str_replace(' ', '-', $url);
        $url = strtolower($url);
        $this->setUrl($url);
        return $this;
    }
    
    private function setUrl($url) {
        $this->url = $url;
        return $this;
    }

}
