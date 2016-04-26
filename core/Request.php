<?php
namespace Justfun\Core;

use Justfun\Core\Application as Application;

/**
 * Description of Request
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Request {
    
    protected $application,$getData,$postData,$serverData;
    
    
    public function __construct() {
        $this->setApplication()->setGetData()->setPostData()->setServerData();
    }
    

    /**
     * 
     * @return Justfun\Core\Application
     */
    public function getApplication(){
        return $this->application;
    }

    
    public function getGetData() {
        return $this->getData;
    }

    public function getPostData() {
        return $this->postData;
    }

    /**
     *  *NB: lo setto protetto perchè può essere esteso da qualcosa 
     *       che espone il metodo per simulare una request, inoltre
     *       ho creato le propietà per avere possibilità future di fare evenquali
     *      astrazioni.
     */
    
    /**
     * 
     * @return \Justfun\Core\Request
     */
    protected function setGetData() {
        $this->getData = $this->getApplication()->getCore()->getGet();
        return $this;
    }

    /**
     * 
     * @return \Justfun\Core\Request
     */
    protected function setPostData() {
        $this->postData = $this->getApplication()->getCore()->getPost();
        return $this;
    }

    /**
     * 
     * @return \Justfun\Core\Request
     */
    private function setApplication(){
        $this->application = Application::getInstance();
        return $this;
    }
    
    public function getServerData() {
        return $this->serverData;
    }

    protected function setServerData() {
        $this->serverData = $this->getApplication()->getCore()->getServer();
        return $this;
    }
    
    public function getRequestUri(){
        return $this->getServerData()['REQUEST_URI'];
    }
    
}
