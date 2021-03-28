<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $user = new User();
        // $user->setEmail('admin@example.com');
        // $user->setRoles(['ROLE_SUPERADMIN']);
        // $user->setPassword($passwordEncoder->encodePassword($user, 'admin_pass'));
        // $user->setFirstname('Admin Firstname');
        // $user->setLastname('Admin Lastname');
        // $user->setPhone('0000000000');
        // $user->setAddress('5th, Street Test Avenue');
        // $manager->persist($user);

        // $manager->flush();
    }
}
