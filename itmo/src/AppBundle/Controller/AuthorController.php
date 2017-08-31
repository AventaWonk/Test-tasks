<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
{
  /**
   * @Route("author/", name="author")
   */
  public function indexAction(Request $request)
  {
    $authors = $this->getDoctrine()
      ->getRepository(Author::class)
      ->findAll();

    return $this->render('author/index.html.twig', [
      "authors" => $authors
    ]);
  }

  /**
   * @Route("author/add/", name="author_add")
   */
  public function addAction(Request $request)
  {
    $author = new Author();

    $form = $this->createForm(AuthorType::class, $author)
      ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $author = $form->getData();

      if ($this->isAuthorExists($author)) {
        $this->addFlash(
          'error',
          'This author has been alredy created'
        );
        return $this->render('author/edit.html.twig', [
          'form' => $form->createView(),
          'type'=> "Create",
        ]);
      }

      $em = $this->getDoctrine()->getManager();
      $em->persist($author);
      $em->flush();
      return $this->redirectToRoute('author');
    }

    return $this->render('author/edit.html.twig', [
      'form' => $form->createView(),
      'type'=> "Create",
    ]);
  }

  /**
   * @Route("author/update/{id}", name="author_update", requirements={"id": "\d+"})
   */
  public function updateAction(Request $request, $id)
  {
    $author = $this->getDoctrine()
      ->getRepository(Author::class)
      ->find($id);

    if (!$author) {
      $this->addFlash(
        'error',
        'Bad request'
      );
      return $this->redirectToRoute('author');
    }

    $authors = $this->getDoctrine()
      ->getRepository(Author::class)
      ->findAll();

    $form = $this->createForm(AuthorType::class, $author)
      ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $author = $form->getData();

      if ($this->isAuthorExists($author)) {
        $this->addFlash(
          'error',
          'This author has been alredy created'
        );
        return $this->render('author/edit.html.twig', [
          'form' => $form->createView(),
          'type'=> "Update",
        ]);
      }

      $em = $this->getDoctrine()->getManager();
      $em->persist($author);
      $em->flush();

      $this->addFlash(
        'success',
        'The book was successfully updated'
      );
      return $this->redirectToRoute('author');
    }

    return $this->render('author/edit.html.twig', [
      'form' => $form->createView(),
      'type'=> "Update",
    ]);
  }

  /**
   * @Route("author/delete/{id}", name="author_delete", requirements={"id": "\d+"})
   */
  public function deleteAction($id)
  {
    $author = $this->getDoctrine()
      ->getRepository(Author::class)
      ->find($id);

    if (!$author) {
      $this->addFlash(
        'error',
        'Bad request'
      );
      return $this->redirectToRoute('author');
    }

    $em = $this->getDoctrine()->getManager();
    $em->remove($author);
    $em->flush();

    $this->addFlash(
      'success',
      'The Author was successfully deleted'
    );
    return $this->redirectToRoute('author');
  }

  private function isAuthorExists(Author $author)
  {
    $em = $this->getDoctrine()->getManager();

    $sameAuthor = $em->getRepository(Author::class)->findOneBy([
      'firstName' => $author->getFirstName(),
      'lastName' => $author->getLastName(),
      'patronymic' => $author->getPatronymic(),
    ]);

    if ($sameAuthor && $sameAuthor->getId() != $author->getId()) {
      return true;
    }
    return false;
  }
}
