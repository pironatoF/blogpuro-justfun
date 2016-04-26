<?php

namespace Justfun\Traits;

/**
 * Trait sessionsTrait
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
trait sessionsTrait {
    public static function sessionDataPush($key,$value){
        $_SESSION[$key] = $value;
    }
    
    public static function sessionDataPop($key){
        unset($_SESSION[$key]);
    }
    
    public static function sessionDataExist($key){
        return isset($_SESSION[$key]);
    }
    
    public static function sessionDataGet($key){
        return $_SESSION[$key];
    }
}
