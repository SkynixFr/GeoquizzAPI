<?php
// Chargement de l'autoloader
require '../src/vendor/autoload.php';

// Set up de la connection à la bdd
$conf = parse_ini_file("../src/conf/conf.ini");
$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection($conf);
$db->setAsGlobal();
$db->bootEloquent();

// Configuration de slim
$configuration = ['settings' => ['displayErrorDetails' => true]];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

// Création des routes à travers les méthodes du controller Player
$app->get('/game/{id}', \player\geoquizz\controller\PlayerController::class . ':getGame');
$app->post('/game[/]', \player\geoquizz\controller\PlayerController::class . ':addGame');
$app->post('/game/{id}', \player\geoquizz\controller\PlayerController::class . ':updateGame');
$app->get('/series[/]', \player\geoquizz\controller\PlayerController::class . ':getSeries');
$app->get('/serie/{id}/picture', \player\geoquizz\controller\PlayerController::class . ':getPhotosSerie');




$app->run();

