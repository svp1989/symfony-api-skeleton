<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; $i++) {

            $entity = new Profile();
            $entity->setFirstName('Userf' . $i);
            $entity->setLastName('Usern' . $i);
            $entity->setPatronymic('Userp' . $i);
            $entity->setCitizenship('citizenship' . $i);
            $entity->setDocument('passport');
            $entity->setNumber('33 33 123 12' . $i);
            $entity->setBirthday(new \DateTime());

            $manager->persist($entity);
            $manager->flush();
        }
    }
}