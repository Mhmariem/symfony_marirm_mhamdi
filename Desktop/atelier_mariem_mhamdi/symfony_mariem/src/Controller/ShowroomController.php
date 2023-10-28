<?php

namespace App\Controller;

use App\Repository\ShowroomRepository;
use App\Entity\Showroom;
use App\Form\ShowroomType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowroomController extends AbstractController
{
    #[Route('/showroom', name: 'app_showroom')]
    public function index(): Response
    {
        return $this->render('showroom/index.html.twig', [
            'controller_name' => 'ShowroomController',
        ]);
    }
    #[Route('/show', name: 'show')]
    public function show(ShowroomRepository $showroomRepo): Response
    {

        $x = $showroomRepo->findAll();
        return $this->render('showroom/show.html.twig', [
            'showroom' => $x
        ]);
    
}
#[Route('/addshow', name: 'addshow')]
public function addshow(ManagerRegistry $manager, Request $req): Response
{
    $em = $manager->getManager();
    $showroom = new Showroom();
    $form = $this->createForm(ShowroomType::class,   $showroom);
    $form->add('save',SubmitType::class);
    $form->handleRequest($req);
    if ($form->isSubmitted() and $form->isValid()) {
        $em->persist($showroom);
        $em->flush();

        return $this->redirectToRoute('show');
    }

    return $this->renderForm('showroom/add.html.twig', [
        'form' => $form
    ]);
}
#[Route('/editshow/{id}', name: 'editshow')]
public function editshow($id, ManagerRegistry $manager, ShowroomRepository $showrepo, Request $req): Response
{
    // var_dump($id) . die();

    $em = $manager->getManager();
    $idData = $showrepo->find($id);
    // var_dump($idData) . die();
    $form = $this->createForm(ShowroomType::class, $idData);
    $form->add('save',SubmitType::class);
    $form->handleRequest($req);

    if ($form->isSubmitted() and $form->isValid()) {
        $em->persist($idData);
        $em->flush();

        return $this->redirectToRoute('show');
    }

    return $this->renderForm('showroom/edit.html.twig', [
        'form' => $form
    ]);
}
/*

#[Route('/deleteshow/{id}', name: 'deleteshow')]
    public function deleteshow($id, ManagerRegistry $manager, ShowroomRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('show');
    }*/
}