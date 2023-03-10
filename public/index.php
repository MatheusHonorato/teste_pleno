<?php

declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: HEAD, GET, PUT, PATCH, POST, DELETE, OPTIONS");

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use Matheus\TestePleno\Util\ControllerCall;

$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__, 1));
$dotenv->load();

if(getenv('APP_ENV') == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

// api/resource/1
if($_SERVER["REQUEST_URI"]) {
    $url = explode('/', $_SERVER['REQUEST_URI']);
    array_shift($url);

    $controller = ControllerCall::generate($url);

    array_shift($url);

    $method = strtolower($_SERVER['REQUEST_METHOD']);

    try {

        $response = call_user_func_array(array(new $controller, $method), $url);

        http_response_code(200);

        echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
        die;
    } catch (\Exception $exception) {
        http_response_code(404);

        echo json_encode(array('status' => 'error', 'data' => 'route not found'), JSON_UNESCAPED_UNICODE);
        die;
    }
}