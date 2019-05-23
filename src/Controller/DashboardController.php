<?php

namespace App\Controller;

use App\Repository\ConsultationRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(ConsultationRepository $consultationRepository, ReservationRepository $reservationRepository)
    {
        $consultations = $consultationRepository->findConsultationsInCurrentWeek($this->getUser()->getId());
        $reservations = $reservationRepository->findReservationsInCurrentWeek($this->getUser()->getId());

        return $this->render('dashboard/index.html.twig', [
            'consultations' => $consultations,
            'reservations' => $reservations
        ]);
    }
}
