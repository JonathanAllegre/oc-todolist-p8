<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-04
 * Time: 07:00
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class UserControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testlListAction()
    {
        $crawler = $this->client->request('GET', "/users");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Liste des utilisateurs")')
                ->count()
        );
    }
}