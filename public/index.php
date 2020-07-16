<?php


require $_SERVER['DOCUMENT_ROOT']."\..\src\api.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = parse_url($_SERVER['REQUEST_URI']);

if (!isset($url["query"])) {
    $url["query"] = NULL;
}

$API = new Api($_SERVER["REQUEST_METHOD"], $url["path"], $url["query"]);
$API->processRequest();

?>