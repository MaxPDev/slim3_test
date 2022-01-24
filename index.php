<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;

$app->get('/huba/{name}[/]',
    function (Request $req, Response $resp, $args) {
        $name = $args['name'];
        $resp->getBody()->write("<h1>huba huba, $name</h1>");
        return $resp;
    }
);

// Mettre plus spécifique en 1er
$app->get('/hello/jb',
    function (Request $req, Response $resp, $args) {
        $resp->getBody()->write("<h1>Hello JB</h1>");
        return $resp;
    }
);

$app->get('/hello/{name}[/]',
    function (Request $req, Response $resp, $args) {
        $name = $args['name'];
        $resp->getBody()->write("<h1>Hello world, $name</h1>");
        return $resp;
    }
);

$app->get('/ciao/{name}[/]',
    function (Request $rq, Response $rs, array $args): Response {
        $data['args']        = $args['name'];
        $data['method']      = $rq->getMethod();
        $data['accept']      = $rq->getHeader('Accept');
        $data['query param'] = $rq->getQueryParam('p','no p');

        // to do : docker-compose, git pull les deux dossier
        // reprendre :
        // video 2, 3,17
        $rs = $rs->withHeader("Content-Type", "application/json");
        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
);




// $app->get('/hello/{name}',
//     function (Request $req, Response $resp, $args) {
//         $name = $args['name'];
//         $resp->getBody()->write(json_encode("Hello, $name"));
//         return $resp;
//     }
// )->setName('hello');

// $url = $app->getContainer()['router']->pathFor('hello', [ 'name'=>'bob']);

// $app->get('/hi/{name}',
//     function (Request $req, Response $resp, $args) {
//         $name = $args['name'];
//         $url = $this['router']->pathFor('hello', ['name' => 'bob']);
//         $resp->getBody()->write(json_encode("Hi, $name, $url"));
//         return $resp;
//     }
// )->setName('hi');

$app->run();

