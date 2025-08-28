<?php

namespace App\DataFixtures;

use App\Entity\Sweat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SweatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sweats = [
            ['name' => 'Blackbelt', 'price' => 29.9, 'top' => 1, 'file_name' => 'images/689c5742124ab.jpg'],
            ['name' => 'BlueBelt', 'price' => 29.9, 'top' => 0, 'file_name' => 'images/689c5761e378f.jpg'],
            ['name' => 'Street', 'price' => 34.5, 'top' => 0, 'file_name' => 'images/689c5787972a4.jpg'],
            ['name' => 'Pokeball', 'price' => 45, 'top' => 1, 'file_name' => 'images/689c57aa3127e.jpg'],
            ['name' => 'PinkLady', 'price' => 29.9, 'top' => 0, 'file_name' => 'images/689c57c1ee9b7.jpg'],
            ['name' => 'Snow', 'price' => 32, 'top' => 0, 'file_name' => 'images/689c57d711002.jpg'],
            ['name' => 'Greyback', 'price' => 28.5, 'top' => 0, 'file_name' => 'images/689c57f51d244.jpg'],
            ['name' => 'BlueCloud', 'price' => 45, 'top' => 0, 'file_name' => 'images/689c5814ee487.jpg'],
            ['name' => 'BornInUsa', 'price' => 59.9, 'top' => 1, 'file_name' => 'images/68a87df57f165.jpg'],
            ['name' => 'GreenSchool', 'price' => 42.2, 'top' => 0, 'file_name' => 'images/689c597732971.jpg'],
        ];

        foreach ($sweats as $index => $data) {
            $sweat = new Sweat();
            $sweat->setName($data['name']);
            $sweat->setPrice($data['price']);
            $sweat->setTop($data['top']);
            $sweat->setFileName($data['file_name']);
            $manager->persist($sweat);

            $this->addReference('sweat_' . $index, $sweat);
        }

        $manager->flush();
    }
}
