<?php

namespace Justfun\Traits;

/**
 * Trait dataPersistenceTrait
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
trait dataPersistenceTrait {

    public static function hydrate($result, $entityPrototype) {
        $entity = clone $entityPrototype;
        foreach ($result as $k => $v) {
            $offsetToNormalize = strstr($k, '_');
            // normalize user_id as userId
            if ($offsetToNormalize) {
                $k = str_replace($offsetToNormalize, '', $k);
                $k.= ucfirst(str_replace('_', '', $offsetToNormalize));
            }
            $methodName = 'set' . ucfirst($k);
            $entity->$methodName($v);
        }
        return $entity;
    }

    public static function save($data, $entity) {
        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $offsetToNormalize = strstr($k, '_');
                // normalize user_id as userId
                if ($offsetToNormalize) {
                    $k = str_replace($offsetToNormalize, '', $k);
                    $k.= ucfirst(str_replace('_', '', $offsetToNormalize));
                }
                $methodName = 'set' . ucfirst($k);
                $entity->$methodName($v);
            }
            return $entity;
        }
    }
    
    public static function camelToMysql($string){
        preg_match_all('/((?:^|[A-Z])[a-z]+)/',$string,$matches);
        if(isset($matches[0][1])){
            $matchesCount = count($matches[0]);
            $toRet = '';
            foreach($matches[0] as $match){
                $toRet.= strtolower($match);
                if($matchesCount >= 2) $toRet.='_';
                $matchesCount--;
            }
        }else{
            $toRet = $string;
        }
        return $toRet;
    }
    
}
