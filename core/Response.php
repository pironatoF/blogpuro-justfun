<?php
namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Services\Factory as servicesFactory;
use Justfun\Services\urlManagerService as urlManagerService;
/**
 * Description of Response
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Response {

    const RESPONSE_HTML = 'html';
    const RESPONSE_JSON = 'json';
    
    const DEFAULT_ACTIVE_NAV_ITEM = 'homepage';
    const DEFAULT_LOGIN_URL = '/auth/login';
    const DEFAULT_LOGOUT_URL = '/auth/logout';
    
    const DEFAULT_LAYOUT  = 'index';
    
    // @TODO: spostare questa costante in un file apposito di configurazione!
    const BASE_URL = 'http://blogpuro.local';
    
    protected $login = array('name'=>'Login','url'=>self::DEFAULT_LOGIN_URL),
              $logout = array('name'=>'Logout','url'=>self::DEFAULT_LOGOUT_URL);
    
    protected $type, $acceptedType = array('html', 'json'),$data = array(),$controller,$action,$response;
    
    protected $layout;
    
    protected $stylesheets = array();
    
    protected $authService;
    
    // ora è usata per l'interfaccia editori; @todo: estendere globalmente
    protected $activeNav;
    
    // determina se verrà usato o meno tinymce per le textarea (impostabile da controller setIsActiveTinymce(true|false))
    protected $isActiveTinymce;
    
    public function __construct() {
        $this->setController()->setAction()->setAuthService()->setLayout($this->getController());
        
        /**
         *  setto tinymce attivo di default (disattivabile da specifica controller => action);
         *  o nell'init del controller, per tutte le action, e attivabile solamente in N action specifiche.
         */
        $this->setIsActiveTinymce(true); 
    }
    
    public function getLayout() {
        return $this->layout;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
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
                $dir = __DIR__.'/views/layouts/'.$this->getLayout().'.phtml'; 
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
            case 'author':
                $activeItem = 'author';
            break;
            case 'post': $activeItem= 'none'; break;
            default:
                $activeItem = self::DEFAULT_ACTIVE_NAV_ITEM;    
            break;
        endswitch;  
        
        return $activeItem;
    }
    
    public function getCurrentStatusLoginOrLogoutNavItem(){
        return $this->getAuthService()->isLogged() === true ? $this->logout :$this->login;
    }
        
    
    public function redirect($url, $statusCode = 303)
    {
       $url = self::BASE_URL.$url;     
       header('Location: ' . $url, true, $statusCode);
       die();
    }
    
    protected function setAuthService(){
        $this->authService = servicesFactory::getAuthService();
        return $this;
    }
    
    public function getAuthService(){
        return $this->authService;
    }
    
    public function getUrlManager($url,$urlType,$urlName){
        return servicesFactory::getUrlManagerService($url,$urlType,$urlName);
    }
    
    public function getAlertOperationStatus(){
       $data = $this->getRequest()->getGetData();
       if(isset($data['op'])){
           if($data['op'] === 'ok'): $alert = array('class'=>'success','message'=>'Fine - The operation was successful'); endif;
           if($data['op'] === 'wa'): $alert = array('class'=>'warning','message'=>'Warning - Operation problem'); endif;
           if($data['op'] === 'ko'): $alert = array('class'=>'danger','message'=>'Danger - Operation problem'); endif;
       }else{
           return null;
       }
       return $alert;
    }
    
    public function getActiveNav() {
        return $this->activeNav;
    }

    public function isActiveNav($nav) {
        return $this->activeNav === $nav ? true: false;
    }
    
    public function setActiveNav($activeNav) {
        $this->activeNav = $activeNav;
        return $this;
    }

    public function getIsActiveTinymce() {
        return $this->isActiveTinymce;
    }

    public function setIsActiveTinymce($isActiveTinymce) {
        $this->isActiveTinymce = $isActiveTinymce;
        return $this;
    }


}
