<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\UuidType;
use App\Form\CreateCategoryFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Category;

class EditCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', CreateCategoryFormType::class)
            ->add('uuid', UuidType::class, [
                'attr' => ['hidden' => 'hidden']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}
