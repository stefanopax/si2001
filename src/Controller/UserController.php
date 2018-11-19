<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Service\UserService;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository, UserService $service): Response
    {
       return $this->render('user/index.html.twig', [
            'users' => $service->getAllUsers($userRepository)
            ]
        );
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserService $service): Response
    {
        // if verified, this control will render the custom error template
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /**
             * @var UploadedFile $file
             */
            $file = $form->get('link')->getData();

            try {
                // insert default values and check if user is in db
                $service->updateUser($user, $file, $em, $passwordEncoder);
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'user',
                    'An user with this username already exists!'
                );
                return $this->redirectToRoute('user_new');
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, UserService $service): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                /**
                 * @var UploadedFile $file
                 */
                $file = $form->get('link')->getData();

                // insert default values and check if user is in db
                $service->updateUser($user, $file, $em, $passwordEncoder);
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'user',
                    'An user with this username already exists!'
                );
                return $this->redirectToRoute('user_edit',['id' => $user->getId()]);
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/search", name="user_search", methods="POST")
     */
    public function search(UserService $service): Response
    {
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $country=$request->request->get('search');

        $users=$service->searchUsers($em, $country);

        $check=true; // in this way I can discriminate actions in the template without using GET method

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'check' => $check,
        ]);
    }
}
