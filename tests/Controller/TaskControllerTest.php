<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-28
 * Time: 19:22
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class TaskControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * ASSERT STATUS 200
     */
    public function testListAction()
    {
        // ASSERT WITH LOG OK
        $this->login($this->client);
        $crawler = $this->client->request('GET', '/tasks');

        // ASSERT 200
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // ASSERT HTML CONTAIN
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Créer une tâche")')->count()
        );

        // ASSERT LOG KO
        $this->client = static::createClient();
        $this->client->request('GET', '/tasks');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nom d\'utilisateur :")')->count()
        );
    }

    public function testCreateAction()
    {
        $this->login($this->client);
        $crawler =  $this->client->request('GET', '/tasks/create');

        // ASSERT STATUS CODE 200
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // ASSERT HTML CONTAIN
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Title")')->count()
        );

        // ADD TASK

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = "Ma Tache";
        $form['task[content]'] = "Le Contenu";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Superbe ! La tâche a été bien été ajoutée.")')->count()
        );
    }

    public function testEditAction()
    {

        $this->logIn($this->client);
    }

    protected function logIn(Client $client)
    {
        $session = $client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('jonathan', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
