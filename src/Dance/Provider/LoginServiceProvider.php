<?php


namespace Dance\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;


class LoginServiceProvider implements ServiceProviderInterface
{
    const AUTH_VALIDATE_CREDENTIALS = 'auth.validate.credentials';
    const AUTH_VALIDATE_TOKEN       = 'auth.validate.token';
    const AUTH_NEW_TOKEN            = 'auth.new.token';

    public function register(Application $app)
    {
        $app[self::AUTH_VALIDATE_CREDENTIALS] = $app->protect(function ($user, $pass) use ($app){
            return $this->validateCredentials($user, $pass, $app);
        });

        $app[self::AUTH_VALIDATE_TOKEN] = $app->protect(function ($token, $user) use ($app){
            return $this->validateToken($app, $user, $token);
        });

        $app[self::AUTH_NEW_TOKEN] = $app->protect(function ($user) use ($app){
            return $this->getNewTokenForUser($app, $user );
        });


    }

    public function boot(Application $app)
    {
    }

    private function validateCredentials($user, $pass, $app)
    {
        $userModel = $app['model.user'];
        $userObject = $userModel->loadUserByUsername($user);
        $salt = $userObject->getSalt();
        $dbPass = $userObject->getPassword();
        $password = $userModel->getEncodedPassword($pass, $salt);
        return $password == $dbPass;
    }

    private function validateToken($app, $user, $token)
    {
        $userModel = $app['model.user'];
        $userObject = $userModel->loadUserByUsername($user);
        $userToken = $userObject->getToken();
        return $token == $userToken;
    }

    private function getNewTokenForUser($app, $user)
    {
        $userModel = $app['model.user'];
        $userObject = $userModel->loadUserByUsername($user);
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $userObject->setToken($token);
        $userModel->save($userObject);
        return $token;
    }
}