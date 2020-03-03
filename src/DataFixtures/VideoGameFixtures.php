<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use App\Entity\VideoGame;

class VideoGameFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker::create();
        $editor = new Editor();
        $editor->setName($faker->company)
            ->setNationality($faker->country);
        $manager->persist($editor);

        for ($i = 0; $i < 5; $i++){
            $videoGame = new VideoGame();
             $videoGame->setTitle($faker->jobTitle)
                 ->setSupport($faker->domainName)
                 ->setDescription($faker->text)
                 ->setReleaseAt(new \DateTime())
                 ->setEditor($editor);
             $manager->persist($videoGame);
        }
        $manager->flush();
    }
}
