<?php
use utilities;

$app->get('/stories', function () use ($app) {

    $stories = null;
    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        $stories = models\Story::stories();
        //$stories = models\Story::stories();
        $stories = json_encode($stories);
    }
    catch (Exception $err) {
        // TODO log descriptive error with request info
        $err->getMessage();

        // TODO send user-friendly error
        $app->halt(500, json_encode($err->getMessage()));
    }

    $response->status('200');
    echo $stories;
});

$app->options('/:url', function () use ($app) {
    $response = $app->response();
    $response->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
    $response->status('200');
})->conditions(array('url' => '.+'));

$app->post('/stories', function () use ($app) {

    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        // TODO validation
        // TODO author_id from session
        $story = (object) array (
            'account_id' => '1',
            'slug' => null,
            'title' => null,
            'description' => null
        );

        $story = utilities\Request::post($story);
        $id = models\Story::create($story);
        $id = json_encode(array('id'=>$id));
    }
    catch (Exception $err) {
        echo $err->getMessage();
        $app->halt(500, json_encode($err->getMessage()));
    }

    $response->status('201');
    echo $id;
});