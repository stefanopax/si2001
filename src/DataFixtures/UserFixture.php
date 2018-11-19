<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Skill;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Used to populate the database at the beginning
class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user=new User();

        // setting first attributes
        $user->setName('Mauricio');
        $user->setSurname('Enriquez');
        $user->setUsername('admin');
        $user->setPassword(
            $this->encoder->encodePassword($user,'password'));
        $user->setLink("logo.jpg");
        $user->setCountry("Italy");
        $user->setBirthday(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));

        // creating a new status and assigning
        $status=new Status();
        $status->setName("Single");
        $user->setStatus($status);

        // creating new skills and assigning
        $skill=new Skill();
        $skill->setName("PHP");
        $skill2=new Skill();
        $skill2->setName("JAVA");
        $user->addSkillId($skill);
        $user->addSkillId($skill2);

        // creating a new role and assigning
        $role=new Role();
        $role->setName("ROLE_USER");
        $user->addRole($role);
        $role2=new Role();
        $role2->setName("ROLE_ADMIN");
        $user->addRole($role2);

        // make an user instance managed and persistent
        $manager->persist($user);
        // enter the user object into the database
        $manager->flush();
    }
}