<?php

namespace Justfun\Services;

/**
 * Class urlManagerService
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
Class urlManagerService {
    
    
    //const URL_ADD       = '/post/add/';
    const URL_ADD       = 'add';
    const URL_EDIT      = 'edit';
    const URL_SHOW      = 'show';
    const URL_DELETE    = 'delete';
    const URL_ROSTER      = 'roster';
    const URL_PAGE      = 'page';
    
    const URL_EXTRACT = 'extract';
    //const URL_BUILD = 'build';
    
    protected $url,$originalUrl,$urlType,$urlName;
    
    
    protected $urlTypes = array(
        'edit' => self::URL_EDIT,
        'show' => self::URL_SHOW,
        'delete' => self::URL_DELETE,
        'roster' => self::URL_ROSTER,
    );
    
    /**
     * 
     * @param type $url  The original Url
     * @param type $urlType add|edit|show|delete|list|page|tag (can be extended)
     * @param type $urlMode extract|build
     * @param type $urlName The name of the url es. post or user
     */
    public function __construct($url= null,$urlType,$urlName) {
        $this->setOriginalUrl($url)
             ->setUrlType($urlType)
             ->setUrlName($urlName)
             ->extract();
    }
    
    public function getUrlName() {
        return $this->urlName;
    }

    private function setUrlName($urlName) {
        $this->urlName = $urlName;
        return $this;
    }
        
    public function getUrlType() {
        return $this->urlType;
    }
       
    private function setUrlType($urlType) {
        $this->urlType = $urlType;
        return $this;
    }

    public function getOriginalUrl() {
        return $this->originalUrl;
    }

    public function getUrl() {
        return $this->url;
    }

    private function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    private function setOriginalUrl($originalUrl) {
        $this->originalUrl = $originalUrl;
        return $this;
    }

       
    /**
     * Effettua l'estrazione dell'url(per matchare dati) dalla request-uri
     * 
     * ("entity|controller..")/show/($entityUrl)
     * ("entity|controller..")/edit/(id)
     * ("entity|controller..")/delete/(id)  (logico ???mmm)
     * ("entity|controller..")/list/($listname) es: /post/list/last-week
     */
    private function extract(){
        foreach($this->urlTypes as $k => $type){
            if($type == $this->urlType){
                $this->setUrl($this->$type());
                return $this;
            }
        }
    }
    
    private function createPattern(){
        return '/'.$this->urlName.'/'.$this->urlType.'/';
    }
    
    private function show(){
        return str_replace($this->createPattern(), '', $this->getOriginalUrl());
    }
    
    private function edit(){
        return (int)str_replace($this->createPattern(), '', $this->getOriginalUrl());
    }
    private function delete(){
        return (int)str_replace($this->createPattern(), '', $this->getOriginalUrl());
    }
    private function roster(){
        return str_replace($this->createPattern(), '', $this->getOriginalUrl());
    }
   
    
}
