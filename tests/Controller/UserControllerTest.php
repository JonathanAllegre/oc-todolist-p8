<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-04
 * Time: 07:00
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

        // ASSERT 302 NO LOGIN
        $this->client->request('GET', "/users");
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Créer un utilisateur")')
                ->count()
        );

        // ASSERT 200 LOGIN ADMIN
        $this->logIn($this->client, 'admin');
        $crawler = $this->client->request('GET', "/users");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Créer un utilisateur")')
                ->count()
        );

        // ASSERT 403 LOGIN USER
        $this->logIn($this->client, 'user');
        $this->client->request('GET', "/users");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        // ASSERT 302 -> Login WITHOUT ADMIN AUTH
        $this->client->request('GET', '/users/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // ASSERT 403 WITHOUT ADMIN AUTH
        $this->logIn($this->client, 'user');
        $this->client->request('GET', '/users/create');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        // ASSERT 200 WITHOUT ADMIN AUTH
        $this->logIn($this->client, 'admin');
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
            ->findOneByUsername('UserForEditaction');

        // ASSERT 302 -> Login WITHOUT ADMIN AUTH
        $this->client->request('GET', "/users/". $user->getId(). "/edit");
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // ASSERT 403 WITHOUT ADMIN AUTH
        $this->logIn($this->client, 'user');
        $this->client->request('GET', "/users/". $user->getId(). "/edit");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        // ASSERT 200 WITHOUT ADMIN AUTH
        $this->logIn($this->client, 'admin');
        $crawler = $this->client->request('GET', "/users/". $user->getId(). "/edit");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Modifier UserForEditaction")')
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
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

    protected function logIn(Client $client, string $username)
    {
        $session = $client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $user = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByUsername($username);

        $token = new UsernamePasswordToken($user, 'test', $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
