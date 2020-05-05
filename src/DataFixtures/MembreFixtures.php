<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreFixtures extends BaseFixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(3, null, function($i) use ($manager) {
            $membre = new Membre();
            $membre->setPrenom($this->faker->firstName);
            $membre->setNom($this->faker->lastName);
            $membre->setEmail(sprintf('admin%d@example.com', $i));

            $membre->setDateEmbauchement($this->faker->dateTimeBetween($startDate = '-10 months', $endDate = 'now', $timezone = null));
            $membre->setCreatedAt($this->faker->dateTimeBetween($startDate = '-10 months', $endDate = 'now', $timezone = null));           

            //the reason to pass membre first , to know which encoder to use
            $membre->setPassword($this->passwordEncoder->encodePassword(
                $membre,
                '123456'
            ));
            $membre->setRoles(['ROLE_CHEF_PROJET','ROLE_DEV']);
            $membre->setCreatedBy(4);  

            return $membre;
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
