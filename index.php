<?php

// environment...
define('PRODUCTION', 'production');
define('DEVELOPMENT', 'development');
$appDir = 'plotlinesapi/';
$slimMode = DEVELOPMENT;
if (($_SERVER['HTTP_HOST'] != 'localhost') && ($_SERVER['HTTP_HOST'] != 'api.plotlines'))
{
    $appDir = '../../plotlinesapi/';
	$slimMode = PRODUCTION;
}
// ...environment

// session_set_cookie_params(0, '/', NULL, TRUE, TRUE); // for subdomain cookies?

// register vendor and application autoloaders
require $appDir . 'vendor/autoload.php';
require $appDir . 'autoload.php';


// slim instantiation...
$app = new \Slim\Slim(array(
    'mode' => $slimMode
));
$app->configureMode(PRODUCTION, function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => false
    ));
});
$app->configureMode(DEVELOPMENT, function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true
    ));
});

// load app code
require $appDir . 'routes/stories.php';

/*
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
}
else {
    header('Access-Control-Allow-Origin: *');
    //echo 'foobar';
}
*/

// go
$response = $app->response();
$response->header('Access-Control-Allow-Origin','http://plotlines'); // TODO add real domain
//$response->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
//$response->header('Access-Control-Allow-Headers', 'Content-Type');
//$response->header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Accept,Origin,Content-Type');
$app->run();

