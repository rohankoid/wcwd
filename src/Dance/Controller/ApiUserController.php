<?php

namespace Dance\Controller;

use Dance\Entity\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
    
class ApiUserController
{

    public function loginAction(Request $request, Application $app)
    {
        return $app->json([
            'status' => true,
            'info'   => [
                'name'    => 'rohan',
                'surname' => 'maleku'
            ]]);
    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();
        return $app->redirect($app['url_generator']->generate('homepage'));
    }

    public function registerAction(Request $request, Application $app)
    {

    }
}

