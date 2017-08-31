<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Author;
use AppBundle\Form\BookType;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
  /**
   * @Route("book/", name="book")
   */
  public function indexAction(Request $request)
  {
    $books = $this->getDoctrine()
      ->getRepository(Book::class)
      ->findAll();

    return $this->render('book/index.html.twig', [
      "books" => $books
    ]);
  }

  /**
   * @Route("book/add/", name="book_add")
   */
  public function addAction(Request $request)
  {
    $book = new Book();
    $form = $this->createForm(BookType::class, $book)
      ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $book = $form->getData();

      $image = $book->getImage();
      if ($image) {
        $fileName = md5(uniqid()).'.'.$image->guessExtension();
        $image->move(
          $this->getParameter('images_directory'),
          $fileName
        );
        $book->setImage($fileName);
      }

      $em = $this->getDoctrine()->getManager();
      if ($this->isBookExists($book)) {
        $this->addFlash(
          'error',
          'The same book has been alredy created'
        );
        return $this->render('book/edit.html.twig', [
          'form' => $form->createView(),
          'type'=> "Create",
        ]);
      }

      $em->persist($book);
      $em->flush();

      $this->addFlash(
        'success',
        'The book was successfully created'
      );
      return $this->redirectToRoute('book');
    }

    return $this->render('book/edit.html.twig', [
      'form' => $form->createView(),
      'type'=> "Create",
    ]);
  }

  /**
   * @Route("book/update/{id}", name="book_update", requirements={"id": "\d+"})
   */
  public function updateAction(Request $request, $id)
  {
    $book = $this->getDoctrine()
      ->getRepository(Book::class)
      ->find($id);

    if (!$book) {
      $this->addFlash(
        'error',
        'Bad request'
      );
      return $this->redirectToRoute('book');
    }

    $imageName = $book->getImage();
    if ($imageName) {
      $book->setImage(new File($this->getParameter('images_directory').'/' . $imageName));
    }

    $form = $this->createForm(BookType::class, $book)
      ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $book = $form->getData();

      if ($image = $book->getImage()) {
        $fileName = md5(uniqid()).'.'.$image->guessExtension();
        $image->move(
          $this->getParameter('images_directory'),
          $fileName
        );
        $book->setImage($fileName);
      } else  if ($imageName) {
        $book->setImage($imageName);
      }

      $em = $this->getDoctrine()->getManager();
      if ($this->isBookExists($book)) {
        $this->addFlash(
          'error',
          'The same book has been alredy created'
        );
        return $this->redirectToRoute('book');
      }

      $em->persist($book);
      $em->flush();

      $this->addFlash(
        'success',
        'The book was successfully updated'
      );
      return $this->redirectToRoute('book');
    }

    return $this->render('book/edit.html.twig', [
      'form' => $form->createView(),
      'type'=> "Update",
    ]);
  }

  /**
   * @Route("book/delete/{id}", name="book_delete", requirements={"id": "\d+"})
   */
  public function deleteAction($id)
  {
    $book = $this->getDoctrine()
      ->getRepository(Book::class)
      ->find($id);

    if (!$book) {
      $this->addFlash(
        'error',
        'Bad request'
      );
      return $this->redirectToRoute('book');
    }

    unlink($this->getParameter('images_directory').'/'.$book->getImage());
    $em = $this->getDoctrine()->getManager();
    $em->remove($book);
    $em->flush();

    $this->addFlash(
      'success',
      'The book was successfully deleted'
    );
    return $this->redirectToRoute('book');
  }

  protected function isBookExists(Book $book)
  {
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery(
      'SELECT b
      FROM AppBundle:Book b
      WHERE b.title = :title
      AND b.year = :year
      OR b.isbn = :isbn')
      ->setParameter('title', $book->getTitle())
      ->setParameter('year', $book->getYear())
      ->setParameter('isbn', $book->getIsbn());

    $sameBook = $query->getResult();
    if ($sameBook && $sameBook[0] && $sameBook[0]->getId() != $book->getId()) {
      return true;
    }
    return false;
  }
}
