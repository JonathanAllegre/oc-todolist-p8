<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-13
 * Time: 18:22
 */

namespace App\Services;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $manager;
    private $encoder;

    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    /**
     * @return \App\Entity\Task[]|User[]|object[]
     */
    public function getList(): array
    {
        $users = $this->manager->getRepository(User::class)->findAll();

        return $users;
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(User $user): User
    {
        // SET PASS TO USER
        $user->setPassword($this->encodePass($user));

        // PERSIST & FLUSH
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function edit(User $user):User
    {
        $user->setPassword($this->encodePass($user));
        $this->manager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @return string
     */
    protected function encodePass(User $user): string
    {
        $pass = $this->encoder->encodePassword($user, $user->getPassword());

        return $pass;
    }
}
