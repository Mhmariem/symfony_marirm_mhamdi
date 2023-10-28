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
use App\Controller\EntityManager;

class AuthorController extends AbstractController
{

    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $authorRepo): Response

    {

        $x = $authorRepo->findAll();
        return $this->render('author/showDBauthor.html.twig', [
            'authors' => $x
        ]);
    }
    #[Route('/test', name: 'test')]
    public function test($id, AuthorRepository $authorRepo): Response
    
    {

        $test = $authorRepo->ListBook($id);
        var_dump($test).die();
        return $this->render('author/showDBauthor.html.twig', [
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
    #[Route('/addstaticSauthor', name: 'addstaticSauthor')]
    public function addstaticSauthor(ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $author = new Author();

        $author->setUsername("3a56");
        $author->setEmail("3a56@esprit.tn");
        $em->persist($author);
        $em->flush();

        return new Response("add with succcess");
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

            return $this->redirectToRoute('showDBauthor');
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

            return $this->redirectToRoute('showDBauthor');
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


        return $this->redirectToRoute('showDBauthor');
    }

    #[Route('/showauthor', name: 'app_showauthor')]
    public function showauthor(): Response
    {

        return $this->render('author/show.html.twig', [
            'authorshtml' => $this->authors,
        ]);
    }

    #[Route('/authorDetails/{id}', name: 'authorDetails')]
    public function authorDetails($id): Response
    {
        //var_dump($id) . die();

        $author = null;
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            }
        }

        return $this->render('author/details.html.twig', [
            'author' => $author
        ]);
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
    #[Route('/deleteAuthorsWithZero', name: 'deleteAuthorsWithZero')]
    public function deleteAuthorsWithZeroBooks( $id,AuthorRepository $authorRepository,ManagerRegistry $manager): Response
    {
        $authors = $authorRepository->findAuthorsWithZeroBooks();

       

        return $this->redirectToRoute('liste');
    }
}
