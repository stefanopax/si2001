<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Used by controllers to communicate with data model
class MyService
{
    public function getAllUsers(UserRepository $repository)
    {
        return $repository->findAll();
    }

    public function updateUser(User $user, ObjectManager $em, UserPasswordEncoderInterface $pw)
    {
        //encoding password before updating db
        $password = $pw->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        // add default image and birthday
        $user->setLink("person.jpg");
        $user->setBirthday(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));

        $em->persist($user);
        $em->flush();
    }
    public function searchUsers(ObjectManager $em, string $country)
    {
        return $em->getRepository(User::class)->findBy(array("country"=>$country));
    }
}