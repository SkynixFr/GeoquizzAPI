<?php

namespace player\geoquizz\controller;
use player\geoquizz\model\Serie;
use player\geoquizz\model\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class BackOfficeController {

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

    public function getUser(Request $req, Response $resp, array $args) {
        $id = $args['id'];
        if(isset($id)){
            try{ 
                $user = User::select()->where('id','=', $id)->firstOrFail();
                $resp = $resp->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
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