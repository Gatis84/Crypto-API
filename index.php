<?php
require_once 'vendor/autoload.php';

use App\Cmc;
use App\GetDate;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/cmc', Cmc::class);
    $r->addRoute('GET', '/getdate', GetDate::class);

});

$httpMethod = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        var_dump("... GP, 404 Not Found");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        var_dump("... GP, 405 Method Not Allowed");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $handler = new $handler;

        if ($handler instanceof \App\Handler) {
            $handler->getData();
        }

        $loader = new \Twig\Loader\FilesystemLoader('views');
        $twig = new \Twig\Environment($loader);

        $template= $twig->load('index.html');

        echo $template->render(["cryptos" => $handler->getData()]);

        break;
}