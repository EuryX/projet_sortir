<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\RegistrationType;
use App\Form\TripType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;


class ProfileController extends Controller
{
    /**
     * @Route("profil/show/{id}", name="profilShow")
     */
    public function show($id,Participants $participants, EntityManagerInterface $entityManager)
    {
        $participant = $entityManager
            ->getRepository(Participants::class)
            ->find($id);

        return $this->render('profile/show.html.twig');
    }

    /**
     * @Route("profil/modify", name="profilModify")
     */
    public function edit(Security $security, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, Request $request)
    {
        // TODO A REFAIRE
        $participant = new Participants();
        $form = $this->createForm(RegistrationType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($password);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte est créé');
        }


        return $this->render('profile/edit.html.twig');
    }
}
