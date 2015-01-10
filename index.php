<?php

use Justfun\Core\Factory as CoreFactory;

error_reporting(E_ALL);
ini_set('display_errors','On');

function __autoload($class_name) {
    // @TODO: dopo aver rifattorizzato l'autoload in una classe apposita, trasformare in costanti
    $base = 'Justfun';
    $classControllersName = 'Controller';
    $classInterfacesName = 'Interface';
    $classCoresName = 'Core'; 
    $class_name = str_replace($base, '', $class_name);
    $class_name = str_replace('\\', '/', $class_name);
    
    $path = __DIR__.$class_name;

    // core
    if(strpos($class_name, $classCoresName) !== false){
        // core interfaces
        if(strpos($class_name, $classInterfacesName) !== false){
            $class_name = str_replace('/Core/Interfaces/', '', $class_name);
            //die(var_dump($class_name));
            $path = __DIR__.'/core/'.$class_name;
            
            // core core(gestita eccezione => generic )
        }elseif(strpos($class_name, '/Core/Core') !== false){
            $class_name = str_replace('/Core/Core','Core', $class_name);
            $path = __DIR__.'/core/'.$class_name;
        }else {
            // core generic (*NB: lasciare alla fine del blocco, aggiungendo elseif sopra..)
            $class_name = str_replace('/'.$classCoresName,'', $class_name);
            $path = __DIR__.'/core/'.$class_name;
        }
    }else{
        // application(implicit) controllers
        if(strpos($class_name, $classControllersName) !== false){
            $class_name = str_replace('/Controllers/', '', $class_name);
            $path = __DIR__.'/controllers/'.$class_name; 
        }
    }
 
    include $path. '.php';
}

// include plugin
$pluginsInDir = glob("plugins/*.php");
if(count($pluginsInDir) > 0){
    foreach($pluginsInDir as $plugin) include $plugin;
}
// include entities
$entitiesInDir = glob("entities/*.php");
if(count($entitiesInDir) > 0){
    foreach($entitiesInDir as $entity) include $entity;
}
// include repositories
$repositoriesInDir = glob("repositories/*.php");
if(count($repositoriesInDir) > 0){
    foreach($repositoriesInDir as $repository) include $repository;
}

// starta l'applicazione
$app = CoreFactory::getApplication();
$plugins = CoreFactory::getPluginManager()->getPlugins();
$app->run($plugins);













?>