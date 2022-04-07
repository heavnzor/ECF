<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Formation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Formation();
            $user = new User();
            $auteur = $user;
            $user->setEmail('alveyy@gmail.com'.$i);
            $user->setPassword('tertrer');
            $product->setTitre('product ' . $i);
            $product->setAuteur($user);
            $product->setDescription(mt_rand(10, 100));
            $product->setImage('product ' . $i);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
