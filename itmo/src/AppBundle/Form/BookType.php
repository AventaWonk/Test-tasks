<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title', TextType::class)
      ->add('pages', IntegerType::class)
      ->add('year', IntegerType::class)
      ->add('isbn', TextType::class)
      ->add('authors', EntityType::class, [
        'class' => 'AppBundle:Author',
        'choice_label' => function($author) {
            return $author->getInitials();
        },
        'multiple' => true,
        'expanded' => true,
      ])
      ->add('image', FileType::class, [
        'label' => 'Cover image',
        'required' => false,
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Save'
      ]);
  }
}
