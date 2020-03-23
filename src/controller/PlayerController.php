<?php 

namespace player\geoquizz\controller;
use player\geoquizz\model\Partie;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class PlayerController {

    /* 
        Recupère toutes les parties de la base de données
        Retourne une réponse en json
    */
    public function getParties(Request $req, Response $resp, array $args) {
        $parties = Partie::select()->get();
        $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $resp->getBody()->write(json_encode([
			'type' => 'collection',
			'count' => count($parties),
			'parties' => $parties
		]));
    	return $resp;
    }

    /* 
        Recupère une partie de la base de données
        Retourne une réponse en json
    */
    public function getPartie(Request $req, Response $resp, array $args){
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
    public function addPartie(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        if(isset($input['nbphotos']) && isset($input['status']) && isset($input['score']) && isset($input['pseudo'])) {
            try{
                $partie = new Partie();
                $partie->id = $partie->gen_uuid();
                $partie->token = $partie->gen_uuid();
                $partie->nbphotos = filter_var($input['nbphotos'], FILTER_SANITIZE_NUMBER_INT);
                $partie->status = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
                $partie->score = filter_var($input['score'], FILTER_SANITIZE_NUMBER_FLOAT);
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
        Supprime une partie dans la base de données
    */
    public function deletePartie(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        if(isset($id)){
            try{ 
                $partie = Partie::select()->where('id','=', $id)->firstOrFail(); 
                $partie->delete();
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
    }

    /* 
        Modifie une partie dans la base de données
        Retourne une réponse en json
    */
    public function updatePartie(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        $partie = Partie::select()->where('id','=', $id)->firstOrFail(); 
        $nbphotos = $partie->nbphotos;
        $status = $partie->status;
        $score = $partie->score;
        $pseudo = $partie->pseudo;

        $input = $req->getParsedBody();
        if(isset($input['nbphotos']) && $input['nbphotos'] != $nbphotos) {
            $nbphotos = filter_var($input['nbphotos'], FILTER_SANITIZE_NUMBER_INT);
        }else if(isset($input['status']) && $input['status'] != $status){
            $status = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
        }else if(isset($input['score']) && $input['score'] != $score) {
            $score = filter_var($input['score'], FILTER_SANITIZE_NUMBER_FLOAT);
        }else if(isset($input['pseudo']) && $input['pseudo'] != $pseudo) {
            $pseudo = filter_var($input['pseudo'], FILTER_SANITIZE_STRING);
        }

        try{
            $partie->nbphotos = $nbphotos;
            $partie->status = $status;
            $partie->score = $score;
            $partie->pseudo = $pseudo;
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
}
