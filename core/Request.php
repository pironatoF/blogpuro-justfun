<?php
namespace Justfun\Core;

use Justfun\Core\Application as Application;

/**
 * Description of Request
 *
 * @author outsider
 */
class Request {
    
    protected $application;
    
    public function __construct() {
        $this->application = Application::getInstance();
    }
    
    public function getApplication(){
        return $this->application;
    }
}
