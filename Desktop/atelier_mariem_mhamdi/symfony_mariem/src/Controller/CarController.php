<?php

namespace App\Controller;


use App\Repository\CarRepositoryRepository;
use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }
    #[Route('/showcar', name: 'showcar')]
    public function showcar(CarRepository $carRepo): Response
    {

        $x = $carRepo->findAll();
        return $this->render('car/showcar.html.twig', [
            'cars' => $x
        ]);
    
}
#[Route('/addcar', name: 'addcar')]
public function addcar(ManagerRegistry $manager, Request $req): Response
{
    $em = $manager->getManager();
    $car = new Car();
    $form = $this->createForm(CarType::class,   $car);
    $form->handleRequest($req);
    if ($form->isSubmitted() and $form->isValid()) {
        $em->persist($car);
        $em->flush();

        return $this->redirectToRoute('showcar');
    }

    return $this->renderForm('car/addcar.html.twig', [
        'f' => $form
    ]);
}

}



