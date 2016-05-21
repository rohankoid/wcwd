<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// Register service providers.
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/',
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/admin/login_check',
                'username_parameter' => 'form[username]',
                'password_parameter' => 'form[password]',
            ),
            'logout'  => true,
            'anonymous' => true,
            'users' => $app->share(function () use ($app) {
                return new Dance\Model\UserModel($app['db'], $app['security.encoder.digest']);
            }),
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
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


// Protect user urls.
//$app->before(function (Request $request) use ($app) {
//    $protected = array(
//        '/admin/' => 'ROLE_ADMIN',
//        '/me' => 'ROLE_USER',
//    );
//    $path = $request->getPathInfo();
//    foreach ($protected as $protectedPath => $role) {
//        if (strpos($path, $protectedPath) !== FALSE && !$app['security']->isGranted($role)) {
//            throw new AccessDeniedException();
//        }
//    }
//});

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
