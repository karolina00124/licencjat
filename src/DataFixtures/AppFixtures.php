<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Todolist;
use App\Entity\Event;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppFixtures extends Fixture
{
    /**
     * Password encoder.
     *
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@terminarz.local');

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'user'
            )
        );
        $manager->persist($user);

        $userAdmin = new User();
        $userAdmin->setEmail('admin@terminarz.local');
        $userAdmin->setRoles([ User::ROLE_ADMIN]);
        $userAdmin->setPassword(
            $this->passwordEncoder->encodePassword(
                $userAdmin,
                'admin'
            )
        );
        $manager->persist($userAdmin);


        $todolistNamesToAdd = [
            'studia',
            'praca',
            'wolne',
        ];
        $todolist = [];
        for ($i = 0; $i < count($todolistNamesToAdd); ++$i) {
            $todolist[$i] = new Todolist();
            $todolist[$i]->setName($todolistNamesToAdd[$i]);
            $manager->persist($todolist[$i]);
        }


        $faker = Factory::create();
        for ($i = 0; $i < 30; ++$i) {
            $event = new Event();
            $sentence = $faker->sentence;
            $event->setTitle(explode(' ', $sentence)[0]);
            $event->setDescription($faker->sentence);
            $event->setStartTime($faker->dateTimeBetween('-100 days', '-51 days'));
            $event->setEndTime($faker->dateTimeBetween('-50 days', '-1 days'));
            $randomIndex = rand(0, count($todolistNamesToAdd)-1);
            $event->setTodolist($todolist[$randomIndex]);
            $event->setUser($user);
            $manager->persist($event);
        }
        $manager->flush();
    }
}


