<?php

namespace App\Controller;

use App\Entity\Cities;
use App\Entity\Registrations;
use App\Entity\Status;
use App\Entity\Trips;
use App\Form\TripCancelType;
use App\Form\TripType;
use App\Repository\RegistrationsRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class TripController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/trip", name="trip")
     */
    public function index()
    {
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    /**
     * @Route("/trip/add", name="tripAdd")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $cities = $entityManager->getRepository(Cities::class)->findAll();
        $trip = new Trips();
        $form = $this->createForm(TripType::class, $trip);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trip);
            $this->entityManager->flush();


            $this->addFlash('success', 'Votre sortie est ajoutée !');
            return $this->redirectToRoute('welcome', compact('participant'));
        }

        return $this->render('trip/add.html.twig', [ 'cities' => $cities,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trip/modify/{id}", name="tripModify", requirements={"id":"\d+"})
     */
    public function modify(Trips $trip, Request $request, EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie est modifiée !');
            return $this->redirectToRoute('tripModify', ['id' => $trip->getId()]);
        }


        return $this->render('trip/modify.html.twig', ['trip' => $trip,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/trip/show/{id}",name="tripShow", requirements={"id":"\d+"})
     */
    public function show($id, RegistrationsRepository $registrationsRepository, Request $request, Trips $trip)
    {
        $listRegistrations = $registrationsRepository->findByIdTrip($id);
        dump($id);
        dump($listRegistrations);

        return $this->render('trip/show.html.twig', compact('trip', 'listRegistrations'));

    }

    /**
     * @Route("/trip/cancel/{id}", name="tripCancel", requirements={"id":"\d+"})
     */
    public function cancel($id, StatusRepository $statusRepository, RegistrationsRepository $registrationsRepository, Request $request, Trips $trip, EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(TripCancelType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $statusRepository->find(6);
            dump($status);
            $trip->setStatus($status);

            $this->entityManager->flush();

            $this->addFlash('success', 'Votre sortie est annulée !');
            return $this->redirectToRoute('welcome', compact('participant'));

        }
        return $this->render('trip/cancel.html.twig', ['ad' => $trip,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trip/delete/{id}", name="tripDelete", requirements={"id":"\d+"})
     */
    public function remove ($id, Request $request, EntityManagerInterface $entityManager)
    {
        $trip = $entityManager
            ->getRepository(Trips::class)
            ->find($id);


        if (!$trip instanceof Trips) {
            throw $this->createNotFoundException();
        }

        $entityManager->remove($trip);
        $entityManager->flush();

        $this->addFlash('success', 'Votre sortie est supprimée !');
        return $this->redirectToRoute('welcome', compact('participant'));

    }

}


