<?php

namespace App\Service;

use App\Security\TokenAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RoleService
 * @package App\Service
 */
class RoleService
{
    const DEFAULT_ROLES = [
        'admin' => 'ROLE_ADMIN',
        'user' => 'ROLE_USER'
    ];

    private $user;
    private $entityManager;

    /**
     * RoleService constructor.
     * @param TokenAuthenticator $authToken
     * @param RequestStack $requestStack
     * @param EntityManager $entityManager
     */
    public function __construct(TokenAuthenticator $authToken, RequestStack $requestStack, EntityManager $entityManager)
    {
        $request = $requestStack->getCurrentRequest();
        $token = $authToken->getCredentials($request);
        $this->user = $authToken->getUser($token);
        $this->entityManager = $entityManager;
    }

    /**
     * @return \App\Entity\User|null|object|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    private function isAdmin()
    {
        return in_array(self::DEFAULT_ROLES['admin'], $this->user->getRoles());
    }

    /**
     * @param string $role
     * @param int $userId
     * @return bool
     */
    public function addRole(string $role, int $userId)
    {
        if ($this->isAdmin() && $this->checkRoles($role)) {
            $userRepository = $this->entityManager->getRepository('App:User');
            $user = $userRepository->find($userId);
            $user->addRole(self::DEFAULT_ROLES[$role]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;

        } else {
            throw new HttpException(403, 'Permission denied');
        }
    }

    /**
     * @param string $role
     * @return bool
     */
    private function checkRoles(string $role)
    {
        if (in_array($role, array_keys(self::DEFAULT_ROLES))) {
            return true;
        }

        throw new HttpException(403, 'Permission denied');
    }

    /**
     * @param string $role
     * @param int $userId
     * @return bool
     */
    public function removeRole(string $role, int $userId){
        if ($this->isAdmin() && $this->checkRoles($role)) {
            $userRepository = $this->entityManager->getRepository('App:User');
            $user = $userRepository->find($userId);
            $user->removeRole(self::DEFAULT_ROLES[$role]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;

        } else {
            throw new HttpException(403, 'Permission denied');
        }
    }
}