<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class StudentFixtures extends Fixture
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
         
         $teacher = $this->userRepository->findOneBy(['email' => 'julienkunze0@gmail.com']);
         if (!$teacher) {
            // Créez un teacher si nécessaire
            $teacher = new User();
            $teacher->setEmail('julienkunze0@gmail.com');
            $teacher->setRoles(['ROLE_PROF']);
            $hashedPassword = $this->passwordHasher->hashPassword($teacher, 'password');
            $teacher->setPassword($hashedPassword);
            $teacher->setFirstname('Julien');
            $teacher->setLastname('Kunze');
            $teacher->setEtablissement('Politzer');
            $manager->persist($teacher);
            $manager->flush();
        }
         $faker = Factory::create('fr_FR');

         for($i=0;$i<20;$i++) {
            $student = new Student();
            $student->setFirstname($faker->firstName());
            $student->setLastname($faker->lastName());
            $student->setEtablissement('Politzer');
            $student->setTeacher($teacher);
            $manager->persist($student);
         }

        $manager->flush();
    }
}
