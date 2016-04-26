<?php

namespace Justfun\Core;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Routing as Router;

/**
 * Description of Plugin
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
class Plugin {
    
    protected $plugins = array();
    
    /**
     * Class name of the singleton  object.
     * @var string
     */
    private static $_pluginClassName = 'Plugin';

    private static $_plugin = null;

    public static function getInstance()
    {
        if (self::$_plugin === null) {
            self::init();
        }
        return self::$_plugin;
    }

    public static function setInstance(Plugin $plugin)
    {
        if (self::$_plugin !== null) {
            Exception('Plugin is already initialized');
        }
        self::setClassName(get_class($plugin));
        self::$_plugin = $plugin;
    }

    protected static function init()
    {
        $plugin = new self;
        
        self::setInstance($plugin);
        
    }
    
    public static function setClassName($pluginClassName = 'Plugin')
    {
        if (self::$_plugin !== null) {
            
            throw new Exception('Plugin is already initialized');
        }

        if (!is_string($pluginClassName)) {
            
            throw new Exception("Argument is not a class name");
        }

        self::$_pluginClassName = $pluginClassName;
    }
   
    public function getApplication(){
        return CoreFactory::getApplication();
    }
    
    /***
     * 
     */
    public function addPlugin($callback,$params)
    {
        $this->plugins[] = array($callback,$params);
    }
    
    public function getPlugins(){
        return $this->plugins;
    }
}
