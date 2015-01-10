<?php

namespace Justfun\Core;

use Justfun\Core\Core as Core;
use Justfun\Core\Factory as CoreFactory;
/**
 * Description of Application
 *
 * @author outsider
 */
class Application{
    
    protected $core;
    
    protected $router;
    
    /**
     * Class name of the singleton  object.
     * @var string
     */
    private static $_applicationClassName = 'Application';

    private static $_application = null;

    public static function getInstance()
    {
        if (self::$_application === null) {
            self::init();
        }
        return self::$_application;
    }

    public static function setInstance(Application $application)
    {
        if (self::$_application !== null) {
            Exception('Application is already initialized');
        }
        self::setClassName(get_class($application));
        self::$_application = $application;
    }

    protected static function init()
    {
        //$application = new self::$_applicationClassName();
        $application = new self;
        $application->setCore(new Core($_SERVER, $_GET, $_POST));
        self::setInstance($application);
        /* in questo modo ho il routing(router) già istanziato prima di runnare l'app e 
         * posso agganciare le callback dei relativi hook prima di runnarlo
         * 
         */
        $application->setRouter();
    }
    
    public static function setClassName($applicationClassName = 'Application')
    {
        if (self::$_application !== null) {
            
            throw new Exception('Application is already initialized');
        }

        if (!is_string($applicationClassName)) {
            
            throw new Exception("Argument is not a class name");
        }

        self::$_applicationClassName = $applicationClassName;
    }

    public function getCore() {
        return $this->core;
    }

    public function getRouter(){
        return $this->router;
    }
    
    public function setCore(Core $core) {
        $this->core = $core;
        return $this;
    }

    
    
    public function setRouter() {

        try {
            $this->router = new Routing(CoreFactory::getRoutingAdapter()->build());
            
        } catch (Exception $exception) {
            $this->setStatus(self::STATUS_ERR);
            $this->setException ($exception);
            // da sostituire con un oggetto response da passare al /controller/view, da mandare in 505/404
            // considerare anche l'idea di creare delle eccezioni personalizzate...
            die('<h2 style="color:red">'.$this->getStatus().'</h2> <br/>'.$this->getErrors());
        }
        return $this->router;
    }
    
    public function run($plugins = null){
       $this->router->run( $plugins );
    }
}
