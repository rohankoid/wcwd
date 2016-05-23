<?php

// Register Routes

$app->get('/', function() use($app) {
    return $app->json([
        'status' => false,
        'info'   => [
            'details'    => 'login failed you have been redirected'
        ]]);
});

$app->get('/api/events/', 'Dance\Controller\ApiEventController::indexAction');
$app->get('/api/user_events', 'Dance\Controller\ApiEventController::listAction');
$app->get('/api/event/{event}', 'Dance\Controller\ApiEventController::viewAction');
$app->post('/api/event', 'Dance\Controller\ApiEventController::addAction');
$app->put('/api/event/{event}', 'Dance\Controller\ApiEventController::editAction');
$app->delete('/api/event/{event}', 'Dance\Controller\ApiEventController::deleteAction');

$app->post('/api/user/register', 'Dance\Controller\ApiUserController::registerAction');