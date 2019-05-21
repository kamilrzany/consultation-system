<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationFormType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/consultation")
 * @IsGranted("ROLE_ADMIN")
 */
class ConsultationController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="consultations")
     */
    public function index(ConsultationRepository $consultationRepository): Response
    {
        $consultations = $consultationRepository->findBy(['author' => $this->getUser()], ['startDate' => 'ASC']);

        return $this->render('consultation/index.html.twig', ['consultations' => $consultations]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="add_consultation")
     */
    public function new(Request $request): Response
    {
        $post = new Consultation();
        $post->setAuthor($this->getUser());

        $form = $this->createForm(ConsultationFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Konsultacja została utworzona.');

            if ($request->request->has('submit')) {
                return $this->redirectToRoute('add_consultation');
            }

            return $this->redirectToRoute('consultations');
        }

        return $this->render('consultation/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", methods={"GET"}, name="show_consultation")
     */
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit",methods={"GET", "POST"}, name="edit_consultation")
     */
    public function edit(Request $request, Consultation $consultation): Response
    {
        $form = $this->createForm(ConsultationFormType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Konsultacja została zaktualizowana.');

            return $this->redirectToRoute('edit_consultation', ['id' => $consultation->getId()]);
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}/delete", methods={"GET"}, name="delete_consultation")
     */
    public function delete(Consultation $consultation): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($consultation);
        $em->flush();

        $this->addFlash('success', 'Konsultacja została usunięta.');

        return $this->redirectToRoute('consultations');
    }
}
