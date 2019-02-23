<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-27
 * Time: 13:15
 */

namespace App\DataFixtures\Tests;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->newUser('jonathan', 'test', 'admin.admin@snowtrick.test', ['ROLE_USER']);
        $manager -> persist($user);
        $manager -> flush();

        $user = $this->newUser('jonathan-test', 'test', 'admin.adminjk@snowtrick.test', ['ROLE_USER']);
        $manager -> persist($user);
        $manager -> flush();

        $user = $this->newUser('userTestForEdit', 'test', 'testEdit.admin@snowtrick.test', ['ROLE_USER']);
        $manager -> persist($user);
        $manager -> flush();

        $user = $this->newUser('user', 'test', 'user.user@user.test', ['ROLE_USER']);
        $manager -> persist($user);
        $manager -> flush();

        $user = $this->newUser('admin', 'admin', 'admin.admin@admin.test', ['ROLE_ADMIN']);
        $manager -> persist($user);
        $manager -> flush();

        $user = $this->newUser('anonymous', 'anonymous', 'anonymous.anonymous@anonymous.test', ['ROLE_ANONYMOUS']);
        $manager -> persist($user);
        $manager -> flush();
    }

    /**
     * @param $name
     * @param $pass
     * @param $mail
     * @return User
     * @throws \Exception
     */
    public function newUser($name, $pass, $mail, $roles):User
    {
        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, $pass))
            ->setUsername($name)
            ->setEmail($mail)
            ->setRoles($roles);

        return $user;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
