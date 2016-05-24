<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 22/05/16
 * Time: 22:30
 */

require_once __DIR__ . '/../src/Application.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\WebTestCase;



class ApiUserControllerTest extends WebTestCase
{
    public function createApplication()
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $app = new Dance\Application();

        require __DIR__ . '/../app/config/dev.php';
        require __DIR__ . '/../src/app.php';
        require __DIR__ . '/../src/route.php';

        unset($app['exception_handler']);

        return $app;
    }

    public function testLogin()
    {
        $client = $this->createClient();
        $client->setServerParameters(array('username' => 'admin', 'password' => 'admin'));
        $client->request('GET', '/auth/login/');
        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testRegister()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user/register', array('username' => 'user', 'password' => 'user', 'mail' => 'user@email.com'));
        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testDuplicateRegister()
    {
        $client = $this->createClient();
        $client->setServerParameters(array('username' => 'user', 'password' => 'user', 'mail' => 'user@email.com'));
        $client->request('POST', '/api/user/register');


        $this->assertFalse($client->getResponse()->isOk());
    }



}
