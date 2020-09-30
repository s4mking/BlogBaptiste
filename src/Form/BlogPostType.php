<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('banner')
            ->add('publicationDate')
            ->add('categories', EntityType::class, array(
                'class'         => Category::class,
                'placeholder'   => 'Select a category',
                'choices'       => $options['categories'],
                'expanded'     => true,
                'multiple'     => true,
                'by_reference' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
            'categories'        => null
        ]);
    }
}
