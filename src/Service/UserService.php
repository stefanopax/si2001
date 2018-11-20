<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Used by UserController to communicate with data model
class UserService
{

    public function getAllUsers(UserRepository $repository)
    {
        return $repository->findAll();
    }

    public function updateUser(User $user, UploadedFile $file, ObjectManager $em, UserPasswordEncoderInterface $pw)
    {
        //encoding password before updating db
        $password = $pw->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        // get the path of the selected image
        $fileName = $file->getClientOriginalName();

        // move the file to the directory where images are stored/loaded by the application
        try {
            $file->move(
                $this->getTargetDir(),
                $fileName
            );
        } catch (FileException $e) {
            throw new FileNotFoundException('File ' . $fileName . ' not found.');
        }

        $user->setLink($fileName);

        // add default image and birthday;
        $user->setBirthday(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));

        $em->persist($user);
        $em->flush();
    }

    public function searchUsers(ObjectManager $em, string $country)
    {
        return $em->getRepository(User::class)->findBy(array("country"=>$country));
    }

    // needed since parameters in services cannot be called by a service
    public function getTargetDir()
    {
        return 'uploads';
    }
}