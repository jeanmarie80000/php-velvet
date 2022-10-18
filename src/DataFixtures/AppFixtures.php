<?php

namespace App\DataFixtures;

use App\Entity\Disc;
use APP\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 3; $i++) {
            $artist = new Artist();
            $artist->setName('Name'.$i);
            $artist->setUrl('url'.$i);

            $manager->persist($artist);
        }

        for ($i = 0; $i < 5; $i++) {
            $disc = new Disc();
            $disc->setTitle('title'.$i);
            $disc->setPicture('picture'.$i);
            $disc->setPrice(mt_rand(0.1, 100).' €');
            $disc->setLabel('label'.$i);

            $manager->persist($disc);
        }

        $manager->flush();
    }
}
