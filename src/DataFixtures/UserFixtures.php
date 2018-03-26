<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface, FixtureInterface, DependentFixtureInterface
{
    const USERS = [
        [
            'username' => 'user',
            'email' => 'user@user.com',
            'profile' => 1,
            'password' => 'user',
            'role' => 'ROLE_USER'

        ],
        [
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'firstName' => 'Admin',
            'profile' => 2,
            'password' => 'admin',
            'role' => 'ROLE_ADMIN'
        ]
    ];

    /**
     * @var Container
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        foreach (self::USERS as $user) {
            $entity = new User();
            $profile = $manager->find('App:Profile', $user['profile']);
            $entity->setProfile($profile);

            $entity->setEmail($user['email']);
            $entity->setUsername($user['username']);
            $entity->setEnabled(true);

            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($entity, $user['password']);
            $entity->addRole($user['role']);
            $entity->setPassword($password);

            $manager->persist($entity);
            $manager->flush();
        }
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            ProfileFixtures::class
        ];

    }
}