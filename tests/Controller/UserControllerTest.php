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

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // FILL FORM
        // ADD TASK
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = "UtilisateurTest";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "test@test.com";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Superbe ! L\'utilisateur a bien été ajouté.")')
                ->count()
        );
    }

}