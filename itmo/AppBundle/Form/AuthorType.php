<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('firstName', TextType::class)
      ->add('lastName', TextType::class)
      ->add('patronymic', TextType::class, [
        'required' => false,
      ])
      ->add('save', SubmitType::class, ['label' => 'Save']);
  }
}
