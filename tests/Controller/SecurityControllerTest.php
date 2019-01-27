<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-27
 * Time: 12:16
 */

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use \Symfony\Bundle\FrameworkBundle\Client as Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{


    public function testIndex()
    {

        $this->loadFixtures(array(
            'App\DataFixtures\Tests\UserFixtures',
        ));

        $client = $this->makeClient();
        $this->logIn($client);
        $crawler = $client->request('GET', '/security');
        $this->assertStatusCode(200, $client);
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