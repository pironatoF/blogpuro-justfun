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

    public function setCore(Core $core) {
        $this->core = $core;
        return $this;
    }

    
    
    public function getRouting() {

        try {
            $this->routing = new Routing(CoreFactory::getRoutingAdapter()->build());
            
        } catch (Exception $exception) {
            $this->setStatus(self::STATUS_ERR);
            $this->setException ($exception);
            // da sostituire con un oggetto response da passare al /controller/view, da mandare in 505/404
            die('<h2 style="color:red">'.$this->getStatus().'</h2> <br/>'.$this->getErrors());
        }
        return $this->routing;
    }
    
    public function run(){
        $routing = $this->getRouting();
        
        //@ŢODO: fare in modo che si possa hookare il routing da plugin
        
        $routingDebug = function(array $params)
                        {
                            echo '<section id="trace" style="border:1px solid #000;margin:5px;padding:5px;width:20%">';
                            echo '<h2 style="color:crimson;text-align:center;border-bottom:1px dashed #000">PreRouting Trace</h2>';
                            echo '<p>Controller: <strong>'.$params['controller'].'</strong></p>';
                            echo '<p>Action: <strong>'.$params['action'].'</strong></p>';
                            if($params['params']){
                                echo '<h3>Parameters:</h3>';
                                echo '<ol style="list-style:none">';
                                foreach($params['params'] as $k=>$v){
                                    echo '<li>';
                                        echo '<p>'.$k.': <strong>'.$v.'</strong></p>';
                                    echo '</li>';
                                }
                                echo '</ol>';
                            } 
                            echo '<p style="color:crimson;text-align:center;border-top:1px dashed #000">Time: <strong>'.$params['time'].'</strong></p>';
                            echo '</section>';
                        };
        $time =  $this->getCore()->getServer()['REQUEST_TIME_FLOAT'];
        $routing->run(  
                    /*$routingDebug,
                                    array(
                                        'controller'=>$routing->getController(),
                                        'action'=>$routing->getAction(),
                                        'params'=>$routing->getParams(),
                                        'time'=>$time)*/
                    );
    }
}
