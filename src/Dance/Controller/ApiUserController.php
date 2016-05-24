<?php

namespace Dance\Controller;

use Dance\Entity\User;
use Silex\Application;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
    
class ApiUserController
{

    public function registerAction(Request $request, Application $app)
    {
        if (!$request->request->has('username')) {
            return $app->json('Missing required parameter: username', 400);
        }
        if (!$request->request->has('password')) {
            return $app->json('Missing required parameter: password', 400);
        }
        if (!$request->request->has('mail')) {
            return $app->json('Missing required parameter: mail', 400);
        }
        
        try {
            $user = new User();
            $user->setUsername($request->request->get('username'));
            $user->setPassword($request->request->get('password'));
            $user->setMail($request->request->get('mail'));
            $app['model.user']->save($user);
            $headers = array('Location' => '/api/user/register' . $user->getIduser());
            $token = $app['model.user']->getNewToken();
            $user->setToken($token);
            $app['model.user']->save($user); // update user with new token

            return $app->json(
                ['status' => 'true',
                    'info' => ['token' => $token, 'user' => $user->getUsername()]]
                , 200, $headers);

        } catch (Exception $exception) {
            return $app->json($exception->getMessage(), 400);
        }



    }
}

