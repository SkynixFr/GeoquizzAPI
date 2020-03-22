<?php 

namespace player\geoquizz\controller;
use lbs\command\model\Command;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class PlayerController {

    public function getTest(Request $req, Response $resp, array $args) {
        $name = $args['name'];
		$resp->getBody()->write("Hello, $name");
		return $resp;
    }
}
