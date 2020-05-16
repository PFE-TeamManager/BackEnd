<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(1, null, function($i) use ($manager) {
            
            $user = new User();
            $user->setUsername($this->faker->name);
            $user->setEmail(sprintf('admin%d@example.com', $i));

            // $user->setPrenom($this->faker->firstName);
            // $user->setNom($this->faker->lastName);
            // $user->setEmail(sprintf('admin%d@example.com', $i));

            $user->setDateEmbauchement($this->faker->dateTime($max = 'now', $timezone = null));       

            //the reason to pass membre first , to know which encoder to use
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'secret123#'
            ));
            $user->setRoles(['ROLE_CHEF_PROJET','ROLE_DEV']);

            return $user;
        });

        $manager->flush();

    }

    // public function getDependencies()
    // {
    //     //run before ArticleFixtures
    //     return [
    //         TagFixture::class,
    //     ];
    // }
}
