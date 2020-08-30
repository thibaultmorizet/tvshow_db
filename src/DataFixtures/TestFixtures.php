<?php

namespace App\DataFixtures;

use App\Entity\TvShow;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tvShow1 = new TvShow();
        $tvShow1->setTitle("Stargate SG-1");
        $manager->persist($tvShow1);

        $tvShow2 = new TvShow();
        $tvShow2->setTitle("Derrick");
        $manager->persist($tvShow2);

        $user1 = new User();
        $user1->setEmail("admin@mail.com");
        $user1->setPassword("");
        $user1->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("user@mail.com");
        $user2->setPassword("");
        $user2->setRoles([]);
        $manager->persist($user2);

        $manager->flush();
    }
}
