<?php

use Justfun\Core\Plugin as PluginManager;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Routing as Router;

// per disattivare il plugin basta commentare addPlugin()

$pluginManager = CoreFactory::getPluginManager();
$app = $pluginManager->getApplication();
$router = $app->getRouter();
$callback = function(array $params)
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
$params =  array(
                'controller'=>$router->getController(),
                'action'=>$router->getAction(),
                'params'=>$router->getParams(),
                'callTime'=> Router::CALL_TIME_PRE,
                'time'=>$app->getCore()->getServer()['REQUEST_TIME_FLOAT']);
//$pluginManager->addPlugin($callback,$params);