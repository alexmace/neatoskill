<?php

use AlexMace\NeatoBotvac\Service\RobotApi;
use AlexMace\NeatoBotvac\Robot;
use GuzzleHttp\Client;

// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

$container['neato'] =  function ($c) {
    $settings = $c->get('settings');
    $logger = $c->get('logger');
    $robotApi = new RobotApi(
        new Client(),
        $settings['neato']['serial'],
        $settings['neato']['secret']
    );
    return new Robot($robotApi);
    /*$client = new NeatoBotvacClient();
    $robots = [];
    $auth = $client->authorize($settings['neato']['email'], $settings['neato']['password']);

    if ($auth !== false) {
        $logger->addInfo('Token: ' . $auth);
        $result = $client->getRobots();

        if ($result !== false) {
            foreach($result['robots'] as $robot) {
                $logger->addInfo('Serial: ' . $robot['serial']);
                $logger->addInfo('Secret Key:' . $robot['secret_key']);
                // Short cut and return the first robot found, because we only
                // have one at the moment. When we have more than one, probably
                // sensible to return an object that represents a set of robots
                // that allows one to retrieved by name.
                return new NeatoBotvacRobot($robot['serial'], $robot['secret_key']);
            }
        }
    } else {
        $logger->addInfo('Unable to authorise with Neato');
    }*/
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\StephenAction::class] = function ($c) {
    return new App\Action\StephenAction($c->get('neato'), $c->get('logger'));
};
