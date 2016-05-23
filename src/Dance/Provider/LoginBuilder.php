<?php

namespace Dance\Provider;

use Silex\Application;

class LoginBuilder
{
    public static function mountProviderIntoApplication($route, Application $app)
    {
        $app->register(new LoginServiceProvider());
        $app->mount($route, (new LoginControllerProvider())->setBaseRoute($route));
    }
}