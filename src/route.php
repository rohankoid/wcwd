<?php

// Register Routes
$app->get('/', 'Dance\Controller\ApiEventController::indexAction');
$app->get('/api/events/', 'Dance\Controller\ApiEventController::indexAction');
$app->get('/api/user_events', 'Dance\Controller\ApiEventController::listAction');
$app->get('/api/event/{event}', 'Dance\Controller\ApiEventController::viewAction');
$app->post('/api/event', 'Dance\Controller\ApiEventController::addAction');
$app->put('/api/event/{event}', 'Dance\Controller\ApiEventController::editAction');
$app->delete('/api/event/{event}', 'Dance\Controller\ApiEventController::deleteAction');