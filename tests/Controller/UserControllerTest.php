<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-04
 * Time: 07:00
 */

namespace App\Tests\Controller;

use App\Entity\User;
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
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = "UtilisateurTest";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "test@test.com";
        $form['user[roles][1]'] = "ROLE_USER";


        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Superbe ! L\'utilisateur a bien été ajouté.")')
                ->count()
        );
    }

    public function testEditAction()
    {
        $user = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByUsername('jonathan-test');

        $crawler = $this->client->request('GET', "/users/". $user->getId(). "/edit");

        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Modifier jonathan-test")')
                ->count()
        );

        // FILL FORM
        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = "UtilisateurTestModifié";

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Superbe ! L\'utilisateur a bien été modifié")')
                ->count()
        );
    }

    private function getContainer()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }
}
