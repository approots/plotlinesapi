<?php

$app->get('/passagepage/:id', function ($id) use ($app) {

    $data = null;
    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        // TODO authenticate and get accountId and verify that the passed in story $id belongs to the user

        // get story, passage, and links
        $passage = models\Passage::passage($id);
        if (! $passage) {
            throw new Exception("Passage does not exist.");
        }

        $story = models\Story::story($passage['story_id']);//null;//models\Story::story($passage->story_id);

        $links = models\Link::links($passage['id']);

        $data = json_encode(array(
            'story' => $story,
            'passage' => $passage,
            'links' => $links
        ));
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        // TODO send user-friendly error
        $app->halt(500, $err->getTraceAsString());
    }

    $response->status('200');
    echo $data;
});

$app->post('/passages', function () use ($app) {
    $request = $app->request();
    $response = $app->response();
    $response->header('Content-type','application/json');
    $passage = (object) array (
        'storyId' => null,
        'title' => null,
        'body' => null
    );

    try {
        // TODO validation
        // TODO author_id from session
        $passage = utilities\Request::objectFromJson($request, $passage);
        $passage->id = models\Passage::create($passage);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, $err->getMessage());
    }

    $response->status('201');
    echo json_encode($passage);
});

// TODO authorization and validation
$app->delete('/passages', function () use ($app) {

    $response = $app->response();
    $request = $app->request();

    try {
        // TODO validation
        // TODO author_id from session to be sure we're deleting this author's passage
        $accountId = 1;

        $obj = utilities\Request::objectFromJson($request);
        $id = $obj->id;

        models\Passage::delete($id);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, print_r($err->getMessage()));
    }
    // 204 success and no returning content
    $response->status('204');
});

$app->put('/passages', function () use ($app) {
    $request = $app->request();
    $response = $app->response();
    $response->header('Content-type','application/json');
    $passage = (object) array (
        'id' => null,
        'title' => null,
        'body' => null
    );

    try {
        // TODO validation
        // TODO author_id from session to be sure we're updating this author's passage
        $passage = utilities\Request::objectFromJson($request, $passage);
        $passage = models\Passage::update($passage);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, $err->getMessage());
    }
    // 204 success and no returning content
    $response->status('200');
    echo json_encode($passage);
});