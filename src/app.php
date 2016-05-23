<?php


use Symfony\Component\HttpFoundation\Response;

// Register service providers.
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
    ),
));

// Register Models.
$app['model.event'] = $app->share(function ($app) {
    return new Dance\Model\EventModel($app['db']);
});
$app['model.user'] = $app->share(function ($app) {
    return new Dance\Model\UserModel($app['db'], $app['security.encoder.digest']);
});
$app['model.event_type'] = $app->share(function ($app) {
    return new Dance\Model\EventTypeModel($app['db']);
});


// Register the error handler.
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;
