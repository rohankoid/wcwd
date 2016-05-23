<?php

namespace Dance\Provider;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;
use Silex\Application;

class LoginControllerProvider implements ControllerProviderInterface
{
    const VALIDATE_CREDENTIALS = '/login';
    const TOKEN_HEADER_KEY     = 'X-Token';
    const TOKEN_REQUEST_KEY    = '_token';
    const USER_HEADER_KEY     = 'X-User';
    const USER_REQUEST_KEY    = '_user';


    private $baseRoute;

    public function setBaseRoute($baseRoute)
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }

    public function connect(Application $app)
    {
        $this->setUpMiddlewares($app);

        return $this->extractControllers($app);
    }

    private function extractControllers(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get(self::VALIDATE_CREDENTIALS, function (Request $request) use ($app) {
            $user   = $request->get('username');
            $pass   = $request->get('password');
            $status = $app[LoginServiceProvider::AUTH_VALIDATE_CREDENTIALS]($user, $pass);

            return $app->json([
                'status' => $status,
                'info'   => $status ? ['token' => $app[LoginServiceProvider::AUTH_NEW_TOKEN]($user)] : []
            ]);
        });

        return $controllers;
    }

    private function setUpMiddlewares(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
            if (!$this->isAuthRequiredForPath($request->getPathInfo())) {
                if (!$this->isValidTokenForApplication($app, $this->getTokenFromRequest($request), $this->getUserFromRequest($request))) {
                    throw new AccessDeniedHttpException('Access Denied');
                }
            }
        });
    }

    private function getTokenFromRequest(Request $request)
    {
        return $request->headers->get(self::TOKEN_HEADER_KEY, $request->get(self::TOKEN_REQUEST_KEY));
    }

    private function getUserFromRequest(Request $request)
    {
        return $request->get('username');
    }

    private function isAuthRequiredForPath($path)
    {
        return in_array($path, [$this->baseRoute . self::VALIDATE_CREDENTIALS, '/api/user/register']);
    }

    private function isValidTokenForApplication(Application $app, $token, $user)
    {
        return $app[LoginServiceProvider::AUTH_VALIDATE_TOKEN]($token, $user);
    }
}