<?php

namespace App\DataFixtures;

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

                if($genre == 'male') $picture = $picture . 'women/' . $pictureId;
                else $picture = $picture . 'men/' . $pictureId;

                $user->setName($faker->name);
                $user->setEmail($faker->email);
                $user->setPseudo('pseudo');
                $user->setBirthAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
                $user->setBiographie($faker->realText());
                $user->setRoles(['ROLE_ADMIN']);
                $user->setAvatar($picture);
                $user->setPassword($this->hasher->hashPassword($user, 'password'));

                $manager->persist($user);
                $users[] = $user;
            }
            

            $manager->flush();
        }
   
}
