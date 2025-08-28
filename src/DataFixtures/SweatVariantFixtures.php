<?php

namespace App\DataFixtures;

use App\Entity\Size;
use App\Entity\Sweat;
use App\Entity\SweatVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SweatVariantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $index = 0;
        $i = 0;
        while ($this->hasReference('sweat_' . $i, Sweat::class)) {
            $sweat = $this->getReference('sweat_' . $i, Sweat::class);

            $j = 0;
            while ($this->hasReference('size_' . $j, Size::class)) {
                $size = $this->getReference('size_' . $j, Size::class);

                $sweatVariant = new SweatVariant();
                $sweatVariant->setSweat($sweat);
                $sweatVariant->setSize($size);
                $sweatVariant->setStock(random_int(2, 99));
                $manager->persist($sweatVariant);
                
                $this->addReference('sweat_variant_' . $index, $sweatVariant);

                $index ++;
                $j++;
            }

            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SweatFixtures::class,
            SizeFixtures::class,
        ];
    }
}
