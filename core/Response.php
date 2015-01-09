<?php
namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;
/**
 * Description of Response
 *
 * @author outsider
 */
class Response {

    const RESPONSE_HTML = 'html';
    const RESPONSE_JSON = 'json';
    
    protected $type, $acceptedType = array('html', 'json'),$data,$controller,$action,$response;
    
    public function __construct($type,$data) {
        $this->setType($type)->setData($data);
        $this->setController()->setAction();
    }
    
    protected function setController(){
        $this->controller = CoreFactory::getRoutingAdapter()->build()->controller;
        return $this;
    } 

    protected function setAction(){
        $this->action = CoreFactory::getRoutingAdapter()->build()->action;
        return $this;
    } 
    
    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getRequest(){
        return CoreFactory::getRequest();
    } 

    protected function setData($data){
        $this->data = $data;
        return $this;
    }
    
    public function getData(){
        return $this->data;
    }
    
    public function getType() {
        return $this->type;
    }

    private function setType($type) {
        if(!in_array($type, $this->acceptedType)) return new \Exception('type not accepted');
        $this->type = $type;
        return $this;
    }
    
    private function setResponse(){
        $this->response = $this;
        return $this;
    }
    
    public function getView($controller = null,$action = null){
        if(!$controller) $controller = $this->getController();
        if(!$action) $action = $this->getAction();
        $dir = __DIR__.'/views/'.$controller.'/'.$action.'.phtml'; 
        $dir = str_replace('core/', '', $dir);
        include  $dir;
    }

    public function render(){
        $this->setResponse();
        switch ($this->getType()){
            case self::RESPONSE_HTML:
                // set default layout
                $dir = __DIR__.'/views/layouts/'.$this->getController().'.phtml'; 
                $dir = str_replace('core/', '', $dir);
                if( !file_exists($dir) ){
                    $dir = str_replace($this->getController(), 'index', $dir);
                }
                include  $dir;
            break;
            case self::RESPONSE_JSON:
                echo json_encode($this->getData());
            break;
        }
    }
    
}
