<?php

require '../src/vendor/autoload.php';
$configuration = ['settings' => ['displayErrorDetails' => true]];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/hello/{name}', \player\geoquizz\controller\PlayerController::class . ':getTest');
$app->run();

