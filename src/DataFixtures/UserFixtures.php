<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['email' => 'tr.ko@test.fr', 'roles' => [], 'password' => '1234567', 'name' => 'JohnDoe', 'delivery_address' => 'Paris', 'is_verified' => 1],
            ['email' => 'test@gmail.com', 'roles' => ["ROLE_ADMIN"], 'password' => '1234567', 'name' => 'Admin', 'delivery_address' => 'Paris', 'is_verified' => 1]
        ];

        foreach ($users as $index => $data) {
            $user = new Customer();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $hashedPassword = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
            $user->setName($data['name']);
            $user->setDeliveryAddress($data['delivery_address']);
            $user->setIsVerified($data['is_verified']);
            $manager->persist($user);

            $this->addReference('user_' . $index, $user);
        }
        
        $manager->flush();
    }
}
