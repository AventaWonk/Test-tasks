<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
  /**
   * @Route("/", name="home")
   */
  public function indexAction(Request $request)
  {
    $books = $this->getDoctrine()
      ->getRepository(Book::class)
      ->findAll();

    $authors = $this->getDoctrine()
      ->getRepository(Author::class)
      ->findAll();

    return $this->render('page/index.html.twig', [
      "booksCount" => count($books),
      "authorsCount" => count($authors),
    ]);
  }
}
