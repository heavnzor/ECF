<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Quizz;
use App\Entity\Section;
use App\Entity\Progress;
use App\Entity\Formation;
use App\Entity\Newsletter;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // admin
        $admin = new User();
        $admin->setEmail('webmaster@waldganger.net');
        $admin->setPseudo('admin');
        $passwordAdmin = $this->hasher->hashPassword($admin, 'tqzcectv');
        $admin->setPassword($passwordAdmin);
        $admin->setIsVerified(true);
        $admin->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($admin);

        // Instructeur pas encore validé
        $instructeurUnVerified = new User();
        $instructeurUnVerified->setEmail('instructeurUnVerifiedUnvalided@waldganger.net');
        $instructeurUnVerified->setRoles(['ROLE_USER']);
        $passwordInstructeurUnVerified = $this->hasher->hashPassword($instructeurUnVerified, 'yannick');
        $instructeurUnVerified->setPassword($passwordInstructeurUnVerified);
        $instructeurUnVerified->setPrenom($faker->firstName());
        $instructeurUnVerified->setNom($faker->name());
        $instructeurUnVerified->setPhoto('jadot.jpg');
        $instructeurUnVerified->setDescription($faker->words(5, true));
        $instructeurUnVerified->setIsPostulant(true);

        $manager->persist($instructeurUnVerified);

        // Instructeur validé
        $instructeur = new User();
        $instructeur->setEmail('alveyy@gmail.com');
        $instructeur->setRoles(['ROLE_INSTRUCTEUR']);
        $passwordInstructeur = $this->hasher->hashPassword($instructeur, 'tqzcectv');
        $instructeur->setPassword($passwordInstructeur);
        $instructeur->setPrenom($faker->firstName());
        $instructeur->setNom($faker->name());
        $instructeur->setPhoto('vitalik.jpg');
        $instructeur->setDescription($faker->words(8, true));
        $instructeur->setIsVerified(true);
        $instructeur->setIsPostulant(true);
        $manager->persist($instructeur);

        // user #1

        $user = new User();
        $user->setEmail('anto.mela@live.fr');
        $user->setRoles(['ROLE_USER']);
        $passwordUser = $this->hasher->hashPassword($user, 'tqzcectv');
        $user->setPassword($passwordUser);
        $user->setPseudo($faker->firstName());
        $user->setIsVerified(true);
        $manager->persist($user);


        for ($f = 0; $f < 10; $f++) {
            $formation = new Formation();
            $formation->setAuteur($instructeur);
            $formation->setImage('informatique.png');
            $formation->setDescription($faker->words(50, true));
            $formation->setTitre($faker->words(6, true));
            $manager->persist($formation);
            $user->addFormation($formation);
            $instructeur->addFormation($formation);
            // prendre une formation au hasard, mettre tous ses courts finis et donc la formation fini aussi


            for ($s = 0; $s < 3; $s++) {
                $section = new Section();
                $section->setFormation($formation);
                $section->setTitre($faker->words(6, true));
                $section->addAuteur($instructeur);
                $manager->persist($section);
                $user->addSection($section);

                $quizz = new Quizz();
                $quizz->setSection($section);
                $quizz->setQuestion1($faker->words(10, true));
                $quizz->setReponse1($faker->words(8, true));
                $quizz->setReponse2($faker->words(8, true));
                $quizz->setQuestion2($faker->words(10, true));
                $quizz->setReponse3($faker->words(8, true));
                $quizz->setReponse4($faker->words(8, true));
                $quizz->setBonneReponse1('reponse2');
                $quizz->setBonneReponse2('reponse3');
                $manager->persist($quizz);

                for ($l = 0; $l < 5; $l++) {
                    $cours = new cours();
                    $cours->setTitre($faker->words(6, true));
                    $cours->setSection($section);
                    $cours->setCours($faker->text(2000));
                    $cours->setImage('greencomputer.png');
                    $cours->setPdf('greenIT.pdf');
                    $cours->setVideo('wfhAh4y53tI');
                    $cours->addUser($instructeur);
                    $manager->persist($cours);
                    $cours->addUser($user);
                    $formation->addCours($cours);


                    $randProgress = rand(0, 2);

                    $progress = new Progress();
                    $progress->setUser($user);
                    $progress->setFormation($formation);
                    $progress->setCours($cours);
                    $progress->setCoursFinished($randProgress);
                    $manager->persist($progress);

                    $randProgress2 = rand(0, 2);

                    $progress2 = new Progress();
                    $progress2->setUser($instructeur);
                    $progress2->setFormation($formation);
                    $progress2->setCours($cours);
                    $progress2->setCoursFinished($randProgress2);
                    $manager->persist($progress2);
                }
            }
        }
        $randProgressF = rand(0, 1);
        $progressF = new Progress();
        if ($randProgressF == 1) {
            $cours = $formation->getCours();
            foreach ($cours as $coursF) {
                $progressF->setUser($user);
                $progressF->setFormation($formation);
                $progressF->setCours($coursF);
                $progressF->setCoursFinished($randProgressF);
                $progressF->setFormationFinished($randProgressF);
            }
            $manager->persist($progressF);
        }
        $manager->flush();
    }
}
