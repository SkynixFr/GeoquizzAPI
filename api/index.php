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
$app->get('/hello/{name}', \player\geoquizz\controller\PlayerController::class . ':getTest');
$app->get('/', \player\geoquizz\controller\PlayerController::class . ':getParties');
$app->post('/partie[/]', \player\geoquizz\controller\PlayerController::class . ':addPartie');
$app->run();

