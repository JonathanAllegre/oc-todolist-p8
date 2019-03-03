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
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private $manager;
    private $encoder;
    private $security;

    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $encoder, Security $security)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->security = $security;
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

    /**
     * RETOURN THE CURRENT USER
     * @return User|null
     */
    public function getCurrentUser(): ?UserInterface
    {
        return $this->security->getUser();
    }

    /**
     * CHECK IF USER HAS ADMIN ROLE
     * @param User $user
     * @return bool
     */
    public function userHasAdminRole(User $user): bool
    {
        foreach ($user->getRoles() as $userRole) {
            if ('ROLE_ADMIN' ===$userRole) {
                dump($userRole);
                return true;
            }
        }

        return false;
    }

    /**
     * RETURN AN ANONYMOUS USER
     * @return User
     */
    public function getAnonymousUser(): User
    {
        $anonymousUser = $this->manager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        return $anonymousUser;
    }
}
