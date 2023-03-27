<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 50
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
                'choice_value' => 'uuid'
            ])
            ->add('submit', SubmitType::class,[]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class
        ]);
    }
}