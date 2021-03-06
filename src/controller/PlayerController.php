<?php 

namespace player\geoquizz\controller;
use player\geoquizz\model\Partie;
use player\geoquizz\model\Serie;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class PlayerController {

    /* 
        Recupère toutes les parties de la base de données
        Retourne une réponse en json
    */
    public function getGames(Request $req, Response $resp, array $args) {
        $parties = Partie::select()->get();
        $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $resp->getBody()->write(json_encode([
			'type' => 'collection',
			'count' => count($parties),
			'series' => $parties
		]));
    	return $resp;
    }
    /* 
        Recupère une partie de la base de données
        Retourne une réponse en json
    */
    public function getGame(Request $req, Response $resp, array $args){
        $id = $args['id'];
        if(isset($id)){
            try{ 
                $partie = Partie::select()->where('id','=', $id)->firstOrFail();
                $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                'type' => 'collection',
                'count' => 1,
                'partie' => $partie
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
        Ajoute une partie dans la base de données
        Retourne une réponse en json
    */
    public function addGame(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['pseudo'])) {
            try{
                $partie = new Partie();
                $partie->id = $partie->gen_uuid();
                $partie->token = $partie->gen_uuid();
                $partie->nbphotos = 10;
                $partie->status = 0;
                $partie->score = 0;
                $partie->pseudo = filter_var($input['pseudo'], FILTER_SANITIZE_STRING);
                $partie->saveOrFail();

                $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                    'type' => 'collection',
                    'count' => 1,
                    'partie' => $partie
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
        Modifie une partie dans la base de données
        Retourne une réponse en json
    */
    public function updateGame(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        $partie = Partie::select()->where('id','=', $id)->firstOrFail(); 
        $status = $partie->status;
        $score = $partie->score;

        $input = $req->getParsedBody();
        if(isset($input['status']) && $input['status'] != $status){
            $status = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
        }
        if(isset($input['score']) && $input['score'] != $score) {
            $score = filter_var($input['score'], FILTER_SANITIZE_NUMBER_INT);
        }

        try{
            $partie->status = $status;
            $partie->score = $score;
            $partie->saveOrFail();

            $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'collection',
                'count' => 1,
                'partie' => $partie
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
        Recupère toutes les séries de la base de données
        Retourne une réponse en json
    */
    public function getSeries(Request $req, Response $resp, array $args) {
        $series = Serie::select()->get();
        $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $resp->getBody()->write(json_encode([
			'type' => 'collection',
			'count' => count($series),
			'series' => $series
		]));
    	return $resp;
    }
   
    /* 
        Recupère toutes les photos d'une série de la base de données
        Retourne une réponse en json
    */
    public function getPhotosSerie(Request $req, Response $resp, array $args){
        $id = $args['id'];
        if(isset($id)){
            try{ 
                $serie = Serie::select()->where('id','=', $id)->firstOrFail();
                $photosSerie = $serie->photos()->get();
                $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
                $resp->getBody()->write(json_encode([
                'type' => 'collection',
                'count' => count($photosSerie),
                'photos' => $photosSerie
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
            $resp = $resp->withStatus(404)->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode([
                'type' => 'error',
                'error' => 500,
                'message' => "Donnée non trouvée"
            ])); 
        }
        return $resp;
    }
}
