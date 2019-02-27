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
    private $manager;

    public const ANONYMOUS_USER = 'anonymous_user';
    public const USER_USER = 'user_user';
    public const ADMIN_USER = 'admin_user';

    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->newUser('user', 'test', 'user.user@user.test', ['ROLE_USER']);
        $admin = $this->newUser('admin', 'admin', 'admin.admin@admin.test', ['ROLE_ADMIN']);
        $anonymous = $this->newUser('anonymous', 'anonymous', 'anonymous.anonymous@anonymous.test', ['ROLE_ANONYMOUS']);

        $this->addReference(self::ANONYMOUS_USER, $anonymous);
        $this->addReference(self::ADMIN_USER, $admin);
        $this->addReference(self::USER_USER, $user);

        // USER FOR FUNCTIONAL TEST
        $this->newUser('UserForEditaction', 'test', 'UserForEditaction.user@user.test', ['ROLE_USER']);
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

        $this->manager->persist($user);
        $this->manager->flush();

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
