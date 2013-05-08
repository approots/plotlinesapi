<?php

$app->options('/:url', function () use ($app) {
    $response = $app->response();
    $response->status('200');
})->conditions(array('url' => '.+'));

$app->get('/storiespage', function () use ($app) {

    $stories = null;
    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        // TODO
        $account_id = 1;
        $stories = models\Story::stories($account_id);
        $existingSlugs = models\Story::existingSlugs($account_id);
        $stories = array(
            'stories' => $stories,
            'existingSlugs' => $existingSlugs
        );
        $stories = json_encode($stories);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        // TODO send user-friendly error
        $app->halt(500, $err->getMessage());
    }

    $response->status('200');
    echo $stories;
});

$app->get('/storypage/:id', function ($id) use ($app) {

    $story = null;
    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        // TODO authenticate and get accountId and verify that the passed in story $id belongs to the user
        $accountId = 1;
        $story = models\Story::story($id);
        $otherSlugs = models\Story::otherSlugs($accountId, $id);
        $passages = models\Story::passages($id);

        $story = array(
            'story' => $story,
            'otherSlugs' => $otherSlugs,
            'passages' => $passages
        );

        $story = json_encode($story);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        // TODO send user-friendly error
        $app->halt(500, $err->getMessage());
    }

    $response->status('200');
    echo $story;
});

// TODO validate story belongs to author (if editing)
/*
$app->get('/story/:id', function ($id) use ($app) {

    $story = null;
    $response = $app->response();
    $response->header('Content-type','application/json');

    try {
        $story = models\Story::story($id);
        $story = json_encode($story);
    }
    catch (Exception $err) {
        // TODO log descriptive error with request info
        $err->getMessage();

        // TODO send user-friendly error
        $app->halt(500, $err->getMessage());
    }

    $response->status('200');
    echo $story;
});
*/
/*
$app->delete('/test', function () use ($app) {

    $response = $app->response();

    try {
        //$data = null;
        //parse_str(file_get_contents('php://input'), $data);
        //echo $data;
        //$env = $app->environment();
        //$data = $env['slim.input'];
        //$data .= $app->request()->getBody();
        //$data .= 'wtf';
       // $app->halt(500, 'some of a');
        //$story = utilities\Request::post($story);
        //models\Story::delete($account_id, $story->id);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, print_r($err->getMessage()));
    }
    // 204 success and no returning content
    $response->status('200');
    //echo $app->request()->getBody();
    echo 'hi' . $app->request()->delete('id');
});
*/


// TODO authorization and validation
$app->delete('/stories', function () use ($app) {

    $response = $app->response();
    $request = $app->request();

    try {
        // TODO validation
        // TODO author_id from session to be sure we're deleting this author's story
        $account_id = 1;

        $obj = utilities\Request::objectFromJson($request);
        $id = $obj->id;

        models\Story::delete($account_id, $id);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, print_r($err->getMessage()));
        //$app->halt(500, ($id || "no id"));
    }
    // 204 success and no returning content
    $response->status('204');

});

$app->put('/stories', function () use ($app) {
    $request = $app->request();
    $response = $app->response();
    $response->header('Content-type','application/json');
    $story = (object) array (
        'account_id' => '1',
        'id' => null,
        'title' => null,
        'description' => null
    );

    try {
        // TODO validation
        // TODO author_id from session to be sure we're updating this author's story
        $story = utilities\Request::objectFromJson($request, $story);
        $story->slug = utilities\Text::toAscii($story->title);

        $story = models\Story::update($story);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, $err->getMessage());
    }
    // 204 success and no returning content
    $response->status('200');
    echo json_encode($story);
});

$app->post('/stories', function () use ($app) {
    $request = $app->request();
    $response = $app->response();
    $response->header('Content-type','application/json');
    $story = (object) array (
        'account_id' => '1',
        'title' => null,
        'description' => null
    );

    try {
        // TODO validation
        // TODO author_id from session
        $story = utilities\Request::objectFromJson($request, $story);
        $story->slug = utilities\Text::toAscii($story->title);
        $story->id = models\Story::create($story);
    }
    catch (Exception $err) {
        $app->getLog()->error($err->getTraceAsString());
        $app->halt(500, $err->getMessage());
    }

    $response->status('201');
    echo json_encode($story);
});
