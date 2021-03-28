<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_SUPERADMIN']);
        $password = $this->encoder->encodePassword($user, 'admin_pass');
        $user->setPassword($password);
        $user->setFirstname('Admin Firstname');
        $user->setLastname('Admin Lastname');
        $user->setPhone('0000000000');
        $user->setAddress('5th, Street Test Avenue');
        $manager->persist($user);

        $manager->flush();
    }
}
