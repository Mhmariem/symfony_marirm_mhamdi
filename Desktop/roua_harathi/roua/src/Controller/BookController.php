<?php

namespace App\Controller;

use  App\Repository\BookRepository;
use App\Entity\Book;
use app\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/addbook', name: 'addbook')]
    public function addBook(Request $request , ManagerRegistry $manager): Response
    {
        
        $em = $manager->getManager();
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();

            // Set 'published' to true
            $book->setPublished(true);
            $em->persist($book);
            $em->flush();

            // Redirect to a success page or perform other actions as needed
            return $this->redirectToRoute('afficherbook');
        }

        return $this->render('book/addbook.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/afficherbook', name: 'afficherbook')]
    public function afficherbook(BookRepository $bookRepository): Response
    {
        $books=$bookRepository->findAll();
        return $this->render('book/afficherbook.html.twig', [
            'books' =>$books,
        ]);
    }
    #[Route('/editbook/{ref}', name: 'editbook')]
    public function editbook($ref, ManagerRegistry $manager, BookRepository $authorrepo, Request $req): Response
    {
        // var_dump($id) . die();

        $em = $manager->getManager();
        $idData = $authorrepo->find($ref);
        // var_dump($idData) . die();
        $form = $this->createForm(BookType::class, $idData);
        $form->add('save',SubmitType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($idData);
            $em->flush();

            return $this->redirectToRoute('afficherbook');
        }

        return $this->renderForm('book/editbook.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deletebook/{ref}', name: 'deletebook')]
    public function deletebook($ref, ManagerRegistry $manager, BookRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($ref);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('afficherbook');
    }
    #[Route('/searchbook', name: 'searchbook')]
    public function searchBookByRef(Request $request, BookRepository $bookRepository): Response
    {
        $ref = $request->query->get('ref');
        $books = $bookRepository->searchByRef($ref);

        return $this->render('book/afficherbook.html.twig', [
            'books' => $books,
        ]);

    }
 #[Route('/listbook', name: 'listbook')]
    public function listbook( BookRepository $BookRepo): Response
    
    {

        $authors = $BookRepo->findAllByOrderByusernameAuthor();

        return $this->render('book/afficherbook.html.twig', [
            'books' => $BookRepo,
        ]);
    }
    #[Route('/listbookYEAR', name: 'listbookYEAR')]
    public function listBooksBefore2023WithAuthorMoreThan35(BookRepository $bookRepository , ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $year = 2023;
        $books = $bookRepository->findBooksPublishedBeforeYearWithAuthorMoreThan35Books($year);

        return $this->render('book/afficherbook.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/bookStates', name: 'bookStates')]
    public function bookStats(BookRepository $bookRepository): Response
    {
        $books=$bookRepository->findAll();
        $publishedBooks = $bookRepository->countPublishedBooks();
        $unpublishedBooks = $bookRepository->countUnpublishedBooks(); 
        $sumScienceFictionBooks = $bookRepository->sumScienceFictionBooks();

        return $this->render('book/liste.html.twig', [
            'books'=>$books,
            'publishedBooks' => $publishedBooks,
            'unpublishedBooks' => $unpublishedBooks,
            'sumScienceFictionBooks' => $sumScienceFictionBooks,
        ]);
    }
}
