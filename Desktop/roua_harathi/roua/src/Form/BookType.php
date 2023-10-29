<?php

namespace App\Form;

use app\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('ref')
        ->add('title')
        ->add('author')
        ->add('publicationdate')
        -> add('category', ChoiceType::class, [
            'choices' => [
                'Science-Fiction' => 'Science-Fiction',
                'Mystery' => 'Mystery',
                'Autobiography' => 'Autobiography',
                'Romance' => 'Romance',
            ],
        ])
        ->add('published')
      
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
