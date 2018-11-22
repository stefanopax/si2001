<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        // if user logged in, he can't return to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_index'));
        }

        $error = $utils->getLastAuthenticationError();
        $lastUsername=$utils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // add success notice
        $this->get('session')->getFlashBag()->add(
            'logout',
            'A skill with this name already exists!'
        );
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/error", name="error")
     */
    public function error()
    {
        return $this->render('security/error.html.twig');
    }
}
