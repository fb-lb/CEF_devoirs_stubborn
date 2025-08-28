<?php

namespace App\DataFixtures;

use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SizeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sizes = [
            ['size' => 'xs'],
            ['size' => 's'],
            ['size' => 'm'],
            ['size' => 'l'],
            ['size' => 'xl'],
        ];

        foreach ($sizes as $index => $data) {
            $size = new Size();
            $size->setSize($data['size']);
            $manager->persist($size);

            $this->addReference('size_' . $index, $size);
        }

        $manager->flush();
    }
}
