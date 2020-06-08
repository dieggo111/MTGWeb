<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = parse_url($_SERVER['REQUEST_URI']);


// the only valid endpoint for card searches starts with /card
if ($url["path"] != '/cards') {
    header("HTTP/1.1 404 Not Found");
}
// echo $url;
// echo $url;
// echo "\n";
if (isset($url["path"])) {
    echo $url["path"];
    echo "\n";

}

if (isset($url["query"])) {
    echo $url["query"];
    echo "\n";

}

// if ($search_str == "")


// the user id is, of course, optional and must be a number:
// $userId = null;
// if (isset($uri[2])) {
//     $userId = (int) $uri[2];
// }

//get request method that was used to access front controller
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = "Result of query...";
    var_dump($response);
}
if ($requestMethod == "POST") {
    // header($response['status_code_header']);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = "Result of query...";
    var_dump($response);
}
// pass the request method and user ID to the PersonController and process the HTTP request:
// $controller = new PersonController($dbConnection, $requestMethod, $userId);
// $controller->processRequest();

?>