<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-27
 * Time: 12:16
 */

namespace App\Tests\Controller;

use \Symfony\Bundle\FrameworkBundle\Client as Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
    private $client;

    public function testIndex()
    {
        $this->client = static::createClient();
        $this->logIn($this->client);
        $this->client->request('GET', '/security');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
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
