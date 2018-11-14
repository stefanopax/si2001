<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


/**
 * @Route("/skill")
 */
class SkillController extends AbstractController
{
    /**
     * @Route("/", name="skill_index", methods="GET")
     */
    public function index(SkillRepository $skillRepository): Response
    {
        return $this->render('skill/index.html.twig', ['skills' => $skillRepository->findAll()]);
    }

    /**
     * @Route("/new", name="skill_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($skill);
                $em->flush();
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    'A skill with this name already exists!'
                );
                return $this->redirectToRoute('skill_new');
            }

            return $this->redirectToRoute('skill_index');
        }

        return $this->render('skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="skill_show", methods="GET")
     */
    public function show(Skill $skill): Response
    {
        return $this->render('skill/show.html.twig', ['skill' => $skill]);
    }

    /**
     * @Route("/{id}/edit", name="skill_edit", methods="GET|POST")
     */
    public function edit(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $this->getDoctrine()->getManager()->flush();
            }
            catch (UniqueConstraintViolationException $e) {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    'A skill with this name already exists!'
                );
                return $this->redirectToRoute('skill_edit', ['id' => $skill->getId()]);
            }

            return $this->redirectToRoute('skill_edit', ['id' => $skill->getId()]);
        }

        return $this->render('skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="skill_delete", methods="DELETE")
     */
    public function delete(Request $request, Skill $skill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($skill);
            $em->flush();
        }

        return $this->redirectToRoute('skill_index');
    }
}
