<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthorController extends AbstractController
{

    
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showauthor', name: 'showauthor')]
    public function showauthor(AuthorRepository $authorRepo): Response

    {

        $x = $authorRepo->findAll();
        return $this->render('author/showauthor.html.twig', [
            'authors' => $x
        ]);
    }
    #[Route('/test', name: 'test')]
    public function test($id, AuthorRepository $authorRepo): Response
    
    {

        $test = $authorRepo->ListBook($id);
        var_dump($test).die();
        return $this->render('author/showauthor.html.twig', [
            'authors' => $authorRepo->ListBook($id),
        ]);
    }
    #[Route('/listeAuthor', name: 'listeAuthor')]
    public function listeAuthor( AuthorRepository $authorRepo): Response
    
    {

        $authors = $authorRepo->findAllByOrderByEmail();

        return $this->render('author/liste.html.twig', [
            'authors' => $authors,
        ]);
    }
  

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $manager, Request $req): Response
    {
        $em = $manager->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class,   $author);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('showauthor');
        }

        return $this->renderForm('author/add.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id, ManagerRegistry $manager, AuthorRepository $authorrepo, Request $req): Response
    {
        // var_dump($id) . die();

        $em = $manager->getManager();
        $idData = $authorrepo->find($id);
        // var_dump($idData) . die();
        $form = $this->createForm(AuthorType::class, $idData);
        $form->add('save',SubmitType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($idData);
            $em->flush();

            return $this->redirectToRoute('showauthor');
        }

        return $this->renderForm('author/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('showauthor');
    }

   
    #[Route('/searchAuthorsByBook', name: 'searchAuthorsByBook')]
    public function searchAuthorsByBookCount(Request $request, AuthorRepository $authorRepository): Response
    {
        $minCount = $request->query->get('minCount');
        $maxCount = $request->query->get('maxCount');

        $authors = $authorRepository->findAuthorsByBookCountRange($minCount, $maxCount);

        return $this->render('author/liste.html.twig', [
            'authors' => $authors,
        ]);
    }
  
}
