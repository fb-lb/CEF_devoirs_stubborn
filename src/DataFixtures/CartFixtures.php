<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\SweatVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference('user_0', Customer::class);
        $sweatVariantIndex = ['2', '16', '43'];

        foreach ($sweatVariantIndex as $index) {
            $sweatVariant = $this->getReference('sweat_variant_' . $index, SweatVariant::class);
            
            $cartProduct = new Cart();
            $cartProduct->setSweatVariant($sweatVariant);
            $cartProduct->setCustomer($user);
            $manager->persist($cartProduct);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SweatVariantFixtures::class,
            UserFixtures::class,
        ];
    }
}
