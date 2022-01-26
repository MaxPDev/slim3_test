<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../src/vendor/autoload.php';


// video 3 contaeneur (de dépendance ?)
// $config = [
//     // 'dbfile' => __DIR__ . '/../src/conf/db.conf.ini',
//     'settings' => ['dbfile' => __DIR__ . '/../src/conf/db.conf.ini',
//                 'displayErrorDetails'=>true]
// ];

// => déplacé dans le settings.php dans conf

// Pourquoi pas un import ?
$config = require_once __DIR__ . '/../src/conf/settings.php';

$container = new \Slim\Container($config);

$app = new \Slim\App($container);

// vidéo 1

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

// video 2

$app->post('/ciao/{name}[/]',
    function (Request $rq, Response $rs, array $args): Response {
        $data['args']        = $args['name'];
        $data['method']      = $rq->getMethod();
        $data['accept']      = $rq->getHeader('Accept');
        $data['query param'] = $rq->getQueryParam('p','no p');

        $data['content-type']= $rq->getContentType();
        // slim décode automatique le contenu en fonction de son type avec getParsedBody (json, from, text...)
        $data['body']        = $rq->getParsedBody();

        // PSR7 : objet non modifiable, non créer des nvx objets.
        // Par contre : body modifiable ! Donc on appelle la commande
        $rs = $rs->withStatus(202);
        $rs = $rs->withHeader('application-header', 'some value');

        $rs = $rs->withHeader("Content-Type", "application/json");

        // syntax possible, puis la méthode renvoie à chaque fois le résultat :
        // $rs = $rs->withStatus(202)->withHeader('application-header', 'some value')->withHeader("Content-Type", "application/json");



        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
);


    // Video 3
    // pour rendre disponible le contenur : closure binding :
    // la fonction anonyme (= closure) est lié au contenu par $this. $this <=> $container
    $app->get('/video3/{name}[/]',
        function(Request $rq, Response $rs, array $args) : Response {
            $name = $args['name'];

            // $dbfile = $this['dbfile'];
            // soit on y accède par tableau['valeur'], ou par notation d'objet :
            $dbfile = $this->settings['dbfile'];

            $rs->getBody()->write("<h1>Hello $name, </h1> <h2>$dbfile</h2>");
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

