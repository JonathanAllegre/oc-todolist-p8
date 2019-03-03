<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-01-27
 * Time: 13:15
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $manager;

    public const ANONYMOUS_USER = 'anonymous-user';

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     */
    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $manager)
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
        $this->newUser('jonathan', 'test', 'admin.admin@snowtrick.test', ['ROLE_USER']);
        $anonymous = $this->newUser('Anonymous', 'test', 'anonymous@anonymous.com', ['ROLE_USER']);

        $this->addReference(self::ANONYMOUS_USER, $anonymous);
    }

    /**
     * @param $name
     * @param $pass
     * @param $mail
     * @return User
     * @throws \Exception
     */
    public function newUser($name, $pass, $mail, $role):User
    {
        $user = new User();
        $user
            ->setUsername($name)
            ->setPassword($this->encoder->encodePassword($user, $pass))
            ->setEmail($mail)
            ->setRoles($role);

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
        return ['devprod'];
    }
}
