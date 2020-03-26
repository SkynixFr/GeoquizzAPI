<?php

namespace player\geoquizz\controller;
use player\geoquizz\model\Serie;
use player\geoquizz\model\Photo;
use player\geoquizz\model\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class BackOfficeController {

    /* 
        Ajoute un utilisateur dans la base de données
        Retourne une réponse en json
    */
    public function addUser(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['email']) && isset($input['motdepasse'])) {
            try{
                $user = new User();
                $user->id = $user->gen_uuid();
                $user->email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
                $user->motdepasse = filter_var($input['motdepasse'], FILTER_SANITIZE_STRING);
                $user->saveOrFail();

                $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'collection',
                    'count' => 1,
                    'user' => $user
                ]));
            }catch(ModelNotFoundException $e){
                $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'error',
                    'error' => 500,
                    'message' => $e->getMessage()
                ]));
            }
        }else{
            $resp = $resp->withStatus(400)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 400,
                'message' => "Erreur de données transmises"
            ]));
        }
        return $resp;
    }

    /* 
        Verifie si l'utilisateur lors de la connexion rentre ses bonnes informations
        Retourne une réponse en json
    */
    public function verifUser(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['email']) && isset($input['motdepasse'])){
            try{ 
                $user = User::select()->where('email','=', $input['email'])->firstOrFail();
                if($user->motdepasse === $input['motdepasse']) {
                    $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
                    $resp->getBody()->write(json_encode([
                        'type' => 'collection',
                        'count' => 1,
                        'user' => $user
                    ]));   
                }else{
                    $resp = $resp->withStatus(404)->withHeader('Content-Type', 'application/json;charset=utf-8');
                    $resp->getBody()->write(json_encode([
                        'type' => 'error',
                        'error' => 500,
                        'message' => "Mauvais mot de passe"
                    ])); 
                }
            }catch(ModelNotFoundException $e){
                $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'error',
                    'error' => 500,
                    'message' => $e->getMessage()
                ]));
            } 
        }else{
            $resp = $resp->withStatus(404)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 500,
                'message' => "Donnée non trouvée"
            ])); 
        }
        return $resp;
    }

    /* 
        Ajoute une série dans la base de données
        Retourne une réponse en json
    */
    public function addSerie(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['ville']) && isset($input['latitude']) && isset($input['longitude']) && isset($input['dist'])) {
            try{
                $serie = new Serie();
                $serie->id = $serie->gen_uuid();
                $serie->ville = filter_var($input['ville'], FILTER_SANITIZE_STRING);
                $serie->latitude = filter_var($input['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $serie->longitude = filter_var($input['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $serie->dist = filter_var($input['dist'], FILTER_SANITIZE_NUMBER_INT);
                $serie->saveOrFail();

                $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'collection',
                    'count' => 1,
                    'serie' => $serie
                ]));
            }catch(ModelNotFoundException $e){
                $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'error',
                    'error' => 500,
                    'message' => $e->getMessage()
                ]));
            }
        }else{
            $resp = $resp->withStatus(400)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 400,
                'message' => "Erreur de données transmises"
            ]));
        }
        return $resp;
    }

    /* 
        Update une série dans la base de données
        Retourne une réponse en json
    */
    public function updateSerie(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        $serie = Serie::select()->where('id','=', $id)->firstOrFail();
        $ville = $serie->ville;
        $latitude = $serie->latitude;
        $longitude = $serie->longitude;
        $dist = $serie->dist;

        $input = $req->getParsedBody();
        if(isset($input['ville']) && $input['ville'] != $ville){
            $ville = filter_var($input['ville'], FILTER_SANITIZE_STRING);
        }
        if(isset($input['latitude']) && $input['latitude'] != $latitude) {
            $latitude = filter_var($input['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if(isset($input['longitude']) && $input['longitude'] != $longitude) {
            $longitude = filter_var($input['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if(isset($input['dist']) && $input['dist'] != $dist) {
            $dist = filter_var($input['dist'], FILTER_SANITIZE_NUMBER_INT);
        }

        try{
            $serie->ville = $ville;
            $serie->latitude = $latitude;
            $serie->longitude = $longitude;
            $serie->dist = $dist;
            $serie->saveOrFail();

            $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'collection',
                'count' => 1,
                'serie' => $serie
            ]));
        }catch(ModelNotFoundException $e){
            $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 500,
                'message' => $e->getMessage()
            ]));
        }
        return $resp;
    }

     /* 
        Ajoute une photo dans la base de données
        Retourne une réponse en json
    */
    public function addPicture(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['description']) && isset($input['latitude']) && isset($input['longitude']) && isset($input['zoom'])
        && isset($input['url']) && isset($input['id_serie'])) {
            try{
                $photo = new Photo();
                $photo->id = $photo->gen_uuid();
                $photo->description = filter_var($input['description'], FILTER_SANITIZE_STRING);
                $photo->latitude = filter_var($input['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $photo->longitude = filter_var($input['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $photo->zoom = filter_var($input['zoom'], FILTER_SANITIZE_NUMBER_INT);
                $photo->url = filter_var($input['url'], FILTER_SANITIZE_STRING);
                $photo->id_serie = filter_var($input['id_serie'], FILTER_SANITIZE_STRING);
                $photo->saveOrFail();

                $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'collection',
                    'count' => 1,
                    'photo' => $photo
                ]));
            }catch(ModelNotFoundException $e){
                $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'error',
                    'error' => 500,
                    'message' => $e->getMessage()
                ]));
            }
        }else{
            $resp = $resp->withStatus(400)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 400,
                'message' => "Erreur de données transmises"
            ]));
        }
        return $resp;
    }

    /* 
        Update une photo dans la base de données
        Retourne une réponse en json
    */
    public function updatePicture(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        $photo = Photo::select()->where('id','=', $id)->firstOrFail();
        $description = $photo->description;
        $latitude = $photo->latitude;
        $longitude = $photo->longitude;
        $zoom = $photo->zoom;
        $url = $photo->url;
        $id_serie = $photo->id_serie;

        $input = $req->getParsedBody();
        if(isset($input['description']) && $input['description'] != $description){
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
        }
        if(isset($input['latitude']) && $input['latitude'] != $latitude) {
            $latitude = filter_var($input['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if(isset($input['longitude']) && $input['longitude'] != $longitude) {
            $longitude = filter_var($input['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if(isset($input['zoom']) && $input['zoom'] != $zoom) {
            $zoom = filter_var($input['zoom'], FILTER_SANITIZE_NUMBER_INT);
        }
        if(isset($input['url']) && $input['url'] != $url) {
            $url = filter_var($input['url'], FILTER_SANITIZE_STRING);
        }
        if(isset($input['id_serie']) && $input['id_serie'] != $id_serie) {
            $id_serie = filter_var($input['id_serie'], FILTER_SANITIZE_STRING);
        }

        try{
            $photo->description = $description;
            $photo->latitude = $latitude;
            $photo->longitude = $longitude;
            $photo->zoom = $zoom;
            $photo->url = $url;
            $photo->id_serie = $id_serie;
            $photo->saveOrFail();

            $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'collection',
                'count' => 1,
                'photo' => $photo
            ]));
        }catch(ModelNotFoundException $e){
            $resp = $resp->withStatus(500)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 500,
                'message' => $e->getMessage()
            ]));
        }
        return $resp;
    }
    
}