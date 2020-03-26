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

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
});

// Création des routes à travers les méthodes du controller Player
$app->get('/games[/]', \player\geoquizz\controller\PlayerController::class . ':getGames');
$app->get('/game/{id}', \player\geoquizz\controller\PlayerController::class . ':getGame');
$app->post('/game[/]', \player\geoquizz\controller\PlayerController::class . ':addGame');
$app->post('/game/{id}', \player\geoquizz\controller\PlayerController::class . ':updateGame');
$app->get('/series[/]', \player\geoquizz\controller\PlayerController::class . ':getSeries');
$app->get('/serie/{id}/picture', \player\geoquizz\controller\PlayerController::class . ':getPhotosSerie');

// Création des routes à travers les méthodes du controller BackOffice
$app->post('/user[/]', \player\geoquizz\controller\BackOfficeController::class . ':addUser');
$app->post('/user/login', \player\geoquizz\controller\BackOfficeController::class . ':verifUser');
$app->post('/serie[/]', \player\geoquizz\controller\BackOfficeController::class . ':addSerie');
$app->post('/serie/{id}', \player\geoquizz\controller\BackOfficeController::class . ':updateSerie');
$app->post('/picture[/]', \player\geoquizz\controller\BackOfficeController::class . ':addPicture');
$app->post('/picture/{id}', \player\geoquizz\controller\BackOfficeController::class . ':updatePicture');


$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();

