<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-13
 * Time: 19:03
 */

namespace App\Tests\Services;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserServiceTest extends KernelTestCase
{
    public function testCreate()
    {
        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true);
        $manager
            ->method('flush')
            ->willReturn(true);

        $mock['objectManager'] = $manager;

        $userService = $this->initService($mock);

        $result = $userService->create((new User())->setPassword('test'));

        // ONLY CHECK IF PASSWORD IS ENCODED
        $this->assertNotEquals('test', $result->getPassword());
    }

    public function testEdit()
    {
        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true);

        $mock['objectManager'] = $manager;

        $userService = $this->initService($mock);

        $result = $userService->edit((new User())->setPassword('test'));

        // ONLY CHECK IF PASSWORD IS ENCODED
        $this->assertNotEquals('test', $result->getPassword());
    }

    private function getContainer()
    {
        self::bootKernel();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

    public function initService(array $mock = null): UserService
    {
        $manager = $this->getContainer()->get(ObjectManager::class);
        $encoder = $this->getContainer()->get(UserPasswordEncoderInterface::class);
        $security = $this->getContainer()->get(Security::class);

        if (isset($mock['objectManager'])) {
            $manager = $mock['objectManager'];
        }

        if (isset($mock['encoder'])) {
            $encoder = $mock['encoder'];
        }

        return new UserService($manager, $encoder, $security);
    }
}
