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
    
    const DEFAULT_ACTIVE_NAV_ITEM = 'homepage';
    
    protected $type, $acceptedType = array('html', 'json'),$data = array(),$controller,$action,$response;
    
    protected $stylesheets = array();
    
    public function __construct() {
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
    
    public function setData( array $data){
        foreach($data as $k => $v){
            $this->data[$k] = $v;
        }
        return $this;
    }
    
    public function getData(){
        return $this->data;
    }
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
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
    
    /**
     * @TODO: gestire eventuale integrazione di moduli (ad esempio backend)
     * @param type string $name
     * @param type string $controller
     */
    public function renderPartial($name,$controller = null){
        // se non c'è il controller il partial è del layout
        if(!$controller):
            $dir = __DIR__.'/views/layouts/partials/'.$name.'.phtml'; 
        else:
            $dir = __DIR__.'/views/'.$controller.'/partials/'.$name.'.phtml'; 
        endif;
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
    
    public function addStylesheet($name,$src = null){
        if(!$src){
            $src = '/public/css/'.$name.'.css';
        }
        $this->stylesheets[$name] = $src;
        return $this;
    }
    
    public function getStylesheets(){
        return $this->stylesheets;
    }
    
    public function getActiveItemNav(){
        
        switch($this->getController()):
            case 'auth':
                $activeItem = 'auth';
            break;
            case 'about':
                $activeItem = 'about';
            break;
            case 'contact':
                $activeItem = 'contact';
            break;
            case 'privacy':
                $activeItem = 'privacy';
            break;
            default:
                $activeItem = self::DEFAULT_ACTIVE_NAV_ITEM;    
            break;
        endswitch;  
        
        return $activeItem;
    }
    
    
}
