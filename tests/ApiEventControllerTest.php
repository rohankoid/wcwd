<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 22/05/16
 * Time: 22:30
 */


use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\WebTestCase;

use Dance\Controller\ApiEventController;


class ApiEventControllerTest extends WebTestCase
{
    public function createApplication()
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $app = new Silex\Application();

        require __DIR__ . '/../app/config/dev.php';
        require __DIR__ . '/../src/app.php';
        require __DIR__ . '/../src/route.php';

        unset($app['exception_handler']);

        return $app;
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/events/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('body:contains("")'));
    }



}
