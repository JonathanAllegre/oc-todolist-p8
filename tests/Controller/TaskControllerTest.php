<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-28
 * Time: 19:22
 */

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
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
        $crawler = $this->client->request('GET', '/tasks');

        // ASSERT 200
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // ASSERT HTML CONTAIN
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Créer une tâche")')->count()
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

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Superbe ! La tâche a été bien été ajoutée.")')->count()
        );
    }

    public function testEditAction()
    {
        $this->logIn($this->client);

        $task = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Task::class)
            ->findOneByTitle('TaskForEditaction');

        $crawler = $this->client->request('GET', "/tasks/". $task->getId() ."/edit");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Le Contenu de ma tache de test")')->count()
        );

        // MODIF FORM
        $form = $crawler->selectButton("Modifier")->form();

        $form['task[title]']   = "MaTacheTestModifié";
        $form['task[content]'] = "Le Contenu de test modifié";

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Le Contenu de test modifié")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Superbe ! La tâche a bien été modifiée.")')->count()
        );
    }

    public function testToogleTaskAction()
    {
        $this->logIn($this->client);

        $task = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Task::class)
            ->findOneByTitle('TaskForToggleAction');

        $this->client->request('GET', "/tasks/". $task->getId(). "/toggle");
        $crawler = $this->client->followRedirect();

        // Toggle Task "Marquer comme Faite"
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Superbe ! La tâche TaskForToggleAction a bien été marquée comme faite.")')
                ->count()
        );
    }

    public function testDeleteTaskAction()
    {
        $this->login($this->client);

        $task = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Task::class)
            ->findOneByTitle('TaskForDeleteAction');

        $this->client->request('GET', '/tasks/'. $task->getId() .'/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('html:contains("Superbe ! La tâche a bien été supprimée.")')
                ->count()
        );
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
        $user = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByUsername('anonymous');

        $token = new UsernamePasswordToken($user, 'test', $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    private function getContainer()
    {
        self::bootKernel();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }
}
