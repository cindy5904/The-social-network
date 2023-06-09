<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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

            
            $faker = Factory::create('FR-fr');
            $users = [];
            $genres = ['male', 'female'];
            
            for ($i = 0; $i<10; $i++) {

                $user = new User();

                $genre= $faker->randomElement($genres);

                $picture= 'https://randomuser.me/api/portraits/';
                $pictureId= $faker->numberBetween(1, 99) . '.jpg';

                if($genre == 'male') {
                    $picture = $picture . 'men/' . $pictureId;
                    $firstName = $faker->firstNameMale();
                } else {
                     $picture = $picture . 'women/' . $pictureId;
                     $firstName= $faker->firstNameFemale();
                }



                $user->setName($firstName);
                $user->setEmail($faker->email);
                $user->setPseudo($faker->username());
                $user->setBirthAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
                $user->setRoles(['ROLE_USER']);
                $user->setBiographie($faker->realText());
                $user->setAvatar($picture);
                $user->setPassword($this->hasher->hashPassword($user, 'password'));

                $manager->persist($user);
                $users[] = $user;

                for($l = 0; $l<5; $l++) {
                    $publication = new Publication();

                    $publication->setUser($user);
                    $publication->setContent($faker->realText());
                    $publication->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
                    $manager->persist($publication);
                }
                for($n = 0; $n<5; $n++) {
                    $commentaire = new Commentaire();

                    $commentaire->setUser($user);
                    $commentaire->setPublication($publication);
                    $commentaire->setContent($faker->realText());
                    $commentaire->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
                    $manager->persist($commentaire);
                }
            }
            

            $manager->flush();
        }
   
}
