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

// Création des routes à travers les méthodes d'un controller
$app->get('/parties', \player\geoquizz\controller\PlayerController::class . ':getParties');
$app->get('/partie/{id}', \player\geoquizz\controller\PlayerController::class . ':getPartie');
$app->post('/partie[/]', \player\geoquizz\controller\PlayerController::class . ':addPartie');
$app->post('/partie/{id}', \player\geoquizz\controller\PlayerController::class . ':updatePartie');
$app->delete('/partie/{id}', \player\geoquizz\controller\PlayerController::class . ':deletePartie');
$app->run();

