<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @Route("/status")
 */
class StatusController extends AbstractController
{
    /**
     * @Route("/", name="status_index", methods="GET")
     */
    public function index(StatusRepository $statusRepository): Response
    {
        return $this->render('status/index.html.twig', ['statuses' => $statusRepository->findAll()]);
    }

    /**
     * @Route("/new", name="status_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'A status with this name already exists!'
                );
                return $this->redirectToRoute('status_new');
            }

            return $this->redirectToRoute('status_index');
        }

        return $this->render('status/new.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="status_show", methods="GET")
     */
    public function show(Status $status): Response
    {
        return $this->render('status/show.html.twig', ['status' => $status]);
    }

    /**
     * @Route("/{id}/edit", name="status_edit", methods="GET|POST")
     */
    public function edit(Request $request, Status $status): Response
    {
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'A status with this name already exists!'
                );
                return $this->redirectToRoute('status_edit',['id' => $status->getId()]);
            }

            return $this->redirectToRoute('status_edit', ['id' => $status->getId()]);
        }

        return $this->render('status/edit.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="status_delete", methods="DELETE")
     */
    public function delete(Request $request, Status $status): Response
    {
        if ($this->isCsrfTokenValid('delete'.$status->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($status);
            $em->flush();
        }

        return $this->redirectToRoute('status_index');
    }
}
