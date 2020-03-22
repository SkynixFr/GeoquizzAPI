<?php 

namespace player\geoquizz\controller;
use player\geoquizz\model\Partie;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class PlayerController {

    public function getTest(Request $req, Response $resp, array $args) {
        $name = $args['name'];
		$resp->getBody()->write("Hello, $name");
		return $resp;
    }

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
        Ajoute une partie dans la base de données
        Retourne une réponse en json de la partie créée
    */
    public function addPartie(Request $req, Response $resp, array $args) {
        $input = $req->getParsedBody();
        
        if(isset($input->token) && isset($input->nb_photos) && isset($input->status) && isset($input->score) && isset($input->pseudo)) {
            try{
                $partie = new Partie();
                $partie->token = filter_var($input->token, FILTER_SANITIZE_STRING);
                $partie->nb_photos = filter_var($input->nb_photos, FILTER_SANITIZE_NUMBER_INT);
                $partie->status = filter_var($input->status, FILTER_SANITIZE_NUMBER_INT);
                $partie->score = filter_var($input->score, FILTER_SANITIZE_NUMBER_FLOAT);
                $partie->pseudo = filter_var($input->pseudo, FILTER_SANITIZE_STRING);
                $partie->id = Uuid::uuid4();
                $partie->saveOrFail();

                $resp = $resp->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8'); //->withHeader('Location', '/commandes/$commandes->id');
                $resp->getBody()->write(json_encode([
                    'type' => 'collection',
                    'count' => 1,
                    'commandes' => $partie
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
}
