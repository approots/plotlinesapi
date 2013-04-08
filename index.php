<?php

define('IS_PRODUCTION', ($_SERVER['SERVER_NAME'] != 'localhost') && ($_SERVER['SERVER_NAME'] != 'plotlinesapi'));

$baseDir = 'plotlinesapi/';
if (IS_PRODUCTION)
{
    $baseDir = '../../plotlinesapi/';
}


require $baseDir . 'vendor/autoload.php';


$app = new \Slim\Slim();

require $baseDir . 'routes/test.php';
/*
$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});
*/

$app->run();
