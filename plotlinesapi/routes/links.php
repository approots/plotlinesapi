<?php

$app->post('/links', function () use ($app) {
    $request = $app->request();
    $response = $app->response();
    $response->header('Content-type','application/json');
    $link = (object) array (
        'passageId' => null,
        'choice' => null,
        'destinationId' => null
    );

    try {
        // TODO validation
        // TODO author_id from session
        $link = utilities\Request::objectFromJson($request, $link);
        $link->id = models\Link::create($link);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, $err->getMessage());
    }

    $response->status('201');
    echo json_encode($link);
});
